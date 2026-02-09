<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Festival;
use App\Models\Manifestation;
use App\Models\Artiste;
use App\Models\Lieux;
use App\Events\MessageSent;
use App\Events\AdminRequested;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * 🤖 Service principal du chatbot
 * 
 * Gère toute la logique du chatbot de manière centralisée et simple
 */
class ChatbotService
{
    private EscalationDetector $escalationDetector;
    private FallbackResponses $fallbackResponses;

    public function __construct(
        EscalationDetector $escalationDetector,
        FallbackResponses $fallbackResponses
    ) {
        $this->escalationDetector = $escalationDetector;
        $this->fallbackResponses = $fallbackResponses;
    }

    /**
     * Traite un message utilisateur et retourne la réponse du bot
     * 
     * Flux:
     * 1. Récupérer ou créer la conversation
     * 2. Sauvegarder le message utilisateur
     * 3. Vérifier si escalade demandée
     * 4. Appeler l'IA ou utiliser les réponses par défaut
     * 5. Retourner et broadcaster la réponse
     */
    public function handleMessage(string $conversationId, string $userMessage): string
    {
        // 1️⃣ Créer ou récupérer la conversation
        $conversation = Conversation::firstOrCreate(
            ['conversation_id' => $conversationId],
            ['admin_active' => false]
        );

        // 2️⃣ Sauvegarder le message utilisateur
        $userMsg = Message::create([
            'conversation_id' => $conversation->id,
            'sender' => 'user',
            'content' => $userMessage,
        ]);

        // Transmettre l'objet conversation complet (déjà en mémoire)
        $userMsg->conversation = $conversation;
        broadcast(new MessageSent($userMsg));

        // 3️⃣ Vérifier escalade
        if ($this->escalationDetector->shouldEscalate($userMessage)) {
            return $this->handleEscalation($conversation);
        }

        // 4️⃣ Si admin actif, ne pas répondre automatiquement (l'admin répond manuellement)
        if ($conversation->admin_active) {
            return "Un administrateur est connecté à votre conversation. Veuillez patienter.";
        }

        // 5️⃣ Appeler l'IA ou fallback
        return $this->generateBotReply($conversation, $userMessage);
    }

    /**
     * Gère une demande d'escalade vers un humain
     */
    private function handleEscalation(Conversation $conversation): string
    {
        $conversation->update(['admin_active' => true]);
        broadcast(new AdminRequested($conversation));

        $reply = $this->escalationDetector->getEscalationMessage();
        $this->saveBotMessage($conversation, $reply);

        return $reply;
    }

    /**
     * Récupère la réponse d'un administrateur si disponible
     */
    private function getAdminReply(Conversation $conversation): string
    {
        $adminMessage = Message::where('conversation_id', $conversation->id)
            ->where('sender', 'admin')
            ->where('created_at', '>', now()->subMinutes(1))
            ->first();

        return $adminMessage?->content ?? "L'administrateur prépare sa réponse...";
    }

    /**
     * Génère une réponse du bot via l'IA ou fallback
     */
    private function generateBotReply(Conversation $conversation, string $userMessage): string
    {
        $apiKey = env('GOOGLE_AI_KEY');

        // Utiliser l'IA si disponible
        if ($apiKey) {
            $reply = $this->callGeminiAPI($apiKey, $userMessage);
        } else {
            // Sinon, réponse par défaut
            $reply = $this->fallbackResponses->generate($userMessage);
        }

        // Sauvegarder et broadcaster
        $this->saveBotMessage($conversation, $reply);

        return $reply;
    }

    /**
     * Détecte si l'utilisateur demande un festival/manifestation spécifique
     * et prépare un prompt optimisé
     */
    private function buildOptimizedPrompt(string $userMessage): string
    {
        $userLower = strtolower($userMessage);
        
        try {
            // 🔍 Détection: parle-t-il d'un festival ou manifestation spécifique?
            $festivals = Festival::with('manifestations')->get();
            $manifestations = Manifestation::all();
            
            $relevantData = null;
            
            // Chercher si le message mentionne un festival spécifique
            foreach ($festivals as $fest) {
                if (str_contains($userLower, strtolower($fest->THEMEFEST))) {
                    // 🎯 Utilisateur parle d'un festival spécifique
                    $manifs = $fest->manifestations->map(function ($m) {
                        $prix = $m->PRIXMANIF ? $m->PRIXMANIF . '€' : 'GRATUIT';
                        return "  • {$m->NOMMANIF} - {$m->RESUMEMANIF} | {$prix} | {$m->NBMAXPARTICIPANTMANIF} places";
                    })->join("\n");
                    
                    $relevantData = "FESTIVAL: {$fest->THEMEFEST} ({$fest->DATEDEBFEST->format('d/m/Y')} au {$fest->DATEFINFEST->format('d/m/Y')})\n\nMANIFESTATIONS:\n{$manifs}";
                    break;
                }
            }
            
            // Chercher si le message mentionne une manifestation spécifique
            if (!$relevantData) {
                foreach ($manifestations as $manif) {
                    if (str_contains($userLower, strtolower($manif->NOMMANIF))) {
                        $fest = $manif->festival;
                        $relevantData = "MANIFESTATION: {$manif->NOMMANIF}\nFestival: {$fest->THEMEFEST}\nDescription: {$manif->RESUMEMANIF}\nPrix: " . ($manif->PRIXMANIF ? "{$manif->PRIXMANIF}€" : "GRATUIT") . "\nPlaces: {$manif->NBMAXPARTICIPANTMANIF}";
                        break;
                    }
                }
            }
            
            // Si on a trouvé des données spécifiques, utiliser un prompt court
            if ($relevantData) {
                return "🎵 Tu es l'assistant du Festival Cale Sons 2026. Réponds UNIQUEMENT sur le festival en français. Jamais dire 'je ne sais pas'.

DONNÉES PERTINENTES:
{$relevantData}

Question: {$userMessage}";
            }
            
            // Sinon, envoyer les données complètes mais optimisées
            return $this->buildCompletePrompt();
            
        } catch (\Exception $e) {
            Log::error('Error building optimized prompt', ['error' => $e->getMessage()]);
            return "🎵 Tu es l'assistant du Festival Cale Sons 2026. Réponds UNIQUEMENT sur le festival en français. Jamais dire 'je ne sais pas'.";
        }
    }

