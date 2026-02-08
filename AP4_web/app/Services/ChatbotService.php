<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Message;
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
     * Retourne le prompt système pour l'IA
     */
    private function getSystemPrompt(): string
    {
        return "RÔLE: Tu es l'assistant du Festival Cale Sons 2026.
TON: Enthousiaste, concis et utile.
INFOS:
- Date : Août 2026.
- Thème : 'Terres de Légendes'.
- Activités : Concerts, Ateliers.
IMPORTANT:
- Réponds UNIQUEMENT sur le festival
- Réponds en français
- Si tu ne sais pas, dis-le clairement";
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
