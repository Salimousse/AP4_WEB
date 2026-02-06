<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assistance & Support - CALE SONS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <nav class="bg-white shadow-sm py-4">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-blue-600">CALE SONS</a>
            <div class="flex gap-4">
                <a href="{{ route('festivals') }}" class="text-gray-600 hover:text-blue-600">Festivals</a>
                @auth
                    <a href="{{ route('page.mes-reservations') }}" class="text-gray-600 hover:text-blue-600">Mes Réservations</a>
                @endauth
                <a href="/" class="text-gray-600 hover:text-blue-600">Accueil</a>
            </div>
        </div>
    </nav>

    <header class="bg-blue-600 text-white py-16 text-center px-4">
        <h1 class="text-4xl md:text-6xl font-black mb-4">🎧 Assistance & Support</h1>
        <p class="text-blue-200 text-xl font-light">Nous sommes là pour vous aider !</p>
    </header>

    <main class="container mx-auto px-6 py-12">
        
        <!-- Section Chat en direct -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.955 8.955 0 01-2.563-.37l-3.687 1.54A1 1 0 016 20.31V17.94A8 8 0 1121 12z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-3">💬 Chat en Direct</h2>
                <p class="text-gray-600">Discutez avec notre équipe support en temps réel</p>
            </div>

            <!-- Chatbot intégré dans la page -->
            <div class="max-w-4xl mx-auto">
                <div x-data="chatWidget()" x-init="initChat()" class="w-full max-w-2xl mx-auto font-sans">
                    <div class="w-full h-96 bg-white rounded-lg shadow border border-gray-200 flex flex-col overflow-hidden">

                        <!-- Header du chat -->
                        <div class="bg-blue-600 p-4 text-white flex items-center gap-3 shadow-sm">
                            <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                            <div>
                                <h3 class="font-bold">Assistant Cale Sons</h3>
                                <p class="text-xs text-blue-200">En ligne • Réponse sous 2 minutes</p>
                            </div>
                        </div>

                        <!-- Zone de messages -->
                        <div id="chat-box" class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50">
                            <template x-for="msg in messages" :key="msg.id">
                                <div class="flex" :class="msg.sender === 'user' ? 'justify-end' : 'justify-start'">
                                    <div class="max-w-[80%] rounded-lg px-4 py-2 shadow-sm"
                                         :class="msg.sender === 'user' ? 'bg-blue-600 text-white rounded-br-sm' : 'bg-white text-gray-800 border border-gray-100 rounded-bl-sm'">
                                        <p x-text="msg.content" class="text-sm leading-relaxed"></p>
                                    </div>
                                </div>
                            </template>
                            <div x-show="isLoading" class="flex justify-start">
                                <div class="bg-white border border-gray-100 rounded-lg rounded-bl-sm px-4 py-2 shadow-sm">
                                    <p class="text-sm text-gray-500 italic">L'assistant écrit...</p>
                                </div>
                            </div>
                        </div>

                        <!-- Zone de saisie -->
                        <div class="p-4 bg-white border-t border-gray-100">
                            <div class="flex gap-3">
                                <input type="text" x-model="userInput" @keydown.enter="sendMessage()" 
                                       placeholder="Tapez votre message..."
                                       class="flex-1 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 px-4 py-2 text-sm outline-none">
                                <button @click="sendMessage()" 
                                        class="bg-blue-600 text-white rounded-lg px-4 py-2 hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                    <span class="text-sm font-medium">Envoyer</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Script du chat - même logique que le widget flottant -->
                <script>
                    function chatWidget() {
                        return {
                            messages: [{ id: 1, sender: 'bot', content: 'Bonjour ! Je suis l\'assistant support de Cale Sons. Comment puis-je vous aider aujourd\'hui ?' }],
                            userInput: '',
                            isLoading: false,
                            conversationId: localStorage.getItem('chatConversationId') || ('conv_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9)),

                            initChat() {
                                localStorage.setItem('chatConversationId', this.conversationId);
                                this.loadMessages();
                                this.setupRealtime();
                                this.scrollToBottom();
                            },

                            async sendMessage() {
                                if (!this.userInput.trim()) return;

                                const message = this.userInput.trim();
                                this.userInput = '';

                                // Ajouter le message utilisateur
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
                                        body: JSON.stringify({ message })
                                    });

                                    if (!response.ok) {
                                        throw new Error('Erreur réseau');
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
                            },

                            loadMessages() {
                                // Charger l'historique si disponible
                                fetch(`/chat/${this.conversationId}/messages`)
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.messages && data.messages.length > 0) {
                                            this.messages = data.messages.map(msg => ({
                                                id: msg.id,
                                                sender: msg.sender,
                                                content: msg.content
                                            }));
                                        }
                                        this.scrollToBottom();
                                    })
                                    .catch(error => console.error('Erreur chargement messages:', error));
                            },

                            setupRealtime() {
                                if (!window.Echo) {
                                    console.warn('WebSocket non disponible');
                                    return;
                                }

                                window.Echo.channel(`conversation.${this.conversationId}`)
                                    .listen('.message.sent', (event) => {
                                        const exists = this.messages.some(msg => msg.id === event.id);
                                        if (!exists) {
                                            this.messages.push({
                                                id: event.id,
                                                sender: event.sender,
                                                content: event.content
                                            });
                                            this.scrollToBottom();
                                        }
                                    });
                            },

                            scrollToBottom() {
                                this.$nextTick(() => {
                                    const chatBox = document.getElementById('chat-box');
                                    if (chatBox) {
                                        chatBox.scrollTop = chatBox.scrollHeight;
                                    }
                                });
                            }
                        }
                    }
                </script>
            </div>
        </div>

        <!-- Section FAQ -->
        <div class="grid md:grid-cols-2 gap-8 mb-12">
            
            <!-- FAQ Fréquentes -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <span class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                        ❓
                    </span>
                    Questions Fréquentes
                </h2>

                <div class="space-y-4">
                    <details class="group">
                        <summary class="flex justify-between items-center font-medium cursor-pointer list-none p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                            <span>Comment réserver un billet ?</span>
                            <span class="transition group-open:rotate-180">
                                <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24">
                                    <path d="m6 9 6 6 6-6"></path>
                                </svg>
                            </span>
                        </summary>
                        <p class="text-gray-600 mt-3 p-4">
                            Connectez-vous à votre compte, choisissez un festival, puis cliquez sur "Réserver" pour la manifestation de votre choix. Les paiements se font via Stripe pour les événements payants.
                        </p>
                    </details>

                    <details class="group">
                        <summary class="flex justify-between items-center font-medium cursor-pointer list-none p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                            <span>Où trouver mes billets ?</span>
                            <span class="transition group-open:rotate-180">
                                <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24">
                                    <path d="m6 9 6 6 6-6"></path>
                                </svg>
                            </span>
                        </summary>
                        <p class="text-gray-600 mt-3 p-4">
                            Vos billets sont disponibles dans la section "Mes Réservations" de votre compte. Chaque billet contient un QR code pour l'entrée.
                        </p>
                    </details>

                    <details class="group">
                        <summary class="flex justify-between items-center font-medium cursor-pointer list-none p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                            <span>Que faire en cas de problème de paiement ?</span>
                            <span class="transition group-open:rotate-180">
                                <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24">
                                    <path d="m6 9 6 6 6-6"></path>
                                </svg>
                            </span>
                        </summary>
                        <p class="text-gray-600 mt-3 p-4">
                            Contactez-nous immédiatement via le chat ou vérifiez l'historique de vos paiements Stripe. Nous pouvons restaurer votre réservation en cas de problème technique.
                        </p>
                    </details>
                </div>
            </div>

            <!-- Aide rapide -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <span class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        ⚡
                    </span>
                    Aide Rapide
                </h2>

                <div class="space-y-4">
                    <a href="{{ route('page.mes-reservations') }}" class="block p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition duration-200 group">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-200 rounded-full flex items-center justify-center mr-4 group-hover:bg-blue-300">
                                🎫
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Mes Billets</h3>
                                <p class="text-sm text-gray-600">Consulter mes réservations</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('festivals') }}" class="block p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition duration-200 group">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-200 rounded-full flex items-center justify-center mr-4 group-hover:bg-purple-300">
                                🎪
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Festivals</h3>
                                <p class="text-sm text-gray-600">Découvrir les événements</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('contact') }}" class="block p-4 bg-green-50 hover:bg-green-100 rounded-lg transition duration-200 group">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-200 rounded-full flex items-center justify-center mr-4 group-hover:bg-green-300">
                                📞
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Contact Direct</h3>
                                <p class="text-sm text-gray-600">Formulaire de contact</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Section Informations -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">📋 Informations Importantes</h2>
            
            <div class="grid md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Horaires Support</h3>
                    <p class="text-gray-600 text-sm">
                        Lundi - Vendredi<br>
                        9h00 - 18h00<br>
                        <span class="text-green-600 font-medium">● En ligne maintenant</span>
                    </p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.302 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Urgences</h3>
                    <p class="text-gray-600 text-sm">
                        Problème le jour J ?<br>
                        Utilisez le chat<br>
                        <span class="text-red-600 font-medium">Réponse garantie sous 5min</span>
                    </p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Sécurité</h3>
                    <p class="text-gray-600 text-sm">
                        Paiements 100% sécurisés<br>
                        Données protégées<br>
                        <span class="text-indigo-600 font-medium">Conforme RGPD</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Bouton retour -->
        <div class="text-center mt-12">
            <a href="{{ route('festivals') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour aux festivals
            </a>
        </div>

    </main>

    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; {{ date('Y') }} CALE SONS - Tous droits réservés</p>
            <div class="mt-4 space-x-4">
                <a href="{{ route('support') }}" class="text-gray-400 hover:text-white">Support</a>
                <a href="{{ route('privacy') }}" class="text-gray-400 hover:text-white">Confidentialité</a>
                <a href="{{ route('terms') }}" class="text-gray-400 hover:text-white">CGV</a>
            </div>
        </div>
    </footer>

</body>
</html>