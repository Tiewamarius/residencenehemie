<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'residence_id',
        'booking_id',
        'note',
        'commentaire',
        'statut',
    ];

    // --- Relations ---

    /**
     * Un avis appartient à un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Un avis concerne une résidence.
     */
    public function residence()
    {
        return $this->belongsTo(Residence::class);
    }

    /**
     * Un avis peut être lié à une réservation spécifique.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}