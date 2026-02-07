<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Assistance & Support - Festival Cale Sons</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white">

    @include('layouts.header')

    <section class="h-[400px] bg-gradient-to-br from-festival-primary to-festival-secondary flex items-center justify-center">
        <div class="text-center text-white px-6">
            <h1 class="text-5xl font-bold mb-4">🎧 Assistance & Support</h1>
            <p class="text-xl">Nous sommes là pour vous aider !</p>
        </div>
    </section>


    <section class="py-16">
        <div class="max-w-7xl mx-auto px-6">
        
            <!-- Section Chat en direct -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border border-festival-dark/5">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-festival-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-festival-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.955 8.955 0 01-2.563-.37l-3.687 1.54A1 1 0 016 20.31V17.94A8 8 0 1121 12z"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-festival-dark mb-3">💬 Chat en Direct</h2>
                    <p class="text-festival-dark/70">Discutez avec notre équipe support en temps réel</p>
                </div>

                <!-- Chatbot intégré dans la page -->
                <div class="max-w-4xl mx-auto">
                    <div x-data="chatWidget()" x-init="initChat()" class="w-full max-w-2xl mx-auto font-sans">
                        <div class="w-full h-96 bg-white rounded-lg shadow border border-festival-dark/10 flex flex-col overflow-hidden">

                            <!-- Header du chat -->
                            <div class="bg-festival-primary p-4 text-white flex items-center gap-3 shadow-sm">
                                <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                                <div>
                                    <h3 class="font-bold">Assistant Cale Sons</h3>
                                </div>
                            </div>

                            <!-- Zone de messages -->
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

                            <!-- Zone de saisie -->
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

                <!-- Script du chat - même logique que le widget flottant -->
                <script>
                    /**
                     * 🎯 CHATBOT WIDGET - INTERFACE UTILISATEUR
                     * 
                     * Fonction Alpine.js qui gère l'interface de chat côté client.
                     * 
                     * Responsabilités:
                     * 1. Gérer l'état du chat (messages, input, loading)
                     * 2. Envoyer les messages au serveur (POST /chat/{id}/send)
                     * 3. Écouter les réponses via WebSocket (Laravel Echo)
                     * 4. Afficher les messages en temps réel
                     * 5. Gérer l'historique des messages
                     */
                    function chatWidget() {
                        return {
                            // 📊 État du widget
                            messages: [{ id: 1, sender: 'bot', content: 'Bonjour ! Je suis l\'assistant support de Cale Sons. Comment puis-je vous aider aujourd\'hui ?' }],
                            userInput: '',
                            isLoading: false,
                            conversationId: localStorage.getItem('chatConversationId') || ('conv_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9)),

                            /**
                             * 🚀 Initialiser le widget de chat
                             * 
                             * Appelé une seule fois au chargement de la page
                             * 
                             * Étapes:
                             * 1. Sauvegarder l'ID de conversation en localStorage
                             * 2. Réinitialiser les messages au message d'accueil
                             *    (Important: pas de loadMessages() pour éviter de montrer l'historique)
                             * 3. Configurer l'écoute WebSocket (setupRealtime)
                             * 4. Scroller vers le bas pour montrer le dernier message
                             */
                            initChat() {
                                localStorage.setItem('chatConversationId', this.conversationId);
                                // Réinitialiser les messages à chaque chargement
                                // Cela empêche les utilisateurs non-auth de voir les conversations précédentes
                                this.messages = [{ id: 1, sender: 'bot', content: 'Bonjour ! Je suis l\'assistant support de Cale Sons. Comment puis-je vous aider aujourd\'hui ?' }];
                                this.setupRealtime();
                                this.scrollToBottom();
                            },

                            /**
                             * 💬 Envoyer un message à l'API
                             * 
                             * Flux:
                             * 1. Valider que l'input n'est pas vide
                             * 2. Ajouter le message utilisateur à l'écran immédiatement
                             * 3. Vider l'input et afficher "L'assistant écrit..."
                             * 4. Faire un POST vers /chat/{conversationId}/send
                             *    - Contient: message + conversationId
                             *    - Headers: X-CSRF-TOKEN + Content-Type JSON
                             * 5. Attendre la réponse JSON du serveur
                             * 6. Ajouter la réponse du bot aux messages (eviter les doublons via WebSocket)
                             * 7. En cas d'erreur, afficher un message d'erreur
                             * 8. Scroller vers le bas
                             * 
                             * Important: La réponse du bot arrive DEUX FOIS:
                             *   - Via HTTP response (immédiat)
                             *   - Via WebSocket (broadcast) (quelques ms plus tard)
                             *   → On évite les doublons en vérifiant si le message existe déjà
                             */
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
                                    // Appel API: POST /chat/{conversationId}/send
                                    const response = await fetch(`/chat/${this.conversationId}/send`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                        },
                                        body: JSON.stringify({ message, conversationId: this.conversationId })
                                    });

                                    if (!response.ok) {
                                        throw new Error('Erreur réseau');
                                    }

                                    const data = await response.json();
                                    if (data.reply) {
                                        // Vérifier si le message existe DÉJÀ (éviter les doublons WebSocket)
                                        const exists = this.messages.some(m => m.content === data.reply && m.sender === 'bot');
                                        if (!exists) {
                                            this.messages.push({
                                                id: Date.now(),
                                                sender: 'bot',
                                                content: data.reply
                                            });
                                        }
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

                            /**
                             * 📖 Charger l'historique des messages
                             * 
                             * ACTUELLEMENT DÉSACTIVÉ dans initChat()
                             * 
                             * Raison: Pour éviter de montrer aux utilisateurs non-authentifiés
                             * l'historique d'autres conversations
                             * 
                             * Récupère: GET /chat/{conversationId}/messages
                             * Retourne: Array de {id, sender, content, created_at}
                             * 
                             * Flux (si activé):
                             * 1. Faire un GET vers /chat/{conversationId}/messages
                             * 2. Vérifier que data.messages n'est pas vide
                             * 3. Mapper les messages pour extraire id, sender, content
                             * 4. Remplacer le message d'accueil par l'historique
                             * 5. Scroller vers le bas
                             * 
                             * Attention: Cette fonction est présente dans le code mais NON APPELÉE
                             */
                            loadMessages() {
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

                            /**
                             * 📡 Configurer l'écoute WebSocket (Laravel Echo)
                             * 
                             * Utilise: window.Echo (fourni par Laravel Echo + Reverb)
                             * 
                             * Flux:
                             * 1. Vérifier que window.Echo existe (sinon WebSocket désactivé)
                             * 2. S'abonner au canal public: conversation.{conversationId}
                             * 3. Écouter l'événement: .message.sent
                             * 
                             * Quand une réponse arrive (MessageSent event):
                             * 1. Recevoir: {id, conversation_id, sender, content, created_at}
                             * 2. Vérifier que le message n'existe pas déjà (éviter doublons)
                             * 3. L'ajouter aux messages si nouveau
                             * 4. Scroller vers le bas automatiquement
                             * 
                             * Cas d'usage:
                             * - Message du bot (sender='bot') → Réponse de ChatbotController
                             * - Message admin (sender='admin') → Réponse d'un humain pendant escalade
                             * 
                             * Architecture:
                             *   Frontend ─POST→ ChatbotController ─broadcast→ MessageSent event ─WebSocket→ Echo listener ─→ this.messages
                             * 
                             * C'est ce qui permet l'affichage en TEMPS RÉEL
                             */
                            setupRealtime() {
                                if (!window.Echo) {
                                    console.warn('WebSocket pas encore prêt, nouvelle tentative dans 1s...');
                                    // Réessayer toutes les secondes jusqu'à ce que Echo soit disponible
                                    let attempts = 0;
                                    const interval = setInterval(() => {
                                        attempts++;
                                        if (window.Echo) {
                                            clearInterval(interval);
                                            console.log('✅ WebSocket connecté après ' + attempts + ' tentative(s)');
                                            this.connectEcho();
                                        } else if (attempts > 10) {
                                            clearInterval(interval);
                                            console.error('❌ WebSocket indisponible après 10 tentatives');
                                        }
                                    }, 1000);
                                    return;
                                }
                                this.connectEcho();
                            },

                            connectEcho() {
                                // S'abonner au canal public de conversation
                                window.Echo.channel(`conversation.${this.conversationId}`)
                                    .listen('.message.sent', (event) => {
                                        // Éviter les doublons: vérifier si le message existe
                                        const exists = this.messages.some(msg => 
                                            msg.id === event.id || 
                                            (msg.content === event.content && msg.sender === event.sender)
                                        );
                                        if (!exists) {
                                            // Ajouter le nouveau message reçu via WebSocket
                                            this.messages.push({
                                                id: event.id,
                                                sender: event.sender,
                                                content: event.content
                                            });
                                            this.scrollToBottom();
                                        }
                                    });
                                console.log('✅ Écoute WebSocket active sur conversation.' + this.conversationId);
                            },

                            /**
                             * ⬇️ Scroller automatiquement vers le bas
                             * 
                             * Permet de voir le dernier message quand on en reçoit un nouveau
                             * 
                             * Utilise: this.$nextTick()
                             * Raison: Attendre que Alpine.js ait rendu le DOM avant de scroller
                             * 
                             * Logique:
                             * 1. Trouver l'élément HTML: #chat-box (la zone de messages)
                             * 2. Définir scrollTop = scrollHeight
                             *    (scrollHeight = hauteur totale du contenu)
                             *    (cela force un scroll vers le bas)
                             */
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
        <div class="grid md:grid-cols-2 gap-8 mb-12 mt-8">
            
            <!-- FAQ Fréquentes -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-festival-dark/5">
                <h2 class="text-2xl font-bold text-festival-dark mb-6 flex items-center">
                    <span class="w-8 h-8 bg-festival-primary/10 rounded-full flex items-center justify-center mr-3">
                        ❓
                    </span>
                    Questions Fréquentes
                </h2>

                <div class="space-y-4">
                    <details class="group">
                        <summary class="flex justify-between items-center font-medium cursor-pointer list-none p-4 bg-festival-light rounded-lg hover:bg-festival-light/80">
                            <span class="text-festival-dark">Comment réserver un billet ?</span>
                            <span class="transition group-open:rotate-180">
                                <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24">
                                    <path d="m6 9 6 6 6-6"></path>
                                </svg>
                            </span>
                        </summary>
                        <p class="text-festival-dark/70 mt-3 p-4">
                            Connectez-vous à votre compte, choisissez un festival, puis cliquez sur "Réserver" pour la manifestation de votre choix. Les paiements se font via Stripe pour les événements payants.
                        </p>
                    </details>

                    <details class="group">
                        <summary class="flex justify-between items-center font-medium cursor-pointer list-none p-4 bg-festival-light rounded-lg hover:bg-festival-light/80">
                            <span class="text-festival-dark">Où trouver mes billets ?</span>
                            <span class="transition group-open:rotate-180">
                                <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24">
                                    <path d="m6 9 6 6 6-6"></path>
                                </svg>
                            </span>
                        </summary>
                        <p class="text-festival-dark/70 mt-3 p-4">
                            Vos billets sont disponibles dans la section "Mes Réservations" de votre compte. Chaque billet contient un QR code pour l'entrée.
                        </p>
                    </details>

                    <details class="group">
                        <summary class="flex justify-between items-center font-medium cursor-pointer list-none p-4 bg-festival-light rounded-lg hover:bg-festival-light/80">
                            <span class="text-festival-dark">Que faire en cas de problème de paiement ?</span>
                            <span class="transition group-open:rotate-180">
                                <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24">
                                    <path d="m6 9 6 6 6-6"></path>
                                </svg>
                            </span>
                        </summary>
                        <p class="text-festival-dark/70 mt-3 p-4">
                            Contactez-nous immédiatement via le chat ou vérifiez l'historique de vos paiements Stripe. Nous pouvons restaurer votre réservation en cas de problème technique.
                        </p>
                    </details>
                </div>
            </div>

            <!-- Aide rapide -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-festival-dark/5">
                <h2 class="text-2xl font-bold text-festival-dark mb-6 flex items-center">
                    <span class="w-8 h-8 bg-festival-primary/10 rounded-full flex items-center justify-center mr-3">
                        ⚡
                    </span>
                    Aide Rapide
                </h2>

                <div class="space-y-4">
                    <a href="{{ route('page.mes-reservations') }}" class="block p-4 bg-festival-light hover:bg-festival-light/80 rounded-lg transition duration-200 group border border-festival-primary/10">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-festival-primary/20 rounded-full flex items-center justify-center mr-4 group-hover:bg-festival-primary/30">
                                🎫
                            </div>
                            <div>
                                <h3 class="font-semibold text-festival-dark">Mes Billets</h3>
                                <p class="text-sm text-festival-dark/70">Consulter mes réservations</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('festivals') }}" class="block p-4 bg-festival-light hover:bg-festival-light/80 rounded-lg transition duration-200 group border border-festival-primary/10">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-festival-primary/20 rounded-full flex items-center justify-center mr-4 group-hover:bg-festival-primary/30">
                                🎪
                            </div>
                            <div>
                                <h3 class="font-semibold text-festival-dark">Festivals</h3>
                                <p class="text-sm text-festival-dark/70">Découvrir les événements</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('contact') }}" class="block p-4 bg-festival-light hover:bg-festival-light/80 rounded-lg transition duration-200 group border border-festival-primary/10">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-festival-primary/20 rounded-full flex items-center justify-center mr-4 group-hover:bg-festival-primary/30">
                                📞
                            </div>
                            <div>
                                <h3 class="font-semibold text-festival-dark">Contact Direct</h3>
                                <p class="text-sm text-festival-dark/70">Formulaire de contact</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Bouton retour -->
        <div class="text-center mb-8">
            <a href="{{ route('festivals') }}" class="inline-flex items-center text-festival-primary hover:text-festival-secondary font-semibold transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour aux festivals
            </a>
        </div>

    </section>

    @include('layouts.footer')

</body>
</html>