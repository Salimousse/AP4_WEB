<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Manifestation;
use App\Models\Client;
use App\Models\Reservation;
use App\Models\Billet;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class ReservationController extends Controller
{
    // AFFICHER LE FORMULAIRE (Inchangé)
    public function showForm($idManif)
    {
        $manif = Manifestation::findOrFail($idManif);
        return view('pages.page-reservation', compact('manif'));
    }

    // 1. PRÉPARER LA RÉSERVATION (Ne rien enregistrer en BDD encore !)
    public function store(Request $request)
    {
        // Validation
        $request->validate(['id_manif' => 'required|exists:manifestations,IDMANIF']);

        $user = Auth::user();
        $manif = Manifestation::findOrFail($request->id_manif);

        // On prépare les données qu'on VOUDRA sauvegarder plus tard
        $dataReservation = [
            'email_user' => $user->email,
            'nom_user'   => $user->name,
            'telephone'  => $request->telephone ?? '0000000000',
            'id_manif'   => $manif->IDMANIF,
            'prix'       => $manif->PRIXMANIF
        ];

        // CAS 1 : C'est GRATUIT -> On enregistre tout de suite
        if ($manif->PRIXMANIF <= 0) {
            return $this->creationFinale($dataReservation, null); // null = pas de paiement
        }

        // CAS 2 : C'est PAYANT -> On envoie sur Stripe SANS enregistrer en BDD
        
        // On sauvegarde les infos dans la SESSION (mémoire temporaire)
        session(['donnees_en_attente' => $dataReservation]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => ['name' => 'Billet : ' . $manif->NOMMANIF],
                    'unit_amount' => intval($manif->PRIXMANIF * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            // IMPORTANT : On redirige vers notre nouvelle route de validation
            'success_url' => route('reservation.validation'),
            'cancel_url' => route('programme'),
        ]);

        return redirect($session->url);
    }

    // 2. LE RETOUR DE STRIPE (C'est ICI qu'on enregistre en BDD)
    public function validerPaiement()
{
        // On récupère les données temporaires
        $data = session('donnees_en_attente');

        // Sécurité : Si pas de données en session (ex: accès direct à l'url), on jette
        if (!$data) {
            return redirect()->route('programme')->with('error', 'Aucune réservation en attente.');
        }

        // On lance la création réelle en BDD (IDTYPEPAIEMENT = 1 pour CB)
        $billet = $this->creationFinale($data, 1);

        // Redirige vers la page ticket-reservation (route page.ticket-reservation)
        return redirect()->route('page.ticket-reservation', ['idBillet' => $billet->IDBILLET])
            ->with('success', 'Paiement validé ! Voici votre billet.');
}

    // FONCTION PRIVÉE : Pour éviter de copier-coller le code d'insertion
    private function creationFinale($data, $typePaiement)
    {
        // 1. Client
        $client = Client::firstOrCreate(
            ['MAILCLIENT' => $data['email_user']],
            [
                'NOMPERS' => $data['nom_user'],
                'PRENOMPERS' => '',
                'TELCLIENT' => $data['telephone']
            ]
        );

        // 2. Réservation
        $reservation = Reservation::create([
            'IDMANIF' => $data['id_manif'],
            'IDPERS' => $client->IDPERS,
            'DATEHEURERESERVATION' => now(),
            'NBPERSRESERVATION' => 1
        ]);

        // 3. Billet
        $billet = Billet::create([
            'IDSPONSORS' => rand(1, 11), // Ton système aléatoire de sponsors
            'IDRESERVATION' => $reservation->IDRESERVATION,
            'IDMANIF' => $data['id_manif'],
            'IDPERS' => $client->IDPERS,
            'QRCODEBILLET' => Str::uuid(),
            'IDTYPEPAIEMENT' => $typePaiement, // 1 (CB) ou NULL (Gratuit)
            'INVITEBILLET' => 0
        ]);

        // On nettoie la session pour ne pas pouvoir recharger la page et recréer un billet
        session()->forget('donnees_en_attente');

        // On retourne le billet pour la redirection
        return $billet;
    }

    // ...
public function showTicket($idBillet)
{
    // On charge le billet ET les infos liées (Client + Manif)
    $billet = Billet::with(['client', 'manifestation'])->findOrFail($idBillet);

    return view('pages.ticket-reservation', compact('billet'));
}
    
    
    // Ajoute aussi la methode showTicket si elle n'y est plus...
}