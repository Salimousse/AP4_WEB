<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Conversation;
use App\Models\Message;
use App\Events\MessageSent;
use App\Events\AdminRequested;

/**
 * ==========================================
 * CHATBOT CONTROLLER
 * ==========================================
 * 
 * Gère toute la logique du chatbot:
 * - Valide les messages
 * - Crée les conversations
 * - Détecte les demandes d'escalade
 * - Appelle l'IA Google Gemini
 * - Diffuse en WebSocket
 */
class ChatbotController extends Controller
{
    /**
     * 🎯 FONCTION PRINCIPALE: sendMessage()
     * 
     * Traite un message utilisateur et retourne une réponse du bot.
     * 
     * Flux:
     * 1. Valider la requête
     * 2. Créer/récupérer la conversation
     * 3. Sauvegarder le message utilisateur
     * 4. Vérifier si escalade admin demandée
     * 5. Appeler l'IA pour générer une réponse
     * 6. Stocker et diffuser la réponse
     * 
     * @param Request $request La requête HTTP contenant le message
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
        // ============================================
        // ÉTAPE 1: VALIDATION
        // ============================================
        // Vérifier que la requête contient message + conversationId
        $request->validate([
            'message' => 'required|string',
            'conversationId' => 'required|string',
        ]);

        // Récupérer les données
        $userMessageText = $request->input('message');
        $conversationId = $request->input('conversationId');

        // ============================================
        // ÉTAPE 2: CRÉER OU RÉCUPÉRER LA CONVERSATION
        // ============================================
        // Chaque utilisateur a un conversationId unique (généré côté frontend)
        // Si elle existe: la récupère
        // Si elle n'existe pas: la crée avec admin_active = false
        $conversation = Conversation::firstOrCreate(
            ['conversation_id' => $conversationId],
            ['admin_active' => false]
        );

        // ============================================
        // ÉTAPE 3: SAUVEGARDER LE MESSAGE UTILISATEUR
        // ============================================
        // Créer le message en BDD avec sender='user'
        $userMessage = Message::create([
            'conversation_id' => $conversation->id,
            'sender' => 'user',
            'content' => $userMessageText,
        ]);

        // 🚀 Diffuser le message en temps réel via WebSocket
        // Tous les clients abonnés reçoivent ce message immédiatement
        broadcast(new MessageSent($userMessage));

        // ============================================
        // ÉTAPE 4: DÉTECTER DEMANDE D'ESCALADE ADMIN
        // ============================================
        // Vérifier si l'utilisateur demande un humain
        if (stripos($userMessageText, 'humain') !== false || 
            stripos($userMessageText, 'admin') !== false || 
            stripos($userMessageText, 'parler à') !== false) {
            
            // Marquer la conversation comme "en support humain"
            $conversation->update(['admin_active' => true]);

            // 🔔 Notifier les admins qu'une escalade est demandée
            broadcast(new AdminRequested($conversation));

            // Message automatique à l'utilisateur
            $botMessage = Message::create([
                'conversation_id' => $conversation->id,
                'sender' => 'bot',
                'content' => "Un administrateur va prendre le relais. Veuillez patienter.",
            ]);

            // Diffuser ce message au client
            broadcast(new MessageSent($botMessage));
            
            // Répondre au client
            return response()->json(['reply' => "Un administrateur va prendre le relais. Veuillez patienter."]);
        }

        // ============================================
        // ÉTAPE 5: MODE SUPPORT HUMAIN ACTIF
        // ============================================
        // Si un admin est actif dans cette conversation,
        // attendre la réponse de l'admin au lieu d'appeler l'IA
        if ($conversation->admin_active) {
            // Chercher un message admin envoyé dans la dernière minute
            $adminMessage = Message::where('conversation_id', $conversation->id)
                ->where('sender', 'admin')
                ->where('created_at', '>', now()->subMinutes(1))
                ->first();
            
            if ($adminMessage) {
                // L'admin a répondu, retourner sa réponse
                return response()->json(['reply' => $adminMessage->content]);
            } else {
                // L'admin n'a pas encore répondu
                return response()->json(['reply' => "L'administrateur prépare sa réponse..."]);
            }
        }

        // ============================================
        // ÉTAPE 6: APPEL À L'IA (GOOGLE GEMINI)
        // ============================================
        // Récupérer la clé API Google depuis les variables d'environnement
        $apiKey = env('GOOGLE_AI_KEY');
        
        // ============================================
        // MODE FALLBACK (si clé API manquante)
        // ============================================
        if (!$apiKey) {

            // Répondre intelligemment selon le message de l'utilisateur
            $userLower = strtolower($userMessageText);
            
            if (stripos($userLower, 'festival') !== false || stripos($userLower, 'dispo') !== false) {
                $botReply = "🎵 **Festival Cale Sons 2026** 🎵\n\n📅 **Dates**: Août 2026\n🎭 **Thème**: 'Terres de Légendes'\n🎪 **Activités**: Concerts, Ateliers créatifs\n\nQue souhaitez-vous savoir de plus ? (tarifs, programmation, hébergement...)";
            } elseif (stripos($userLower, 'tarif') !== false || stripos($userLower, 'prix') !== false || stripos($userLower, 'billet') !== false) {
                $botReply = "💰 **Tarifs Festival 2026**\n\n🎫 Pass 1 jour: 45€\n🎟️ Pass 2 jours: 80€\n🌟 Pass VIP: 120€\n\n✨ Réductions étudiants disponibles !";
            } elseif (stripos($userLower, 'programme') !== false || stripos($userLower, 'artiste') !== false || stripos($userLower, 'concert') !== false) {
                $botReply = "🎤 **Programmation 2026**\n\n🌟 Têtes d'affiche à venir\n🎸 Scènes multiples\n🎶 Ambiance 'Terres de Légendes'\n\nLe programme complet sera dévoilé prochainement !";
            } elseif (stripos($userLower, 'lieu') !== false || stripos($userLower, 'où') !== false || stripos($userLower, 'adresse') !== false) {
                $botReply = "📍 **Localisation**\n\nLe festival se déroule dans un cadre exceptionnel.\n🚗 Parkings disponibles\n🚌 Navettes spéciales\n\nPlus d'infos sur l'accès bientôt !";
            } else {
                $botReply = "Bonjour ! 😊 Je suis l'assistant du Festival Cale Sons 2026.\n\nJe peux vous renseigner sur :\n🎵 Les festivals disponibles\n💰 Les tarifs\n📅 Les dates\n🎤 La programmation\n📍 L'accès\n\nQue souhaitez-vous savoir ?";
            }

            // Sauvegarder la réponse du bot
            $botMessage = Message::create([
                'conversation_id' => $conversation->id,
                'sender' => 'bot',
                'content' => $botReply,
            ]);

            // Diffuser la réponse en WebSocket
            broadcast(new MessageSent($botMessage));

            // Retourner la réponse au client
            return response()->json(['reply' => $botReply]);
        }

        // ============================================
        // ÉTAPE 7: CONTEXTE POUR L'IA
        // ============================================
        // Donner du contexte à Gemini pour qu'il connaisse son rôle
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
            // ============================================
            // ÉTAPE 8: APPEL API GOOGLE GEMINI
            // ============================================
            // URL de l'API Google AI (Gemini)
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";

            // Faire la requête HTTP POST avec le contexte système + question utilisateur
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url, [
                "contents" => [
                    [
                        "role" => "user",
                        "parts" => [
                            // Combiner le contexte système avec la question de l'utilisateur
                            ["text" => $systemPrompt . "\n\n Question utilisateur : " . $userMessageText]
                        ]
                    ]
                ]
            ]);

            // Vérifier si l'appel API a échoué
            if ($response->failed()) {
                Log::error('Erreur Google API', $response->json() ?? []);
                return response()->json([
                    'reply' => "Erreur technique (" . $response->status() . ")"
                ], 500);
            }

            // ============================================
            // ÉTAPE 9: EXTRAIRE LA RÉPONSE DE L'IA
            // ============================================
            // Gemini retourne la réponse dans une structure complexe
            $data = $response->json();
            $botReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!$botReply) {
                // Si la réponse est vide
                $botReply = "Je n'ai pas compris, pouvez-vous reformuler ?";
            }

            // ============================================
            // ÉTAPE 10: SAUVEGARDER LA RÉPONSE DU BOT
            // ============================================
            $botMessage = Message::create([
                'conversation_id' => $conversation->id,
                'sender' => 'bot',
                'content' => $botReply,
            ]);

            // 🚀 Diffuser la réponse en temps réel via WebSocket
            broadcast(new MessageSent($botMessage));

            // Retourner la réponse au client
            return response()->json(['reply' => $botReply]);

        } catch (\Exception $e) {
            // Erreur lors de l'appel API ou du traitement
            Log::error($e->getMessage());
            return response()->json(['reply' => "Erreur système."], 500);
        }
    }

    /**
     * 📋 Récupérer l'historique des messages
     * 
     * Retourne tous les messages d'une conversation.
     * 
     * Utilisé au chargement de la page pour restaurer l'historique
     * (Note: actuellement désactivé dans support.blade.php)
     * 
     * @param string $conversationId L'ID de la conversation
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMessages($conversationId)
    {
        // Chercher la conversation
        $conversation = Conversation::where('conversation_id', $conversationId)->first();

        // Si elle n'existe pas, retourner une liste vide
        if (!$conversation) {
            return response()->json(['messages' => []]);
        }

        // Récupérer tous les messages de cette conversation, triés par date
        $messages = $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->get(['id', 'sender', 'content', 'created_at']);

        // Retourner les messages en JSON
        return response()->json(['messages' => $messages]);
    }

    /**
     * ✅ Vérifier s'il y a une réponse admin
     * 
     * Utilisé pour vérifier périodiquement si un admin a répondu.
     * 
     * @param Request $request La requête HTTP
     * @param string $conversationId L'ID de la conversation
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkMessage(Request $request, $conversationId)
    {
        // Chercher la conversation
        $conversation = Conversation::where('conversation_id', $conversationId)->first();
        
        // Si elle n'existe pas ou si admin n'est pas actif
        if (!$conversation || !$conversation->admin_active) {
            return response()->json(['message' => null]);
        }
        
        // Chercher le message le plus récent d'un admin
        $latestAdminMessage = Message::where('conversation_id', $conversation->id)
            ->where('sender', 'admin')  // Seuls les messages avec sender='admin'
            ->latest()
            ->first();
        
        // Retourner le message de l'admin ou null
        return response()->json(['message' => $latestAdminMessage ? $latestAdminMessage->content : null]);
    }
}