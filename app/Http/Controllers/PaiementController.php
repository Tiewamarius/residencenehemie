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
        $booking->load('residence.images', 'residence.reviews', 'type');

        // Vérification s'il existe une réservation pending d'un AUTRE utilisateur qui chevauche les dates
        $hasUnpaidBooking = $booking->residence
            ->bookings()
            ->where('statut', 'pending')
            ->where('user_id', '!=', $booking->user_id) // 🔥 exclure le même utilisateur
            ->where(function ($query) use ($booking) {
                $query->whereBetween('date_arrivee', [$booking->date_arrivee, $booking->date_depart])
                    ->orWhereBetween('date_depart', [$booking->date_arrivee, $booking->date_depart])
                    ->orWhere(function ($sub) use ($booking) {
                        $sub->where('date_arrivee', '<=', $booking->date_arrivee)
                            ->where('date_depart', '>=', $booking->date_depart);
                    });
            })
            ->exists();


        return view('Pages.paiement', compact('booking', 'hasUnpaidBooking'));
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

            // 3. Empêcher les doublons de paiement. On vérifie s'il existe déjà un paiement
            // pour cette réservation, quelle que soit la méthode ou le statut.
            $existingPayment = Payment::where('booking_id', $booking->id)->first();

            // Si un paiement existe déjà pour cet ID de réservation, on annule la transaction
            // et on redirige avec le message d'erreur souhaité.
            if ($existingPayment) {
                $infoMessage = "Proceder au paiement";
                return redirect()->route('bookings.details', ['booking' => $booking])->with('success', $infoMessage);
            }

            // 4. Déterminer le statut de la réservation et du paiement en fonction de la méthode
            $bookingStatus = 'confirmed';
            $paymentStatus = 'paid';

            if ($validatedData['payment_method'] === 'espece') {
                $bookingStatus = 'pending';
                $paymentStatus = 'Unpaid';
            }

            // 5. Simuler le processus de paiement (à remplacer par une API de paiement réelle)
            $transactionId = 'TRANS-' . time();

            // 6. Créer l'enregistrement de paiement
            Payment::create([
                'booking_id' => $booking->id,
                'transaction_id' => $transactionId,
                'montant' => $validatedData['total_price'],
                'methode_paiement' => $validatedData['payment_method'],
                'statut' => $paymentStatus,
                'date_paiement' => now(),
            ]);

            // 7. Mettre à jour le statut de la réservation
            $booking->statut = $bookingStatus;
            $booking->save();

            // 8. Confirmer la transaction
            DB::commit();

            // 9. Rediriger avec un message de succès
            $successMessage = $validatedData['payment_method'] === 'espece'
                ? 'Votre réservation a été confirmée ! Un agent vous contactera pour finaliser le paiement.'
                : 'Paiement et réservation confirmés !';

            return redirect()->route('bookings.details', ['booking' => $booking])->with('success', $successMessage);
        } catch (\Exception $e) {
            // 10. En cas d'erreur, annuler la transaction
            DB::rollBack();
            Log::error('Erreur lors du traitement du paiement: ' . $e->getMessage());

            // 11. Rediriger avec un message d'erreur
            return redirect()->back()->with('error', 'Une erreur est survenue lors du paiement. Veuillez réessayer.')->withInput();
        }
    }
}
