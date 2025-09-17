<div class="chat_container" id="chat-container">
    <i class="fas fa-times chat_close" id="chat-close-btn"></i>
    <div class="chat_header">
        <h2>Assistant Virtuel</h2>
    </div>

    <div class="chat_messages" id="chat-messages"
        x-data="{}"
        x-init="
             $nextTick(() => { $el.scrollTop = $el.scrollHeight; });
         "
        x-on:livewire:updated="$nextTick(() => { $el.scrollTop = $el.scrollHeight; })">
        {{-- Boucle Livewire pour afficher les messages --}}
        @foreach ($messages as $message)
        <div class="message @if(Str::startsWith($message['sender_id'], 'user-') || Str::startsWith($message['sender_id'], 'guest-')) user-message @else bot-message @endif">
            {{ $message['content'] }}
        </div>
        @endforeach
    </div>

    {{-- Formulaire géré par Livewire --}}
    <form class="chat_input_area" wire:submit.prevent="sendMessage">
        <button type="button" class="End_close"><i class="fas fa-bars"></i></button>
        <input
            type="text"
            id="chat_input"
            placeholder="Tapez votre message ici..."
            wire:model="newMessage" />
        <button id="send_chat_btn" class="button" type="submit">Envoyer</button>
    </form>
</div>