<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail Conversation - {{ substr($conversation->conversation_id, -8) }}</title>
    <script src="https://js.pusherapp.com/8.2/pusher.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/js/app.js', 'resources/js/chat-realtime.js'])
    @else
        {{-- Dev fallback to Vite dev server (no manifest) --}}
        <script type="module" src="http://localhost:5173/resources/js/app.js"></script>
        <script type="module" src="http://localhost:5173/resources/js/chat-realtime.js"></script>
    @endif
</head>
<body class="bg-gray-100 min-h-screen" x-data="chatDetail()">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        Conversation #{{ substr($conversation->conversation_id, -8) }}
                    </h1>
                    <p class="text-gray-600 mt-1">
                        Créée {{ $conversation->created_at->diffForHumans() }}
                        @if($conversation->admin_active)
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Actif
                            </span>
                        @endif
                    </p>
                </div>
                <a href="{{ route('admin.interventions') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                    ← Retour aux interventions
                </a>
            </div>
        </div>

        <!-- Chat Interface -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Messages Container -->
            <div id="chat-messages" class="h-96 overflow-y-auto p-6 space-y-4 bg-gray-50">
                @foreach($conversation->messages as $message)
                    <div class="flex" x-data="{ message: @js($message) }"
                         :class="message.sender === 'user' ? 'justify-end' : 'justify-start'">
                        <div class="max-w-[75%] rounded-2xl px-4 py-3 shadow-sm"
                             :class="message.sender === 'user' ? 'bg-blue-600 text-white rounded-br-none' : message.sender === 'admin' ? 'bg-green-600 text-white rounded-bl-none' : 'bg-gray-200 text-gray-800 rounded-bl-none'">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-medium opacity-75">
                                    {{ ucfirst($message->sender) }}
                                </span>
                                <span class="text-xs opacity-50">
                                    {{ $message->created_at->format('H:i') }}
                                </span>
                            </div>
                            <p class="text-sm">{{ $message->content }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Message Input -->
            <div class="p-6 bg-white border-t border-gray-100">
                <form @submit.prevent="sendAdminMessage()">
                    <div class="flex gap-3">
                        <input type="text" x-model="newMessage"
                               placeholder="Tapez votre réponse..."
                               class="flex-1 border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                        <button type="submit"
                                :disabled="!newMessage.trim()"
                                :class="!newMessage.trim() ? 'bg-gray-300 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700'"
                                class="px-6 py-3 text-white rounded-lg transition-colors font-medium">
                            Envoyer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function chatDetail() {
            return {
                newMessage: '',
                conversationId: '{{ $conversation->conversation_id }}',

                init() {
                    this.setupRealtime();
                    this.scrollToBottom();
                },

                setupRealtime() {
                    // Écouter les nouveaux messages pour cette conversation
                    if (window.Echo) {
                        // Use public channel as the event is broadcast on a public channel.
                        window.Echo.channel(`conversation.${this.conversationId}`)
                            .listen('.message.sent', (event) => {
                                console.log('Nouveau message dans la conversation:', event);
                                this.addMessage(event);
                            });
                    }
                },

                addMessage(messageData) {
                    const messagesContainer = document.getElementById('chat-messages');

                    const messageElement = document.createElement('div');
                    messageElement.className = 'flex';
                    messageElement.setAttribute('x-data', `{ message: ${JSON.stringify(messageData)} }`);

                    if (messageData.sender === 'user') {
                        messageElement.classList.add('justify-end');
                    } else {
                        messageElement.classList.add('justify-start');
                    }

                    const messageBubble = document.createElement('div');
                    messageBubble.className = 'max-w-[75%] rounded-2xl px-4 py-3 shadow-sm';

                    if (messageData.sender === 'user') {
                        messageBubble.classList.add('bg-blue-600', 'text-white', 'rounded-br-none');
                    } else if (messageData.sender === 'admin') {
                        messageBubble.classList.add('bg-green-600', 'text-white', 'rounded-bl-none');
                    } else {
                        messageBubble.classList.add('bg-gray-200', 'text-gray-800', 'rounded-bl-none');
                    }

                    messageBubble.innerHTML = `
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-medium opacity-75">
                                ${messageData.sender.charAt(0).toUpperCase() + messageData.sender.slice(1)}
                            </span>
                            <span class="text-xs opacity-50">
                                ${new Date(messageData.created_at).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })}
                            </span>
                        </div>
                        <p class="text-sm">${messageData.content}</p>
                    `;

                    messageElement.appendChild(messageBubble);
                    messagesContainer.appendChild(messageElement);

                    this.scrollToBottom();
                },

                sendAdminMessage() {
                    if (!this.newMessage.trim()) return;

                    const message = this.newMessage;
                    this.newMessage = '';

                    fetch(`{{ route("admin.intervention.respond", $conversation->id) }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ message: message })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Le message sera ajouté via WebSocket
                            console.log('Message admin envoyé');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors de l\'envoi:', error);
                        alert('Erreur lors de l\'envoi du message');
                    });
                },

                scrollToBottom() {
                    this.$nextTick(() => {
                        const container = document.getElementById('chat-messages');
                        if (container) {
                            container.scrollTop = container.scrollHeight;
                        }
                    });
                }
            }
        }

        // Définir l'utilisateur actuel comme admin
        window.currentUser = { role: 'admin' };
    </script>
</body>
</html>