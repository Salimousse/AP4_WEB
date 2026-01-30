<div x-data="chatWidget()" x-init="initChat()" class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-2 font-sans" style="display: none;" x-show="true">

    <div x-show="isOpen"
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-10 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-10 scale-95"
         class="w-80 h-96 bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden border border-gray-200">

        <div class="bg-blue-600 p-3 text-white flex justify-between items-center shadow-md">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                <h3 class="font-bold text-sm">Assistant Cale Sons</h3>
            </div>
            <button @click="isOpen = false" class="text-white hover:bg-blue-700 rounded p-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <div id="chat-box" class="flex-1 overflow-y-auto p-3 space-y-3 bg-gray-50 text-sm">
            <template x-for="msg in messages" :key="msg.id">
                <div class="flex" :class="msg.sender === 'user' ? 'justify-end' : 'justify-start'">
                    <div class="max-w-[85%] rounded-2xl px-3 py-2 shadow-sm"
                         :class="msg.sender === 'user' ? 'bg-blue-600 text-white rounded-br-none' : 'bg-white text-gray-800 border border-gray-100 rounded-bl-none'">
                        <p x-text="msg.content"></p>
                    </div>
                </div>
            </template>
            <div x-show="isLoading" class="text-xs text-gray-500 italic ml-2">L'assistant écrit...</div>
        </div>

        <div class="p-3 bg-white border-t border-gray-100">
            <div class="flex gap-2">
                <input type="text" x-model="userInput" @keydown.enter="sendMessage()" placeholder="Une question ?"
                       class="w-full text-sm border-gray-200 rounded-full focus:ring-blue-500 focus:border-blue-500 bg-gray-50 px-3 py-2 outline-none">
                <button @click="sendMessage()" class="bg-blue-600 text-white rounded-full p-2 hover:bg-blue-700 transition shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <button @click="isOpen = !isOpen"
            class="h-14 w-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg flex items-center justify-center transition-all transform hover:scale-110 focus:outline-none">
        <svg x-show="!isOpen" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        <svg x-show="isOpen" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>

<script>
    function chatWidget() {
        return {
            isOpen: false,
            messages: [{ id: 1, sender: 'bot', content: 'Bonjour ! Je suis l\'IA du Festival. Une question ?' }],
            userInput: '',
            isLoading: false,
            conversationId: localStorage.getItem('chatConversationId') || ('conv_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9)),

            initChat() {
                localStorage.setItem('chatConversationId', this.conversationId);
                this.loadMessages();
                this.setupRealtime();
            },

            setupRealtime() {
                // Écouter les messages temps réel pour cette conversation
                if (window.Echo) {
                    window.Echo.private(`conversation.${this.conversationId}`)
                        .listen('.message.sent', (event) => {
                            console.log('Nouveau message temps réel:', event);

                            // Éviter les doublons
                            const exists = this.messages.some(msg => msg.id === event.id);
                            if (!exists) {
                                this.messages.push({
                                    id: event.id,
                                    sender: event.sender,
                                    content: event.content
                                });
                                this.scrollToBottom();

                                // Si c'est un message admin, arrêter le polling
                                if (event.sender === 'admin') {
                                    this.stopPolling();
                                }
                            }
                        });
                }
            },

            loadMessages() {
                axios.get(`/chat/${this.conversationId}/messages`)
                    .then(res => {
                        res.data.messages.forEach(msg => {
                            // Éviter les doublons
                            if (!this.messages.some(m => m.id === msg.id)) {
                                this.messages.push({
                                    id: msg.id,
                                    sender: msg.sender,
                                    content: msg.content
                                });
                            }
                        });
                        this.scrollToBottom();
                    })
                    .catch(err => console.log('Load messages error', err));
            },

            checkForAdminMessage() {
                // Garder le polling comme fallback si WebSocket ne fonctionne pas
                if (this.messages.length > 1) {
                    axios.get(`/chat/${this.conversationId}/check`)
                        .then(res => {
                            if (res.data.message) {
                                const exists = this.messages.some(msg => msg.content === res.data.message && msg.sender === 'admin');
                                if (!exists) {
                                    this.messages.push({ id: Date.now(), sender: 'admin', content: res.data.message });
                                    this.scrollToBottom();
                                }
                            }
                        })
                        .catch(err => console.log('Check error', err))
                        .finally(() => {
                            // Polling moins fréquent maintenant qu'on a WebSocket
                            setTimeout(() => this.checkForAdminMessage(), 10000); // Toutes les 10 secondes
                        });
                } else {
                    setTimeout(() => this.checkForAdminMessage(), 10000);
                }
            },

            stopPolling() {
                // Cette méthode pourrait être appelée pour arrêter le polling
                // Mais pour l'instant on le garde comme fallback
            },

            sendMessage() {
                if (this.userInput.trim() === '') return;
                const userMsg = this.userInput;
                this.messages.push({ id: Date.now(), sender: 'user', content: userMsg });
                this.userInput = '';
                this.scrollToBottom();
                this.isLoading = true;

                axios.post(`/chat/${this.conversationId}/send`, { message: userMsg, conversationId: this.conversationId })
                    .then(res => {
                        // Le message bot arrivera via WebSocket, pas besoin de l'ajouter ici
                        // Mais on garde comme fallback
                        setTimeout(() => {
                            if (!this.messages.some(msg => msg.content === res.data.reply && msg.sender === 'bot')) {
                                this.messages.push({ id: Date.now() + 1, sender: 'bot', content: res.data.reply });
                                this.scrollToBottom();
                            }
                        }, 1000); // Délai pour laisser WebSocket arriver en premier
                    })
                    .catch(err => {
                        this.messages.push({ id: Date.now(), sender: 'bot', content: "Je ne peux pas répondre pour l'instant." });
                    })
                    .finally(() => {
                        this.isLoading = false;
                        this.scrollToBottom();
                    });
            },

            scrollToBottom() {
                this.$nextTick(() => {
                    const box = document.getElementById('chat-box');
                    if(box) box.scrollTop = box.scrollHeight;
                });
            }
        }
    }
</script>