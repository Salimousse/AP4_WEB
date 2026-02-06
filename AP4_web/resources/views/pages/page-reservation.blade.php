<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Réservation - Festival Cale Sons</title>
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

    <section class="h-[300px] bg-gradient-to-br from-festival-primary to-festival-secondary flex items-center justify-center">
        <div class="text-center text-white px-6">
            <h2 class="text-4xl font-bold mb-4">Réserver votre place</h2>
            <p class="text-lg">Réservez vos places pour le festival</p>
        </div>
    </section>

    <section class="py-16">
        <div class="max-w-md mx-auto bg-white p-8 rounded-xl shadow-lg mt-10">
            <div class="bg-festival-light p-4 rounded-lg mb-6 border border-festival-dark/10">
                <h2 class="font-bold text-xl text-festival-dark">{{ $manif->NOMMANIF }}</h2>
                <p class="text-festival-dark/70">{{ $manif->RESUMEMANIF }}</p>
                <p class="mt-2 font-bold text-festival-primary text-lg">
                    Prix : {{ $manif->PRIXMANIF > 0 ? $manif->PRIXMANIF . ' €' : 'Gratuit' }}
                </p>
            </div>
            <form action="{{ route('reservation.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id_manif" value="{{ $manif->IDMANIF }}">
                <div class="mb-4">
                    <label class="block text-festival-dark text-sm font-bold mb-2">Votre Email (Compte)</label>
                    <input type="text" value="{{ Auth::user()->email }}" disabled class="w-full bg-gray-200 p-2 rounded border">
                </div>
                <div class="mb-6">
                    <label class="block text-festival-dark text-sm font-bold mb-2">Téléphone (Optionnel)</label>
                    <input type="text" name="telephone" class="w-full p-2 rounded border focus:ring focus:ring-festival-primary/20">
                </div>
                <button type="submit" class="w-full bg-festival-primary hover:bg-festival-secondary text-white font-bold py-3 rounded-lg transition">
                    CONFIRMER & PAYER
                </button>
            </form>
        </div>
    </section>

    @include('layouts.footer')
</body>
</html>