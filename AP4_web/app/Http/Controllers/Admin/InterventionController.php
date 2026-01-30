<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;

class InterventionController extends Controller
{
    public function index()
    {
        $conversations = Conversation::with('messages')->where('admin_active', true)->get();
        return view('admin.interventions', compact('conversations'));
    }

    public function show($id)
    {
        $conversation = Conversation::with('messages')->findOrFail($id);
        return view('admin.intervention-detail', compact('conversation'));
    }

    public function respond(Request $request, $id)
    {
        $request->validate(['message' => 'required|string']);

        $conversation = Conversation::findOrFail($id);

        // Créer le message admin
        $adminMessage = Message::create([
            'conversation_id' => $conversation->id,
            'sender' => 'admin',
            'content' => $request->message,
        ]);

        // Diffuser le message en temps réel
        broadcast(new MessageSent($adminMessage));

        return response()->json(['success' => true, 'message' => 'Réponse envoyée.']);
    }
}
