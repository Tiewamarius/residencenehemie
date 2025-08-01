<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model // Assurez-vous que votre modèle pour 'types' est bien nommé 'Type'
{
    use HasFactory;

    protected $table = 'types'; // Spécifiez explicitement le nom de la table si votre modèle est 'Type'

    protected $fillable = [
        'residence_id', // Clé étrangère vers Residence
        'nom',
        'description',
        'capacite_adultes',
        'capacite_enfants',
        'superficie',
        'prix_base',
        'nombre_lits',
        'type_lit',
    ];

    // --- Relations ---

    /**
     * Un type de chambre/appartement appartient à une résidence.
     */
    public function residence()
    {
        return $this->belongsTo(Residence::class);
    }

    /**
     * Un type de chambre/appartement a plusieurs réservations.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Un type de chambre/appartement a des prix et disponibilités quotidiens.
     */
    public function dailyPricesAvailability()
    {
        return $this->hasMany(RoomTypeDailyPriceAvailability::class);
    }

    /**
     * Un type de chambre/appartement peut être ajouté aux favoris (relation polymorphe).
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    // Optionnel: Si vous avez des images spécifiques pour les types de chambres
    public function images()
    {
        return $this->hasMany(RoomTypeImage::class); // Si vous avez une telle table et un modèle
    }
}