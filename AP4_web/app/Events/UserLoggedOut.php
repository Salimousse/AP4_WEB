<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

/**
 * ==========================================
 * EVENT: UTILISATEUR DÉCONNECTÉ
 * ==========================================
 * 
 * Cet événement est déclenché quand un utilisateur
 * se déconnecte de l'application.
 * 
 * Responsabilité: Supprimer les conversations
 * temporaires de l'utilisateur.
 */
class UserLoggedOut
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
