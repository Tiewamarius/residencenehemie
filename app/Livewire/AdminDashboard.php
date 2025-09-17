<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminDashboard extends Component
{
    // Propriétés du composant
    public $conversations;
    public $selectedConversation;
    public $messages = [];
    public $newMessage = '';
    public $unreadMessagesCount = 0;

    // Écouteur pour les événements de diffusion
    protected $listeners = ['echo:chat-channel,MessageSent' => 'loadConversations'];

    /**
     * S'exécute lors de l'initialisation du composant.
     */
    public function mount()
    {
        $this->loadConversations();
        $this->getUnreadMessagesCount();
    }

    /**
     * Charge toutes les conversations uniques.
     */
    public function loadConversations()
    {
        $adminId = Auth::guard('admin')->user()->id;

        // Récupérer les expéditeurs uniques qui ont envoyé des messages à l'administrateur
        $this->conversations = Message::select('sender_id')
            ->selectRaw('MAX(created_at) as last_message_at')
            ->where('receiver_id', $adminId)
            ->groupBy('sender_id')
            ->orderBy('last_message_at', 'desc')
            ->with('senderUser')
            ->get();
    }

    /**
     * Sélectionne une conversation et charge ses messages.
     * @param Message $conversation Le premier message de la conversation.
     */
    public function selectConversation(Message $conversation)
    {
        $this->selectedConversation = $conversation;
        $this->loadMessages();
        $this->getUnreadMessagesCount();
    }

    /**
     * Charge les messages de la conversation sélectionnée.
     */
    public function loadMessages()
    {
        if ($this->selectedConversation) {
            $adminId = Auth::guard('admin')->user()->id;
            $senderId = $this->selectedConversation->sender_id;

            // Récupérer les messages entre l'administrateur et l'expéditeur sélectionné
            $this->messages = Message::where(function ($query) use ($adminId, $senderId) {
                $query->where('sender_id', $adminId)->where('receiver_id', $senderId);
            })->orWhere(function ($query) use ($adminId, $senderId) {
                $query->where('sender_id', $senderId)->where('receiver_id', $adminId);
            })->orderBy('created_at', 'asc')->get();

            // Marquer les messages comme lus
            Message::where('sender_id', $senderId)
                ->where('receiver_id', $adminId)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }
    }

    /**
     * Envoie un nouveau message.
     */
    public function sendMessage()
    {
        $this->validate(['newMessage' => 'required|string|max:1000']);

        $adminId = Auth::guard('admin')->user()->id;

        Message::create([
            'sender_id' => $adminId,
            'receiver_id' => $this->selectedConversation->sender_id,
            'content' => $this->newMessage,
        ]);

        $this->newMessage = '';
        $this->loadMessages();

        // Dispatche un événement pour les autres composants
        $this->dispatch('messageSent');
    }

    /**
     * Compte les messages non lus pour l'administrateur.
     */
    public function getUnreadMessagesCount()
    {
        if (Auth::guard('admin')->check()) {
            $adminId = Auth::guard('admin')->user()->id;
            $this->unreadMessagesCount = Message::where('receiver_id', $adminId)
                ->whereNull('read_at')
                ->count();
        }
    }

    /**
     * Rend la vue du composant.
     */
    public function render()
    {
        return view('livewire.admin-dashboard');
    }
}
