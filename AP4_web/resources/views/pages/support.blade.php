<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Assistance & Support - Festival Cale Sons</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    
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
                    <x-chat-widget />
                </div>

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