<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Residence;
use App\Models\Booking; // Assurez-vous d'avoir un modèle Booking
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class BookingController extends Controller
{
    /**
     * Gère la soumission du formulaire de réservation.
     */
    public function store(Request $request, Residence $residence)
    {
        // 1. Validation des données du formulaire
        $request->validate([
            'date_arrivee' => ['required', 'date', 'after_or_equal:today'],
            'date_depart' => ['required', 'date', 'after:date_arrivee'],
            'num_guests' => ['required', 'integer', 'min:1'],
        ]);

        $checkIn = Carbon::parse($request->input('date_arrivee'));
        $checkOut = Carbon::parse($request->input('date_depart'));
        $numGuests = $request->input('num_guests');

        // 2. Vérification de la disponibilité
        // On vérifie si une autre réservation existe sur la même période
        $isAvailable = Booking::where('residence_id', $residence->id)
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('date_arrivee', [$checkIn, $checkOut->subDay()])
                    ->orWhereBetween('date_depart', [$checkIn->addDay(), $checkOut]);
            })
            ->doesntExist();

        if (!$isAvailable) {
            // Les dates ne sont pas disponibles
            return redirect()->back()->withErrors(['message' => 'Les dates sélectionnées ne sont pas disponibles.']);
        }

        // 3. Création de la réservation si tout est bon
        try {
            $booking = new Booking();
            $booking->residence_id = $residence->id;
            $booking->user_id = Auth::id(); // Récupère l'ID de l'utilisateur connecté
            $booking->date_arrivee = $checkIn;
            $booking->date_depart = $checkOut;
            $booking->num_guests = $numGuests;
            $booking->save();

            // Redirection avec un message de succès
            return redirect()->route('residences.show', $residence->id)
                ->with('success', 'Votre réservation a été effectuée avec succès !');
        } catch (\Exception $e) {
            // Gérer les erreurs de sauvegarde
            return redirect()->back()->withErrors(['message' => 'Une erreur est survenue lors de la réservation.']);
        }
    }
}
