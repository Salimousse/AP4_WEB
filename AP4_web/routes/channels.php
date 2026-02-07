<?php

use Illuminate\Support\Facades\Broadcast;

// ========================================
// 🔊 CONFIGURATION DES CANAUX WEBSOCKET
// ========================================
// 
// Ce fichier configure les canaux de broadcast (diffusion)
// utilisés par Laravel Reverb pour les connexions WebSocket.
//
// Deux types de canaux:
// 1. PUBLIC: Accessible à tous (return true)
// 2. PRIVATE: Nécessite authentification (return true/false selon condition)

/**
 * Canal utilisateur par défaut (généré par Laravel)
 * 
 * Utilisé par: Notifications et messages privés
 * Format: App.Models.User.{id}
 * 
 * Vérification: L'ID de l'utilisateur connecté doit correspondre
 *               à l'ID du canal demandé
 * 
 * Accès:
 * - Seul l'utilisateur 42 peut écouter le canal App.Models.User.42
 */
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

/**
 * 💬 Canal de conversation (CHATBOT)
 * 
 * Format: conversation.{conversationId}
 * Type: PUBLIC
 * 
 * Objectif: Permet à tous les participants d'une conversation
 *           de recevoir les messages en temps réel
 * 
 * Flux:
 * 1. Utilisateur ouvre /assistance sans auth
 * 2. Un UUID conversation_id est généré (JavaScript)
 * 3. Frontend fait: window.Echo.channel('conversation.' + conversationId)
 * 4. ChatbotController::sendMessage() crée un Message
 * 5. broadcast(new MessageSent($message)) diffuse sur ce canal
 * 6. Tous les clients écoutant 'conversation.{id}' reçoivent le message
 * 
 * Événement diffusé: MessageSent
 * Événement écouté: .message.sent
 * 
 * Données reçues:
 * {
 *   "id": 42,
 *   "conversation_id": 1,
 *   "sender": "user|bot|admin",
 *   "content": "Texte du message",
 *   "created_at": "2024-01-15T10:30:00Z"
 * }
 * 
 * SÉCURITÉ: Public - fonctionne sans authentification
 *           (Les utilisateurs ne voient que LEUR conversation)
 */
Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    return true;
});

/**
 * 🚨 Canal d'administration (ESCALADE)
 * 
 * Format: admin-support (pas de paramètre)
 * Type: PRIVATE (mais actuellement non sécurisé)
 * 
 * Objectif: Notifier les admins quand une escalade est demandée
 * 
 * Flux d'escalade:
 * 1. Utilisateur écrit: "Je veux parler à un admin"
 * 2. ChatbotController détecte le mot-clé
 * 3. AdminRequested event est émis:
 *    broadcast(new AdminRequested($conversation))
 * 4. Admins écoutent ce canal et reçoivent la notification
 * 
 * Événement diffusé: AdminRequested
 * Événement écouté: .admin.requested
 * 
 * Données reçues:
 * {
 *   "conversation_id": "uuid-123",
 *   "admin_active": true,
 *   "last_message": "Aidez-moi, je veux parler à un humain!"
 * }
 * 
 * ⚠️ SECURITY TODO:
 * Actuellement SANS SÉCURITÉ - return true accepte tous
 * 
 * Solution recommandée:
 * Broadcast::channel('admin-support', function ($user) {
 *     // Vérifier que l'utilisateur est un admin
 *     return $user && $user->is_admin === true;
 * });
 * 
 * Cela empêcherait les utilisateurs normaux d'accéder au canal
 */
Broadcast::channel('admin-support', function ($user) {
    return true; // TODO: Implémenter une vraie vérification is_admin
});
