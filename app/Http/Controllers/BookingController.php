<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Residence;
use App\Models\Type; // Importez le modèle Type
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str; // Importez Str pour la génération de numéro unique
use Carbon\Carbon; // Importez Carbon pour les calculs de dates

/**
 * Gère les opérations de création de réservation.
 * Ce contrôleur inclut des méthodes pour les utilisateurs connectés et les invités.
 */
class BookingController extends Controller
{
    /**
     * Enregistre une nouvelle réservation pour un utilisateur authentifié.
     *
     * @param \Illuminate\Http\Request $request La requête HTTP contenant les données de réservation.
     * @param \App\Models\Residence $residence La résidence pour laquelle la réservation est effectuée.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reserver(Request $request, Residence $residence)
    {
        // 1. Valider les données de la requête pour les utilisateurs connectés.
        $validatedData = $request->validate([
            'type_id' => 'required|exists:types,id',
            'date_arrivee' => 'required|date|after_or_equal:today',
            'date_depart' => 'required|date|after:date_arrivee',
            'nombre_adultes' => 'required|integer|min:1',
            'nombre_enfants' => 'nullable|integer|min:0',
            'note_client' => 'nullable|string|max:500',
        ]);

        try {
            // 2. Calculer le prix total et générer le numéro de réservation.
            $type = Type::findOrFail($validatedData['type_id']);
            $checkInDate = Carbon::parse($validatedData['date_arrivee']);
            $checkOutDate = Carbon::parse($validatedData['date_depart']);
            $numberOfNights = $checkInDate->diffInDays($checkOutDate);

            // Prix par nuit du type de résidence + frais de service fixes
            $serviceFee = 10000;
            $totalPrice = ($type->prix_base * $numberOfNights) + $serviceFee;
            $reservationNumber = 'RES-' . Str::upper(Str::random(8)) . '-' . time();

            // 3. Créer une nouvelle instance de réservation avec les données validées.
            Booking::create([
                'user_id' => Auth::id(),
                'residence_id' => $residence->id,
                'type_id' => $validatedData['type_id'],
                'date_arrivee' => $validatedData['date_arrivee'],
                'date_depart' => $validatedData['date_depart'],
                'nombre_adultes' => $validatedData['nombre_adultes'],
                'nombre_enfants' => $validatedData['nombre_enfants'] ?? 0,
                'statut' => 'pending',
                'total_price' => $totalPrice,
                'numero_reservation' => $reservationNumber,
                'note_client' => $validatedData['note_client'],
                'details_client' => null, // Pas de détails client pour les utilisateurs connectés
            ]);

            return redirect()->back()->with('success', 'Votre réservation a été effectuée avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la réservation. Veuillez réessayer.')->withInput();
        }
    }

    /**
     * Enregistre une nouvelle réservation pour un utilisateur non connecté (invité).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeGuestBooking(Request $request)
    {
        try {
            // 1. Valider les données de l'invité et de la réservation.
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'residence_id' => 'required|exists:residences,id',
                'type_id' => 'required|exists:types,id',
                'date_arrivee' => 'required|date|after_or_equal:today',
                'date_depart' => 'required|date|after:date_arrivee',
                'nombre_adultes' => 'required|integer|min:1',
                'nombre_enfants' => 'nullable|integer|min:0',
                'note_client' => 'nullable|string|max:500',
            ]);

            // 2. Calculer le prix total et générer le numéro de réservation.
            $type = Type::findOrFail($validatedData['type_id']);
            $checkInDate = Carbon::parse($validatedData['date_arrivee']);
            $checkOutDate = Carbon::parse($validatedData['date_depart']);
            $numberOfNights = $checkInDate->diffInDays($checkOutDate);

            $serviceFee = 10000;
            $totalPrice = ($type->prix_base * $numberOfNights) + $serviceFee;
            $reservationNumber = 'RES-' . Str::upper(Str::random(8)) . '-' . time();

            // 3. Créer un tableau de détails client pour le champ JSON.
            $clientDetails = [
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
            ];

            // 4. Créer la réservation pour l'invité.
            Booking::create([
                'user_id' => null,
                'residence_id' => $validatedData['residence_id'],
                'type_id' => $validatedData['type_id'],
                'date_arrivee' => $validatedData['date_arrivee'],
                'date_depart' => $validatedData['date_depart'],
                'nombre_adultes' => $validatedData['nombre_adultes'],
                'nombre_enfants' => $validatedData['nombre_enfants'] ?? 0,
                'statut' => 'pending',
                'total_price' => $totalPrice,
                'numero_reservation' => $reservationNumber,
                'note_client' => $validatedData['note_client'],
                'details_client' => $clientDetails,
            ]);

            return redirect()->back()->with('success', 'Votre réservation a été effectuée avec succès !');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue. Veuillez vérifier vos informations et réessayer.')->withInput();
        }
    }
}
