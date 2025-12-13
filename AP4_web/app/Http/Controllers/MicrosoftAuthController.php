<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class MicrosoftAuthController extends Controller
{
    // 1. Rediriger l'utilisateur vers la page de connexion Microsoft
    public function redirect()
    {
        return Socialite::driver('azure')->redirect();
    }

    // 2. Gérer le retour de Microsoft
    public function callback()
    {
        try {
            // Récupérer les infos de l'utilisateur via Socialite
            $microsoftUser = Socialite::driver('azure')->user();

            // --- Logique de connexion / Inscription ---

            // A. Est-ce que cet utilisateur Microsoft existe déjà dans notre base ?
            $user = User::where('microsoft_id', $microsoftUser->getId())->first();

            if ($user) {
                // OUI : on le connecte direct (et met à jour l'email au cas où il aurait changé)
                $user->update(['microsoft_email' => $microsoftUser->getEmail()]);
                Auth::login($user);
                return redirect()->route('connected-accounts');
            }

            // B. Si NON : Est-ce qu'il existe déjà un utilisateur avec cet Email ?
            // (Ex: il s'est inscrit avec Google ou Email/Mdp avant)
            $user = User::where('email', $microsoftUser->getEmail())->first();

            if ($user) {
                // OUI : On met à jour son compte pour ajouter l'ID Microsoft
                // Comme ça, il pourra se connecter des deux façons
                $user->update([
                    'microsoft_id' => $microsoftUser->getId(),
                    'microsoft_email' => $microsoftUser->getEmail()
                ]);

                Auth::login($user);
                return redirect()->route('connected-accounts');
            }

            // C. Si NON à tout : C'est un nouvel inscrit
            $newUser = User::create([
                'name' => $microsoftUser->getName(),
                'email' => $microsoftUser->getEmail(),
                'microsoft_id' => $microsoftUser->getId(),
                'microsoft_email' => $microsoftUser->getEmail(),
                'password' => null, // Pas de mot de passe car géré par Microsoft
                // 'google_id' => null, // Automatique
            ]);

            Auth::login($newUser);
            return redirect()->route('connected-accounts');

        } catch (Exception $e) {
            // En cas d'erreur (annulation, problème réseau...)
            return redirect()->route('login')->with('error', 'Erreur de connexion Microsoft : ' . $e->getMessage());
        }
    }

    // Délier le compte Microsoft
    public function unlink()
    {
        /** @var User $user */
        $user = Auth::user();

        // Sécurité : Empêcher de délier si c'est le seul moyen de connexion et qu'il n'y a pas de mot de passe
        if (!$user->password && !$user->google_id && !$user->facebook_id) {
            return redirect()->route('connected-accounts')->with('error', 'Vous ne pouvez pas délier ce compte car vous n\'avez pas de mot de passe ni d\'autre compte lié.');
        }

        $user->update([
            'microsoft_id' => null,
            'microsoft_email' => null
        ]);
        return redirect()->route('connected-accounts')->with('success', 'Compte Microsoft délié avec succès.');
    }
}