<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'transaction_id',
        'montant',
        'devise',
        'methode_paiement',
        'statut',
        'date_paiement',
    ];

    protected $casts = [
        'date_paiement' => 'datetime',
    ];

    // --- Relations ---

    /**
     * Un paiement appartient à une réservation.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}