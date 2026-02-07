<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl sm:text-2xl text-festival-dark leading-tight">
            {{ __('Mes Billets') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12 bg-festival-light/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if($reservations->isEmpty())
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl p-6 sm:p-8 text-center border border-festival-dark/10">
                    <div class="text-5xl mb-4">🎫</div>
                    <p class="text-festival-dark/70 mb-4 text-lg">Vous n'avez aucune réservation pour le moment.</p>
                    <a href="{{ route('festivals') }}" class="inline-block bg-festival-primary text-white hover:bg-festival-secondary font-bold py-3 px-6 rounded-lg transition">
                        Voir la programmation
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($reservations as $resa)
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden flex flex-col sm:flex-row border border-festival-dark/10 hover:shadow-xl transition">
                            
                            <div class="bg-gradient-to-br from-festival-primary to-festival-secondary p-6 sm:p-4 flex items-center justify-center sm:w-1/3 min-h-[200px] sm:min-h-0">
                                @if($resa->billet && $resa->billet->QRCODEBILLET)
                                    <div class="bg-white p-3 rounded-lg">
                                        @if(class_exists(\SimpleSoftwareIO\QrCode\Facades\QrCode::class) && $resa->billet && $resa->billet->QRCODEBILLET)
                                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->generate($resa->billet->QRCODEBILLET) !!}
                                        @else
                                            <div class="text-xs text-gray-500 p-6">QR indisponible</div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-white text-xs sm:text-sm text-center font-bold">⏳ En attente<br>de paiement</span>
                                @endif
                            </div>

                            <div class="p-6 sm:p-5 flex-1 flex flex-col justify-between sm:w-2/3">
                                <div>
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2 mb-3">
                                        <h3 class="text-lg sm:text-xl font-bold text-festival-dark">{{ $resa->manifestation->NOMMANIF }}</h3>
                                        @if($resa->billet && ($resa->billet->IDTYPEPAIEMENT == 1 || $resa->billet->IDTYPEPAIEMENT == 0))
                                            <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-bold w-fit">✅ PAYÉ</span>
                                        @else
                                            <span class="bg-yellow-100 text-yellow-700 text-xs px-3 py-1 rounded-full font-bold w-fit">⏳ EN ATTENTE</span>
                                        @endif
                                    </div>
                                    <p class="text-festival-dark/60 text-xs sm:text-sm mb-2">
                                        Réservé le {{ \Carbon\Carbon::parse($resa->DATEHEURERESERVATION)->format('d/m/Y à H:i') }}
                                    </p>
                                    <p class="text-festival-primary font-bold text-base sm:text-lg">
                                        {{ $resa->manifestation->PRIXMANIF == 0 ? 'Gratuit' : $resa->manifestation->PRIXMANIF . ' €' }}
                                    </p>
                                </div>

                                <div class="mt-4 pt-4 border-t border-festival-dark/10 flex flex-col sm:flex-row gap-3">
                                    @if($resa->billet)
                                        <a href="{{ route('reservation.success', $resa->billet->IDBILLET) }}" class="flex-1 bg-festival-primary text-white text-center py-2 rounded-lg text-sm font-bold hover:bg-festival-secondary transition">
                                            📋 Voir le Billet
                                        </a>
                                        
                                        @if($resa->manifestation->PRIXMANIF > 0 && !$resa->billet->IDTYPEPAIEMENT)
                                            <a href="{{ route('paiement.checkout', $resa->billet->IDBILLET) }}" class="flex-1 bg-yellow-500 text-white text-center py-2 rounded-lg text-sm font-bold hover:bg-yellow-600 transition">
                                                💳 Payer
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>