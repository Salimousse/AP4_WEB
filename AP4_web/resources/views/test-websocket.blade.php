<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test WebSocket</title>
    <script src="https://js.pusherapp.com/8.2/pusher.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Test WebSocket Reverb</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Test conversation -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Test Conversation</h2>
                <button onclick="testConversationMessage()" class="bg-blue-500 text-white px-4 py-2 rounded mb-4">
                    Envoyer message test
                </button>
                <div id="conversation-log" class="bg-gray-50 p-4 rounded text-sm max-h-64 overflow-y-auto">
                    Logs de conversation...
                </div>
            </div>

            <!-- Test admin notifications -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Test Notifications Admin</h2>
                <button onclick="testAdminNotification()" class="bg-red-500 text-white px-4 py-2 rounded mb-4">
                    Simuler demande admin
                </button>
                <div id="admin-log" class="bg-gray-50 p-4 rounded text-sm max-h-64 overflow-y-auto">
                    Logs admin...
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="mt-8 bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Status</h2>
            <div id="status" class="text-sm">
                Initialisation...
            </div>
        </div>
    </div>

    <script>
        // Test des WebSockets
        document.addEventListener('DOMContentLoaded', function() {
            const statusDiv = document.getElementById('status');
            const conversationLog = document.getElementById('conversation-log');
            const adminLog = document.getElementById('admin-log');

            statusDiv.innerHTML = 'Connexion à Echo...';

            if (window.Echo) {
                statusDiv.innerHTML = 'Echo disponible ✓';

                // Tester la conversation (public channel)
                window.Echo.channel('conversation.test123')
                    .listen('.message.sent', (event) => {
                        conversationLog.innerHTML += `<div class="mb-2 p-2 bg-blue-100 rounded">
                            <strong>${event.sender}:</strong> ${event.content}
                        </div>`;
                        conversationLog.scrollTop = conversationLog.scrollHeight;
                    })
                    .error((error) => {
                        conversationLog.innerHTML += `<div class="mb-2 p-2 bg-red-100 rounded">
                            Erreur conversation: ${JSON.stringify(error)}
                        </div>`;
                    });

                // Tester les notifications admin
                window.Echo.private('admin-support')
                    .listen('.admin.requested', (event) => {
                        adminLog.innerHTML += `<div class="mb-2 p-2 bg-yellow-100 rounded">
                            Nouvelle demande admin: ${event.conversation_id}
                        </div>`;
                        adminLog.scrollTop = adminLog.scrollHeight;
                    })
                    .error((error) => {
                        adminLog.innerHTML += `<div class="mb-2 p-2 bg-red-100 rounded">
                            Erreur admin: ${JSON.stringify(error)}
                        </div>`;
                    });

                statusDiv.innerHTML += '<br>Écouteurs configurés ✓';
            } else {
                statusDiv.innerHTML = 'Echo non disponible ✗';
            }
        });

        // Fonction de test pour envoyer un message
        function testConversationMessage() {
            fetch('/chat/test123/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    message: 'Message de test ' + new Date().toLocaleTimeString(),
                    conversationId: 'test123'
                })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('conversation-log').innerHTML +=
                    `<div class="mb-2 p-2 bg-green-100 rounded">
                        Message envoyé: ${data.reply || 'OK'}
                    </div>`;
            })
            .catch(error => {
                document.getElementById('conversation-log').innerHTML +=
                    `<div class="mb-2 p-2 bg-red-100 rounded">
                        Erreur envoi: ${error.message}
                    </div>`;
            });
        }

        // Fonction de test pour simuler une demande admin
        function testAdminNotification() {
            fetch('/chat/test123/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    message: 'humain',
                    conversationId: 'test123'
                })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('admin-log').innerHTML +=
                    `<div class="mb-2 p-2 bg-green-100 rounded">
                        Demande admin simulée
                    </div>`;
            })
            .catch(error => {
                document.getElementById('admin-log').innerHTML +=
                    `<div class="mb-2 p-2 bg-red-100 rounded">
                        Erreur simulation: ${error.message}
                    </div>`;
            });
        }
    </script>
</body>
</html>