    /**
     * Construit le prompt complet avec toutes les données
     */
    private function buildCompletePrompt(): string
    {
        try {
            $festivals = Festival::with('manifestations')->get();
            $artistes = Artiste::all();
            $lieux = Lieux::all();

            // 🎭 Formater les festivals et manifestations
            $festivalInfos = $festivals->map(function ($fest) {
                $manifs = $fest->manifestations->map(function ($m) {
                    $prix = $m->PRIXMANIF ? $m->PRIXMANIF . '€' : 'GRATUIT';
                    return "  • {$m->NOMMANIF} - {$m->RESUMEMANIF} | {$prix} | {$m->NBMAXPARTICIPANTMANIF} pers.";
                })->join("\n");

                return "**{$fest->THEMEFEST}** ({$fest->DATEDEBFEST->format('d/m/Y')} au {$fest->DATEFINFEST->format('d/m/Y')})\n{$manifs}";
            })->join("\n\n");

            // 🎤 Lister les artistes
            $artistesInfos = $artistes->map(function ($a) {
                return "{$a->PRENOMPERS} {$a->NOMPERS}";
            })->join(", ");

            // 📍 Lister les lieux
            $lieuxInfos = $lieux->map(function ($l) {
                return "• {$l->NOMLIEUX} ({$l->CAPACITEMAXLIEUX} places) - {$l->ADRESSELIEUX}";
            })->join("\n");

            return "🎵 RÔLE: Tu es l'assistant VIP du Festival Cale Sons 2026.
👤 PERSONNALITÉ: Expert, enthousiaste, sympathique et ultra-compétent.

📅 INFORMATIONS EN TEMPS RÉEL:

FESTIVALS & MANIFESTATIONS:
{$festivalInfos}

🎤 ARTISTES:
{$artistesInfos}

📍 LIEUX:
{$lieuxInfos}

⚡ INSTRUCTIONS:
1. Donne des infos DÉTAILLÉES et SPÉCIFIQUES
2. JAMAIS dire 'Je ne sais pas'
3. En français uniquement
4. Propose des alternatives
5. Réponds UNIQUEMENT sur le Festival Cale Sons 2026";
        } catch (\Exception $e) {
            Log::error('Error building complete prompt', ['error' => $e->getMessage()]);
            return "🎵 Tu es l'assistant du Festival Cale Sons 2026. Réponds UNIQUEMENT sur le festival en français.";
        }
    }

    /**
     * Appelle l'API Google Gemini avec prompt optimisé
     */
    private function callGeminiAPI(string $apiKey, string $userMessage): string
    {
        try {
            // 🎯 Utiliser un prompt optimisé selon le contexte
            $systemPrompt = $this->buildOptimizedPrompt($userMessage);
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";

            $response = Http::post($url, [
                "contents" => [
                    [
                        "role" => "user",
                        "parts" => [
                            ["text" => "{$systemPrompt}\n\nQuestion utilisateur : {$userMessage}"]
                        ]
                    ]
                ]
            ]);

            $data = $response->json();
            
            if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                Log::error('Gemini API invalid response', $data);
                return $this->fallbackResponses->generate($userMessage);
            }

            $text = $data['candidates'][0]['content']['parts'][0]['text'];
            
            // 🔧 Décoder les entités HTML pour éviter les problèmes d'encodage
            return html_entity_decode($text, ENT_QUOTES, 'UTF-8');

        } catch (\Exception $e) {
            Log::error('Chatbot Exception', ['error' => $e->getMessage()]);
            return $this->fallbackResponses->generate($userMessage);
        }
    }

    /**
     * Sauvegarde et broadcast un message du bot
     */
    private function saveBotMessage(Conversation $conversation, string $content): void
    {
        $botMsg = Message::create([
            'conversation_id' => $conversation->id,
            'sender' => 'bot',
            'content' => $content,
        ]);

        broadcast(new MessageSent($botMsg));
    }
}
