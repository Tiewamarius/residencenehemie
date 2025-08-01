<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResidenceEquipment extends Model

{
    use HasFactory;

    protected $fillable = [
        'nom',
        'icone', // Si vous avez une colonne pour une icône
    ];

    // --- Relations ---

    /**
     * Un équipement peut être présent dans plusieurs résidences (Many-to-Many).
     */
    public function residences()
    {
        return $this->belongsToMany(Residence::class, 'residence_equipment');
    }
}