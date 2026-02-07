<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Conversation;

/**
 * ==========================================
 * EVENT: DEMANDE D'ESCALADE ADMIN
 * ==========================================
 * 
 * Cet événement est déclenché quand un utilisateur demande
 * à parler à un humain au lieu du bot.
 * 
 * Il notifie les admins qu'une conversation a besoin d'assistance.
 * 
 * Implémente ShouldBroadcastNow:
 * → L'événement est diffusé IMMÉDIATEMENT via WebSocket (pas de queue)
 */
class AdminRequested implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // La conversation qui demande l'escalade
    public $conversation;

    /**
     * Créer une nouvelle instance d'événement.
     * 
     * @param Conversation $conversation La conversation escaladée
     */
    public function __construct(Conversation $conversation)
    {
        $this->conversation = $conversation;
    }

    /**
     * Get the channels the event should broadcast on.
     * 
     * Sur quel canal WebSocket diffuser cet événement ?
     * 
     * → PrivateChannel('admin-support'): Canal PRIVÉ
     *   - Seuls les admins connectés devraient y avoir accès
     *   - Actuellement: TODO - vérifier l'authentification admin
     * 
     * → Les admins écoutent ce canal pour être notifiés
     * 
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin-support'),
        ];
    }

    /**
     * The event's broadcast name.
     * 
     * Quel est le nom de l'événement écouté côté client ?
     * 
     * → Les admins écoutent '.admin.requested' sur le canal 'admin-support'
     * 
     * Dans Vue/Alpine côté admin, ils feraient:
     * window.Echo.private('admin-support')
     *     .listen('.admin.requested', (event) => { ... })
     * 
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'admin.requested';
    }

    /**
     * Get the data to broadcast.
     * 
     * Quelles données envoyer aux admins via WebSocket ?
     * 
     * Ces données seront sérialisées en JSON et envoyées
     * à tous les clients abonnés au canal 'admin-support'
     * 
     * @return array Les données à diffuser
     */
    public function broadcastWith(): array
    {
        return [
            // L'ID unique de la conversation
            'conversation_id' => $this->conversation->conversation_id,
            
            // Booléen: true = admin est actif (gérant cette conversation)
            'admin_active' => $this->conversation->admin_active,
            
            // Le dernier message envoyé par l'utilisateur
            'last_message' => $this->conversation->messages()->latest()->first()?->content,
            
            // Quand la conversation a commencé
            'created_at' => $this->conversation->created_at,
        ];
    }
}
