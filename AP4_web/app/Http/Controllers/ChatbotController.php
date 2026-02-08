<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Services\ChatbotService;

/**
 * 🤖 ChatbotController - Contrôleur simplifié
 * 
 * Délègue toute la logique au ChatbotService
 * Responsabilité unique: validation HTTP et routing
 */
class ChatbotController extends Controller
{
    /**
     * Traite un message utilisateur
     */
    public function sendMessage(Request $request, ChatbotService $chatbot)
    {
        $request->validate([
            'message' => 'required|string',
            'conversationId' => 'required|string',
        ]);

        $userMessage = $request->input('message');
        $conversationId = $request->input('conversationId');

        $reply = $chatbot->handleMessage($conversationId, $userMessage);

        return response()->json(['reply' => $reply]);
    }

    /**
     * Récupère l'historique des messages d'une conversation
     */
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

    /**
     * Vérifie si un admin a répondu
     */
    public function checkMessage($conversationId)
    {
        $conversation = Conversation::where('conversation_id', $conversationId)->first();

        if (!$conversation || !$conversation->admin_active) {
            return response()->json(['message' => null]);
        }

        $latestAdminMessage = Message::where('conversation_id', $conversation->id)
            ->where('sender', 'admin')
            ->latest()
            ->first();

        return response()->json(['message' => $latestAdminMessage?->content]);
    }
}