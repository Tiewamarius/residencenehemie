<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    // Définir les attributs qui peuvent être remplis en masse
    protected $fillable = [
        'nom',
        'icone',
    ];

    // --- Relations ---

    /**
     * Un équipement peut être présent dans plusieurs résidences (relation Many-to-Many).
     * La table pivot est 'residence_equipment'.
     */
    public function residences()
    {
        return $this->belongsToMany(Residence::class, 'residence_equipment');
    }
}