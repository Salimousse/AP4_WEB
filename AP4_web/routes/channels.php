<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    // Canal public pour le chatbot - accessible à tous
    return true;
});

Broadcast::channel('admin-support', function ($user) {
    // Canal public temporairement pour les tests - accessible à tous
    // TODO: Implémenter une vraie vérification admin
    return true;
});
