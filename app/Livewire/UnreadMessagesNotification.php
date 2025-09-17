<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class UnreadMessagesNotification extends Component
{
    // Propriété publique pour le compteur de la vue
    public $unreadMessagesCount = 0;

    // Écouteur qui se rafraîchit à chaque fois qu'un message est envoyé
    protected $listeners = ['messageSent' => 'getUnreadMessagesCount'];

    /**
     * S'exécute lors de l'initialisation du composant.
     */
    public function mount()
    {
        $this->getUnreadMessagesCount();
    }

    /**
     * Compte les messages non lus pour l'administrateur.
     */
    public function getUnreadMessagesCount()
    {
        // Utilise le guard d'authentification de l'administrateur
        if (Auth::guard('admin')->check()) {
            $adminId = Auth::guard('admin')->user()->id;
            $this->unreadMessagesCount = Message::where('receiver_id', $adminId)
                ->whereNull('read_at')
                ->count();
        } else {
            $this->unreadMessagesCount = 0;
        }
    }

    /**
     * Rend la vue du composant.
     */
    public function render()
    {
        // Le wire:poll dans la vue se charge de rafraîchir la méthode
        return view('livewire.unread-messages-notification');
    }
}
