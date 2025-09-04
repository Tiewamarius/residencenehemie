<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $type; // new | update | cancel

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, string $type)
    {
        $this->booking = $booking;
        $this->type = $type;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = match ($this->type) {
            'new'    => '📩 Nouvelle réservation',
            'update' => '🔄 Réservation modifiée',
            'cancel' => '❌ Réservation annulée',
            default  => 'ℹ️ Notification réservation',
        };

        return $this->subject($subject)
            ->view("emails.notification")
            ->with([
                'booking' => $this->booking,
                'type' => $this->type,
            ]);
    }
}
