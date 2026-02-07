<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajouter un avis - {{ $billet->manifestation->NOMMANIF }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white">

    @include('layouts.header')

    <section class="h-[300px] bg-gradient-to-br from-festival-primary to-festival-secondary flex items-center justify-center">
        <div class="text-center text-white px-6">
            <h1 class="text-4xl font-bold mb-2">📝 Donnez votre avis</h1>
            <p class="text-lg">Aidez d'autres personnes à découvrir cette manifestation</p>
        </div>
    </section>

    <section class="py-12">
        <div class="max-w-2xl mx-auto px-6">

            <!-- Informations du billet et de la manifestation -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border border-festival-dark/5">
                <div class="flex gap-6 items-start">
                    <!-- Image de la manifestation -->
                    <div class="w-24 h-24 rounded-lg overflow-hidden flex-shrink-0 bg-festival-light">
                        @if($billet->manifestation->AFFICHEMANIF)
                            <img src="{{ asset('storage/' . $billet->manifestation->AFFICHEMANIF) }}" 
                                 alt="{{ $billet->manifestation->NOMMANIF }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-3xl">
                                🎭
                            </div>
                        @endif
                    </div>

                    <!-- Détails -->
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-festival-dark mb-2">
                            {{ $billet->manifestation->NOMMANIF }}
                        </h2>
                        <p class="text-festival-dark/70 mb-3">{{ $billet->manifestation->RESUMEMANIF }}</p>
                        
                        <div class="flex gap-4 text-sm">
                            <div class="text-festival-dark/60">
                                <span class="font-semibold">Billet:</span> #{{ $billet->IDBILLET }}
                            </div>
                            <div class="text-festival-dark/60">
                                <span class="font-semibold">Date:</span> 
                                {{ \Carbon\Carbon::parse($billet->manifestation->DATEMANIF)->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Affichage si avis existant -->
            @if($avisExistant)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <p class="text-blue-700 text-sm">
                        ✏️ <strong>Vous avez déjà donné un avis.</strong> Vous pouvez le modifier ci-dessous.
                    </p>
                </div>
            @endif

            <!-- Formulaire d'avis -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-festival-dark/5">
                <form action="{{ route('avis.store', $billet->IDBILLET) }}" method="POST">
                    @csrf

                    <!-- Note -->
                    <div class="mb-6">
                        <label class="block text-lg font-bold text-festival-dark mb-3">
                            ⭐ Votre note
                        </label>
                        
                        <div class="flex gap-3" id="rating-container">
                            @for($i = 1; $i <= 5; $i++)
                                <input type="radio" name="note" value="{{ $i }}" 
                                       id="note-{{ $i }}" class="hidden"
                                       {{ old('note', $avisExistant?->NOTEAVIS) == $i ? 'checked' : '' }}>
                                <label for="note-{{ $i }}" class="cursor-pointer text-4xl transition-transform hover:scale-110 star-label" 
                                       data-value="{{ $i }}">
                                    ⭐
                                </label>
                            @endfor
                        </div>
                        
                        @error('note')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror

                        <p class="text-sm text-festival-dark/60 mt-3">
                            Cliquez sur les étoiles pour évaluer votre expérience
                        </p>
                    </div>

                    <!-- Commentaire -->
                    <div class="mb-6">
                        <label for="commentaire" class="block text-lg font-bold text-festival-dark mb-3">
                            💬 Votre commentaire
                        </label>
                        
                        <textarea name="commentaire" id="commentaire" rows="5" 
                                  placeholder="Partagez votre impressions sur cette manifestation... (optionnel)"
                                  class="w-full border border-festival-dark/10 rounded-lg focus:ring-2 focus:ring-festival-primary focus:border-festival-primary bg-festival-light/20 px-4 py-3 text-festival-dark placeholder-festival-dark/40 outline-none">{{ old('commentaire', $avisExistant?->COMMENTAIREAVIS) }}</textarea>
                        
                        @error('commentaire')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror

                        <p class="text-xs text-festival-dark/60 mt-2">
                            Caractères restants: <span id="char-count">1000</span>/1000
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-4 pt-6 border-t border-festival-dark/10">
                        <a href="{{ route('reservation.success', $billet->IDBILLET) }}" 
                           class="flex-1 bg-festival-dark/10 text-festival-dark font-bold rounded-lg px-6 py-3 hover:bg-festival-dark/20 transition text-center">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="flex-1 bg-festival-primary text-white font-bold rounded-lg px-6 py-3 hover:bg-festival-secondary transition">
                            {{ $avisExistant ? '✏️ Mettre à jour mon avis' : '✅ Publier mon avis' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Lien vers les avis de la manifestation -->
            <div class="text-center mt-8">
                <a href="{{ route('avis.index', $billet->IDMANIF) }}" 
                   class="inline-block text-festival-primary font-bold hover:underline">
                    📊 Voir tous les avis de cette manifestation →
                </a>
            </div>

        </div>
    </section>

    @include('layouts.footer')

    <script>
        // Gestion des étoiles interactives
        const labels = document.querySelectorAll('.star-label');
        const container = document.getElementById('rating-container');
        const radios = document.querySelectorAll('input[name="note"]');

        labels.forEach(label => {
            label.addEventListener('click', function() {
                const value = this.dataset.value;
                document.getElementById(`note-${value}`).checked = true;
                updateStars(value);
            });
        });

        function updateStars(rating) {
            labels.forEach(label => {
                const value = label.dataset.value;
                if (value <= rating) {
                    label.textContent = '⭐';
                    label.classList.remove('opacity-30');
                } else {
                    label.textContent = '⭐';
                    label.classList.add('opacity-30');
                }
            });
        }

        // Initialiser les étoiles au chargement
        const checkedValue = document.querySelector('input[name="note"]:checked')?.value;
        if (checkedValue) {
            updateStars(checkedValue);
        } else {
            labels.forEach(label => label.classList.add('opacity-30'));
        }

        // Compteur de caractères
        const commentaire = document.getElementById('commentaire');
        const charCount = document.getElementById('char-count');
        
        commentaire.addEventListener('input', function() {
            const remaining = 1000 - this.value.length;
            charCount.textContent = remaining;
        });

        // Initialiser le compteur
        charCount.textContent = 1000 - (commentaire.value?.length || 0);
    </script>

    <style>
        .star-label {
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
            transition: all 0.2s ease;
        }

        .star-label:hover {
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
        }

        .opacity-30 {
            opacity: 0.3;
        }
    </style>

</body>
</html>
