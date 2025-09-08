<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail; // Pour l'envoi d'emails
use App\Mail\GuestAccountCreated;
// use Illuminate\Support\Facades\Notification; // Pour l'envoi de notifications (SMS)
use Illuminate\Support\Facades\Hash;
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

        // Vérifier chevauchement de réservation en "pending"
        $hasUnpaidBooking = $booking->residence
            ->bookings()
            ->where('statut', 'Attente')
            ->whereDoesntHave('payment', function ($query) {
                $query->where('statut', 'Paid');
            })
            ->where(function ($query) use ($booking) {
                $query->whereBetween('date_arrivee', [$booking->date_arrivee, $booking->date_depart])
                    ->orWhereBetween('date_depart', [$booking->date_arrivee, $booking->date_depart])
                    ->orWhere(function ($sub) use ($booking) {
                        $sub->where('date_arrivee', '<=', $booking->date_arrivee)
                            ->where('date_depart', '>=', $booking->date_depart);
                    });
            })
            ->exists();
        // $hasUnpaidBooking = $booking->residence
        //     ->bookings()
        //     ->where('statut', 'Attente')
        //     ->when(!is_null($booking->user_id), function ($query) use ($booking) {
        //         // Si user connecté → exclure ses propres pending
        //         $query->where('user_id', '!=', $booking->user_id);
        //     }, function ($query) {
        //         // Si user non connecté → exclure les bookings sans user_id
        //         $query->whereNotNull('user_id');
        //     })
        //     ->where(function ($query) use ($booking) {
        //         $query->whereBetween('date_arrivee', [$booking->date_arrivee, $booking->date_depart])
        //             ->orWhereBetween('date_depart', [$booking->date_arrivee, $booking->date_depart])
        //             ->orWhere(function ($sub) use ($booking) {
        //                 $sub->where('date_arrivee', '<=', $booking->date_arrivee)
        //                     ->where('date_depart', '>=', $booking->date_depart);
        //             });
        //     })
        //     ->exists();

        return view('Pages.paiement', compact('booking', 'hasUnpaidBooking'));
    }




    // Guest paiement

    public function showGuestPaymentPage(Booking $booking)
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


        return view('Pages.paiementGuest', compact('booking', 'hasUnpaidBooking'));
    }


    // guest
    public function finaliser(Request $request)
    {
        $data = $request->validate([
            'booking_id'     => 'required|exists:bookings,id',
            'payment_method' => 'required|string',
            'total_price'    => 'required|numeric|min:0',
            'email'          => 'required|string|email|max:255',
            'name'           => 'required|string|max:255',
            'phone_number'   => 'required|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            // 🔒 1) Verrouiller la réservation
            $booking = Booking::lockForUpdate()->findOrFail($data['booking_id']);

            // 2) User existant ou création avec mdp aléatoire
            $user = User::where('email', $data['email'])->first();
            $plainPassword = null;

            if (!$user) {
                $plainPassword = Str::random(10);
                $user = User::create([
                    'name'         => $data['name'],
                    'email'        => $data['email'],
                    'phone_number' => $data['phone_number'],
                    'password'     => Hash::make($plainPassword),
                ]);


                Mail::to($user->email)->send(new GuestAccountCreated($user, $plainPassword));
            }
            // 🚀 3) Authentifier automatiquement l'user
            Auth::login($user);
            // 3) Sécurité : empêcher l’appropriation
            if (!is_null($booking->user_id) && $booking->user_id !== $user->id) {
                DB::rollBack();
                return back()->with('error', "Cette réservation est déjà associée à un autre utilisateur.");
            }

            // 4) Associer la réservation au user et définir statut
            $booking->user_id = $user->id;
            $booking->statut  = $data['payment_method'] === 'espece' ? 'pending' : 'confirmed';
            $booking->save();

            // 5) Éviter les doublons
            if (Payment::where('booking_id', $booking->id)->exists()) {
                DB::commit();
                return redirect()->route('bookings.details', $booking)
                    ->with('success', 'Paiement déjà initié pour cette réservation.');
            }

            // 6) Créer le paiement
            Payment::create([
                'booking_id'       => $booking->id,
                'transaction_id'   => 'TRANS-' . now()->timestamp,
                'montant'          => $data['total_price'],
                'methode_paiement' => $data['payment_method'],
                'statut'           => $data['payment_method'] === 'espece' ? 'unpaid' : 'paid',
                'date_paiement'    => now(),
            ]);

            DB::commit();

            $msg = $data['payment_method'] === 'espece'
                ? 'Votre réservation est en attente. Un agent vous contactera.'
                : 'Paiement confirmé et réservation validée !';

            return redirect()->route('bookings.details', $booking)->with('success', $msg);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Erreur finaliser (guest): ' . $e->getMessage());
            return back()->withInput()->with('error', 'Une erreur est survenue. Réessayez.');
        }
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
