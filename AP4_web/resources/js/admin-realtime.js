// JavaScript pour le panneau d'administration - notifications temps réel
// À utiliser dans le dashboard admin

// Écouter les demandes d'admin en temps réel
function listenToAdminRequests() {
    // Vérifier que Echo est disponible (pas besoin de vérifier currentUser pour l'instant)
    if (window.Echo) {
        console.log('Démarrage de l\'écoute des demandes admin...');
        window.Echo.private('admin-support')
            .listen('.admin.requested', (event) => {
                console.log('Nouvelle demande d\'admin reçue:', event);

                // Afficher la notification
                showAdminNotification(event);

                // Jouer un son de notification (optionnel)
                playNotificationSound();

                // Mettre à jour le compteur de demandes en attente
                updatePendingRequestsCount();
            })
            .error((error) => {
                console.error('Erreur WebSocket admin-support:', error);
            });
    } else {
        console.warn('Echo n\'est pas disponible');
    }
}

// Fonction pour afficher une notification de demande d'admin
function showAdminNotification(data) {
    // Créer l'élément de notification
    const notification = document.createElement('div');
    notification.className = 'admin-notification alert alert-warning';
    notification.innerHTML = `
        <div class="notification-header">
            <strong>Demande de support</strong>
            <span class="timestamp">${new Date(data.created_at).toLocaleTimeString()}</span>
        </div>
        <div class="notification-content">
            <p><strong>Dernier message:</strong> ${data.last_message || 'Aucun message'}</p>
            <button class="btn btn-primary btn-sm" onclick="openConversation('${data.conversation_id}')">
                Prendre en charge
            </button>
        </div>
    `;

    // Ajouter au conteneur de notifications
    const container = document.getElementById('admin-notifications') || document.body;
    container.appendChild(notification);

    // Auto-suppression après 30 secondes
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 30000);
}

// Fonction pour ouvrir une conversation
function openConversation(conversationId) {
    // Rediriger vers la page de chat admin avec cette conversation
    window.location.href = `/admin/chat/${conversationId}`;
}

// Fonction pour jouer un son de notification
function playNotificationSound() {
    try {
        const audio = new Audio('/sounds/notification.mp3');
        audio.play().catch(e => console.log('Son de notification non disponible'));
    } catch (e) {
        console.log('Son de notification non supporté');
    }
}

// Fonction pour mettre à jour le compteur de demandes
function updatePendingRequestsCount() {
    const counter = document.getElementById('pending-requests-count');
    if (counter) {
        const currentCount = parseInt(counter.textContent) || 0;
        counter.textContent = currentCount + 1;
        counter.style.display = 'inline';
    }
}

// Initialiser l'écoute quand la page se charge
document.addEventListener('DOMContentLoaded', function() {
    listenToAdminRequests();
});