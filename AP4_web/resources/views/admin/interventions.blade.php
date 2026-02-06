<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interventions Chatbot</title>
    <script src="https://js.pusherapp.com/8.2/pusher.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/js/app.js', 'resources/js/admin-realtime.js'])
    @else
        {{-- Dev fallback to Vite dev server (no manifest) --}}
        <script type="module" src="http://localhost:5173/resources/js/app.js"></script>
        <script type="module" src="http://localhost:5173/resources/js/admin-realtime.js"></script>
    @endif
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Interventions Chatbot</h1>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-sm text-gray-600">Serveur actif</span>
                </div>
                <span id="pending-requests-count" class="bg-red-500 text-white text-sm px-2 py-1 rounded-full hidden">0</span>
            </div>
        </div>

        <!-- Conteneur pour les notifications temps réel -->
        <div id="admin-notifications" class="fixed top-4 right-4 z-50 space-y-2 max-w-sm">
            <!-- Les notifications apparaîtront ici -->
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($conversations as $conversation)
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            Conversation #{{ substr($conversation->conversation_id, -8) }}
                        </h3>
                        @if($conversation->admin_active)
                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                Actif
                            </span>
                        @else
                            <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">
                                En attente
                            </span>
                        @endif
                    </div>

                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Dernier message :</p>
                        <p class="text-sm bg-gray-50 p-3 rounded-lg">
                            {{ Str::limit($conversation->messages->last()->content ?? 'Aucun message', 100) }}
                        </p>
                    </div>

                    <div class="flex justify-between items-center text-xs text-gray-500 mb-4">
                        <span>{{ $conversation->created_at->diffForHumans() }}</span>
                        <span>{{ $conversation->messages->count() }} messages</span>
                    </div>

                    <a href="{{ route('admin.intervention.show', $conversation->id) }}"
                       class="block w-full bg-blue-600 text-white text-center py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                        Voir et répondre
                    </a>
                </div>
            @endforeach
        </div>

        @if($conversations->isEmpty())
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">💬</div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Aucune conversation</h3>
                <p class="text-gray-500">Les conversations avec demandes d'admin apparaîtront ici.</p>
            </div>
        @endif
    </div>

    <script>
        // Définir l'utilisateur actuel comme admin pour les WebSockets
        window.currentUser = { is_admin: true };
    </script>
</body>
</html>