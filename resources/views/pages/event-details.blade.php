@extends('layouts.base')

@section('title', $event->title)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/event-card.css') }}">
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
                        'label' => 'Events',
                        'url' => route('events.index'),
                    ],
                    [
                        'label' => $event->title,
                    ],
                ],
            ])

            <div class="event-card">

                <div class="row">

                    {{-- LEFT: IMAGE --}}
                    <div class="col-lg-6">

                        <div class="event-card__media">

                            @if ($event->image)
                                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}"
                                    class="event-card__image">
                            @else
                                <img src="{{ asset('image/category1.webp') }}" alt="{{ $event->title }}"
                                    class="event-card__image">
                            @endif

                        </div>

                    </div>


                    {{-- RIGHT: INFO --}}
                    <div class="col-lg-6">

                        <div class="event-card__info">

                            {{-- Title + Favorite --}}
                            <div class="event-card__top">

                                <h1 class="event-card__title">
                                    {{ $event->title }}
                                </h1>

                                @auth

                                    @php
                                        $isFavorite = $isFavorite ?? false;
                                    @endphp

                                    <form
                                        action="{{ $isFavorite ? route('favorites.destroy', $event) : route('favorites.store', $event) }}"
                                        method="POST" class="event-card__favorite-form" data-favorite-form>

                                        @csrf

                                        @if ($isFavorite)
                                            @method('DELETE')
                                        @endif

                                        <button type="submit"
                                            class="event-card__favorite {{ $isFavorite ? 'event-card__favorite--active' : '' }}"
                                            aria-label="{{ $isFavorite ? 'Remove from favorites' : 'Add to favorites' }}"
                                            aria-pressed="{{ $isFavorite ? 'true' : 'false' }}" data-favorite-button>

                                            <span class="event-card__favorite-icon">
                                                {{ $isFavorite ? '★' : '☆' }}
                                            </span>

                                        </button>

                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="event-card__favorite" aria-label="Login to favorite">

                                        <i class="fa-regular fa-star"></i>

                                    </a>

                                @endauth

                            </div>


                            {{-- Organizer --}}
                            <div class="event-card__organizer">

                                <i class="fa-solid fa-user"></i>

                                <span>
                                    {{ $event->user->name ?? 'Unknown Organizer' }}
                                </span>

                            </div>


                            {{-- Full schedule + location details --}}
                            <div class="event-card__details">

                                <div class="event-card__detail-row">

                                    <i class="fa-regular fa-calendar"></i>

                                    <span>

                                        {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}

                                        @if ($event->end_date != $event->start_date)
                                            -
                                            {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}
                                        @endif

                                    </span>

                                </div>


                                <div class="event-card__detail-row">

                                    <i class="fa-regular fa-clock"></i>

                                    <span>

                                        {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}

                                        -

                                        {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}

                                    </span>

                                </div>


                                <div class="event-card__detail-row">

                                    <i class="fa-solid fa-location-dot"></i>

                                    <span>

                                        {{ $event->location }}{{ $event->city ? ', ' . $event->city : '' }}

                                    </span>

                                </div>

                            </div>


                            {{-- Category + Capacity + Interested --}}
                            <div class="event-card__tags">

                                @if ($event->category)
                                    <span class="event-card__tag">

                                        <i class="fa-solid fa-layer-group"></i>

                                        {{ $event->category->name }}

                                    </span>
                                @endif


                                <span class="event-card__tag event-card__tag--capacity">

                                    <i class="fa-solid fa-users"></i>

                                    {{ $availableSpots }}

                                    {{ \Illuminate\Support\Str::plural('spot', $availableSpots) }}

                                    available

                                </span>


                                <span class="event-card__tag event-card__tag--capacity">

                                    <i class="fa-regular fa-star"></i>

                                    {{ $interestedCount }} interested

                                </span>

                            </div>


                            {{-- Price --}}
                            <div class="event-card__price">

                                @if ($event->price == 0)
                                    Free
                                @else
                                    ${{ number_format($event->price, 2) }}
                                @endif

                            </div>


                            <hr class="event-card__divider">


                            {{-- Description --}}
                            <h2 class="event-card__description-heading">
                                Event Description
                            </h2>

                            <p class="event-card__description-text">
                                {{ $event->description }}
                            </p>


                            {{-- Actions --}}
                            <div class="event-card__actions">

                                {{-- Interested --}}
                                @auth

                                    @if ($isInterested)
                                        <form action="{{ route('interests.destroy', $event) }}" method="POST">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="event-card__interest-btn active">

                                                <i class="fa-solid fa-check"></i>
                                                Interested

                                            </button>

                                        </form>
                                    @else
                                        <form action="{{ route('interests.store', $event) }}" method="POST">

                                            @csrf

                                            <button type="submit" class="event-card__interest-btn">

                                                <i class="fa-regular fa-star"></i>
                                                Interested

                                            </button>

                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="event-card__interest-btn">

                                        <i class="fa-regular fa-star"></i>
                                        Interested

                                    </a>

                                @endauth


                                {{-- Booking --}}
                                @auth

                                    @if ($isBooked)
                                        <div class="event-card__booking-actions">

                                            <button type="button" class="event-card__book-btn" disabled>

                                                <i class="fa-solid fa-check"></i>
                                                Already Booked

                                            </button>


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

                                        </div>
                                    @elseif ($availableSpots <= 0)
                                        <button type="button" class="event-card__book-btn" disabled>

                                            <i class="fa-solid fa-ban"></i>
                                            Sold Out

                                        </button>
                                    @elseif ($event->user_id === Auth::id())
                                        <button type="button" class="event-card__book-btn" disabled>

                                            <i class="fa-solid fa-user"></i>
                                            Your Event

                                        </button>
                                    @else
                                        <form action="{{ route('bookings.store', $event) }}" method="POST"
                                            class="event-card__booking-form">

                                            @csrf

                                            <button type="submit" class="event-card__book-btn">

                                                <i class="fa-solid fa-ticket"></i>
                                                Book This Event

                                            </button>

                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="event-card__book-btn">

                                        <i class="fa-solid fa-ticket"></i>
                                        Book This Event

                                    </a>

                                @endauth

                            </div>


                            {{-- Owner Actions --}}
                            @auth

                                @if ($event->user_id === Auth::id())
                                    <div class="event-card__owner-actions">

                                        <a href="{{ route('events.edit', $event) }}" class="event-card__edit-btn">

                                            <i class="fa-solid fa-pen"></i>
                                            Edit Event

                                        </a>


                                        <form action="{{ route('events.destroy', $event) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this event?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="event-card__delete-btn">

                                                <i class="fa-solid fa-trash"></i>
                                                Delete Event

                                            </button>

                                        </form>

                                    </div>
                                @endif

                            @endauth

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    @push('scripts')
        <script src="{{ asset('js/favorite.js') }}"></script>
    @endpush

@endsection
