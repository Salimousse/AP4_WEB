<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programme - {{ $festival->THEMEFEST }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <nav class="bg-white shadow-sm py-4">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-blue-600">CALE SONS</a>
            <a href="/" class="text-gray-600 hover:text-blue-600">Retour Accueil</a>
        </div>
    </nav>

    <header class="bg-blue-600 text-white py-16 text-center px-4">
        <h1 class="text-4xl md:text-6xl font-black mb-4 uppercase">{{ $festival->THEMEFEST }}</h1>
        <div class="text-blue-200 text-xl font-light">
            📅 Du {{ \Carbon\Carbon::parse($festival->DATEDEBFEST)->format('d/m/Y') }} 
            au {{ \Carbon\Carbon::parse($festival->DATEFINFEST)->format('d/m/Y') }}
        </div>
    </header>

    <main class="container mx-auto px-6 py-12">
        <h2 class="text-3xl font-bold mb-8 border-l-4 border-blue-600 pl-4">À l'affiche</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            @foreach($festival->manifestations as $manif)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300 flex flex-col">
                
                <div class="h-48 bg-gray-200 relative">
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-400 to-purple-500 text-white text-4xl font-bold opacity-80">
                        {{ substr($manif->NOMMANIF, 0, 1) }}
                    </div>

                    <div class="absolute top-4 right-4 bg-white text-gray-900 font-bold px-3 py-1 rounded-full text-sm shadow">
                        {{ $manif->PRIXMANIF == 0 ? 'Gratuit' : number_format($manif->PRIXMANIF, 0) . ' €' }}
                    </div>
                </div>

                <div class="p-6 flex-1 flex flex-col">
                    <h3 class="text-xl font-bold mb-2 text-gray-900">{{ $manif->NOMMANIF }}</h3>
                    <p class="text-gray-600 text-sm mb-4 flex-1">
                        {{ $manif->RESUMEMANIF }}
                    </p>

                    <div class="mt-auto pt-4 border-t border-gray-100 flex justify-between items-center">
                        <span class="text-xs text-gray-500">
                            Places : {{ $manif->NBMAXPARTICIPANTMANIF }}
                        </span>
                        
                        <a href="{{ route('reservation.create', $manif->IDMANIF) }}" 
   class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition">
    Réserver
</a>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </main>

    <footer class="bg-gray-900 text-white py-8 text-center mt-12">
        <p>&copy; 2026 Festival Cale Sons.</p>
    </footer>

    <x-chatbot-widget />

</body>
</html>