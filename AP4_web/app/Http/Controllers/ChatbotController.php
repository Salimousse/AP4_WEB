<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Conversation;
use App\Models\Message;
use App\Events\MessageSent;
use App\Events\AdminRequested;

class ChatbotController extends Controller
{
    public function sendMessage(Request $request)
    {
        // 1. Validation
        $request->validate([
            'message' => 'required|string',
            'conversationId' => 'required|string',
        ]);

        $userMessageText = $request->input('message');
        $conversationId = $request->input('conversationId');

        // 2. Récupérer ou créer la conversation
        $conversation = Conversation::firstOrCreate(
            ['conversation_id' => $conversationId],
            ['admin_active' => false]
        );

        // 3. Stocker le message utilisateur
        $userMessage = Message::create([
            'conversation_id' => $conversation->id,
            'sender' => 'user',
            'content' => $userMessageText,
        ]);

        // Diffuser le message utilisateur
        broadcast(new MessageSent($userMessage));

        // 4. Vérifier si demande d'humain
        if (stripos($userMessageText, 'humain') !== false || stripos($userMessageText, 'admin') !== false || stripos($userMessageText, 'parler à') !== false) {
            $conversation->update(['admin_active' => true]);

            // Notifier les admins en temps réel
            broadcast(new AdminRequested($conversation));

            // Stocker réponse automatique
            $botMessage = Message::create([
                'conversation_id' => $conversation->id,
                'sender' => 'bot',
                'content' => "Un administrateur va prendre le relais. Veuillez patienter.",
            ]);

            // Diffuser le message bot
            broadcast(new MessageSent($botMessage));
            return response()->json(['reply' => "Un administrateur va prendre le relais. Veuillez patienter."]);
        }

        // 5. Si admin actif, vérifier s'il y a une réponse admin
        if ($conversation->admin_active) {
            $adminMessage = Message::where('conversation_id', $conversation->id)
                ->where('sender', 'admin')
                ->where('created_at', '>', now()->subMinutes(1)) // Réponse récente
                ->first();
            if ($adminMessage) {
                return response()->json(['reply' => $adminMessage->content]);
            } else {
                return response()->json(['reply' => "L'administrateur prépare sa réponse..."]);
            }
        }

        // 6. Sinon, appel IA
        $apiKey = env('GOOGLE_AI_KEY');

        if (!$apiKey) {
            return response()->json(['reply' => "Erreur : Clé API manquante."], 500);
        }

        // 7. Le Cerveau (Contexte du Festival)
        $systemPrompt = "
            RÔLE: Tu es l'assistant du Festival Cale Sons 2026.
            TON: Enthousiaste, concis et utile.
            INFOS:
            - Date : Août 2026.
            - Thème : 'Terres de Légendes'.
            - Activités : Concerts, Ateliers.
            IMPORTANT:
            - Si tu ne sais pas répondre, dis que tu ne peux pas répondre.
            - Reponds à l'utilisateur sur toutes ces questions à propos du festival :
              - Dates et horaires
              - Lieu et accès
              - Programmation musicale
              - Ateliers et activités
              - Tarifs et billets
              - Hébergement à proximité
              - Restauration sur place
              - Mesures sanitaires
              - Contacts et informations supplémentaires
            - Ne parle pas d'autres sujets.
            - Réponds en français.


        ";

        try {
            // 8. Appel API
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url, [
                "contents" => [
                    [
                        "role" => "user",
                        "parts" => [
                            ["text" => $systemPrompt . "\n\n Question utilisateur : " . $userMessage]
                        ]
                    ]
                ]
            ]);

            if ($response->failed()) {
                Log::error('Erreur Google API', $response->json() ?? []);
                return response()->json([
                    'reply' => "Erreur technique (" . $response->status() . ")"
                ], 500);
            }

            $data = $response->json();
            $botReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!$botReply) {
                $botReply = "Je n'ai pas compris, pouvez-vous reformuler ?";
            }

            // 9. Stocker réponse bot
            $botMessage = Message::create([
                'conversation_id' => $conversation->id,
                'sender' => 'bot',
                'content' => $botReply,
            ]);

            // Diffuser le message bot
            broadcast(new MessageSent($botMessage));

            return response()->json(['reply' => $botReply]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['reply' => "Erreur système."], 500);
        }
    }

    public function getMessages($conversationId)
    {
        $conversation = Conversation::where('conversation_id', $conversationId)->first();

        if (!$conversation) {
            return response()->json(['messages' => []]);
        }

        $messages = $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->get(['id', 'sender', 'content', 'created_at']);

        return response()->json(['messages' => $messages]);
    }

    public function checkMessage(Request $request, $conversationId)
    {
        $conversation = Conversation::where('conversation_id', $conversationId)->first();
        if (!$conversation || !$conversation->admin_active) {
            return response()->json(['message' => null]);
        }
        $latestAdminMessage = Message::where('conversation_id', $conversation->id)
            ->where('sender', 'admin')
            ->latest()
            ->first();
        return response()->json(['message' => $latestAdminMessage ? $latestAdminMessage->content : null]);
    }
}