<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Residence;
use App\Models\Type;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Gère le processus de réservation pour une résidence spécifique.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Residence  $residence
     * @return \Illuminate\Http\Response
     */
    public function reserver(Request $request, Residence $residence)
    {
        // 1. Valider les données du formulaire
        $validatedData = $request->validate([
            'residence_id' => 'required|exists:residences,id',
            'type_id' => 'required|exists:types,id',
            'date_arrivee' => 'required|date|after_or_equal:today',
            'date_depart' => 'required|date|after:date_arrivee',
            'nombre_adultes' => 'required|integer|min:1',
            'nombre_enfants' => 'nullable|integer|min:0',
            'note_client' => 'nullable|string|max:500',
        ]);

        try {
            // 2. Récupérer le type de résidence pour calculer le prix
            $type = Type::findOrFail($validatedData['type_id']);

            // 3. Calculer les informations de la réservation
            $checkInDate = Carbon::parse($validatedData['date_arrivee']);
            $checkOutDate = Carbon::parse($validatedData['date_depart']);
            $numberOfNights = $checkInDate->diffInDays($checkOutDate);
            $serviceFee = 10000;
            $totalPrice = ($type->prix_base * $numberOfNights) + $serviceFee;
            $reservationNumber = 'RES-' . Str::upper(Str::random(8)) . '-' . time();
            $detailsClient = [
                'adultes' => $validatedData['nombre_adultes'],
                'enfants' => $validatedData['nombre_enfants'] ?? 0,
            ];

            // 4. Créer et sauvegarder la nouvelle réservation
            Booking::create([
                'user_id' => Auth::id(),
                'residence_id' => $validatedData['residence_id'],
                'type_id' => $validatedData['type_id'],
                'date_arrivee' => $validatedData['date_arrivee'],
                'date_depart' => $validatedData['date_depart'],
                'nombre_adultes' => $validatedData['nombre_adultes'],
                'nombre_enfants' => $validatedData['nombre_enfants'] ?? 0,
                'statut' => 'pending',
                'total_price' => $totalPrice,
                'numero_reservation' => $reservationNumber,
                'details_client' => $detailsClient,
                'note_client' => $validatedData['note_client'] ?? null,
            ]);

            // 5. Rediriger avec un message de succès
            return redirect()->route('paiements.show', ['booking' => $residence->id]);
        } catch (\Exception $e) {
            // Gérer les erreurs de réservation
            // Cela peut inclure des erreurs de validation ou d'autres exceptions
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la réservation. Veuillez réessayer.')->withInput();
        }
    }
}
