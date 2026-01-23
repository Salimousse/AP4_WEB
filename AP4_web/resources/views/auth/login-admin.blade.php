<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="hidden" name="admin_login" value="1">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Adresse email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Mot de passe" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>



        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-festival-dark/20 text-festival-primary shadow-sm focus:ring-festival-primary" name="remember">
                <span class="ms-2 text-sm text-festival-dark/70">Se souvenir de moi</span>
            </label>

            @if (Route::has('password.request'))
                <a class="underline text-sm text-festival-primary hover:text-festival-secondary rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-festival-primary" href="{{ route('password.request') }}">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        <div class="flex items-center justify-center mt-4">
            <x-primary-button>
                Se connecter
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
                <span class="text-sm font-medium text-festival-dark">Se connecter avec Google</span>
            </a>

            <a href="{{ route('auth.microsoft') }}" class="flex items-center justify-center px-4 py-2 bg-white border border-festival-dark/20 rounded-lg shadow-sm hover:bg-festival-light">
                <img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Microsoft_logo.svg" alt="Microsoft Logo" class="w-5 h-5 me-2">
                <span class="text-sm font-medium text-festival-dark">Se connecter avec Microsoft</span>
            </a>

            <a href="{{ route('auth.facebook') }}" class="flex items-center justify-center px-4 py-2 bg-white border border-festival-dark/20 rounded-lg shadow-sm hover:bg-festival-light">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg" alt="Facebook Logo" class="w-5 h-5 me-2">
                <span class="text-sm font-medium text-festival-dark">Se connecter avec Facebook</span>
            </a>
        </div>
    </form>
</x-guest-layout>
