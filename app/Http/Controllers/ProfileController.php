<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Affiche la page d'accueil de l'utilisateur avec ses réservations.
     */
    public function homeUser(Request $request): View
    {
        // Récupérer l'utilisateur connecté
        $user = $request->user();

        // Vérifier si l'utilisateur est authentifié et charger ses réservations
        // Si la relation n'existe pas ou qu'il n'y a pas de réservations,
        // on retourne une collection vide pour éviter les erreurs.
        $reservations = $user ? $user->bookings : collect();

        return view('profile.homeUser', [
            'user' => $user,
            'reservations' => $reservations, // Passer les réservations (ou la collection vide) à la vue
        ]);
    }

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
}
