<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $bookings = $user
            ->bookings()
            ->with(['event.category'])
            ->orderByDesc('booked_at')
            ->get();

        return view('pages.bookings', compact('bookings'));
    }

    public function store(Event $event)
    {
        $user = Auth::user();

        try {
            $event->bookFor($user);
        } catch (ValidationException $exception) {
            return redirect()
                ->route('event.details', $event)
                ->with('error', $exception->errors()['booking'][0]);
        }

        return redirect()
            ->route('event.details', $event)
            ->with('success', 'Your booking has been confirmed successfully.');
    }

    public function destroy(Booking $booking)
    {
        $user = Auth::user();

        if ((int) $booking->user_id !== (int) $user->id) {
            abort(403);
        }

        try {
            $booking->event->cancelBookingFor($user);
        } catch (ValidationException $exception) {
            return back()->with('error', $exception->errors()['booking'][0]);
        }

        return back()
            ->with('success', 'Your booking has been cancelled successfully.');
    }
}
