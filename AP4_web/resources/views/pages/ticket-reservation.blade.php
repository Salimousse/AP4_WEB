<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Votre Billet') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-center">
        
        <div class="bg-white w-full max-w-4xl shadow-2xl rounded-xl overflow-hidden flex flex-col md:flex-row border border-gray-200">
            
            <div class="bg-blue-900 text-white p-8 md:w-2/3 flex flex-col justify-between relative">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-blue-500 rounded-full opacity-20 blur-3xl"></div>

                <div>
                    <h1 class="text-3xl font-black uppercase tracking-widest mb-1">CALE SONS</h1>
                    <p class="text-blue-200 text-sm tracking-widest">FESTIVAL 2026</p>
                </div>

                <div class="flex flex-col items-center justify-center my-8 z-10">
                    <div class="bg-white p-4 rounded-lg shadow-lg">
                        @if(class_exists(\SimpleSoftwareIO\QrCode\Facades\QrCode::class) && isset($billet) && $billet->QRCODEBILLET)
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($billet->QRCODEBILLET) !!}
                        @else
                            <div class="text-xs text-gray-500">QR unavailable</div>
                        @endif
                    </div>
                    <p class="mt-2 text-xs text-blue-300 font-mono">Ref: {{ substr($billet->QRCODEBILLET, 0, 8) }}...</p>
                </div>

                <div class="z-10">
                    <p class="text-xs uppercase text-blue-400">Détenteur du billet</p>
                    <p class="text-2xl font-bold">
                        {{ $billet->client->NOMPERS }} {{ $billet->client->PRENOMPERS }}
                    </p>
                </div>
            </div>

            <div class="p-8 md:w-1/3 bg-gray-50 flex flex-col justify-center relative border-l-2 border-dashed border-gray-300">
                <div class="absolute -left-3 top-0 bottom-0 flex flex-col justify-between py-2">
                    <div class="w-6 h-6 bg-gray-100 rounded-full -mt-3"></div>
                    <div class="w-6 h-6 bg-gray-100 rounded-full -mb-3"></div>
                </div>

                <div class="space-y-6 text-center md:text-left">
                    <div>
                        <p class="text-gray-400 text-xs uppercase font-bold">Événement</p>
                        <h2 class="text-xl font-bold text-gray-900 leading-tight">
                            {{ $billet->manifestation->NOMMANIF }}
                        </h2>
                    </div>

                    <div>
                        <p class="text-gray-400 text-xs uppercase font-bold">Prix</p>
                        <p class="text-lg font-bold text-gray-800">
                            {{ $billet->manifestation->PRIXMANIF == 0 ? 'Gratuit' : number_format($billet->manifestation->PRIXMANIF, 2) . ' €' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-400 text-xs uppercase font-bold">Statut</p>
                        @if($billet->IDTYPEPAIEMENT == 1)
                            <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold border border-green-200">
                                ✅ PAYÉ (CB)
                            </span>
                        @elseif($billet->IDTYPEPAIEMENT == 0)
                            <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold border border-green-200">
                                ✅ PAYÉ (Gratuit)
                            </span>
                        @else
                            <span class="inline-block bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold border border-yellow-200">
                                ⏳ EN ATTENTE
                            </span>
                        @endif
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <button onclick="window.print()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Imprimer
                    </button>
                    <a href="{{ route('festivals') }}" class="block text-center mt-4 text-sm text-gray-500 hover:underline">
                        Retour au programme
                    </a>
                </div>
            </div>
        </div>
        </div>
    </div>
</x-app-layout>