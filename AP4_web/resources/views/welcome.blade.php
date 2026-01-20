<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Festival Cale Sons - Accueil</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white">
    <div x-data="{ show: !localStorage.getItem('newsletter-closed') }" x-show="show" x-transition class="fixed top-0 left-0 right-0 z-50 bg-festival-light shadow-lg border-b border-festival-dark/10">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between gap-6">
            <div>
                <h3 class="text-lg font-bold text-festival-dark">Inscrivez-vous à notre newsletter</h3>
                <p class="text-sm text-festival-dark/70">Restez informé du festival 2026</p>
            </div>
            <form class="flex gap-3 items-center">
                <input type="email" placeholder="Votre email" class="px-4 py-2 border border-festival-dark/20 rounded-lg focus:ring-2 focus:ring-festival-primary min-w-[300px]">
                <button type="submit" class="px-6 py-2 bg-festival-primary text-white rounded-lg hover:bg-festival-secondary transition font-medium">S'inscrire</button>
                <button type="button" @click="show = false; localStorage.setItem('newsletter-closed', 'true')" class="p-2 text-festival-dark/40 hover:text-festival-dark transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </form>
        </div>
    </div>

    @include('layouts.header')

    <section class="h-[500px] bg-gradient-to-br from-festival-primary to-festival-secondary flex items-center justify-center">
        <div class="text-center text-white px-6">
            <h2 class="text-5xl font-bold mb-4">Festival Cale Sons 2026</h2>
            <p class="text-xl">Terres de Légendes : Entre Racines et Futur</p>
        </div>
    </section>

    @php
        $sections = [
            ['4 jours de festival', 'En août 2026, le festival revient avec une programmation enrichie : concerts, expositions, conférences et ateliers dans plusieurs lieux emblématiques.'],
            ['Une thématique unique', 'Cette année, nous explorons les "Terres de Légendes : Entre Racines et Futur", une célébration de notre patrimoine culturel et de l\'innovation artistique.']
        ];
    @endphp

    <section class="py-16">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12">
            @foreach($sections as [$title, $text])
                <div>
                    <h3 class="text-2xl font-bold text-festival-dark mb-4">{{ $title }}</h3>
                    <p class="text-festival-dark/70 leading-relaxed">{{ $text }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section class="py-16 bg-festival-light">
        <div class="max-w-7xl mx-auto px-6">
            <h3 class="text-center text-2xl font-bold text-festival-dark mb-12">Nos partenaires</h3>
            <div class="flex justify-center gap-12">
                @for($i = 1; $i <= 5; $i++)
                    <div class="w-32 h-16 bg-festival-dark/10 rounded flex items-center justify-center opacity-50 hover:opacity-100 transition">
                        <span class="text-festival-dark/60 font-semibold">Logo {{ $i }}</span>
                    </div>
                @endfor
            </div>
        </div>
    </section>

    @include('layouts.footer')

    <x-chatbot-widget />

</body>
</html>