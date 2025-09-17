<div class="flex h-screen bg-gray-100">
    <div class="w-1/4 bg-white p-4 overflow-y-auto border-r border-gray-200">
        <h2 class="text-xl font-bold mb-4">Conversations ({{ $unreadMessagesCount }})</h2>
        <ul>
            @foreach($conversations as $conversation)
            <li wire:click="selectConversation({{ $conversation->id }})" class="p-3 mb-2 rounded-lg cursor-pointer hover:bg-gray-200 @if($selectedConversation && $selectedConversation->id === $conversation->id) bg-gray-300 @endif">
                <span class="font-semibold">
                    @if($conversation->senderUser)
                    {{ $conversation->senderUser->name }}
                    @else
                    Visiteur #{{ $conversation->sender_id }}
                    @endif
                </span>
                @if(Message::where('sender_id', $conversation->sender_id)->where('receiver_id', auth()->user()->id)->whereNull('read_at')->count() > 0)
                <span class="ml-2 text-sm text-white bg-red-500 rounded-full px-2 py-1">Nouveau</span>
                @endif
            </li>
            @endforeach
        </ul>
    </div>

    <div class="flex-1 flex flex-col">
        @if($selectedConversation)
        <div class="bg-white p-4 border-b border-gray-200">
            <h2 class="text-xl font-bold">
                Chat avec
                @if($selectedConversation->senderUser)
                {{ $selectedConversation->senderUser->name }}
                @else
                Visiteur #{{ $selectedConversation->sender_id }}
                @endif
            </h2>
        </div>

        <div class="flex-1 p-4 overflow-y-auto" x-data="{}" x-init="$el.scrollTop = $el.scrollHeight;">
            @foreach($messages as $message)
            <div class="flex mb-4 @if($message->sender_id === auth()->user()->id) justify-end @else justify-start @endif">
                <div class="p-3 rounded-lg @if($message->sender_id === auth()->user()->id) bg-blue-500 text-white @else bg-gray-300 text-black @endif">
                    {{ $message->content }}
                </div>
            </div>
            @endforeach
        </div>

        <form wire:submit.prevent="sendMessage" class="bg-white p-4 border-t border-gray-200">
            <div class="flex">
                <input wire:model.defer="newMessage" type="text" placeholder="Tapez votre message..." class="flex-1 border rounded-lg p-2 mr-2">
                <button type="submit" class="bg-blue-500 text-white p-2 rounded-lg">Envoyer</button>
            </div>
        </form>
        @else
        <div class="flex-1 flex items-center justify-center">
            <p class="text-gray-500 text-lg">Sélectionnez une conversation pour commencer.</p>
        </div>
        @endif
    </div>
</div>