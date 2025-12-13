<x-guest-layout>
    <div class="mb-4 text-sm text-festival-dark/70">
        Merci de vous être inscrit ! Avant de commencer, pourriez-vous vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer ? Si vous n'avez pas reçu l'email, nous vous en enverrons un autre avec plaisir.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            Un nouveau lien de vérification a été envoyé à l'adresse email que vous avez fournie lors de l'inscription.
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    Renvoyer l'email de vérification
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-festival-primary hover:text-festival-secondary rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-festival-primary">
                Se déconnecter
            </button>
        </form>
    </div>
</x-guest-layout>
