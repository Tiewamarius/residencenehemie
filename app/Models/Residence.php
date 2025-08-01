<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Residence extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'adresse',
        'ville',
        'pays',
        'latitude',
        'longitude',
        'telephone',
        'email',
        'nombre_chambres',
        'note_moyenne',
    ];

    // --- Relations ---

    /**
     * Une résidence a plusieurs types de chambres/appartements.
     */
    public function types()
    {
        return $this->hasMany(Type::class); // Assurez-vous que votre modèle 'types' est nommé 'Type'
    }

    /**
     * Une résidence a plusieurs images.
     */
    public function images()
    {
        return $this->hasMany(ResidenceImage::class); // Assurez-vous que le modèle ResidenceImage existe
    }

    /**
     * Une résidence a plusieurs réservations.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class); // Assurez-vous que le modèle Booking existe
    }

    /**
     * Une résidence a plusieurs avis.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class); // Assurez-vous que le modèle Review existe
    }

    /**
     * Une résidence peut avoir plusieurs équipements (relation Many-to-Many).
     */
    public function equipment()
    {
        return $this->belongsToMany(Equipment::class, 'residence_equipment');
    }

    /**
     * Une résidence peut être ajoutée aux favoris (relation polymorphe).
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}