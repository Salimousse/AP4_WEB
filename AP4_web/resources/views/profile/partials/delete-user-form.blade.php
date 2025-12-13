<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-festival-dark">
            {{ __('Supprimer le compte') }}
        </h2>

        <p class="mt-1 text-sm text-festival-dark/70">
            {{ __('Une fois votre compte supprimé, toutes ses ressources et données seront définitivement supprimées. Avant de supprimer votre compte, veuillez télécharger toutes les données ou informations que vous souhaitez conserver.') }}
        </p>
    </header>

    @if (!is_null($user->password))
        {{-- Utilisateur avec mot de passe --}}
        <x-danger-button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        >{{ __('Supprimer le compte') }}</x-danger-button>

        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-festival-dark">
                    {{ __('Êtes-vous sûr de vouloir supprimer votre compte ?') }}
                </h2>

                <p class="mt-1 text-sm text-festival-dark/70">
                    {{ __('Une fois votre compte supprimé, toutes ses ressources et données seront définitivement supprimées. Veuillez entrer votre mot de passe pour confirmer que vous souhaitez supprimer définitivement votre compte.') }}
                </p>

                <div class="mt-6">
                    <x-input-label for="password" value="{{ __('Mot de passe') }}" class="sr-only" />

                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="{{ __('Mot de passe') }}"
                    />

                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Annuler') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3">
                        {{ __('Supprimer le compte') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    @else
        {{-- Utilisateur sans mot de passe (connexion sociale) --}}
        <x-danger-button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion-step1')"
        >{{ __('Supprimer le compte') }}</x-danger-button>

        {{-- Première confirmation --}}
        <x-modal name="confirm-user-deletion-step1" focusable>
            <div class="p-6">
                <h2 class="text-lg font-medium text-festival-dark">
                    {{ __('⚠️ Attention !') }}
                </h2>

                <p class="mt-3 text-sm text-festival-dark/70">
                    {{ __('Vous êtes sur le point de supprimer définitivement votre compte.') }}
                </p>

                <p class="mt-2 text-sm font-semibold text-red-600">
                    {{ __('Cette action est irréversible. Toutes vos données seront perdues.') }}
                </p>

                <div class="mt-6 flex justify-end gap-3">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Annuler') }}
                    </x-secondary-button>

                    <x-danger-button x-on:click="$dispatch('close'); setTimeout(() => $dispatch('open-modal', 'confirm-user-deletion-step2'), 100)">
                        {{ __('Continuer') }}
                    </x-danger-button>
                </div>
            </div>
        </x-modal>

        {{-- Deuxième confirmation (finale) --}}
        <x-modal name="confirm-user-deletion-step2" focusable>
            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-festival-dark">
                    {{ __('Dernière confirmation') }}
                </h2>

                <p class="mt-3 text-sm text-festival-dark/70">
                    {{ __('Confirmez-vous vraiment vouloir supprimer votre compte ?') }}
                </p>

                <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded">
                    <p class="text-sm text-red-800 font-medium">
                        {{ __('Cette action supprimera :') }}
                    </p>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        <li>{{ __('Toutes vos informations personnelles') }}</li>
                        <li>{{ __('Vos comptes connectés (Google, Microsoft, Facebook)') }}</li>
                        <li>{{ __('Toutes vos données associées') }}</li>
                    </ul>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Non, conserver mon compte') }}
                    </x-secondary-button>

                    <x-danger-button>
                        {{ __('Oui, supprimer définitivement') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    @endif
</section>
