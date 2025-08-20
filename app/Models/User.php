<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Favorite;

/**
 * @property-read Collection|Favorite[] $favorites
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_picture',
        'phone_number',
        'address',   // NOUVEAU
        'description',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // --- Relations ---

    /**
     * Un utilisateur peut avoir plusieurs réservations.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Un utilisateur peut avoir plusieurs avis.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Un utilisateur peut avoir plusieurs favoris (relation polymorphe).
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }



    /**
     * Un utilisateur peut avoir plusieurs messages.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // --- Accessors ou autres méthodes utiles ---

    // Exemple de méthode pour vérifier le rôle
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isHotelManager()
    {
        return $this->role === 'hotel_manager';
    }
}
