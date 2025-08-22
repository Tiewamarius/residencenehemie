<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\VonageMessage; // Importez la classe de message Vonage

class ManagerSmsNotification extends Notification
{
    use Queueable;

    public $booking;

    /**
     * Crée une nouvelle instance de notification.
     *
     * @param  Booking  $booking
     * @return void
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Obtenez les canaux de livraison de la notification.
     * Ici, nous spécifions le canal 'vonage' pour les SMS.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['vonage'];
    }

    /**
     * Obtenez la représentation SMS de la notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\VonageMessage
     */
    public function toVonage($notifiable)
    {
        // Construisez le message SMS.
        // Le contenu du message peut être aussi simple ou complexe que vous le souhaitez.
        $message = "Nouvelle réservation confirmée ! Numéro : {$this->booking->numero_reservation}. "
            . "Arrivée : {$this->booking->date_arrivee}, Départ : {$this->booking->date_depart}. "
            . "Client : {$this->booking->user->name}.";

        return (new VonageMessage)->content($message);
    }
}
