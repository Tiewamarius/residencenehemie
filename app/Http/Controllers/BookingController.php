<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Residence;
use App\Models\Type;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail; // Pour l'envoi d'emails
use Illuminate\Support\Facades\Notification; // Pour l'envoi de notifications (SMS)
use Illuminate\Support\Facades\Log; // Pour l'enregistrement des erreurs

// Assurez-vous d'importer les classes Mailable et Notification nécessaires
use App\Mail\ConfirmationEmail;
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
            $booking = Booking::create([
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
                'details_client' => json_encode($detailsClient),
                'note_client' => $validatedData['note_client'] ?? null,
            ]);

            // 5. Envoi d'un e-mail de confirmation à l'utilisateur
            // Remarque : Assurez-vous que la classe ConfirmationEmail existe.
            try {
                Mail::to(Auth::user()->email)->send(new \App\Mail\ConfirmationEmail($booking));
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi de l\'e-mail de confirmation au client: ' . $e->getMessage());
            }

            // 6. Définir les informations du manager (idéalement via un fichier de configuration ou des variables d'environnement)
            $managerEmail = 'info@odedis.com'; // Remplacez par le vrai email du manager
            $managerPhoneNumber = '+2250707646363'; // Remplacez par le vrai numéro de téléphone du manager

            // 7. Envoi d'un e-mail de notification au manager
            // Remarque : Assurez-vous que la classe ManagerBookingEmail existe.
            try {
                Mail::to($managerEmail)->send(new ConfirmationEmail($booking));
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi de l\'e-mail de notification au manager: ' . $e->getMessage());
            }

            // 8. Envoi d'un SMS de notification au manager
            // Remarque : Assurez-vous d'avoir configuré un service SMS (comme Vonage) et que la classe ManagerSmsNotification existe.
            try {
                // Utilisation de la facade Notification pour envoyer un SMS.
                // Le premier paramètre de la méthode 'route' dépend de votre service SMS (ex: 'vonage', 'twilio').
                Notification::route('vonage', $managerPhoneNumber)->notify(new \App\Notifications\ManagerSmsNotification($booking));
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi du SMS de notification au manager: ' . $e->getMessage());
            }

            // 9. Rediriger vers la page de paiement en utilisant l'ID de la réservation
            return redirect()->route('paiements.show', ['booking' => $booking->id])->with('message', 'Réservation effectuée avec succès !');;
        } catch (\Exception $e) {
            // Gérer les erreurs de réservation
            Log::error('Erreur de réservation: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la réservation. Veuillez réessayer.')->withInput();
        }
    }
    public function details($id)
    {
        $reservation = Booking::with(['residence.images', 'type', 'Payment'])->findOrFail($id);
        return view('Pages.details', compact('reservation'));
    }

    public function cancel($id)
    {
        $reservation = Booking::findOrFail($id);

        if (in_array($reservation->statut, ['En attente', 'Confirmée'])) {
            $reservation->statut = 'Annulée';
            $reservation->save();

            return redirect()
                ->route('Pages.details', $reservation->id)
                ->with('status', 'La réservation a bien été annulée.');
        }

        return redirect()
            ->route('Pages.details', $reservation->id)
            ->withErrors('Cette réservation ne peut pas être annulée.');
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
}
