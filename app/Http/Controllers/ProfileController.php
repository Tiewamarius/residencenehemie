<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Residence;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        $user = $request->user();

        $data = $request->validated();

        // Upload image
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profiles', 'public');
            $data['profile_picture'] = $path;
        }

        // Si mot de passe rempli → on hash
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']); // éviter d'écraser avec null
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.homeUser')->with('status', 'profile-updated');
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


    // review
    public function store(Request $request, Residence $residence)
    {
        $request->validate([
            'note' => 'required|integer|min:1|max:5',
            'commentaire' => 'required|string|max:1000',
            'booking_id' => 'required|exists:bookings,id',
        ]);

        $booking = auth()->user()->bookings()
            ->where('id', $request->booking_id)
            ->where('residence_id', $residence->id)
            ->first();

        if (!$booking) {
            return back()->with('error', 'Réservation invalide.');
        }

        // Vérifier que le séjour est terminé
        if (now()->lt($booking->date_depart)) {
            return back()->with('error', 'Vous pouvez laisser un avis uniquement après la fin du séjour.');
        }

        // Empêcher plusieurs avis pour la même réservation
        if ($booking->review()->exists()) {
            return back()->with('error', 'Vous avez déjà laissé un avis pour ce séjour.');
        }

        Review::create([
            'user_id' => auth()->id(),
            'residence_id' => $residence->id,
            'booking_id' => $booking->id,
            'note' => $request->note,
            'commentaire' => $request->commentaire,
            'statut' => 'pending',
        ]);

        return back()->with('success', 'Votre avis a été soumis et sera examiné.');
    }


    // Validation par admin
    public function approve(Review $review)
    {
        $review->update(['statut' => 'approved']);
        return back()->with('success', 'Avis approuvé.');
    }

    public function decline(Review $review)
    {
        $review->update(['statut' => 'declined']);
        return back()->with('success', 'Avis refusé.');
    }
}
