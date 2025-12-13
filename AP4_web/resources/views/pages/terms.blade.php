<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Conditions générales de vente - Festival Cale Sons</title>
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
                    <h1 class="text-5xl font-bold text-gray-900 mb-6">Conditions générales de vente</h1>
                </div>

                <!-- Content Section -->
                <div class="prose prose-lg max-w-none">
                    <p class="text-sm text-gray-500 uppercase tracking-wide mb-3">Eyebrow text to label this content</p>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">A headline for some text</h2>
                    <p class="text-gray-700 leading-relaxed mb-8">
                        On the one hand, all you need to do is say what you mean, in your words, in your voice. On the other, there are so many rules to consider! Are you thinking of keywords you should rank for? Are you including links in your text to authoritative sources? Things that we've built for your own website, which helps boost your SEO? That's what you've written easy to scan? There's a theory that people read in an F-shape pattern, and that this should influence how you structure content on your website. Lots of this and outs—it's no wonder writers rule the world.
                    </p>

                    <h3 class="text-2xl font-bold text-gray-900 mb-4 mt-12">Article 1 - Objet</h3>
                    <p class="text-gray-700 leading-relaxed mb-6">
                        Les présentes conditions générales de vente (CGV) régissent les relations contractuelles entre festival et ses clients. Toute commande implique l'acceptation sans réserve des présentes CGV.
                    </p>

                    <h3 class="text-2xl font-bold text-gray-900 mb-4 mt-12">Article 2 - Prix</h3>
                    <p class="text-gray-700 leading-relaxed mb-6">
                        Les prix de nos services sont indiqués en euros, toutes taxes comprises (TTC). festival se réserve le droit de modifier ses tarifs à tout moment, étant toutefois entendu que le prix figurant sur le site le jour de la commande sera le seul applicable au client.
                    </p>

                    <h3 class="text-2xl font-bold text-gray-900 mb-4 mt-12">Article 3 - Commandes</h3>
                    <p class="text-gray-700 leading-relaxed mb-6">
                        Le client passe commande en ligne via notre plateforme. Toute commande vaut acceptation des présentes CGV. La confirmation de commande sera envoyée par email à l'adresse indiquée lors de l'inscription.
                    </p>

                    <h3 class="text-2xl font-bold text-gray-900 mb-4 mt-12">Article 4 - Paiement</h3>
                    <p class="text-gray-700 leading-relaxed mb-6">
                        Le paiement s'effectue par carte bancaire, PayPal ou tout autre moyen de paiement proposé sur notre plateforme. Le client garantit qu'il dispose des autorisations nécessaires pour utiliser le mode de paiement choisi.
                    </p>

                    <h3 class="text-2xl font-bold text-gray-900 mb-4 mt-12">Article 5 - Livraison</h3>
                    <p class="text-gray-700 leading-relaxed mb-6">
                        Les services numériques sont disponibles immédiatement après confirmation du paiement. Les billets pour événements sont envoyés par email dans un délai maximum de 24 heures après la commande.
                    </p>

                    <h3 class="text-2xl font-bold text-gray-900 mb-4 mt-12">Article 6 - Droit de rétractation</h3>
                    <p class="text-gray-700 leading-relaxed mb-6">
                        Conformément à l'article L221-28 du Code de la consommation, le droit de rétractation ne peut être exercé pour les billets d'événements datés. Pour les autres services, le client dispose d'un délai de 14 jours à compter de la souscription pour exercer son droit de rétractation.
                    </p>

                    <h3 class="text-2xl font-bold text-gray-900 mb-4 mt-12">Article 7 - Responsabilité</h3>
                    <p class="text-gray-700 leading-relaxed mb-6">
                        festival ne saurait être tenu responsable de l'inexécution du contrat en cas de rupture de stock, indisponibilité du service, force majeure, perturbation ou grève totale ou partielle.
                    </p>

                    <h3 class="text-2xl font-bold text-gray-900 mb-4 mt-12">Article 8 - Litiges</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Les présentes CGV sont soumises au droit français. En cas de litige, une solution amiable sera recherchée avant toute action judiciaire. À défaut, les tribunaux français seront seuls compétents.
                    </p>
                </div>
            </div>
        </main>

        @include('layouts.footer')
    </body>
</html>
