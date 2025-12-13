<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>À propos - Festival Cale Sons</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-white">
        @include('layouts.header')

        <!-- Main Content -->
        <main class="py-16">
            <div class="max-w-4xl mx-auto px-6">
                <!-- Page Title -->
                <div class="text-center mb-16">
                    <p class="text-sm text-festival-primary uppercase tracking-wide mb-3">Festival Cale Sons</p>
                    <h1 class="text-5xl font-bold text-festival-dark mb-6">À propos de nous</h1>
                </div>

                <!-- Content Section -->
                <div class="prose prose-lg max-w-none">
                    <p class="text-sm text-festival-primary uppercase tracking-wide mb-3">Découvrez notre histoire</p>
                    <h2 class="text-3xl font-bold text-festival-dark mb-6">Qui sommes nous ?</h2>
                    <p class="text-festival-dark/80 leading-relaxed mb-8">
                        L'association des Cale-Sons est le fruit d'une rencontre multigénérationnelle et d'une passion commune pour la musique. Nous sommes animés par l'ambition de proposer au public des manifestations de qualité et accessibles à tous. Forts du succès de la dernière édition, nous relançons le Freestone.
                    </p>

                    <h3 class="text-2xl font-bold text-festival-dark mb-4 mt-12">Le Festival Cale Sons 2026</h3>
                    <p class="text-festival-dark/80 leading-relaxed mb-6">
                        Après une annulation marquante due à des incertitudes budgétaires, le festival Cale Sons cherche à se réinventer pour son édition 2026. Cette année, prévue sur 4 jours en août 2026, l'organisation du festival veut re-dynamiser l'événement et mieux gérer les différentes manifestations pour s'imposer comme une référence culturelle majeure, en touchant un public jeune et connecté.
                    </p>

                    <h3 class="text-2xl font-bold text-festival-dark mb-4 mt-12">Thématique 2026</h3>
                    <p class="text-festival-dark/80 leading-relaxed mb-6">
                        Cette année, la thématique choisie est <strong>« Terres de Légendes : Entre Racines et Futur »</strong>. Une exploration entre tradition et modernité qui guidera l'ensemble de notre programmation.
                    </p>

                    <h3 class="text-2xl font-bold text-festival-dark mb-4 mt-12">Nos manifestations</h3>
                    <p class="text-festival-dark/80 leading-relaxed mb-6">
                        Le festival propose une programmation variée :
                    </p>
                    <ul class="list-disc list-inside text-festival-dark/80 space-y-2 mb-8">
                        <li>Concerts et performances musicales</li>
                        <li>Expositions artistiques</li>
                        <li>Conférences et débats</li>
                        <li>Ateliers participatifs</li>
                    </ul>

                    <h3 class="text-2xl font-bold text-festival-dark mb-4 mt-12">Nos lieux</h3>
                    <p class="text-festival-dark/80 leading-relaxed mb-6">
                        Les manifestations se déroulent dans différents lieux emblématiques : Maison de la Culture et des Loisirs (MCL), médiathèque, Grange Dimière, Église, parc du prieuré... avec des capacités d'accueil adaptées (la MCL peut accueillir jusqu'à 350 personnes par exemple).
                    </p>
                </div>
            </div>
        </main>

        @include('layouts.footer')
    </body>
</html>
