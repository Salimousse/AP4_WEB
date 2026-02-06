<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement Confirmé - CALE SONS</title>
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
                <a href="{{ route('page.mes-reservations') }}" class="text-gray-600 hover:text-blue-600">Mes Réservations</a>
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-6 py-12 max-w-3xl">
        
        <!-- Confirmation Success -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <div class="text-center mb-8">
                <!-- Icône de succès -->
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                
                <h1 class="text-3xl md:text-4xl font-black text-gray-800 mb-3">
                    🎉 Paiement Confirmé !
                </h1>
                <p class="text-gray-600 text-lg">
                    Merci pour votre réservation. Votre billet a été généré avec succès.
                </p>
            </div>

            <!-- Détails de la réservation -->
            <div class="border-t border-gray-200 pt-6 space-y-4">
                <h2 class="text-xl font-bold text-gray-800 mb-4">📋 Détails de votre réservation</h2>
                
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Numéro de billet</p>
                        <p class="font-mono text-lg font-bold text-blue-600">#{{ $billet->IDBILLET }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Date de réservation</p>
                        <p class="font-semibold">{{ \Carbon\Carbon::parse($billet->DATERESERV)->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm text-blue-600 font-semibold mb-2">🎪 Festival</p>
                    <p class="font-bold text-lg">{{ $festival->THEMEFEST }}</p>
                    <p class="text-sm text-gray-600">
                        Du {{ \Carbon\Carbon::parse($festival->DATEDEBFEST)->format('d/m/Y') }} 
                        au {{ \Carbon\Carbon::parse($festival->DATEFINFEST)->format('d/m/Y') }}
                    </p>
                </div>

                <div class="bg-purple-50 p-4 rounded-lg">
                    <p class="text-sm text-purple-600 font-semibold mb-2">🎵 Manifestation</p>
                    <p class="font-bold text-lg">{{ $manifestation->NOMMANIF }}</p>
                    <div class="text-sm text-gray-600 mt-2">
                        <p>📅 {{ \Carbon\Carbon::parse($manifestation->DATEMANIF)->format('d/m/Y') }} à {{ $manifestation->HEUREDEBMANIF }}</p>
                        <p>📍 {{ $manifestation->LIEUMANIF }}</p>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-4 border-t">
                    <span class="text-gray-600">Prix payé</span>
                    <span class="text-2xl font-bold text-green-600">
                        @if($manifestation->PRIXMANIF == 0)
                            GRATUIT
                        @else
                            {{ number_format($manifestation->PRIXMANIF, 2) }} €
                        @endif
                    </span>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col md:flex-row gap-4">
                <a href="{{ route('page.ticket-reservation', ['idBillet' => $billet->IDBILLET]) }}" 
                   class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-lg text-center transition duration-200 shadow-lg hover:shadow-xl">
                    🎫 Voir mon billet complet
                </a>
                
                <a href="{{ route('page.mes-reservations') }}" 
                   class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-4 px-6 rounded-lg text-center transition duration-200">
                    📚 Toutes mes réservations
                </a>
            </div>

            <!-- Note importante -->
            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <p class="text-sm text-yellow-800">
                    <strong>📧 Important :</strong> Un email de confirmation a été envoyé à votre adresse. 
                    Conservez votre billet, il vous sera demandé à l'entrée.
                </p>
            </div>
        </div>

        <!-- Bouton retour -->
        <div class="text-center">
            <a href="{{ route('festivals') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                ← Retour aux festivals
            </a>
        </div>

    </main>

    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; {{ date('Y') }} CALE SONS - Tous droits réservés</p>
        </div>
    </footer>

</body>
</html>
