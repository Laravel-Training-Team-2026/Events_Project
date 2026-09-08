<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use App\Models\Category;
use App\Models\City;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{


    public function index(Request $request)
    {
        $events = Event::with(['category', 'user'])
            ->withCount('interestedUsers')
            ->whereDate('end_date', '>=', today());

        if ($request->filled('category')) {
            $events->where('category_id', $request->integer('category'));
        }

        // Search
        if ($request->filled('q')) {

            $search = $request->q;

            $events->where(function ($query) use ($search) {

                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $events = $events
            ->orderBy('start_date')
            ->get();

        $categories = Category::all();
        $cities = City::query()->orderBy('name')->pluck('name');

        $favoriteEventIds = [];

        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            $favoriteEventIds = $user
                ->favoriteEvents()
                ->pluck('events.id')
                ->toArray();
        }

        return view('pages.events', compact(
            'events',
            'categories',
            'favoriteEventIds',
            'cities'
        ));
    }

    public function show(Event $event)
    {
        $event->load(['category', 'user']);

        $interestedCount = $event
            ->interestedUsers()
            ->count();

        $isInterested = false;

        $isFavorite = false;


        $isBooked = false;
        $booking = null;


        if (Auth::check()) {

            /** @var User $user */
            $user = Auth::user();


            $isInterested = $event
                ->interestedUsers()
                ->where('user_id', $user->id)
                ->exists();


            $isFavorite = $event
                ->favoritedBy()
                ->where('user_id', $user->id)
                ->exists();


            $isBooked = $event->isBookedBy($user);
            
            $booking = $event->bookings()
                ->where('user_id', $user->id)
                ->where('status', Booking::STATUS_CONFIRMED)
                ->first();
        }


        $availableSpots = $event->availableSpots();


        return view('pages.event-details', compact(
            'event',
            'interestedCount',
            'isInterested',
            'isFavorite',
            'isBooked',
            'booking',
            'availableSpots'
        ));
    }


    public function edit(Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Category::all();
        $cities = City::query()->orderBy('name')->pluck('name');

        return view('pages.edit-event', compact('event', 'categories', 'cities'));
    }

public function update(Request $request, Event $event)
{
    /*
    |--------------------------------------------------------------------------
    | Only the event owner can update the event
    |--------------------------------------------------------------------------
    */

    if ($event->user_id !== Auth::id()) {
        abort(403);
    }


    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([

        'title' => 'required|string|max:255',

        'description' => 'required|string',

        'category_id' => 'required|exists:categories,id',

        'start_date' => 'required|date',

        'end_date' => 'required|date|after_or_equal:start_date',

        'start_time' => 'required',

        'end_time' => 'required',

        'location' => 'required|string|max:255',

        'city' => 'required|string|max:100|exists:cities,name',

        'price' => 'required|numeric|min:0',

        'capacity' => 'required|integer|min:1',

        'image' => 'nullable|image|max:2048',

        'remove_image' => 'nullable|boolean',

    ]);


    /*
    |--------------------------------------------------------------------------
    | Existing Image
    |--------------------------------------------------------------------------
    */

    $oldImage = $event->image;


    /*
    |--------------------------------------------------------------------------
    | Remove Image
    |--------------------------------------------------------------------------
    */

    $removeImage =
        $request->boolean('remove_image');


    /*
    |--------------------------------------------------------------------------
    | New Image Upload
    |--------------------------------------------------------------------------
    */

    if ($request->hasFile('image')) {

        /*
        | Store the new image first
        */

        $newImage = $request
            ->file('image')
            ->store('events', 'public');


        $validated['image'] = $newImage;


        /*
        | Delete old image after new image is stored
        */

        if ($oldImage) {
            Storage::disk('public')
                ->delete($oldImage);
        }

    }

    /*
    |--------------------------------------------------------------------------
    | Delete Current Image
    |--------------------------------------------------------------------------
    */

    elseif ($removeImage) {

        if ($oldImage) {

            Storage::disk('public')
                ->delete($oldImage);
        }


        $validated['image'] = null;
    }


    /*
    |--------------------------------------------------------------------------
    | Update Event
    |--------------------------------------------------------------------------
    */

    $event->update($validated);


    /*
    |--------------------------------------------------------------------------
    | Redirect
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('event.details', $event)
        ->with(
            'success',
            'Event updated successfully.'
        );
}


    public function create()
    {
        $categories = Category::all();
        $cities = City::query()->orderBy('name')->pluck('name');

        return view('pages.create-event', compact('categories', 'cities'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',

            'category_id' => 'required|exists:categories,id',

            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',

            'start_time' => 'required',
            'end_time' => 'required',

            'location' => 'required|string|max:255',
            'city' => 'required|string|max:100|exists:cities,name',

            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',

            'image' => 'required|image|max:2048',
        ]);

        /*
    |--------------------------------------------------------------------------
    | Upload Event Image
    |--------------------------------------------------------------------------
    */

        if ($request->hasFile('image')) {

            $validated['image'] = $request
                ->file('image')
                ->store('events', 'public');
        }

        /*
    |--------------------------------------------------------------------------
    | Event Owner
    |--------------------------------------------------------------------------
    */

        $validated['user_id'] = Auth::id();

        /*
    |--------------------------------------------------------------------------
    | Create Event
    |--------------------------------------------------------------------------
    */

        Event::create($validated);

        return redirect()
            ->route('events.index')
            ->with('success', 'Event created successfully.');
    }

    public function destroy(Event $event)
    {
        if ((int) $event->user_id !== (int) Auth::id()) {
            abort(403);
        }

        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()
            ->route('events.index')
            ->with('success', 'Event deleted successfully.');
    }
}
