<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'favoritable_id',
        'favoritable_type',
    ];

    // --- Relations ---

    /**
     * Un favori appartient à un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Définit la relation polymorphe.
     * Cette méthode permet de récupérer l'élément favori (Residence ou Type).
     */
    public function favoritable()
    {
        return $this->morphTo();
    }
}
