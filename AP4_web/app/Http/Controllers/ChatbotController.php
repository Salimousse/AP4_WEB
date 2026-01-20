<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function sendMessage(Request $request)
    {
        // 1. Validation
        $request->validate([
            'message' => 'required|string',
        ]);

        $userMessage = $request->input('message');
        $apiKey = env('GOOGLE_AI_KEY');

        if (!$apiKey) {
            return response()->json(['reply' => "Erreur : Clé API manquante."], 500);
        }

        // 2. Le Cerveau (Contexte du Festival)
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
            // 3. Appel API avec le bon modèle (gemini-2.5-flash)
            // On utilise v1beta car les versions 2.5+ sont souvent sur ce canal
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

            // 4. Vérification des erreurs
            if ($response->failed()) {
                Log::error('Erreur Google API', $response->json() ?? []);
                return response()->json([
                    'reply' => "Erreur technique (" . $response->status() . ")"
                ], 500);
            }

            // 5. Récupération de la réponse
            $data = $response->json();
            $botReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!$botReply) {
                return response()->json(['reply' => "Je n'ai pas compris, pouvez-vous reformuler ?"], 200);
            }

            return response()->json(['reply' => $botReply]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['reply' => "Erreur système."], 500);
        }
    }
}