<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Reservation;
use App\Models\Client;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function ShowTicket()
    {
        // 1. On récupère l'utilisateur connecté
        $user = Auth::user();

        // 2. On cherche sa fiche Client via l'email
        $client = Client::where('MAILCLIENT', $user->email)->first();

        $reservations = [];

        if ($client) {
            // 3. On récupère ses réservations avec les infos du Billet et de la Manif
            $reservations = Reservation::where('IDPERS', $client->IDPERS)
                                ->with(['manifestation', 'billet']) // "Eager loading" pour optimiser
                                ->orderBy('DATEHEURERESERVATION', 'desc')
                                ->get();
        }

        return view('pages.mes-reservations', compact('reservations'));
    }
}


