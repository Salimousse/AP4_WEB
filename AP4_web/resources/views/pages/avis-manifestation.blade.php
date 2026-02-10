<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Avis - Manifestation</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white">

    @include('layouts.header')

    <section class="h-[300px] bg-gradient-to-br from-festival-primary to-festival-secondary flex items-center justify-center">
        <div class="text-center text-white px-6">
            <h1 class="text-4xl font-bold mb-2">📊 Avis des participants</h1>
            <p class="text-lg">Découvrez les retours des personnes qui ont assisté à cette manifestation</p>
        </div>
    </section>

    <section class="py-12">
        <div class="max-w-4xl mx-auto px-6">

            <!-- Résumé des évaluations -->
            <div class="bg-gradient-to-br from-festival-primary/10 to-festival-secondary/10 rounded-2xl p-8 mb-8 border border-festival-dark/5">
                <div class="flex items-center gap-8">
                    <div class="flex-shrink-0">
                        <div class="text-6xl font-bold text-festival-primary">
                            {{ number_format($noteMoyenne, 1) }}
                        </div>
                        <div class="flex gap-1 mt-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($noteMoyenne))
                                    <span class="text-2xl">⭐</span>
                                @else
                                    <span class="text-2xl opacity-20">⭐</span>
                                @endif
                            @endfor
                        </div>
                    </div>

                    <div class="flex-1">
                        <p class="text-3xl font-bold text-festival-dark mb-2">
                            {{ $totalAvis }} {{ $totalAvis === 1 ? 'avis' : 'avis' }}
                        </p>
                        <p class="text-festival-dark/70 text-lg">
                            Note moyenne basée sur les évaluations des participants
                        </p>

                        @if($totalAvis == 0)
                            <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <p class="text-blue-700 text-sm">
                                    Aucun avis pour le moment. Soyez le premier à partager votre expérience !
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Distribution des notes -->
            @if($totalAvis > 0)
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border border-festival-dark/5">
                    <h2 class="text-2xl font-bold text-festival-dark mb-6">Distribution des notes</h2>
                    
                    <div class="space-y-3">
                        @for($note = 5; $note >= 1; $note--)
                            @php
                                $count = $avis->where('NOTEAVIS', $note)->count();
                                $percentage = $totalAvis > 0 ? ($count / $totalAvis) * 100 : 0;
                            @endphp
                            <div class="flex items-center gap-4">
                                <div class="w-16 flex items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $note)
                                            <span class="text-lg">⭐</span>
                                        @else
                                            <span class="text-lg opacity-20">⭐</span>
                                        @endif
                                    @endfor
                                </div>
                                
                                <div class="flex-1">
                                    <div class="w-full bg-festival-dark/10 rounded-full h-2">
                                        <div class="bg-festival-primary h-2 rounded-full" 
                                             style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                                
                                <span class="w-16 text-right text-festival-dark/70 font-semibold">
                                    {{ $count }}
                                </span>
                            </div>
                        @endfor
                    </div>
                </div>
            @endif

            <!-- Liste des avis -->
            <div class="space-y-6">
                @forelse($avis as $avi)
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-festival-dark/5 hover:shadow-xl transition">
                        <!-- En-tête du commentaire -->
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <div class="flex gap-2 items-center mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $avi->NOTEAVIS)
                                            <span class="text-xl">⭐</span>
                                        @else
                                            <span class="text-xl opacity-30">⭐</span>
                                        @endif
                                    @endfor
                                </div>
                                
                                <p class="text-festival-dark/80 font-semibold">
                                    <strong>{{ $avi->billet?->client?->NOMPERS ?? 'Anonyme' }}</strong>
                                    <span class="text-festival-dark/50 text-sm">
                                        • {{ $avi->created_at ? $avi->created_at->format('d/m/Y') : 'Date inconnue' }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Commentaire -->
                        @if($avi->COMMENTAIREAVIS)
                            <p class="text-festival-dark leading-relaxed">
                                {{ $avi->COMMENTAIREAVIS }}
                            </p>
                        @else
                            <p class="text-festival-dark/60 italic">
                                Aucun commentaire détaillé.
                            </p>
                        @endif
                    </div>
                @empty
                    <div class="bg-white rounded-2xl shadow-lg p-12 border border-festival-dark/5 text-center">
                        <div class="text-5xl mb-4">📝</div>
                        <p class="text-festival-dark/70 text-lg">
                            Aucun avis n'a été publié pour le moment.
                        </p>
                        <p class="text-festival-dark/50 mt-2">
                            @auth
                                Achetez un billet pour cette manifestation et partagez votre expérience !
                            @else
                                Connectez-vous et achetez un billet pour partager votre avis.
                            @endauth
                        </p>
                    </div>
                @endforelse
            </div>

            <!-- Bouton pour revenir -->
            <div class="mt-8 text-center">
                <a href="{{ route('programme', ['id' => $idManif]) }}" 
                   class="inline-block bg-festival-primary text-white font-bold rounded-lg px-8 py-3 hover:bg-festival-secondary transition">
                   ← Retour à la manifestation
                </a>
            </div>

        </div>
    </section>

    @include('layouts.footer')

</body>
</html>
