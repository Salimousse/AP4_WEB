<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
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
        Message::create([
            'conversation_id' => $conversation->id,
            'sender' => 'admin',
            'content' => $request->message,
        ]);
        return redirect()->back()->with('success', 'Réponse envoyée.');
    }
}
