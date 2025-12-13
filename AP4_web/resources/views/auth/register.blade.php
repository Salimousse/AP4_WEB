<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nom complet" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" value="Adresse email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Mot de passe" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmer le mot de passe" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-festival-primary hover:text-festival-secondary rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-festival-primary" href="{{ route('login') }}">
                Déjà inscrit ?
            </a>
        </div>

        <div class="flex items-center justify-center mt-4">
            <x-primary-button>
                S'inscrire
            </x-primary-button>
        </div>

        <div class="flex items-center my-4">
            <div class="flex-grow border-t border-festival-dark/10"></div>
            <span class="flex-shrink-0 mx-4 text-festival-dark/50 text-sm">OU</span>
            <div class="flex-grow border-t border-festival-dark/10"></div>
        </div>

        <div class="flex justify-center mb-4 flex-col gap-2">
            <a href="{{ route('google-auth') }}" class="flex items-center justify-center px-4 py-2 bg-white border border-festival-dark/20 rounded-lg shadow-sm hover:bg-festival-light">
                <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google Logo" class="w-5 h-5 me-2">
                <span class="text-sm font-medium text-festival-dark">S'inscrire avec Google</span>
            </a>

            <a href="{{ route('auth.microsoft') }}" class="flex items-center justify-center px-4 py-2 bg-white border border-festival-dark/20 rounded-lg shadow-sm hover:bg-festival-light">
                <img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Microsoft_logo.svg" alt="Microsoft Logo" class="w-5 h-5 me-2">
                <span class="text-sm font-medium text-festival-dark">S'inscrire avec Microsoft</span>
            </a>

            <a href="{{ route('auth.facebook') }}" class="flex items-center justify-center px-4 py-2 bg-white border border-festival-dark/20 rounded-lg shadow-sm hover:bg-festival-light">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg" alt="Facebook Logo" class="w-5 h-5 me-2">
                <span class="text-sm font-medium text-festival-dark">S'inscrire avec Facebook</span>
            </a>
        </div>
    </form>
</x-guest-layout>
