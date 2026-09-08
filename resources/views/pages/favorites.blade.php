@extends('layouts.base')

@section('title', 'My Favorites')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/favorite.css') }}">
    <link rel="stylesheet" href="{{ asset('css/event-card.css') }}">
    <link rel="stylesheet" href="{{ asset('css/breadcrumb.css') }}">
@endpush

@section('content')

    <section class="favorites">

        <div class="container">
@include('components.breadcrumb', [
    'items' => [
        [
            'label' => 'Home',
            'url' => route('home'),
        ],
        [
            'label' => 'Favorites',
        ],
    ],
])
            {{-- Header --}}
            <div class="favorites__header">

                <p class="favorites__eyebrow">
                    Your Collection
                </p>

                <h1 class="favorites__title">
                    My Favorites
                </h1>

                <p class="favorites__subtitle">
                    Keep track of the events you don't want to miss.
                </p>

            </div>


            {{-- Favorites --}}
            @if ($favorites->isEmpty())

                <div class="favorites__empty">

                    <div class="favorites__empty-icon">
                        <i class="fa-regular fa-heart"></i>
                    </div>

                    <h2 class="favorites__empty-title">
                        No Favorites Yet
                    </h2>

                    <p class="favorites__empty-text">
                        You haven't saved any events yet.
                        Explore events and add the ones you love to your favorites.
                    </p>

                    <a href="{{ url('/events') }}" class="favorites__empty-link">
                        Explore Events
                    </a>

                </div>

            @else

                <div class="favorites__grid">

                    @foreach ($favorites as $event)

                        @include('components.event-card', [
                            'event' => $event,
                            'favoriteEventIds' => $favoriteEventIds,
                        ])

                    @endforeach

                </div>

            @endif

        </div>

    </section>

@endsection

@push('scripts')
    <script src="{{ asset('js/favorite.js') }}"></script>
@endpush