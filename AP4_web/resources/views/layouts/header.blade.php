<header class="bg-festival-light border-b border-festival-dark/10" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 sm:py-6">
        <div class="flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2 sm:gap-3">
                <img src="{{ asset('images/cale-sons-logo.svg') }}" alt="Cale Sons Logo" class="w-10 sm:w-12 h-10 sm:h-12">
                <span class="text-xl sm:text-3xl font-black text-festival-dark tracking-tight">CALE SONS</span>
            </a>
            
            <!-- Menu desktop -->
            <nav class="hidden md:flex items-center gap-6 lg:gap-8">
                <a href="{{ route('contact') }}" class="text-festival-dark hover:text-festival-primary transition font-medium text-sm lg:text-base">Contact</a>
                <a href="{{ route('about') }}" class="text-festival-dark hover:text-festival-primary transition font-medium text-sm lg:text-base">À propos</a>
                <a href="{{ route('support') }}" class="text-festival-dark hover:text-festival-primary transition font-medium text-sm lg:text-base">Assistance</a>
                <a href="{{ route('festivals') }}" class="text-festival-dark hover:text-festival-primary transition font-medium text-sm lg:text-base">Programme</a>
                @auth
                    <div class="relative">
                        <button @click="open = !open" type="button" class="flex items-center gap-2 text-festival-dark hover:text-festival-primary transition font-medium text-sm lg:text-base">
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
                    <a href="{{ route('login') }}" class="text-festival-dark hover:text-festival-primary transition font-medium text-sm">Connexion</a>
                    <a href="{{ route('register') }}" class="bg-festival-primary text-white px-4 py-2 rounded-lg hover:bg-festival-secondary transition font-medium text-sm">Inscription</a>
                @endauth
            </nav>

            <!-- Menu mobile -->
            <button @click="open = !open" class="md:hidden p-2 text-festival-dark">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- Menu mobile dropdown -->
        <nav x-show="open" class="md:hidden mt-4 space-y-2 pb-4">
            <a href="{{ route('contact') }}" class="block px-4 py-2 text-festival-dark hover:bg-festival-light rounded transition">Contact</a>
            <a href="{{ route('about') }}" class="block px-4 py-2 text-festival-dark hover:bg-festival-light rounded transition">À propos</a>
            <a href="{{ route('support') }}" class="block px-4 py-2 text-festival-dark hover:bg-festival-light rounded transition">Assistance</a>
            <a href="{{ route('festivals') }}" class="block px-4 py-2 text-festival-dark hover:bg-festival-light rounded transition">Programme</a>
            @auth
                <a href="{{ url('/dashboard') }}" class="block px-4 py-2 text-festival-dark hover:bg-festival-light rounded transition">Tableau de bord</a>
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-festival-dark hover:bg-festival-light rounded transition">Profil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-festival-dark hover:bg-festival-light rounded transition">Déconnexion</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block px-4 py-2 text-festival-dark hover:bg-festival-light rounded transition">Connexion</a>
                <a href="{{ route('register') }}" class="block px-4 py-2 bg-festival-primary text-white rounded hover:bg-festival-secondary transition">Inscription</a>
            @endauth
        </nav>
    </div>
</header>
