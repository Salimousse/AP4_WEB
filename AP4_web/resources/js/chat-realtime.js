// Exemple d'utilisation de Laravel Echo pour écouter les messages en temps réel
// À ajouter dans votre JavaScript frontend

// Écouter les nouveaux messages pour une conversation spécifique
function listenToConversation(conversationId) {
    // S'abonner au canal de la conversation
    window.Echo.private(`conversation.${conversationId}`)
        .listen('.message.sent', (event) => {
            console.log('Nouveau message reçu:', event);

            // Ajouter le message à l'interface
            addMessageToUI({
                sender: event.sender,
                content: event.content,
                created_at: event.created_at
            });
        });
}

// Fonction exemple pour ajouter un message à l'interface
function addMessageToUI(message) {
    const messagesContainer = document.getElementById('messages-container');

    const messageElement = document.createElement('div');
    messageElement.className = `message ${message.sender}`;
    messageElement.innerHTML = `
        <div class="sender">${message.sender === 'user' ? 'Vous' : 'Bot'}</div>
        <div class="content">${message.content}</div>
        <div class="timestamp">${new Date(message.created_at).toLocaleTimeString()}</div>
    `;

    messagesContainer.appendChild(messageElement);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

// Démarrer l'écoute pour une conversation
// listenToConversation('conversation-id-here');