<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidenceImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'residence_id',
        'chemin_image',
        'description',
        'est_principale',
        'order', 
    ];

    // --- Relations ---

    /**
     * Une image appartient à une résidence.
     */
    public function residence()
    {
        return $this->belongsTo(Residence::class);
    }
}