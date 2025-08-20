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

            // 3. Déterminer le statut de la réservation et du paiement en fonction de la méthode
            $bookingStatus = 'paid';
            $paymentStatus = 'completed';

            // Si la méthode de paiement est 'espece', la réservation est confirmée, mais le paiement est en attente
            if ($validatedData['payment_method'] === 'espece') {
                $bookingStatus = 'confirmed';
                $paymentStatus = 'pending';
            }

            // 4. Simuler le processus de paiement (remplacé par l'API de paiement dans un environnement réel)
            $transactionId = 'TRANS-' . time();

            // 5. Créer l'enregistrement de paiement
            Payment::create([
                'booking_id' => $booking->id,
                'transaction_id' => $transactionId,
                'montant' => $validatedData['total_price'],
                'methode_paiement' => $validatedData['payment_method'],
                'statut' => $paymentStatus,
                'date_paiement' => now(),
            ]);

            // 6. Mettre à jour le statut de la réservation
            $booking->statut = $bookingStatus;
            $booking->save();

            // 7. Confirmer la transaction
            DB::commit();

            // 8. Rediriger avec un message de succès
            // Le message a été rendu plus générique pour s'adapter à toutes les méthodes de paiement
            $successMessage = $validatedData['payment_method'] === 'espece'
                ? 'Votre réservation a été confirmée ! Un agent vous contactera pour finaliser le paiement.'
                : 'Paiement et réservation confirmés !';

            return redirect()->route('/')->with('success', $successMessage);
        } catch (\Exception $e) {
            // 9. En cas d'erreur, annuler la transaction
            DB::rollBack();
            Log::error('Erreur lors du traitement du paiement: ' . $e->getMessage());

            // 10. Rediriger avec un message d'erreur
            return redirect()->back()->with('error', 'Une erreur est survenue lors du paiement. Veuillez réessayer.')->withInput();
        }
    }
}
