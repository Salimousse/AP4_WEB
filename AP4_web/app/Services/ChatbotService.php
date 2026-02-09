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
     * Appelle l'API Google Gemini
     */
    private function callGeminiAPI(string $apiKey, string $userMessage): string
    {
        try {
            $systemPrompt = $this->getSystemPrompt();
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
     * Retourne le prompt système pour l'IA avec données réelles de la BDD
     */
    private function getSystemPrompt(): string
    {
        try {
            // 📚 Récupérer les données réelles
            $festivals = Festival::with('manifestations')->get();
            $manifestions = Manifestation::all();
            $artistes = Artiste::all();
            $lieux = Lieux::all();

            // 🎭 Formater les festivals et manifestations
            $festivalInfos = $festivals->map(function ($fest) {
                $manifs = $fest->manifestations->map(function ($m) {
                    return "  • {$m->NOMMANIF} - {$m->RESUMEMANIF} | Prix: " . ($m->PRIXMANIF ? "{$m->PRIXMANIF}€" : "GRATUIT") . " | Max: {$m->NBMAXPARTICIPANTMANIF} pers.";
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

📅 INFORMATIONS EN TEMPS RÉEL (Données actualisées de la BDD):

FESTIVALS & MANIFESTATIONS:
{$festivalInfos}

🎤 ARTISTES CONFIRMÉS:
{$artistesInfos}

📍 LIEUX D'ACCUEIL:
{$lieuxInfos}

⚡ INSTRUCTIONS CRITIQUES:
1. TU DOIS donner des infos DÉTAILLÉES et SPÉCIFIQUES du festival
2. Toujours proposer au MINIMUM 2-3 événements ou tarifs
3. JAMAIS dire 'Je ne sais pas', 'Je n\'ai pas d\'info' ou 'Demandez quand'
4. PROPOSE des alternatives: 'Voulez-vous plutôt...'
5. Mentionne les artistes, lieux et dates réels
6. En français uniquement
7. Sois proactif: fais des suggestions de questions à poser après
8. Réponds UNIQUEMENT sur le Festival Cale Sons 2026";
        } catch (\Exception $e) {
            Log::error('Error fetching festival data', ['error' => $e->getMessage()]);
            return "🎵 RÔLE: Tu es l'assistant du Festival Cale Sons 2026.
TON: Enthousiaste, expert et très utile.
IMPORTANT: Donne des réponses DÉTAILLÉES, JAMAIS 'je ne sais pas'. En français uniquement.";
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
