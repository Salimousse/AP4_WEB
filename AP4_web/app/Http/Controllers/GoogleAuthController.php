<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    // Redirige l'utilisateur vers Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Gère le retour de Google
    public function callbackGoogle()
    {
        try {
            $google_user = Socialite::driver('google')->user();

            // CAS 1 : L'utilisateur est DÉJÀ connecté -> On lie le compte
            if (Auth::check()) {
                /** @var User $currentUser */
                $currentUser = Auth::user();
                
                // Vérifier si ce compte Google est déjà utilisé par quelqu'un d'autre
                $existingAccount = User::where('google_id', $google_user->getId())->first();
                
                if ($existingAccount && $existingAccount->id !== $currentUser->id) {
                    return redirect()->route('connected-accounts')->with('error', 'Ce compte Google est déjà lié à un autre utilisateur.');
                }

                $currentUser->update([
                    'google_id' => $google_user->getId(),
                    'google_email' => $google_user->getEmail()
                ]);
                return redirect()->route('connected-accounts')->with('success', 'Compte Google lié avec succès !');
            }

            // CAS 2 : Login / Inscription classique
            $user = User::where('google_id', $google_user->getId())->first();

            if (!$user) {
                $user = User::where('email', $google_user->getEmail())->first();

                if ($user) {
                    $user->update([
                        'google_id' => $google_user->getId(),
                        'google_email' => $google_user->getEmail()
                    ]);
                } else {
                    $user = User::create([
                        'name' => $google_user->getName(),
                        'email' => $google_user->getEmail(),
                        'google_id' => $google_user->getId(),
                        'google_email' => $google_user->getEmail(),
                        'password' => null,
                    ]);
                }
            } else {
                // Mettre à jour l'email au cas où il aurait changé
                $user->update(['google_email' => $google_user->getEmail()]);
            }

            Auth::login($user);
            return redirect()->route('connected-accounts');

        } catch (Exception $e) {
            Log::error("Erreur Google Login : " . $e->getMessage());
            return redirect()->route('login')->with('error', 'Erreur de connexion Google.');
        }
    }

    // Délier le compte Google
    public function unlink()
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Sécurité : Empêcher de délier si c'est le seul moyen de connexion et qu'il n'y a pas de mot de passe
        if (!$user->password && !$user->microsoft_id && !$user->facebook_id) {
            return redirect()->route('connected-accounts')->with('error', 'Vous ne pouvez pas délier ce compte car vous n\'avez pas de mot de passe ni d\'autre compte lié.');
        }

        $user->update([
            'google_id' => null,
            'google_email' => null
        ]);
        return redirect()->route('connected-accounts')->with('success', 'Compte Google délié avec succès.');
    }

}
    
