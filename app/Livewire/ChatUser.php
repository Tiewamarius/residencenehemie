<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ChatUser extends Component
{
    public $messages = [];
    public $newMessage = '';
    public $adminId;
    public $conversationId;

    public function mount()
    {
        $this->setConversationId();

        $admin = Admin::first();
        if ($admin) {
            $this->adminId = $admin->id;
        } else {
            abort(404, 'Aucun administrateur n\'a été trouvé pour le chat.');
        }

        $this->loadMessages();
    }

    private function setConversationId()
    {
        if (Auth::check()) {
            $this->conversationId = 'user-' . Auth::id();
        } else {
            if (!Session::has('guest_conversation_id')) {
                Session::put('guest_conversation_id', 'guest-' . uniqid());
            }
            $this->conversationId = Session::get('guest_conversation_id');
        }
    }

    public function loadMessages()
    {
        // Change: Convert the collection to an array
        $this->messages = Message::where('conversation_id', $this->conversationId)
            ->oldest('created_at')
            ->get()
            ->toArray();
    }

    public function sendMessage()
    {
        $this->validate([
            'newMessage' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'conversation_id' => $this->conversationId,
            'sender_id' => $this->conversationId,
            'receiver_id' => $this->adminId,
            'content' => $this->newMessage,
        ]);

        // Change: Add the new message as an array to the messages array
        $this->messages = array_merge($this->messages, [$message->toArray()]);

        $this->reset('newMessage');
    }

    public function render()
    {
        return view('livewire.chat-user');
    }
}
