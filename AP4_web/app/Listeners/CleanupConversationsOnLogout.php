<?php

namespace App\Listeners;

use App\Events\UserLoggedOut;
use App\Models\Conversation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * ==========================================
 * LISTENER: NETTOYAGE À LA DÉCONNEXION
 * ==========================================
 * 
 * Quand un utilisateur se déconnecte:
 * → Supprimer toutes ses conversations (avec messages)
 * 
 * Raison: Éviter de remplir la base de données
 * avec des conversations temporaires.
 */
class CleanupConversationsOnLogout implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Supprimer les conversations de l'utilisateur
     * 
     * La suppression en cascade supprime aussi les messages
     * (grâce à onDelete('cascade') dans la migration)
     */
    public function handle(UserLoggedOut $event): void
    {
        // Supprimer TOUTES les conversations de cet utilisateur
        Conversation::where('user_id', $event->user->id)->delete();
        
        // Alternative: Supprimer seulement les conversations sans escalade admin
        // Conversation::where('user_id', $event->user->id)
        //     ->where('admin_active', false)
        //     ->delete();
    }
}
