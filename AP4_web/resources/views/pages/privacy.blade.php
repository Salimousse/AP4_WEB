<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Politique de confidentialité - Festival Cale Sons</title>
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
                    <p class="text-sm text-gray-500 uppercase tracking-wide mb-3">Eyebrow text to label this content</p>
                    <h1 class="text-5xl font-bold text-gray-900 mb-6">Politique de confidentialité</h1>
                </div>

                <!-- Content Section -->
                <div class="prose prose-lg max-w-none">
                    <p class="text-sm text-gray-500 uppercase tracking-wide mb-3">Eyebrow text to label this content</p>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">A headline for some text</h2>
                    <p class="text-gray-700 leading-relaxed mb-8">
                        On the one hand, all you need to do is say what you mean, in your words, in your voice. On the other, there are so many rules to consider! Are you thinking of keywords you should rank for? Are you including links in your text to authoritative sources? Things that we've built for your own website, which helps boost your SEO? That's what you've written easy to scan? There's a theory that people read in an F-shape pattern, and that this should influence how you structure content on your website. Lots of this and outs—it's no wonder writers rule the world.
                    </p>

                    <h3 class="text-2xl font-bold text-gray-900 mb-4 mt-12">Collecte des données</h3>
                    <p class="text-gray-700 leading-relaxed mb-6">
                        Nous collectons uniquement les données nécessaires au bon fonctionnement de notre service. Ces données incluent votre nom, adresse email, et les informations fournies lors de votre inscription via les services tiers (Google, Microsoft, Facebook).
                    </p>

                    <h3 class="text-2xl font-bold text-gray-900 mb-4 mt-12">Utilisation des données</h3>
                    <p class="text-gray-700 leading-relaxed mb-6">
                        Vos données personnelles sont utilisées pour :
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-8">
                        <li>Gérer votre compte utilisateur</li>
                        <li>Vous permettre d'accéder à nos services</li>
                        <li>Améliorer votre expérience utilisateur</li>
                        <li>Vous envoyer des communications importantes concernant votre compte</li>
                    </ul>

                    <h3 class="text-2xl font-bold text-gray-900 mb-4 mt-12">Protection des données</h3>
                    <p class="text-gray-700 leading-relaxed mb-6">
                        Nous mettons en œuvre toutes les mesures techniques et organisationnelles appropriées pour protéger vos données personnelles contre toute perte, utilisation abusive, accès non autorisé, divulgation, altération ou destruction.
                    </p>

                    <h3 class="text-2xl font-bold text-gray-900 mb-4 mt-12">Vos droits</h3>
                    <p class="text-gray-700 leading-relaxed mb-6">
                        Conformément au RGPD, vous disposez des droits suivants :
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-8">
                        <li>Droit d'accès à vos données personnelles</li>
                        <li>Droit de rectification de vos données</li>
                        <li>Droit à l'effacement (droit à l'oubli)</li>
                        <li>Droit à la portabilité de vos données</li>
                        <li>Droit d'opposition au traitement de vos données</li>
                    </ul>

                    <h3 class="text-2xl font-bold text-gray-900 mb-4 mt-12">Contact</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Pour toute question concernant cette politique de confidentialité ou pour exercer vos droits, vous pouvez nous contacter via notre <a href="{{ route('contact') }}" class="text-blue-600 hover:underline">page de contact</a>.
                    </p>
                </div>
            </div>
        </main>

        @include('layouts.footer')
    </body>
</html>
