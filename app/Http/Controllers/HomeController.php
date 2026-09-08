<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $cities = City::query()->orderBy('name')->pluck('name');

        /*
        |--------------------------------------------------------------------------
        | Home Events
        |--------------------------------------------------------------------------
        */

        $today = now()->toDateString();

        $events = Event::with(['category', 'user'])
            ->withCount('interestedUsers')
            ->orderByRaw(
                "CASE WHEN start_date >= ? THEN 0 ELSE 1 END",
                [$today]
            )
            ->orderBy('start_date', 'asc')
            ->take(6)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Favorite Event IDs
        |--------------------------------------------------------------------------
        */

        $favoriteEventIds = [];

        if (Auth::check()) {
            $favoriteEventIds = DB::table('favorites')
                ->where('user_id', Auth::id())
                ->pluck('event_id')
                ->toArray();
        }


        /*
        |--------------------------------------------------------------------------
        | Home View
        |--------------------------------------------------------------------------
        */

        return view('pages.home', compact(
            'events',
            'categories',
            'favoriteEventIds',
            'cities'
        ));
    }
}
