<?php

namespace App\Http\Controllers;

use App\Models\Avi;
use App\Models\Billet;
use App\Models\Client;
use App\Models\Manifestation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvisController extends Controller
{
    /**
     * Afficher le formulaire pour ajouter un avis
     * 
     * @param int $idBillet L'ID du billet
     * @return \Illuminate\View\View
     */
    public function showForm($idBillet)
    {
        // Charger le billet avec ses relations
        $billet = Billet::with(['manifestation', 'client'])->findOrFail($idBillet);
        
        // Vérifier que l'utilisateur connecté est bien le propriétaire du billet
        $user = Auth::user();
        $client = Client::where('MAILCLIENT', $user->email)->first();
        
        if (!$client || $billet->IDPERS !== $client->IDPERS) {
            return abort(403, 'Vous n\'êtes pas autorisé à commenter ce billet.');
        }
        
        // Vérifier s'il a déjà un avis pour cette manifestation
        $avisExistant = Avi::where('IDBILLET', $idBillet)
            ->where('IDMANIF', $billet->IDMANIF)
            ->first();
        
        return view('pages.form-avis', [
            'billet' => $billet,
            'avisExistant' => $avisExistant
        ]);
    }
    
    /**
     * Créer ou mettre à jour un avis
     * 
     * @param Request $request
     * @param int $idBillet
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $idBillet)
    {
        // Valider les données
        $request->validate([
            'note' => 'required|integer|min:1|max:5',
            'commentaire' => 'nullable|string|max:1000'
        ]);
        
        // Vérifier que le billet existe et appartient à l'utilisateur
        $billet = Billet::findOrFail($idBillet);
        $user = Auth::user();
        $client = Client::where('MAILCLIENT', $user->email)->first();
        
        if (!$client || $billet->IDPERS !== $client->IDPERS) {
            return abort(403, 'Vous n\'êtes pas autorisé à commenter ce billet.');
        }
        
        // Créer ou mettre à jour l'avis
        Avi::updateOrCreate(
            [
                'IDBILLET' => $idBillet,
                'IDMANIF' => $billet->IDMANIF
            ],
            [
                'NOTEAVIS' => $request->input('note'),
                'COMMENTAIREAVIS' => $request->input('commentaire')
            ]
        );
        
        return redirect()->route('reservation.success', ['idBillet' => $idBillet])
            ->with('success', 'Votre avis a été enregistré avec succès !');
    }
    
    /**
     * Afficher les avis d'une manifestation
     * 
     * @param int $idManif L'ID de la manifestation
     * @return \Illuminate\View\View
     */
    public function showByManifestration($idManif)
    {
        // Récupérer tous les avis pour cette manifestation
        $avis = Avi::where('IDMANIF', $idManif)
            ->with(['billet.client', 'manifestation'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculer la note moyenne
        $noteMoyenne = $avis->avg('NOTEAVIS');
        $totalAvis = $avis->count();
        
        return view('pages.avis-manifestation', [
            'avis' => $avis,
            'noteMoyenne' => $noteMoyenne,
            'totalAvis' => $totalAvis,
            'idManif' => $idManif
        ]);
    }
}
