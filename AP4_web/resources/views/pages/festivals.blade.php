<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Festivals - Cale Sons</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <nav class="bg-white shadow-sm py-4">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-blue-600">CALE SONS</a>
            <div class="flex gap-4">
                <a href="{{ route('support') }}" class="text-gray-600 hover:text-blue-600">Assistance</a>
                @auth
                    <a href="{{ route('page.mes-reservations') }}" class="text-gray-600 hover:text-blue-600">Mes Réservations</a>
                @endauth
                <a href="/" class="text-gray-600 hover:text-blue-600">Accueil</a>
            </div>
        </div>
    </nav>

    <header class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20 text-center px-4">
        <h1 class="text-5xl md:text-7xl font-black mb-6 uppercase">Nos Festivals</h1>
        <p class="text-xl md:text-2xl text-blue-100 max-w-3xl mx-auto">
            Découvrez notre programmation de festivals tout au long de l'année
        </p>
    </header>

    <main class="container mx-auto px-6 py-16">
        
        @if($festivals->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @foreach($festivals as $festival)
            <a href="{{ route('programme', $festival->IDFESTIVAL) }}" 
               class="group bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                
                <!-- Header avec gradient -->
                <div class="h-48 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 relative flex items-center justify-center">
                    <div class="absolute inset-0 bg-black opacity-20"></div>
                    <div class="relative z-10 text-center px-6">
                        <div class="text-white text-6xl mb-3">🎪</div>
                        <h2 class="text-3xl font-black text-white uppercase tracking-wide drop-shadow-lg">
                            {{ $festival->THEMEFEST }}
                        </h2>
                    </div>
                </div>

                <!-- Dates -->
                <div class="bg-blue-600 text-white py-4 px-6 flex items-center justify-center space-x-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="font-bold text-lg">
                        {{ \Carbon\Carbon::parse($festival->DATEDEBFEST)->format('d/m/Y') }} 
                        - 
                        {{ \Carbon\Carbon::parse($festival->DATEFINFEST)->format('d/m/Y') }}
                    </span>
                </div>

                <!-- Nombre de manifestations -->
                <div class="p-6">
                    <div class="flex items-center justify-between text-gray-600 mb-4">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            <span class="font-semibold">{{ $festival->manifestations->count() }} manifestations</span>
                        </div>
                    </div>

                    <!-- Types de manifestations -->
                    <div class="flex flex-wrap gap-2">
                        @php
                            $concerts = $festival->manifestations->filter(fn($m) => $m->concert !== null)->count();
                            $conferences = $festival->manifestations->filter(fn($m) => $m->conference !== null)->count();
                            $ateliers = $festival->manifestations->filter(fn($m) => $m->atelier !== null)->count();
                        @endphp

                        @if($concerts > 0)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                            🎸 {{ $concerts }} Concert{{ $concerts > 1 ? 's' : '' }}
                        </span>
                        @endif

                        @if($conferences > 0)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                            💡 {{ $conferences }} Conférence{{ $conferences > 1 ? 's' : '' }}
                        </span>
                        @endif

                        @if($ateliers > 0)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                            🛠️ {{ $ateliers }} Atelier{{ $ateliers > 1 ? 's' : '' }}
                        </span>
                        @endif
                    </div>

                    <!-- CTA -->
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <span class="inline-flex items-center text-blue-600 font-bold group-hover:text-blue-700 transition">
                            Voir le programme
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="text-center py-20">
            <div class="text-gray-400 text-8xl mb-6">🎪</div>
            <p class="text-gray-500 text-2xl font-light">Aucun festival programmé pour le moment</p>
            <p class="text-gray-400 mt-2">Restez connectés pour découvrir nos prochains événements !</p>
        </div>
        @endif

    </main>

    <footer class="bg-gray-900 text-white py-8 text-center mt-16">
        <p>&copy; 2026 Festival Cale Sons. Tous droits réservés.</p>
    </footer>

</body>
</html>
