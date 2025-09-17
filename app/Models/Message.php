<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'sender_id', // ID de l'expéditeur
        'receiver_id', // ID du destinataire
        'subject',
        'content',
        'read_at', // Timestamp quand le message a été lu
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    // --- Relations ---

    /**
     * Un message est envoyé par un utilisateur.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Un message est destiné à un utilisateur.
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
