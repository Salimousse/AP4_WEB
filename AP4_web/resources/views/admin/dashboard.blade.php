<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-festival-dark leading-tight">
            {{ __('Dashboard Administrateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-festival-dark">
                    <h3 class="text-lg font-medium text-festival-dark mb-4">Bienvenue sur l'espace d'administration</h3>
                    <p>Gérez les interventions chatbot, les utilisateurs, etc.</p>
                    <a href="{{ route('admin.interventions') }}" class="mt-4 inline-block bg-festival-primary text-white px-4 py-2 rounded">Voir les interventions</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
