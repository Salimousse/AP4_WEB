<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class FacebookAuthController extends Controller
{
    // Redirige l'utilisateur vers Facebook
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    // Gère le retour de Facebook
    public function callback()
    {
        try {
            $facebook_user = Socialite::driver('facebook')->user();

            // Vérifier que Facebook nous a bien donné un email
            if (!$facebook_user->getEmail()) {
                return redirect()->route('login')->with('error', 'Facebook n\'a pas partagé votre adresse email. Veuillez autoriser l\'accès à votre email.');
            }

            // CAS 1 : L'utilisateur est DÉJÀ connecté -> On lie le compte
            if (Auth::check()) {
                /** @var User $currentUser */
                $currentUser = Auth::user();
                
                // Vérifier si ce compte Facebook est déjà utilisé par quelqu'un d'autre
                $existingAccount = User::where('facebook_id', $facebook_user->getId())->first();
                
                if ($existingAccount && $existingAccount->id !== $currentUser->id) {
                    return redirect()->route('connected-accounts')->with('error', 'Ce compte Facebook est déjà lié à un autre utilisateur.');
                }

                $currentUser->update([
                    'facebook_id' => $facebook_user->getId(),
                    'facebook_email' => $facebook_user->getEmail()
                ]);
                return redirect()->route('connected-accounts')->with('success', 'Compte Facebook lié avec succès !');
            }

            // CAS 2 : Login / Inscription classique
            $user = User::where('facebook_id', $facebook_user->getId())->first();

            if (!$user) {
                $user = User::where('email', $facebook_user->getEmail())->first();

                if ($user) {
                    $user->update([
                        'facebook_id' => $facebook_user->getId(),
                        'facebook_email' => $facebook_user->getEmail()
                    ]);
                } else {
                    $user = User::create([
                        'name' => $facebook_user->getName(),
                        'email' => $facebook_user->getEmail(),
                        'facebook_id' => $facebook_user->getId(),
                        'facebook_email' => $facebook_user->getEmail(),
                        'password' => null,
                    ]);
                }
            } else {
                // Mettre à jour l'email au cas où il aurait changé
                $user->update(['facebook_email' => $facebook_user->getEmail()]);
            }

            Auth::login($user);
            return redirect()->intended(route('connected-accounts'));

        } catch (Exception $e) {
            Log::error("Erreur Facebook Login : " . $e->getMessage());
            return redirect()->route('login')->with('error', 'Erreur de connexion Facebook : ' . $e->getMessage());
        }
    }

    // Délier le compte Facebook
    public function unlink()
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Sécurité : Empêcher de délier si c'est le seul moyen de connexion et qu'il n'y a pas de mot de passe
        if (!$user->password && !$user->google_id && !$user->microsoft_id) {
            return redirect()->route('connected-accounts')->with('error', 'Vous ne pouvez pas délier ce compte car vous n\'avez pas de mot de passe ni d\'autre compte lié.');
        }

        $user->update([
            'facebook_id' => null,
            'facebook_email' => null
        ]);
        return redirect()->route('connected-accounts')->with('success', 'Compte Facebook délié avec succès.');
    }
}