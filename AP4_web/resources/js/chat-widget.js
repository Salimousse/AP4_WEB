/**
 * 🤖 ChatWidget - Service centralisé
 * 
 * Gère toute la logique du widget de chat côté client
 * Responsabilités:
 * - État du chat (messages, conversation)
 * - Communication avec l'API
 * - WebSocket (Laravel Echo)
 * - Affichage des messages
 */

export class ChatWidget {
    constructor(options = {}) {
        this.conversationId = options.conversationId || 
            (localStorage.getItem('chatConversationId') || 
             ('conv_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9)));
        
        this.messages = options.initialMessage ? 
            [{ id: 1, sender: 'bot', content: options.initialMessage }] : 
            [];
        
        this.userInput = '';
        this.isLoading = false;
        this.chatbox = options.chatbox;
    }

    /**
     * Initialise le widget
     */
    init() {
        localStorage.setItem('chatConversationId', this.conversationId);
        this.setupWebSocket();
        this.scrollToBottom();
    }

    /**
     * Configure l'écoute WebSocket (Laravel Echo)
     */
    setupWebSocket() {
        if (!window.Echo) {
            console.warn('Laravel Echo not loaded, retrying...');
            setTimeout(() => this.setupWebSocket(), 1000);
            return;
        }

        window.Echo.channel(`conversation.${this.conversationId}`)
            .listen('.message.sent', (event) => {
                const message = {
                    id: event.message.id,
                    sender: event.message.sender,
                    content: event.message.content
                };

                // Éviter les doublons
                if (!this.messages.some(m => m.id === message.id)) {
                    this.messages.push(message);
                    this.scrollToBottom();
                }
            });
    }

    /**
     * Envoie un message à l'API
     */
    async sendMessage(message) {
        if (!message.trim()) return;

        // Ajouter le message utilisateur immédiatement
        this.messages.push({
            id: Date.now(),
            sender: 'user',
            content: message
        });
        
        this.userInput = '';
        this.isLoading = true;
        this.scrollToBottom();

        try {
            const response = await fetch(`/chat/${this.conversationId}/send`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ 
                    message, 
                    conversationId: this.conversationId 
                })
            });

            if (!response.ok) {
                throw new Error('Erreur réseau');
            }

            const data = await response.json();
            
            // Ajouter la réponse si elle n'existe pas déjà via WebSocket
            if (data.reply && !this.messages.some(m => m.content === data.reply && m.sender === 'bot')) {
                this.messages.push({
                    id: Date.now(),
                    sender: 'bot',
                    content: data.reply
                });
            }
        } catch (error) {
            console.error('Erreur envoi:', error);
            this.messages.push({
                id: Date.now(),
                sender: 'bot',
                content: 'Désolé, une erreur est survenue. Veuillez réessayer.'
            });
        } finally {
            this.isLoading = false;
            this.scrollToBottom();
        }
    }

    /**
     * Charge l'historique des messages
     */
    async loadHistory() {
        try {
            const response = await fetch(`/chat/${this.conversationId}/messages`);
            const data = await response.json();

            if (data.messages && data.messages.length > 0) {
                this.messages = data.messages.map(msg => ({
                    id: msg.id,
                    sender: msg.sender,
                    content: msg.content
                }));
                this.scrollToBottom();
            }
        } catch (error) {
            console.error('Erreur chargement historique:', error);
        }
    }

    /**
     * Scroll vers le bas de la boîte de chat
     */
    scrollToBottom() {
        if (this.chatbox) {
            this.$nextTick(() => {
                this.chatbox.scrollTop = this.chatbox.scrollHeight;
            });
        }
    }
}
