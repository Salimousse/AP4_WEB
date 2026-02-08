/**
 * 💬 Chat Adapter - Simplifie l'utilisation du WebSocket dans les composants
 * 
 * Permet à n'importe quel composant d'écouter les messages sans gérer Echo
 */

export class ChatAdapter {
    constructor(conversationId, webSocketService) {
        this.conversationId = conversationId;
        this.ws = webSocketService;
        this.messageHandlers = [];
        this.channelName = `conversation.${conversationId}`;
        this.isConnected = false;
    }

    /**
     * Initialise l'adaptateur et s'abonne aux messages
     */
    connect(onMessageCallback) {
        this.messageHandlers.push(onMessageCallback);
        
        // S'abonner au canal
        this.ws.subscribePublic(this.channelName, () => {
            this.isConnected = true;
            console.log(`✅ Chat adapté à la conversation: ${this.conversationId}`);
        });

        // Écouter les messages
        this.ws.listen(this.channelName, 'message.sent', (event) => {
            this.handleMessage(event);
        });
    }

    /**
     * Gère un message reçu
     */
    handleMessage(event) {
        const message = {
            id: event.id,
            sender: event.sender,
            content: event.content,
            created_at: event.created_at
        };

        // Appeler tous les callbacks
        this.messageHandlers.forEach(handler => {
            try {
                handler(message);
            } catch (error) {
                console.error('❌ Erreur handler message:', error);
            }
        });
    }

    /**
     * S'abonne à un nouveau handler de messages
     */
    onMessage(callback) {
        this.messageHandlers.push(callback);
    }

    /**
     * Arrête d'écouter
     */
    disconnect() {
        this.ws.unsubscribe(this.channelName);
        this.messageHandlers = [];
        this.isConnected = false;
    }
}
