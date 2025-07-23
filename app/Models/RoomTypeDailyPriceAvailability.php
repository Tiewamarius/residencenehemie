<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomTypeDailyPriceAvailability extends Model
{
    use HasFactory;

    protected $table = 'room_type_daily_prices_availability'; // Nom de la table

    protected $fillable = [
        'type_id', // ID du type de chambre/appartement
        'date',
        'price',
        'available_units',
    ];

    protected $casts = [
        'date' => 'date',
        'price' => 'decimal:2', // Assure que le prix est toujours avec 2 décimales
    ];

    // --- Relations ---

    /**
     * Un prix/disponibilité quotidien appartient à un type de chambre/appartement.
     */
    public function roomType()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }
}