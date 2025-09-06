<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Residence;
use App\Models\Review;
use App\Models\User;
use App\Models\Type;
// use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail; // Pour l'envoi d'emails
use App\Mail\ReservationNotification;
use Illuminate\Support\Facades\Notification; // Pour l'envoi de notifications (SMS)
use Illuminate\Support\Facades\Log; // Pour l'enregistrement des erreurs

// Assurez-vous d'importer les classes Mailable et Notification nécessaires
use App\Mail\ConfirmationEmail;
use App\Mail\ManagerBookingEmail;

// use App\Mail\ManagerBookingEmail;
// use App\Notifications\ManagerSmsNotification;

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
            'residence_id'   => 'required|exists:residences,id',
            'type_id'        => 'required|exists:types,id',
            'date_arrivee'   => 'required|date|after_or_equal:today',
            'date_depart'    => 'required|date|after:date_arrivee',
            'nombre_adultes' => 'required|integer|min:1',
            'nombre_enfants' => 'nullable|integer|min:0',
            'note_client'    => 'nullable|string|max:500',
        ]);

        try {
            // 2. Récupérer le type de résidence pour calculer le prix
            $type = Type::findOrFail($validatedData['type_id']);

            // 3. Calculer les informations de la réservation
            $checkInDate     = Carbon::parse($validatedData['date_arrivee']);
            $checkOutDate    = Carbon::parse($validatedData['date_depart']);
            $numberOfNights  = $checkInDate->diffInDays($checkOutDate);
            // $serviceFee      = 10000;
            $serviceFee      = 0;
            $totalPrice      = ($type->prix_base * $numberOfNights) + $serviceFee;
            $reservationNumber = 'RES-' . Str::upper(Str::random(8)) . '-' . time();
            $detailsClient   = [
                'adultes' => $validatedData['nombre_adultes'],
                'enfants' => $validatedData['nombre_enfants'] ?? 0,
            ];

            // 4. Créer et sauvegarder la nouvelle réservation
            $booking = Booking::create([
                'user_id'           => Auth::id(),
                'residence_id'      => $validatedData['residence_id'],
                'type_id'           => $validatedData['type_id'],
                'date_arrivee'      => $validatedData['date_arrivee'],
                'date_depart'       => $validatedData['date_depart'],
                'nombre_adultes'    => $validatedData['nombre_adultes'],
                'nombre_enfants'    => $validatedData['nombre_enfants'] ?? 0,
                'statut'            => 'pending',
                'total_price'       => $totalPrice,
                'numero_reservation' => $reservationNumber,
                'details_client'    => json_encode($detailsClient),
                'note_client'       => $validatedData['note_client'] ?? null,
            ]);

            // 5. Envoi des notifications
            try {
                // Notification au client
                Mail::to($booking->user->email)
                    ->send(new ConfirmationEmail($booking, 'new'));

                // Notification au manager
                Mail::to(config('mail.manager_address', 'support@odedis.com'))
                    ->send(new ReservationNotification($booking, 'new'));
            } catch (\Exception $mailException) {
                Log::error("Erreur envoi email réservation: " . $mailException->getMessage());
            }

            // 6. Rediriger vers la page de paiement
            return redirect()->route('paiements.show', ['booking' => $booking->id])
                ->with('message', '✅ Réservation effectuée avec succès ! Un email de confirmation vous a été envoyé.');
        } catch (\Exception $e) {
            // Gérer les erreurs de réservation
            Log::error('Erreur de réservation: ' . $e->getMessage());
            return redirect()->back()->with('error', '❌ Une erreur est survenue lors de la réservation. Veuillez réessayer.')->withInput();
        }
    }


    // methode userGuest
    public function guestReserver(Request $request, Residence $residence)
    {
        // dd($request->all());
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
            // 2. Récupérer le type de résidence
            $type = Type::findOrFail($validatedData['type_id']);

            // 3. Calculer prix et infos
            $checkInDate = Carbon::parse($validatedData['date_arrivee']);
            $checkOutDate = Carbon::parse($validatedData['date_depart']);
            $numberOfNights = $checkInDate->diffInDays($checkOutDate);
            // $serviceFee = 10000;
            $serviceFee = 0;
            $totalPrice = ($type->prix_base * $numberOfNights) + $serviceFee;
            $reservationNumber = 'RES-' . Str::upper(Str::random(8)) . '-' . time();
            $detailsClient = [
                'adultes' => $validatedData['nombre_adultes'],
                'enfants' => $validatedData['nombre_enfants'] ?? 0,
            ];

            // 4. Créer réservation sans user_id
            $booking = Booking::create([
                'user_id' => null, // ⚡ pas encore lié
                'residence_id' => $validatedData['residence_id'],
                'type_id' => $validatedData['type_id'],
                'date_arrivee' => $validatedData['date_arrivee'],
                'date_depart' => $validatedData['date_depart'],
                'nombre_adultes' => $validatedData['nombre_adultes'],
                'nombre_enfants' => $validatedData['nombre_enfants'] ?? 0,
                'statut' => 'pending',
                'total_price' => $totalPrice,
                'numero_reservation' => $reservationNumber,
                'details_client' => json_encode($detailsClient),
                'note_client' => $validatedData['note_client'] ?? null,
            ]);

            // 5. Envoi des notifications
            try {
                // Notification au client
                Mail::to($booking->user->email)
                    ->send(new ConfirmationEmail($booking, 'new'));

                // Notification au manager
                Mail::to(config('mail.manager_address', 'support@odedis.com'))
                    ->send(new ReservationNotification($booking, 'new'));
            } catch (\Exception $mailException) {
                Log::error("Erreur envoi email réservation: " . $mailException->getMessage());
            }

            // 5. Rediriger vers la page de paiement
            return redirect()->route('paiements.showGuest', ['booking' => $booking->id])
                ->with('message', 'Veuillez renseigner vos informations pour finaliser la réservation.');
        } catch (\Exception $e) {
            Log::error('Erreur de réservation (guest): ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la réservation.')->withInput();
        }
    }


    // addreview
    public function storeReview(Request $request, $bookingId)
    {
        $booking = Booking::with('residence')->findOrFail($bookingId);

        // Vérifier que l'user est bien le propriétaire de la réservation
        // if ($booking->user_id !== Auth()->id()) {
        //     return back()->with('error', "Non autorisé.");
        // }

        // Vérifier que le séjour est terminé
        if (now()->lt($booking->date_fin)) {
            return back()->with('error', "Vous ne pouvez noter qu'après votre séjour.");
        }

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            // 'user_id' => auth()->id(),
            'residence_id' => $booking->residence_id,
            'booking_id' => $booking->id,
            'rating' => $data['rating'],
            'comment' => $data['comment'],
        ]);

        return redirect()->route('residences.detailsAppart', $booking->residence_id)
            ->with('success', "Merci pour votre avis !");
    }


    // details booking

    public function details($id)
    {
        $reservation = Booking::with(['residence.images', 'type', 'Payment'])->findOrFail($id);
        return view('Pages.details', compact('reservation'));
    }

    public function cancel($id)
    {
        $reservation = Booking::findOrFail($id);

        // Vérifier que l'user est propriétaire (ou admin)
        if ($reservation->user_id !== auth()->id() && !auth()->user()->is_admin) {
            return redirect()
                ->route('bookings.details', $reservation->id)
                ->withErrors("Vous n'êtes pas autorisé à annuler cette réservation.");
        }

        // Empêcher annulation si déjà annulée ou terminée
        if (in_array($reservation->statut, ['Annulée', 'Terminée'])) {
            return redirect()
                ->route('bookings.details', $reservation->id)
                ->withErrors("Cette réservation ne peut plus être annulée.");
        }

        $now = now();
        $checkinDate = \Carbon\Carbon::parse($reservation->date_debut);

        $refund = 0;
        $newStatus = 'Annulée';

        // Politique d’annulation
        $daysBefore = $now->diffInDays($checkinDate, false);

        if ($daysBefore > 7) {
            $refund = $reservation->total_price;
            $newStatus = 'Annulée - Remboursée';
        } elseif ($daysBefore > 2) {
            $refund = $reservation->total_price * 0.5;
            $newStatus = 'Annulée - Remboursée partiellement';
        } else {
            $refund = 0;
            $newStatus = 'Annulée - Non remboursée';
        }


        // 🔔 Notifications email
        $this->notifyBookingCancelled($reservation, $refund);
        // Sauvegarder le statut
        $reservation->statut = $newStatus;
        $reservation->save();

        // TODO: Intégrer un vrai service de remboursement ici
        // if ($refund > 0 && $reservation->paiement) {
        //     RefundService::refund($reservation->paiement, $refund);
        // }

        return redirect()
            ->route('bookings.details', $reservation->id)
            ->with('status', "Votre réservation a été annulée. Montant remboursé : {$refund} CFA.");
    }


    // verification avant updtae
    public function checkAvailability(Request $request, $bookingId)
    {
        $booking = Booking::with('residence.types')->findOrFail($bookingId);

        $dateArrivee = \Carbon\Carbon::parse($request->query('date_arrivee'));
        $dateDepart = \Carbon\Carbon::parse($request->query('date_depart'));

        // Vérif basique
        if ($dateArrivee->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'La date d’arrivée doit être postérieure à aujourd’hui.'
            ]);
        }

        if ($dateDepart->lte($dateArrivee)) {
            return response()->json([
                'success' => false,
                'message' => 'La date de départ doit être après la date d’arrivée.'
            ]);
        }

        // Vérifier disponibilité (si d’autres réservations occupent déjà l’appart)
        $alreadyBooked = Booking::where('residence_id', $booking->residence_id)
            ->where('id', '!=', $booking->id) // exclure la résa en cours
            ->where(function ($query) use ($dateArrivee, $dateDepart) {
                $query->whereBetween('date_arrivee', [$dateArrivee, $dateDepart])
                    ->orWhereBetween('date_depart', [$dateArrivee, $dateDepart])
                    ->orWhere(function ($q) use ($dateArrivee, $dateDepart) {
                        $q->where('date_arrivee', '<=', $dateArrivee)
                            ->where('date_depart', '>=', $dateDepart);
                    });
            })
            ->exists();

        if ($alreadyBooked) {
            return response()->json([
                'success' => false,
                'message' => 'Ces dates sont déjà occupées.'
            ]);
        }

        // Calcul du nombre de nuits
        $nights = $dateArrivee->diffInDays($dateDepart);

        // Prix de base de l’appartement
        $prixBase = $booking->residence->types->first()->prix_base ?? 0;

        // Calcul total
        $total = $nights * $prixBase;

        return response()->json([
            'success' => true,
            'message' => 'Les dates sont disponibles.',
            'nights'  => $nights,
            'total'   => $total
        ]);
    }


    // Update reservation
    public function userUpdate(Request $request, $id)
    {
        $reservation = Booking::findOrFail($id);

        if ($reservation->user_id !== auth()->id()) {
            abort(403);
        }


        // Règles de validation
        $validated = $request->validate([
            'date_arrivee' => 'required|date|after_or_equal:today',
            'date_depart' => 'required|date|after:date_arrivee',
            'nombre_adultes' => 'required|integer|min:1',
            'nombre_enfants' => 'nullable|integer|min:0',
            // 'payment_method' => 'required|string',
        ]);

        // Conversion en objets Carbon
        $dateArrivee = \Carbon\Carbon::parse($validated['date_arrivee']);
        $dateDepart  = \Carbon\Carbon::parse($validated['date_depart']);

        // Calcul du nombre de nuits
        $nuits = $dateArrivee->diffInDays($dateDepart);

        // ⚠️ Si ton prix vient du type de logement :
        $prixParNuit = $reservation->type->prix_base ?? $reservation->residence->prix_base;

        // Nouveau total
        $validated['total_price'] = $nuits * $prixParNuit;

        // Si tout est ok, tu pourras remettre la suite
        $reservation->update($validated);

        return redirect()->back()->with('status', 'La réservation a été modifiée avec succès.');
    }



    // Recommander (refaire une réservation sur la même résidence)
    public function reorder($id)
    {
        $reservation = Booking::findOrFail($id);

        // Vérifier statut
        if (!in_array($reservation->statut, ['Annulée', 'Terminée'])) {
            return redirect()->route('bookings.show', $reservation->id)
                ->withErrors("Vous ne pouvez pas recommander tant que la réservation n'est pas annulée ou terminée.");
        }

        // Créer une nouvelle réservation avec les mêmes infos
        $newBooking = Booking::create([
            'user_id'      => auth()->id(),
            'residence_id' => $reservation->residence_id,
            'date_arrivee' => now()->addDays(7), // par défaut dans 1 semaine (à ajuster avec un formulaire)
            'date_depart'  => now()->addDays(10),
            'nombre_adultes' => $reservation->nombre_adultes,
            'nombre_enfants' => $reservation->nombre_enfants,
            'total_price'  => $reservation->total_price,
            'frais_service' => $reservation->frais_service,
            'statut'       => 'En attente',
        ]);

        return redirect()->route('bookings.show', $newBooking->id)
            ->with('status', "Nouvelle réservation créée pour la résidence !");
    }

    // Modifier une réservation (redirige vers un formulaire d’édition)
    public function userEdit($id)
    {
        $reservation = Booking::findOrFail($id);

        // Vérifier que l'utilisateur est bien propriétaire
        if ($reservation->user_id !== auth()->id()) {
            abort(403);
        }
        // Empêcher modification si séjour déjà commencé ou trop proche
        if (now()->greaterThanOrEqualTo($reservation->date_arrivee->subDays(2))) {
            return redirect()->route('bookings.show', $reservation->id)
                ->withErrors("Le délai de modification est dépassé.");
        }

        return view('bookings.edit', compact('reservation'));
    }



    /**
     * Effectuer le check-in
     */
    public function checkin($id)
    {
        $reservation = Booking::findOrFail($id);

        if ($reservation->statut === 'paid') {
            $reservation->statut = 'check-in';
            $reservation->save();

            return redirect()
                ->route('bookings.details', $reservation->id)
                ->with('status', 'Check-in effectué avec succès.');
        }

        return redirect()
            ->route('bookings.details', $reservation->id)
            ->withErrors('Impossible de faire le check-in pour cette réservation.');
    }
    public function invoice($id)
    {
        $reservation = Booking::with(['user', 'residence', 'type'])->findOrFail($id);

        // Pour PDF :
        // $pdf = \PDF::loadView('bookings.invoice', compact('reservation'));
        // return $pdf->download("facture_{$reservation->numero_reservation}.pdf");

        return view('Pages.invoice', compact('reservation'));
    }

    /* ==========================================================
   🔔 GESTION DES NOTIFICATIONS EMAIL
========================================================== */


    private function notifyBookingCreated(Booking $booking)
    {
        try {
            // Email au client
            Mail::to($booking->user->email)->send(new ReservationNotification($booking, 'new'));

            // Email au manager
            Mail::to(config('mail.manager_address', 'info@odedis.com'))
                ->send(new ReservationNotification($booking, 'new'));
        } catch (\Exception $e) {
            Log::error("Erreur envoi mail réservation créée: " . $e->getMessage());
        }
    }

    private function notifyBookingUpdated(Booking $booking)
    {
        try {
            // Email au client
            Mail::to($booking->user->email)->send(new ReservationNotification($booking, 'update'));

            // Email au manager
            Mail::to(config('mail.manager_address', 'info@odedis.com'))
                ->send(new ReservationNotification($booking, 'update'));
        } catch (\Exception $e) {
            Log::error("Erreur envoi mail mise à jour: " . $e->getMessage());
        }
    }

    private function notifyBookingCancelled(Booking $booking, $refund)
    {
        try {
            // Email au client
            Mail::to($booking->user->email)->send(new ReservationNotification($booking, 'cancel'));

            // Email au manager
            Mail::to(config('mail.manager_address', 'info@odedis.com'))
                ->send(new ReservationNotification($booking, 'cancel'));
        } catch (\Exception $e) {
            Log::error("Erreur envoi mail annulation: " . $e->getMessage());
        }
    }
}
