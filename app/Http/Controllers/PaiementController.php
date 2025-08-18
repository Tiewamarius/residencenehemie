<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaiementController extends Controller
{
    /**
     * Affiche la page de paiement pour une réservation spécifique.
     *
     * @param  \App\Models\Booking  $residence
     * @return \Illuminate\View\View
     */
    public function showPaymentPage(Booking $residence)
    {
        // Charge les relations nécessaires pour la vue
        $residence->load('residence.images', 'residence.reviews', 'residence.types');

        return view('Pages.paiement', compact('residence'));
    }

    /**
     * Traite le paiement et met à jour les statuts de la réservation et du paiement.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function process(Request $request)
    {
        $validatedData = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'payment_method' => 'required|string',
            'total_price' => 'required|numeric',
        ]);

        // Simuler une transaction réussie pour l'exemple
        // Dans une application réelle, vous feriez appel à une passerelle de paiement (Stripe, CinetPay, etc.)
        $transactionId = 'TRANS-' . time();
        $paymentStatus = 'completed';

        // Utiliser une transaction de base de données pour garantir que les deux opérations réussissent
        DB::transaction(function () use ($validatedData, $transactionId, $paymentStatus) {
            // Créer le paiement
            Payment::create([
                'booking_id' => $validatedData['booking_id'],
                'transaction_id' => $transactionId,
                'montant' => $validatedData['total_price'],
                'methode_paiement' => $validatedData['payment_method'],
                'statut' => $paymentStatus,
                'date_paiement' => now(),
            ]);

            // Mettre à jour le statut de la réservation
            $residence = Booking::findOrFail($validatedData['booking_id']);
            $residence->statut = 'paid';
            $residence->save();
        });

        // Rediriger vers une page de succès
        return redirect()->route('paiements.success')->with('success', 'Paiement et réservation confirmés !');
    }
}
