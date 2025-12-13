<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact - Festival Cale Sons</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white">
    @include('layouts.header')

    <main class="py-16">
        <div class="max-w-4xl mx-auto px-6">
            <!-- Header -->
            <div class="text-center mb-16">
                <p class="text-sm text-festival-primary uppercase tracking-wide mb-3">Festival Cale Sons</p>
                <h1 class="text-5xl font-bold text-festival-dark mb-6">Nous contacter</h1>
                <p class="text-xl text-festival-dark/70 max-w-2xl mx-auto">Une question sur le festival 2026 ? Besoin d'informations ? Nous vous répondrons dans les plus brefs délais.</p>
            </div>

            <!-- Form -->
            <form action="#" method="POST" class="bg-festival-light border border-festival-dark/10 rounded-lg p-8 space-y-6">
                @csrf
                <div class="grid md:grid-cols-2 gap-6">
                    @foreach([['name', 'text', 'Nom complet'], ['email', 'email', 'Email']] as [$name, $type, $label])
                        <div>
                            <label for="{{ $name }}" class="block text-sm font-medium text-festival-dark mb-2">{{ $label }}</label>
                            <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" required class="w-full px-4 py-3 border border-festival-dark/20 rounded-lg focus:ring-2 focus:ring-festival-primary focus:border-transparent transition bg-white">
                        </div>
                    @endforeach
                </div>

                <div>
                    <label for="subject" class="block text-sm font-medium text-festival-dark mb-2">Sujet</label>
                    <input type="text" id="subject" name="subject" required class="w-full px-4 py-3 border border-festival-dark/20 rounded-lg focus:ring-2 focus:ring-festival-primary focus:border-transparent transition bg-white">
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-festival-dark mb-2">Message</label>
                    <textarea id="message" name="message" rows="6" required class="w-full px-4 py-3 border border-festival-dark/20 rounded-lg focus:ring-2 focus:ring-festival-primary focus:border-transparent transition resize-none bg-white"></textarea>
                </div>

                <button type="submit" class="w-full bg-festival-primary text-white px-8 py-4 rounded-lg font-semibold hover:bg-festival-secondary transition">
                    Envoyer le message
                </button>
            </form>

            <!-- Contact Info -->
            <div class="mt-12 grid md:grid-cols-3 gap-8 text-center">
                @php
                    $contacts = [
                        ['Email', 'contact@calesons-festival.fr', 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                        ['Téléphone', '+33 1 23 45 67 89', 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z'],
                        ['Lieu principal', 'Maison de la Culture et des Loisirs<br>Plusieurs sites du festival', 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z']
                    ];
                @endphp

                @foreach($contacts as [$title, $info, $path])
                    <div>
                        <div class="w-12 h-12 bg-festival-primary/10 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-festival-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-festival-dark mb-2">{{ $title }}</h3>
                        <p class="text-festival-dark/70 text-sm">{!! $info !!}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </main>

    @include('layouts.footer')
</body>
</html>
