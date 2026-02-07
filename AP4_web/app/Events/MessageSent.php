<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

/**
 * ==========================================
 * EVENT: MESSAGE ENVOYÉ (CHATBOT)
 * ==========================================
 * 
 * Cet événement est déclenché chaque fois qu'un message
 * est créé dans une conversation (utilisateur, bot, ou admin).
 * 
 * Il notifie TOUS les clients écoutant cette conversation
 * qu'un nouveau message est arrivé.
 * 
 * Implémente ShouldBroadcastNow:
 * → Diffusion IMMÉDIATE via WebSocket (pas de queue/délai)
 */
class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // Le message à diffuser
    public $message;

    /**
     * Créer une nouvelle instance d'événement.
     * 
     * @param Message $message Le message à diffuser
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     * 
     * Sur quel canal WebSocket diffuser ce message ?
     * 
     * → Channel('conversation.' + conversation_id): Canal PUBLIC
     *   - Accessible à TOUS (pas d'authentification requise)
     *   - Permet aux utilisateurs non-connectés de chatter
     *   - La sécurité vient du conversationId unique (non-prédictible)
     * 
     * Exemple: 'conversation.conv_123_abcdef'
     * 
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Utilise un canal public pour que le chatbot soit accessible sans auth
        return [
            new Channel('conversation.' . $this->message->conversation->conversation_id),
        ];
    }

    /**
     * The event's broadcast name.
     * 
     * Quel nom l'événement peut-il être écouté côté client ?
     * 
     * → '.message.sent'
     * 
     * Dans Vue/Alpine côté frontend, le client fait:
     * window.Echo.channel('conversation.conv_123_abcdef')
     *     .listen('.message.sent', (event) => {
     *         // Ajouter le message au chat
     *         this.messages.push(event);
     *     });
     * 
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * Get the data to broadcast.
     * 
     * Quelles données envoyer aux clients via WebSocket ?
     * 
     * Ces données seront sérialisées en JSON et envoyées
     * à TOUS les clients écoutant le canal 'conversation.{id}'
     * 
     * @return array Les données à diffuser
     */
    public function broadcastWith(): array
    {
        return [
            // ID unique du message (pour éviter les doublons)
            'id' => $this->message->id,
            
            // L'ID de la conversation (pour regrouper les messages)
            'conversation_id' => $this->message->conversation->conversation_id,
            
            // Qui a envoyé le message ? ('user', 'bot', ou 'admin')
            // Permet au frontend de styliser différemment
            'sender' => $this->message->sender,
            
            // Le contenu du message
            'content' => $this->message->content,
            
            // Quand le message a été créé
            'created_at' => $this->message->created_at,
        ];
    }
}
