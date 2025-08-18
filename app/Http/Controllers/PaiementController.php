<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaiementController extends Controller
{
    /**
     * Affiche la page de paiement pour une réservation spécifique.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\View\View
     */
    public function showPaymentPage(Booking $booking)
    {
        // Charge les relations nécessaires pour la vue afin d'éviter le problème N+1
        // REMARQUE: Assurez-vous d'avoir une relation 'type' dans votre modèle Booking.
        $booking->load('residence.images', 'residence.reviews', 'type');

        return view('Pages.paiement', compact('booking'));
    }

    /**
     * Traite le paiement et met à jour les statuts de la réservation et du paiement.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function process(Request $request)
    {
        // 1. Valider les données du formulaire
        $validatedData = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'payment_method' => 'required|string',
            'total_price' => 'required|numeric|min:0',
        ]);

        try {
            // Utiliser une transaction de base de données pour garantir l'intégrité
            DB::beginTransaction();

            // 2. Récupérer la réservation
            $booking = Booking::findOrFail($validatedData['booking_id']);

            // 3. Simuler le processus de paiement
            // Dans une application réelle, ce serait ici que vous appelleriez une API de paiement externe
            $transactionId = 'TRANS-' . time();
            $paymentStatus = 'completed'; // Ou 'failed', selon le résultat de l'API

            // 4. Créer l'enregistrement de paiement
            Payment::create([
                'booking_id' => $booking->id,
                'transaction_id' => $transactionId,
                'montant' => $validatedData['total_price'],
                'methode_paiement' => $validatedData['payment_method'],
                'statut' => $paymentStatus,
                'date_paiement' => now(),
            ]);

            // 5. Mettre à jour le statut de la réservation
            $booking->statut = 'paid';
            $booking->save();

            // 6. Confirmer la transaction
            DB::commit();

            // 7. Rediriger avec un message de succès
            return redirect()->route('paiements.success')->with('success', 'Paiement et réservation confirmés !');
        } catch (\Exception $e) {
            // 8. En cas d'erreur, annuler la transaction
            DB::rollBack();
            Log::error('Erreur lors du traitement du paiement: ' . $e->getMessage());

            // 9. Rediriger avec un message d'erreur
            return redirect()->back()->with('error', 'Une erreur est survenue lors du paiement. Veuillez réessayer.')->withInput();
        }
    }
}
