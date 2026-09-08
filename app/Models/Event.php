<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;
use App\Models\Booking;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Event extends Model
{
    use CrudTrait;

    protected $fillable = [
        'title',
        'description',
        'category_id',
        'image',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'location',
        'city',
        'price',
        'capacity',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')
            ->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function interestedUsers()
    {
        return $this->belongsToMany(User::class, 'event_interests')
            ->withTimestamps();
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function bookedSpots(): int
    {
        return (int) $this->bookings()
            ->where('status', Booking::STATUS_CONFIRMED)
            ->sum('quantity');
    }

    public function availableSpots(): int
    {
        return max(
            0,
            (int) $this->capacity - $this->bookedSpots()
        );
    }

    public function hasAvailableSpot(): bool
    {
        return $this->availableSpots() > 0;
    }

    public function isBookedBy(User $user): bool
    {
        return $this->bookings()
            ->where('user_id', $user->id)
            ->where('status', Booking::STATUS_CONFIRMED)
            ->exists();
    }

    public function bookFor(User $user): Booking
    {
        return DB::transaction(function () use ($user) {

            $event = self::query()
                ->whereKey($this->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ((int) $event->user_id === (int) $user->id) {
                throw ValidationException::withMessages([
                    'booking' => 'You cannot book your own event.',
                ]);
            }

            $booking = Booking::query()
                ->where('user_id', $user->id)
                ->where('event_id', $event->id)
                ->lockForUpdate()
                ->first();

            if ($booking && $booking->isConfirmed()) {
                throw ValidationException::withMessages([
                    'booking' => 'You have already booked this event.',
                ]);
            }

            if ($event->availableSpots() < 1) {
                throw ValidationException::withMessages([
                    'booking' => 'Sorry, this event is fully booked.',
                ]);
            }

            if ($booking) {
                $booking->update([
                    'status' => Booking::STATUS_CONFIRMED,
                    'quantity' => 1,
                    'unit_price' => $event->price,
                    'total_price' => $event->price,
                    'booked_at' => now(),
                ]);

                return $booking->fresh();
            }

            return Booking::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'status' => Booking::STATUS_CONFIRMED,
                'quantity' => 1,
                'unit_price' => $event->price,
                'total_price' => $event->price,
                'booked_at' => now(),
            ]);
        });
    }

    public function cancelBookingFor(User $user): Booking
    {
        return DB::transaction(function () use ($user) {

            $event = self::query()
                ->whereKey($this->id)
                ->lockForUpdate()
                ->firstOrFail();

            $booking = Booking::query()
                ->where('event_id', $event->id)
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->first();

            if (!$booking) {
                throw ValidationException::withMessages([
                    'booking' => 'No booking was found for this event.',
                ]);
            }

            if ($booking->isCancelled()) {
                throw ValidationException::withMessages([
                    'booking' => 'This booking has already been cancelled.',
                ]);
            }

            $booking->update([
                'status' => Booking::STATUS_CANCELLED,
            ]);

            return $booking->fresh();
        });
    }
}
