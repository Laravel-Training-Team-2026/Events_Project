<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class InterestedController extends Controller
{
    public function store(Event $event)
    {
        /** @var User $user */
        $user = Auth::user();

        $user->interestedEvents()->syncWithoutDetaching($event->id);

        return back();
    }

    public function destroy(Event $event)
    {
        /** @var User $user */
        $user = Auth::user();

        $user->interestedEvents()->detach($event->id);

        return back();
    }
}
