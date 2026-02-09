<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use App\Models\Billet;
use App\Models\Client;
use App\Models\Festival;
use App\Models\Reservation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use App\Models\Manifestation;
use Illuminate\Support\Facades\Auth;


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
        $request->validate(['id_manif' => 'required|exists:MANIFESTATIONS,IDMANIF']);

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
            $billet = $this->creationFinale($dataReservation, 2); // 2 = gratuit (considéré comme déjà payé)
            return redirect()->route('reservation.success', ['idBillet' => $billet->IDBILLET])
                ->with('success', 'Réservation confirmée ! Voici votre billet.');
        }

        // CAS 2 : C'est PAYANT -> On envoie sur Stripe SANS enregistrer en BDD
        
        // On sauvegarde les infos dans la SESSION (mémoire temporaire)
        session(['donnees_en_attente' => $dataReservation]);

        // On encode aussi les données dans l'URL comme backup
        $encodedData = base64_encode(json_encode($dataReservation));

        Stripe::setApiKey(config('services.stripe.secret'));

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
            // IMPORTANT : On redirige vers notre nouvelle route de validation avec les données
            'success_url' => route('reservation.validation') . '?data=' . $encodedData,
            'cancel_url' => route('festivals'),
        ]);

        return redirect($session->url);
    }

    // 2. LE RETOUR DE STRIPE (C'est ICI qu'on enregistre en BDD)
    public function validerPaiement(Request $request)
    {
        // On essaie d'abord de récupérer depuis l'URL
        $data = null;
        
        if ($request->has('data')) {
            try {
                $data = json_decode(base64_decode($request->get('data')), true);
            } catch (\Exception $e) {
                // Si décodage échoue, on essaie la session
            }
        }
        
        // Fallback : on essaie la session
        if (!$data) {
            $data = session('donnees_en_attente');
        }

        // Sécurité : Si pas de données du tout, on redirige
        if (!$data) {
            return redirect()->route('festivals')->with('error', 'Aucune réservation en attente.');
        }

        // On lance la création réelle en BDD (IDTYPEPAIEMENT = 1 pour CB)
        $billet = $this->creationFinale($data, 1);

        // Récupérer les infos complètes pour affichage
        $manifestation = Manifestation::find($billet->IDMANIF);
        $festival = Festival::find($manifestation->IDFESTIVAL);

        // Afficher la page de validation avec toutes les infos
        return view('pages.validation-reservation', [
            'billet' => $billet,
            'manifestation' => $manifestation,
            'festival' => $festival
        ]);
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
            'IDSPONSORS' => rand(1, 8), // Tu as 8 sponsors dans ta base
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
    // On charge le billet avec TOUTES les infos liées
    $billet = Billet::with([
        'client',                          // Infos du client
        'manifestation.festival',          // Manifestation + Festival
        'reservation',                     // Infos de réservation  
        'sponsor',                         // Sponsor du billet
        'typepaiement'                     // Type de paiement (CB/Gratuit)
    ])->findOrFail($idBillet);

    return view('pages.ticket-reservation', compact('billet'));
}
    
    
    // Ajoute aussi la methode showTicket si elle n'y est plus...
}