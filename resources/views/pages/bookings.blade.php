@extends('layouts.base')

@section('title', 'My Bookings')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/event-details.css') }}">
    <link rel="stylesheet" href="{{ asset('css/breadcrumb.css') }}">
@endpush

@section('content')

    <section class="event-details-page">

        <div class="container">

            @include('components.breadcrumb', [
                'items' => [
                    [
                        'label' => 'Home',
                        'url' => route('home'),
                    ],
                    [
                        'label' => 'My Bookings',
                    ],
                ],
            ])

            <div class="event-card">

                <div class="event-card__info">

                    <div class="event-card__top">
                        <h1 class="event-card__title">My Bookings</h1>
                    </div>

                    <p class="event-card__description-text">
                        View your confirmed and cancelled event bookings in one place.
                    </p>

                    @php
                        $currentBookings = $bookings
                            ->filter(fn ($booking) => $booking->isConfirmed()
                                && $booking->event
                                && \Carbon\Carbon::parse($booking->event->end_date)->gte(today()))
                            ->values();

                        $cancelledBookings = $bookings
                            ->filter(fn ($booking) => $booking->isCancelled())
                            ->values();
                    @endphp

                    <div class="event-card__tags" role="tablist" aria-label="Filter bookings">
                        <button type="button" class="event-card__tag booking-filter is-active" data-booking-filter="current"
                            role="tab" aria-selected="true">
                            Current Bookings
                        </button>

                        <button type="button" class="event-card__tag event-card__tag--capacity booking-filter"
                            data-booking-filter="cancelled" role="tab" aria-selected="false">
                            Cancelled Bookings
                        </button>
                    </div>

                    <section data-booking-section="current">
                        <h2 class="event-card__description-heading">Current Bookings</h2>

                        @forelse ($currentBookings as $booking)
                            <article class="event-card__info">
                                <h3 class="event-card__description-heading">
                                    <a href="{{ route('event.details', $booking->event) }}">{{ $booking->event->title }}</a>
                                </h3>

                                <div class="event-card__detail-row">
                                    <i class="fa-regular fa-calendar"></i>
                                    <span>Date: {{ \Carbon\Carbon::parse($booking->event->start_date)->format('M d, Y') }}</span>
                                </div>

                                <div class="event-card__detail-row">
                                    <i class="fa-solid fa-ticket"></i>
                                    <span>Price: {{ (float) $booking->total_price === 0.0 ? 'Free' : '$' . number_format($booking->total_price, 2) }}</span>
                                </div>

                                <div class="event-card__tags">
                                    <span class="event-card__tag"><i class="fa-solid fa-circle-check"></i> Confirmed</span>
                                </div>

                                <div class="event-card__detail-row">
                                    <i class="fa-regular fa-clock"></i>
                                    <span>Booking Date: {{ $booking->booked_at?->format('M d, Y g:i A') ?? '—' }}</span>
                                </div>

                                <form action="{{ route('bookings.destroy', $booking) }}" method="POST"
                                    class="event-card__cancel-form"
                                    onsubmit="return confirm('Are you sure you want to cancel your booking?');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="event-card__book-btn">
                                        <i class="fa-solid fa-xmark"></i>
                                        Cancel Booking
                                    </button>
                                </form>

                                @if (! $loop->last)
                                    <hr class="event-card__divider">
                                @endif
                            </article>
                        @empty
                            <p class="event-card__description-text">No current bookings.</p>
                        @endforelse
                    </section>

                    <section data-booking-section="cancelled" style="display: none;">
                        <h2 class="event-card__description-heading">Cancelled Bookings</h2>

                        @forelse ($cancelledBookings as $booking)
                            <article class="event-card__info">
                                <h3 class="event-card__description-heading">
                                    @if ($booking->event)
                                        <a href="{{ route('event.details', $booking->event) }}">{{ $booking->event->title }}</a>
                                    @else
                                        Event unavailable
                                    @endif
                                </h3>

                                @if ($booking->event)
                                    <div class="event-card__detail-row">
                                        <i class="fa-regular fa-calendar"></i>
                                        <span>Date: {{ \Carbon\Carbon::parse($booking->event->start_date)->format('M d, Y') }}</span>
                                    </div>
                                @endif

                                <div class="event-card__detail-row">
                                    <i class="fa-solid fa-ticket"></i>
                                    <span>Price: {{ (float) $booking->total_price === 0.0 ? 'Free' : '$' . number_format($booking->total_price, 2) }}</span>
                                </div>

                                <div class="event-card__tags">
                                    <span class="event-card__tag event-card__tag--capacity"><i class="fa-solid fa-ban"></i> Cancelled</span>
                                </div>

                                <div class="event-card__detail-row">
                                    <i class="fa-regular fa-clock"></i>
                                    <span>Booking Date: {{ $booking->booked_at?->format('M d, Y g:i A') ?? '—' }}</span>
                                </div>

                                <p class="event-card__description-text">
                                    <i class="fa-solid fa-ban" aria-hidden="true"></i>
                                    This booking has been cancelled.
                                </p>

                                @if (! $loop->last)
                                    <hr class="event-card__divider">
                                @endif
                            </article>
                        @empty
                            <p class="event-card__description-text">No cancelled bookings.</p>
                        @endforelse
                    </section>

                </div>

            </div>

        </div>

    </section>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const filterButtons = Array.from(document.querySelectorAll('[data-booking-filter]'));
            const sections = Array.from(document.querySelectorAll('[data-booking-section]'));

            const applyFilter = (filter) => {
                sections.forEach((section) => {
                    section.style.display = section.dataset.bookingSection === filter ? 'block' : 'none';
                });

                filterButtons.forEach((button) => {
                    const isActive = button.dataset.bookingFilter === filter;

                    button.classList.toggle('is-active', isActive);
                    button.classList.toggle('event-card__tag--capacity', !isActive);
                    button.setAttribute('aria-selected', isActive ? 'true' : 'false');
                });
            };

            filterButtons.forEach((button) => {
                button.addEventListener('click', () => applyFilter(button.dataset.bookingFilter));
            });

            applyFilter('current');
        });
    </script>
@endpush
