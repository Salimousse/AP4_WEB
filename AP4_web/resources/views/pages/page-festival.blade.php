<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Programme - {{ $festival->THEMEFEST }} - Festival Cale Sons</title>
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
            <h1 class="text-5xl font-bold mb-4 uppercase">{{ $festival->THEMEFEST }}</h1>
            <div class="text-xl">
                📅 Du {{ \Carbon\Carbon::parse($festival->DATEDEBFEST)->format('d/m/Y') }} 
                au {{ \Carbon\Carbon::parse($festival->DATEFINFEST)->format('d/m/Y') }}
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="max-w-7xl mx-auto px-6">
        
        <!-- CONCERTS -->
        @if($concerts->count() > 0)
        <div class="mb-16">
            <h2 class="text-3xl font-bold mb-8 border-l-4 border-festival-primary pl-4 flex items-center text-festival-dark">
                <span class="text-4xl mr-3">🎵</span> Concerts
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($concerts as $manif)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300 flex flex-col border-t-4 border-festival-primary">
                    <div class="h-48 bg-gradient-to-br from-festival-primary to-festival-secondary relative flex items-center justify-center">
                        <div class="text-white text-6xl">🎸</div>
                        <div class="absolute top-4 right-4 bg-white text-festival-dark font-bold px-3 py-1 rounded-full text-sm shadow">
                            {{ $manif->PRIXMANIF == 0 ? 'Gratuit' : number_format($manif->PRIXMANIF, 0) . ' €' }}
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-bold mb-2 text-festival-dark">{{ $manif->NOMMANIF }}</h3>
                        <p class="text-festival-dark/70 text-sm mb-4 flex-1">{{ $manif->RESUMEMANIF }}</p>
                        @if($manif->concert && $manif->concert->DATEHEUREFINCONCERT)
                            <div class="text-xs text-festival-dark/60 mb-3">
                                <span class="font-semibold">Fin prévue:</span> {{ \Carbon\Carbon::parse($manif->concert->DATEHEUREFINCONCERT)->format('H:i') }}
                            </div>
                        @endif
                        <div class="mt-auto pt-4 border-t border-festival-dark/10">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-xs text-festival-dark/60">Places : {{ $manif->NBMAXPARTICIPANTMANIF }}</span>
                                @php
                                    $noteMoyenne = $manif->avis->avg('NOTEAVIS');
                                    $totalAvis = $manif->avis->count();
                                @endphp
                                @if($totalAvis > 0)
                                    <span class="text-xs font-semibold text-festival-dark flex items-center gap-1">
                                        ⭐ {{ number_format($noteMoyenne, 1) }} ({{ $totalAvis }})
                                    </span>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('reservation.create', $manif->IDMANIF) }}" 
                                   class="flex-1 bg-festival-primary text-white text-center px-3 py-2 rounded-lg text-sm font-bold hover:bg-festival-secondary transition">
                                    Réserver
                                </a>
                                @if($totalAvis > 0)
                                    <a href="{{ route('avis.index', $manif->IDMANIF) }}" 
                                       class="bg-festival-dark/10 text-festival-dark px-3 py-2 rounded-lg text-sm font-bold hover:bg-festival-dark/20 transition" title="Voir les avis">
                                        📊
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- CONFÉRENCES -->
        @if($conferences->count() > 0)
        <div class="mb-16">
            <h2 class="text-3xl font-bold mb-8 border-l-4 border-green-600 pl-4 flex items-center text-festival-dark">
                <span class="text-4xl mr-3">🎤</span> Conférences
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($conferences as $manif)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300 flex flex-col border-t-4 border-green-600">
                    <div class="h-48 bg-gradient-to-br from-green-500 to-teal-600 relative flex items-center justify-center">
                        <div class="text-white text-6xl">💡</div>
                        <div class="absolute top-4 right-4 bg-white text-festival-dark font-bold px-3 py-1 rounded-full text-sm shadow">
                            {{ $manif->PRIXMANIF == 0 ? 'Gratuit' : number_format($manif->PRIXMANIF, 0) . ' €' }}
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-bold mb-2 text-festival-dark">{{ $manif->NOMMANIF }}</h3>
                        <p class="text-festival-dark/70 text-sm mb-4 flex-1">{{ $manif->RESUMEMANIF }}</p>
                        @if($manif->conference)
                            <div class="text-xs text-festival-dark/60 mb-3">
                                <span class="font-semibold">Format:</span> {{ $manif->conference->DEBATCONF ? 'Débat interactif' : 'Présentation magistrale' }}
                            </div>
                        @endif
                        <div class="mt-auto pt-4 border-t border-festival-dark/10">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-xs text-festival-dark/60">Places : {{ $manif->NBMAXPARTICIPANTMANIF }}</span>
                                @php
                                    $noteMoyenne = $manif->avis->avg('NOTEAVIS');
                                    $totalAvis = $manif->avis->count();
                                @endphp
                                @if($totalAvis > 0)
                                    <span class="text-xs font-semibold text-festival-dark flex items-center gap-1">
                                        ⭐ {{ number_format($noteMoyenne, 1) }} ({{ $totalAvis }})
                                    </span>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('reservation.create', $manif->IDMANIF) }}" 
                                   class="flex-1 bg-green-600 text-white text-center px-3 py-2 rounded-lg text-sm font-bold hover:bg-green-700 transition">
                                    Réserver
                                </a>
                                @if($totalAvis > 0)
                                    <a href="{{ route('avis.index', $manif->IDMANIF) }}" 
                                       class="bg-festival-dark/10 text-festival-dark px-3 py-2 rounded-lg text-sm font-bold hover:bg-festival-dark/20 transition" title="Voir les avis">
                                        📊
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- ATELIERS -->
        @if($ateliers->count() > 0)
        <div class="mb-16">
            <h2 class="text-3xl font-bold mb-8 border-l-4 border-orange-600 pl-4 flex items-center text-festival-dark">
                <span class="text-4xl mr-3">🎨</span> Ateliers
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($ateliers as $manif)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300 flex flex-col border-t-4 border-orange-600">
                    <div class="h-48 bg-gradient-to-br from-orange-500 to-red-600 relative flex items-center justify-center">
                        <div class="text-white text-6xl">🛠️</div>
                        <div class="absolute top-4 right-4 bg-white text-festival-dark font-bold px-3 py-1 rounded-full text-sm shadow">
                            {{ $manif->PRIXMANIF == 0 ? 'Gratuit' : number_format($manif->PRIXMANIF, 0) . ' €' }}
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-bold mb-2 text-festival-dark">{{ $manif->NOMMANIF }}</h3>
                        <p class="text-festival-dark/70 text-sm mb-4 flex-1">{{ $manif->RESUMEMANIF }}</p>
                        @if($manif->atelier && $manif->atelier->DATEHEUREFINATELIER)
                            <div class="text-xs text-festival-dark/60 mb-3">
                                <span class="font-semibold">Durée jusqu'à:</span> {{ \Carbon\Carbon::parse($manif->atelier->DATEHEUREFINATELIER)->format('H:i') }}
                            </div>
                        @endif
                        <div class="mt-auto pt-4 border-t border-festival-dark/10 flex justify-between items-center">
                            <span class="text-xs text-festival-dark/60">Places : {{ $manif->NBMAXPARTICIPANTMANIF }}</span>
                            <a href="{{ route('reservation.create', $manif->IDMANIF) }}" 
                               class="inline-block bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-orange-700 transition">
                                Réserver
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($concerts->count() == 0 && $conferences->count() == 0 && $ateliers->count() == 0)
        <div class="text-center py-12">
            <p class="text-festival-dark/60 text-lg">Aucune manifestation programmée pour le moment.</p>
        </div>
        @endif

        </div>
    </section>

    @include('layouts.footer')

</body>
</html>