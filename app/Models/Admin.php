<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Admin extends  Authenticatable
{
  
    use HasApiTokens, Notifiable;

    protected $guard='admin';

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
    'address',       
    'description',   // NOUVEAU
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
        return $this->hasMany(Booking::class); // Assurez-vous que le modèle Booking existe
    }

    /**
     * Un utilisateur peut avoir plusieurs avis.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class); // Assurez-vous que le modèle Review existe
    }

    /**
     * Un utilisateur peut avoir plusieurs favoris (relation polymorphe).
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class); // Assurez-vous que le modèle Favorite existe
    }

    /**
     * Un utilisateur peut avoir plusieurs messages.
     */
    public function messages()
    {
        return $this->hasMany(Message::class); // Assurez-vous que le modèle Message existe
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
