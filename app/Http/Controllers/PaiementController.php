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
use App\Mail\ConfirmationEmail;
use App\Mail\ReservationNotification;
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

        // Vérifier chevauchement de réservation en "Attente"
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
        //         // Si user connecté → exclure ses propres Attente
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

        // Vérification s'il existe une réservation Attente d'un AUTRE utilisateur qui chevauche les dates
        $hasUnpaidBooking = $booking->residence
            ->bookings()
            ->where('statut', 'Attente')
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
        if ($hasUnpaidBooking) {
            session()->flash('error', 'Il existe une autre réservation en attente pour ces dates.');
        }


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
            'id_card'        => 'nullable|string|max:255',
            // ✅ Validation pour le champ de type fichier
            'card_picture'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // 🔒 Step 1: Lock the booking to prevent race conditions.
            $booking = Booking::lockForUpdate()->findOrFail($data['booking_id']);

            // 🎯 Step 2: Gérer la photo de la carte d'identité avant de créer l'utilisateur
            $cardPicturePath = null;
            if ($request->hasFile('card_picture')) {
                $cardPicturePath = $request->file('card_picture')->store('card_pictures', 'public');
            }

            // 🎯 Step 3: Find or create the user.
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'         => $data['name'],
                    'phone_number' => $data['phone_number'],
                    'id_card'      => $data['id_card'] ?? null,
                    'card_picture' => $cardPicturePath, // ✅ Utilise le chemin de l'image sauvegardée
                    'password'     => Hash::make(Str::random(10)),
                ]
            );

            // If the user was just created, send the welcome email.
            if ($user->wasRecentlyCreated) {
                $plainPassword = Str::random(10);
                $user->password = Hash::make($plainPassword);
                $user->save();
                Mail::to($user->email)->send(new GuestAccountCreated($user, $plainPassword));
            }

            // 🚀 Step 4: Automatically authenticate the user.
            Auth::login($user);

            // 🛡️ Step 5: Security check (This is already well-implemented).
            if (!is_null($booking->user_id) && $booking->user_id !== $user->id) {
                DB::rollBack();
                return back()->with('error', "Cette réservation est déjà associée à un autre utilisateur.");
            }

            // ⚙️ Step 6: Associate the booking with the user and set the status.
            $booking->user_id = $user->id;
            $booking->statut  = ($data['payment_method'] === 'espece') ? 'Attente' : 'Confirmé';
            $booking->save();

            // 💰 Step 7: Create the payment record.
            Payment::create([
                'booking_id'       => $booking->id,
                'transaction_id'   => 'TRANS-' . now()->timestamp,
                'montant'          => $data['total_price'],
                'methode_paiement' => $data['payment_method'],
                'statut'           => ($data['payment_method'] === 'espece') ? 'unpaid' : 'paid',
                'date_paiement'    => now(),
            ]);

            try {
                // Notification au client
                // Mail::to($booking->user->email)
                //     ->send(new ConfirmationEmail($booking, 'new'));

                // Notification au manager
                Mail::to(config('mail.manager_addresses'))
                    ->send(new ReservationNotification($booking, 'new'));
            } catch (\Exception $mailException) {
                Log::error("Erreur envoi email réservation: " . $mailException->getMessage());
            }
            DB::commit();

            $msg = ($data['payment_method'] === 'espece') ?
                'Votre réservation est en attente. Un agent vous contactera.' :
                'Paiement confirmé et réservation validée !';

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
            $bookingStatus = 'Confirmé';
            $paymentStatus = 'paid';

            if ($validatedData['payment_method'] === 'espece') {
                $bookingStatus = 'Attente';
                $paymentStatus = 'unpaid';
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
