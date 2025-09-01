<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'residence_id',
        'type_id',
        'date_arrivee',
        'date_depart',
        'nombre_adultes',
        'nombre_enfants',
        'total_price',      // <-- Assurez-vous que c'est bien 'total_price' ici aussi
        'statut',
        'numero_reservation',
        'details_client',
        'note_client',
    ];

    protected $casts = [
        'date_arrivee' => 'date',
        'date_depart' => 'date',
        'details_client' => 'array', // Pour que Laravel caste automatiquement le JSON en array
    ];

    // --- Relations ---

    /**
     * Une réservation appartient à un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Une réservation concerne une résidence.
     */
    public function residence()
    {
        return $this->belongsTo(Residence::class);
    }

    /**
     * Une réservation concerne un type de chambre/appartement.
     */
    public function roomType()
    {
        return $this->belongsTo(Type::class, 'type_id'); // Spécifiez 'type_id' si votre FK est différente de 'room_type_id'
    }

    /**
     * Une réservation peut avoir un paiement associé.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }
    /**
     * Une réservation peut avoir un avis associé.
     */
    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
