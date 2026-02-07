// JavaScript pour le panneau d'administration - notifications temps réel
// À utiliser dans le dashboard admin

// Écouter les demandes d'admin en temps réel
function listenToAdminRequests() {
    // Vérifier que Echo est disponible (pas besoin de vérifier currentUser pour l'instant)
    if (window.Echo) {
        console.log('Démarrage de l\'écoute des demandes admin...');
        window.Echo.private('admin-support')
            .listen('.admin.requested', (event) => {
                console.log('[ADMIN] Événement brut reçu:', event);
                console.log('[ADMIN] event.data:', event.data);
                console.log('[ADMIN] event keys:', Object.keys(event));
                
                // Essayer les deux structures possibles
                const data = event.data || event;
                console.log('[ADMIN] Données finales:', data);
                console.log('[ADMIN] Conversation ID:', data.id || data.conversation_id);

                showAdminNotification(data);
                playNotificationSound();
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
    console.log('📢 Données reçues dans showAdminNotification:', data);
    
    // Créer l'élément de notification
    const notification = document.createElement('div');
    notification.className = 'bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-lg';
    
    // Format du timestamp
    const timestamp = new Date(data.created_at).toLocaleTimeString('fr-FR');
    
    // L'ID peut être à data.id ou data.conversation_id selon comment l'événement arrive
    const conversationId = data.id || data.conversation_id;
    console.log('🔗 ID de conversation pour le bouton:', conversationId);
    
    notification.innerHTML = `
        <div class="flex justify-between items-start mb-2">
            <div>
                <p class="font-bold text-red-800">🚨 Demande de support - ${timestamp}</p>
                <p class="text-red-700 text-sm mt-1">Dernier message: <em>${data.last_message || 'Aucun message'}</em></p>
            </div>
            <button onclick="closeNotification(this)" class="text-red-500 hover:text-red-700 font-bold">✕</button>
        </div>
        <button onclick="openConversation(${conversationId})" class="mt-3 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition-colors">
            Prendre en charge →
        </button>
    `;

    // Ajouter au conteneur de notifications
    const container = document.getElementById('admin-notifications') || document.body;
    container.appendChild(notification);

    // Auto-suppression après 60 secondes
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 60000);
}

// Fermer une notification
function closeNotification(button) {
    button.closest('div').parentElement.remove();
}

// Fonction pour ouvrir une conversation
function openConversation(conversationId) {
    // conversationId est maintenant l'ID BDD (numérique)
    // Rediriger vers la page de détail de la conversation
    window.location.href = `/admin/interventions/${conversationId}`;
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

// Exporter les fonctions (APRÈS les définitions)
window.openConversation = openConversation;
window.closeNotification = closeNotification;
