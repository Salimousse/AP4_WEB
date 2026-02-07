<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl sm:text-2xl text-festival-dark leading-tight">
            {{ __('Votre Billet') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12 bg-festival-light/30 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 flex justify-center">
        
        <div class="bg-white w-full shadow-2xl rounded-2xl overflow-hidden flex flex-col md:flex-row border border-festival-dark/10">
            
            <!-- Left Side - Ticket -->
            <div class="bg-gradient-to-br from-festival-primary to-festival-secondary text-white p-6 sm:p-8 md:w-2/3 flex flex-col justify-between relative">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-festival-secondary rounded-full opacity-20 blur-3xl"></div>

                <div>
                    <h1 class="text-3xl sm:text-4xl font-black uppercase tracking-widest mb-1">CALE SONS</h1>
                    <p class="text-festival-light/70 text-xs sm:text-sm tracking-widest">FESTIVAL 2026</p>
                </div>

                <div class="flex flex-col items-center justify-center my-8 z-10">
                    <div class="bg-white p-4 rounded-lg shadow-lg">
                        @if(class_exists(\SimpleSoftwareIO\QrCode\Facades\QrCode::class) && isset($billet) && $billet->QRCODEBILLET)
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(180)->generate($billet->QRCODEBILLET) !!}
                        @else
                            <div class="text-xs text-gray-500 p-8">QR indisponible</div>
                        @endif
                    </div>
                    <p class="mt-3 text-xs text-festival-light/70 font-mono">Ref: {{ substr($billet->QRCODEBILLET, 0, 8) }}...</p>
                </div>

                <div class="z-10">
                    <p class="text-xs uppercase text-festival-light/70 font-semibold">Détenteur du billet</p>
                    <p class="text-xl sm:text-2xl font-bold">
                        {{ $billet->client->NOMPERS }} {{ $billet->client->PRENOMPERS }}
                    </p>
                </div>
            </div>

            <!-- Right Side - Info -->
            <div class="p-6 sm:p-8 md:w-1/3 bg-festival-light flex flex-col justify-center relative border-t md:border-t-0 md:border-l-2 border-dashed border-festival-dark/20">
                <div class="absolute -top-3 left-0 right-0 md:top-0 md:-left-3 md:bottom-0 flex md:flex-col justify-between md:justify-between px-2 md:px-0 md:py-2 pointer-events-none">
                    <div class="w-6 h-6 bg-festival-light rounded-full hidden md:block -mt-3"></div>
                    <div class="w-6 h-6 bg-festival-light rounded-full hidden md:block -mb-3"></div>
                </div>

                <div class="space-y-5 text-center md:text-left">
                    <div>
                        <p class="text-festival-dark/60 text-xs uppercase font-bold mb-1">Événement</p>
                        <h2 class="text-lg sm:text-xl font-bold text-festival-dark leading-tight">
                            {{ $billet->manifestation->NOMMANIF }}
                        </h2>
                    </div>

                    <div>
                        <p class="text-festival-dark/60 text-xs uppercase font-bold mb-1">Prix</p>
                        <p class="text-xl sm:text-2xl font-bold text-festival-primary">
                            {{ $billet->manifestation->PRIXMANIF == 0 ? 'Gratuit' : number_format($billet->manifestation->PRIXMANIF, 2) . ' €' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-festival-dark/60 text-xs uppercase font-bold mb-1">Statut</p>
                        @if($billet->IDTYPEPAIEMENT == 1)
                            <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold border border-green-300">
                                ✅ PAYÉ (CB)
                            </span>
                        @elseif($billet->IDTYPEPAIEMENT == 0)
                            <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold border border-green-300">
                                ✅ PAYÉ (Gratuit)
                            </span>
                        @else
                            <span class="inline-block bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold border border-yellow-300">
                                ⏳ EN ATTENTE
                            </span>
                        @endif
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-festival-dark/20 space-y-2">
                    <button type="button" onclick="window.print()" style="cursor: pointer;" class="w-full bg-festival-dark hover:bg-festival-dark/80 text-white font-bold py-2.5 px-4 rounded-lg transition flex justify-center items-center gap-2 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Imprimer
                    </button>
                    
                    <a href="{{ route('avis.form', ['idBillet' => $billet->IDBILLET]) }}" class="block text-center bg-festival-primary hover:bg-festival-secondary text-white font-bold py-2.5 px-4 rounded-lg transition text-sm no-underline">
                        📝 Ajouter un avis
                    </a>
                    
                    <a href="{{ route('avis.index', ['idManif' => $billet->IDMANIF]) }}" class="block text-center bg-festival-primary/10 hover:bg-festival-primary/20 text-festival-dark font-bold py-2.5 px-4 rounded-lg transition text-sm no-underline">
                        📊 Voir les avis
                    </a>
                    
                    <a href="{{ route('festivals') }}" class="block text-center text-xs text-festival-dark/60 hover:text-festival-dark pt-2">
                        ← Retour au programme
                    </a>
                </div>
            </div>
        </div>
        </div>
    </div>
</x-app-layout>