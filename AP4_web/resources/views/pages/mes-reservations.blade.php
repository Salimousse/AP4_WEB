<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mes Billets') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if($reservations->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <p class="text-gray-500 mb-4">Vous n'avez aucune réservation pour le moment.</p>
                    <a href="{{ route('festivals') }}" class="text-blue-600 hover:underline font-bold">Voir la programmation</a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($reservations as $resa)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col md:flex-row border border-gray-200 hover:shadow-xl transition">
                            
                            <div class="bg-blue-900 p-4 flex items-center justify-center md:w-1/4">
                                @if($resa->billet && $resa->billet->QRCODEBILLET)
                                    <div class="bg-white p-2 rounded">
                        @if(class_exists(\SimpleSoftwareIO\QrCode\Facades\QrCode::class) && $resa->billet && $resa->billet->QRCODEBILLET)
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->generate($resa->billet->QRCODEBILLET) !!}
                        @else
                            <div class="text-xs text-gray-500">QR unavailable</div>
                        @endif                                    </div>
                                @else
                                    <span class="text-white text-xs text-center">En attente<br>de paiement</span>
                                @endif
                            </div>

                            <div class="p-6 flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-xl font-bold text-gray-900">{{ $resa->manifestation->NOMMANIF }}</h3>
                                        @if($resa->billet && ($resa->billet->IDTYPEPAIEMENT == 1 || $resa->billet->IDTYPEPAIEMENT == 0))
                                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-bold">✅ PAYÉ</span>
                                        @else
                                            <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full font-bold">⏳ EN ATTENTE</span>
                                        @endif
                                    </div>
                                    <p class="text-gray-500 text-sm mt-1">
                                        Réservé le {{ \Carbon\Carbon::parse($resa->DATEHEURERESERVATION)->format('d/m/Y à H:i') }}
                                    </p>
                                    <p class="text-blue-600 font-bold mt-2">
                                        {{ $resa->manifestation->PRIXMANIF == 0 ? 'Gratuit' : $resa->manifestation->PRIXMANIF . ' €' }}
                                    </p>
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-100 flex gap-4">
                                    @if($resa->billet)
                                        <a href="{{ route('reservation.success', $resa->billet->IDBILLET) }}" class="flex-1 bg-blue-600 text-white text-center py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition">
                                            Voir le Billet Complet
                                        </a>
                                        
                                        @if($resa->manifestation->PRIXMANIF > 0 && !$resa->billet->IDTYPEPAIEMENT)
                                            <a href="{{ route('paiement.checkout', $resa->billet->IDBILLET) }}" class="flex-1 bg-yellow-500 text-white text-center py-2 rounded-lg text-sm font-bold hover:bg-yellow-600 transition">
                                                Payer maintenant
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