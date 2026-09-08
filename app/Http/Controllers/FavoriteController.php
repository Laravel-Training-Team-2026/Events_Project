<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $favorites = $user
            ->favoriteEvents()
            ->with(['category', 'user'])
            ->latest()
            ->get();

        $favoriteEventIds = $favorites
            ->pluck('id')
            ->toArray();

        return view('pages.favorites', compact(
            'favorites',
            'favoriteEventIds'
        ));
    }

    public function store(Event $event)
    {
        /** @var User $user */
        $user = Auth::user();

        $user->favoriteEvents()
            ->syncWithoutDetaching([$event->id]);

        return response()->json([
            'success' => true,
            'isFavorite' => true,
            'destroy_url' => route('favorites.destroy', $event),
        ]);
    }

    public function destroy(Event $event)
    {
        /** @var User $user */
        $user = Auth::user();

        $user->favoriteEvents()
            ->detach($event->id);

        return response()->json([
            'success' => true,
            'isFavorite' => false,
            'store_url' => route('favorites.store', $event),
        ]);
    }
}