<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programme - {{ $festival->THEMEFEST }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <nav class="bg-white shadow-sm py-4">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-blue-600">CALE SONS</a>
            <a href="/" class="text-gray-600 hover:text-blue-600">Retour Accueil</a>
        </div>
    </nav>

    <header class="bg-blue-600 text-white py-16 text-center px-4">
        <h1 class="text-4xl md:text-6xl font-black mb-4 uppercase">{{ $festival->THEMEFEST }}</h1>
        <div class="text-blue-200 text-xl font-light">
            📅 Du {{ \Carbon\Carbon::parse($festival->DATEDEBFEST)->format('d/m/Y') }} 
            au {{ \Carbon\Carbon::parse($festival->DATEFINFEST)->format('d/m/Y') }}
        </div>
    </header>

    <main class="container mx-auto px-6 py-12">
        
        <!-- CONCERTS -->
        @if($concerts->count() > 0)
        <section class="mb-16">
            <h2 class="text-3xl font-bold mb-8 border-l-4 border-blue-600 pl-4 flex items-center">
                <span class="text-4xl mr-3">🎵</span> Concerts
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($concerts as $manif)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300 flex flex-col border-t-4 border-blue-600">
                    <div class="h-48 bg-gradient-to-br from-blue-500 to-purple-600 relative flex items-center justify-center">
                        <div class="text-white text-6xl">🎸</div>
                        <div class="absolute top-4 right-4 bg-white text-gray-900 font-bold px-3 py-1 rounded-full text-sm shadow">
                            {{ $manif->PRIXMANIF == 0 ? 'Gratuit' : number_format($manif->PRIXMANIF, 0) . ' €' }}
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-bold mb-2 text-gray-900">{{ $manif->NOMMANIF }}</h3>
                        <p class="text-gray-600 text-sm mb-4 flex-1">{{ $manif->RESUMEMANIF }}</p>
                        @if($manif->concert && $manif->concert->DATEHEUREFINCONCERT)
                            <div class="text-xs text-gray-500 mb-3">
                                <span class="font-semibold">Fin prévue:</span> {{ \Carbon\Carbon::parse($manif->concert->DATEHEUREFINCONCERT)->format('H:i') }}
                            </div>
                        @endif
                        <div class="mt-auto pt-4 border-t border-gray-100 flex justify-between items-center">
                            <span class="text-xs text-gray-500">Places : {{ $manif->NBMAXPARTICIPANTMANIF }}</span>
                            <a href="{{ route('reservation.create', $manif->IDMANIF) }}" 
                               class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition">
                                Réserver
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        <!-- CONFÉRENCES -->
        @if($conferences->count() > 0)
        <section class="mb-16">
            <h2 class="text-3xl font-bold mb-8 border-l-4 border-green-600 pl-4 flex items-center">
                <span class="text-4xl mr-3">🎤</span> Conférences
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($conferences as $manif)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300 flex flex-col border-t-4 border-green-600">
                    <div class="h-48 bg-gradient-to-br from-green-500 to-teal-600 relative flex items-center justify-center">
                        <div class="text-white text-6xl">💡</div>
                        <div class="absolute top-4 right-4 bg-white text-gray-900 font-bold px-3 py-1 rounded-full text-sm shadow">
                            {{ $manif->PRIXMANIF == 0 ? 'Gratuit' : number_format($manif->PRIXMANIF, 0) . ' €' }}
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-bold mb-2 text-gray-900">{{ $manif->NOMMANIF }}</h3>
                        <p class="text-gray-600 text-sm mb-4 flex-1">{{ $manif->RESUMEMANIF }}</p>
                        @if($manif->conference)
                            <div class="text-xs text-gray-500 mb-3">
                                <span class="font-semibold">Format:</span> {{ $manif->conference->DEBATCONF ? 'Débat interactif' : 'Présentation magistrale' }}
                            </div>
                        @endif
                        <div class="mt-auto pt-4 border-t border-gray-100 flex justify-between items-center">
                            <span class="text-xs text-gray-500">Places : {{ $manif->NBMAXPARTICIPANTMANIF }}</span>
                            <a href="{{ route('reservation.create', $manif->IDMANIF) }}" 
                               class="inline-block bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-green-700 transition">
                                Réserver
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        <!-- ATELIERS -->
        @if($ateliers->count() > 0)
        <section class="mb-16">
            <h2 class="text-3xl font-bold mb-8 border-l-4 border-orange-600 pl-4 flex items-center">
                <span class="text-4xl mr-3">🎨</span> Ateliers
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($ateliers as $manif)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300 flex flex-col border-t-4 border-orange-600">
                    <div class="h-48 bg-gradient-to-br from-orange-500 to-red-600 relative flex items-center justify-center">
                        <div class="text-white text-6xl">🛠️</div>
                        <div class="absolute top-4 right-4 bg-white text-gray-900 font-bold px-3 py-1 rounded-full text-sm shadow">
                            {{ $manif->PRIXMANIF == 0 ? 'Gratuit' : number_format($manif->PRIXMANIF, 0) . ' €' }}
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-bold mb-2 text-gray-900">{{ $manif->NOMMANIF }}</h3>
                        <p class="text-gray-600 text-sm mb-4 flex-1">{{ $manif->RESUMEMANIF }}</p>
                        @if($manif->atelier && $manif->atelier->DATEHEUREFINATELIER)
                            <div class="text-xs text-gray-500 mb-3">
                                <span class="font-semibold">Durée jusqu'à:</span> {{ \Carbon\Carbon::parse($manif->atelier->DATEHEUREFINATELIER)->format('H:i') }}
                            </div>
                        @endif
                        <div class="mt-auto pt-4 border-t border-gray-100 flex justify-between items-center">
                            <span class="text-xs text-gray-500">Places : {{ $manif->NBMAXPARTICIPANTMANIF }}</span>
                            <a href="{{ route('reservation.create', $manif->IDMANIF) }}" 
                               class="inline-block bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-orange-700 transition">
                                Réserver
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        @if($concerts->count() == 0 && $conferences->count() == 0 && $ateliers->count() == 0)
        <div class="text-center py-12">
            <p class="text-gray-500 text-lg">Aucune manifestation programmée pour le moment.</p>
        </div>
        @endif

    </main>

    <footer class="bg-gray-900 text-white py-8 text-center mt-12">
        <p>&copy; 2026 Festival Cale Sons.</p>
    </footer>

    <x-chatbot-widget />

</body>
</html>