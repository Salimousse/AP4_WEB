<header class="bg-festival-light border-b border-festival-dark/10" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-6 py-6">
        <div class="flex items-center justify-between">
            <a href="{{ url('/') }}" class="text-3xl font-black text-festival-dark tracking-tight">CALE SONS</a>
            <nav class="flex items-center gap-8">
                <a href="{{ route('contact') }}" class="text-festival-dark hover:text-festival-primary transition font-medium">Contact</a>
                <a href="{{ route('about') }}" class="text-festival-dark hover:text-festival-primary transition font-medium">À propos</a>
                <a href="{{ route('festivals') }}" class="text-festival-dark hover:text-festival-primary transition font-medium">Programme des festivals</a>
                @auth
                    <div class="relative">
                        <button @click="open = !open" type="button" class="flex items-center gap-2 text-festival-dark hover:text-festival-primary transition font-medium">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-festival-dark/10 py-2 z-50">
                            <a href="{{ url('/dashboard') }}" class="block px-4 py-2 text-festival-dark hover:bg-festival-primary/10 transition">Tableau de bord</a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-festival-dark hover:bg-festival-primary/10 transition">Profil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-festival-dark hover:bg-festival-primary/10 transition">Déconnexion</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-festival-dark hover:text-festival-primary transition font-medium">Connexion</a>
                    <a href="{{ route('register') }}" class="bg-festival-primary text-white px-6 py-2 rounded-lg hover:bg-festival-secondary transition font-medium">Inscription</a>
                @endauth
            </nav>
        </div>
    </div>
</header>
