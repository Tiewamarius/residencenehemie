<div wire:poll.5000ms>
    <a href="{{ route('admin.dashboard') }}" class="block p-0 text-sm transition-all ease-nav-brand text-slate-500">
        <i class="cursor-pointer fa fa-bell relative">
            @if ($unreadMessagesCount > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                {{ $unreadMessagesCount }}
            </span>
            @endif
        </i>
    </a>
</div>