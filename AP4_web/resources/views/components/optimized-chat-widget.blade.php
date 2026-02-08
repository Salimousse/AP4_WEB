@props(['conversationId' => null, 'initialMessage' => "Bonjour ! Je suis l'assistant support de Cale Sons. Comment puis-je vous aider aujourd'hui ?"])

<div x-data="optimizedChat()" x-init="initChat()" class="w-full max-w-2xl mx-auto font-sans">
    <div class="w-full h-96 bg-white rounded-lg shadow border border-festival-dark/10 flex flex-col overflow-hidden">

        <!-- Header -->
        <div class="bg-festival-primary p-4 text-white flex items-center gap-3 shadow-sm">
            <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
            <div>
                <h3 class="font-bold">Assistant Cale Sons</h3>
            </div>
        </div>

        <!-- Messages -->
        <div id="chat-box" class="flex-1 overflow-y-auto p-4 space-y-3 bg-festival-light/30">
            <template x-for="msg in messages" :key="msg.id">
                <div class="flex" :class="msg.sender === 'user' ? 'justify-end' : 'justify-start'">
                    <div class="max-w-[80%] rounded-lg px-4 py-2 shadow-sm"
                         :class="msg.sender === 'user' ? 'bg-festival-primary text-white rounded-br-sm' : 'bg-white text-festival-dark border border-festival-dark/10 rounded-bl-sm'">
                        <p x-text="msg.content" class="text-sm leading-relaxed"></p>
                    </div>
                </div>
            </template>
            <div x-show="isLoading" class="flex justify-start">
                <div class="bg-white border border-festival-dark/10 rounded-lg rounded-bl-sm px-4 py-2 shadow-sm">
                    <p class="text-sm text-festival-dark/50 italic">L'assistant écrit...</p>
                </div>
            </div>
        </div>

        <!-- Input -->
        <div class="p-4 bg-white border-t border-festival-dark/10">
            <div class="flex gap-3">
                <input type="text" x-model="userInput" @keydown.enter="sendMessage()" 
                       placeholder="Tapez votre message..."
                       class="flex-1 border border-festival-dark/10 rounded-lg focus:ring-2 focus:ring-festival-primary focus:border-festival-primary bg-festival-light/20 px-4 py-2 text-sm outline-none">
                <button @click="sendMessage()" 
                        class="bg-festival-primary text-white rounded-lg px-4 py-2 hover:bg-festival-secondary transition-colors shadow-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    <span class="text-sm font-medium">Envoyer</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * 💬 Composant Chat Optimisé
     * 
     * Utilise:
     * - ChatAdapter pour abstraire le WebSocket
     * - Fetch natif (pas axios)
     * - Code plus propre et testable
     */
    function optimizedChat() {
        return {
            messages: [{ id: 1, sender: 'bot', content: '{{ $initialMessage }}' }],
            userInput: '',
            isLoading: false,
            conversationId: localStorage.getItem('chatConversationId') || 
                           ('conv_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9)),
            chatAdapter: null,

            initChat() {
                localStorage.setItem('chatConversationId', this.conversationId);
                this.setupWebsocket();
            },

            /**
             * Configure le WebSocket via ChatAdapter
             * Au lieu de gérer Echo directement
             */
            setupWebsocket() {
                if (!window.ChatAdapter || !window.webSocketService) {
                    console.warn('Services WebSocket non disponibles, nouvelle tentative...');
                    setTimeout(() => this.setupWebsocket(), 500);
                    return;
                }

                this.chatAdapter = new window.ChatAdapter(
                    this.conversationId, 
                    window.webSocketService
                );

                // Simple: une seule fonction pour gérer les messages
                this.chatAdapter.connect((message) => {
                    // Éviter les doublons
                    if (!this.messages.some(m => m.id === message.id)) {
                        this.messages.push(message);
                        this.scrollToBottom();
                    }
                });
            },

            async sendMessage() {
                if (!this.userInput.trim()) return;

                const message = this.userInput.trim();
                this.userInput = '';

                this.messages.push({
                    id: Date.now(),
                    sender: 'user',
                    content: message
                });
                this.scrollToBottom();

                this.isLoading = true;

                try {
                    const response = await fetch(`/chat/${this.conversationId}/send`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ message, conversationId: this.conversationId })
                    });

                    if (!response.ok) throw new Error('Erreur réseau');

                    const data = await response.json();
                    if (data.reply && !this.messages.some(m => m.content === data.reply && m.sender === 'bot')) {
                        this.messages.push({
                            id: Date.now(),
                            sender: 'bot',
                            content: data.reply
                        });
                    }
                } catch (error) {
                    console.error('Erreur:', error);
                    this.messages.push({
                        id: Date.now(),
                        sender: 'bot',
                        content: 'Désolé, une erreur est survenue.'
                    });
                } finally {
                    this.isLoading = false;
                    this.scrollToBottom();
                }
            },

            scrollToBottom() {
                this.$nextTick(() => {
                    const chatBox = document.getElementById('chat-box');
                    if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
                });
            }
        };
    }
</script>
