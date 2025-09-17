<?php

namespace App\Http\View\Composers;

use App\Models\Message;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class NavbarComposer
{
    public function compose(View $view)
    {
        if (Auth::check()) {
            $unreadMessagesCount = Message::where('receiver_id', Auth::user()->id)
                ->whereNull('read_at')
                ->count();
            $view->with('unreadMessagesCount', $unreadMessagesCount);
        } else {
            $view->with('unreadMessagesCount', 0);
        }
    }
}
