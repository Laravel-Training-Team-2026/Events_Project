@extends('layouts.base')

@section('title', 'Events')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/events.css') }}">
    <link rel="stylesheet" href="{{ asset('css/event-card.css') }}">
    <link rel="stylesheet" href="{{ asset('css/breadcrumb.css') }}">
@endpush

@section('content')

    <section class="events">

        <div class="container">
        {{-- Breadcrumb --}}
        @include('components.breadcrumb', [
            'items' => [
                [
                    'label' => 'Home',
                    'url' => route('home'),
                ],
                [
                    'label' => 'Events',
                ],
            ],
        ])

            <header class="events__header">

                <div class="events__heading">

                    <p class="events__eyebrow">
                        Discover what is happening nearby
                    </p>

                    <h1 class="events__title">
                        Make plans worth looking forward to.
                    </h1>

                    <p class="events__intro">
                        Discover local events, experiences, and activities happening around you.
                    </p>

                </div>

                @auth
                    <a href="{{ url('/create-event') }}" class="events__create-link">
                        <i class="fa-solid fa-plus" aria-hidden="true"></i>
                        <span>Create Event</span>
                    </a>
                @endauth

            </header>



            <div class="events__toolbar">

                {{-- Search --}}
                <div class="events__search">

                    <label class="sr-only" for="event-search">
                        Search events
                    </label>

                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>

                    <input type="search" id="event-search" name="q" value="{{ request('q') }}"
                        placeholder="Search by event, category, city, or organizer" autocomplete="off">

                </div>


                {{-- City --}}
                <label class="events__control" for="event-city">

                    <i class="fa-solid fa-location-dot" aria-hidden="true"></i>

                    <select id="event-city" class="events__select">

                        <option value="">All cities</option>

                        @foreach ($cities as $city)
                            <option value="{{ $city }}">{{ $city }}</option>
                        @endforeach

                    </select>

                    <i class="fa-solid fa-chevron-down events__control-caret" aria-hidden="true">
                    </i>

                </label>


                {{-- Date --}}
                <label class="events__control" for="event-date">

                    <i class="fa-regular fa-calendar" aria-hidden="true"></i>

                    <select id="event-date" class="events__select">

                        <option value="">Any date</option>
                        <option value="today">Today</option>
                        <option value="tomorrow">Tomorrow</option>
                        <option value="week">This week</option>
                        <option value="month">This month</option>

                    </select>

                    <i class="fa-solid fa-chevron-down events__control-caret" aria-hidden="true">
                    </i>

                </label>

            </div>


            {{-- ==========================
         Categories
    =========================== --}}
            <nav class="events__category-nav" aria-label="Filter events by category">

                <span class="events__category-label">
                    Category
                </span>

                <div class="events__category-scroll" data-category-scroll>

                    <button type="button" class="events__category-scroll-button" data-category-scroll-previous
                        aria-label="Show previous categories" hidden>
                        <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                    </button>

                    <div class="events__categories" data-category-list>

                        {{-- All Events --}}
                        <button type="button" class="events__category {{ request('category') ? '' : 'is-active' }}"
                            data-category="" aria-pressed="{{ request('category') ? 'false' : 'true' }}">
                            All events
                        </button>


                        {{-- Categories --}}
                        @foreach ($categories as $category)
                            <button type="button"
                                class="events__category {{ request('category') == $category->id ? 'is-active' : '' }}"
                                data-category="{{ $category->id }}"
                                aria-pressed="{{ request('category') == $category->id ? 'true' : 'false' }}">
                                {{ $category->name }}
                            </button>
                        @endforeach

                    </div>

                    <button type="button" class="events__category-scroll-button" data-category-scroll-next
                        aria-label="Show more categories" hidden>
                        <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                    </button>

                </div>

            </nav>


            {{-- ==========================
         Results Header
    =========================== --}}
            <div class="events__results-header">

                <div>

                    <h2 class="events__section-title">
                        Upcoming events
                    </h2>

                    <p class="events__results-note" id="events-results-note">
                        A considered selection of events near you.
                    </p>

                </div>

                <div class="events__result-actions">

                    <span class="events__count" id="events-count" aria-live="polite">

                        {{ $events->count() }}
                        {{ \Illuminate\Support\Str::plural('event', $events->count()) }}

                    </span>

                    <button type="button" class="events__reset" id="events-reset" hidden>
                        Reset filters
                    </button>

                </div>

            </div>


            {{-- ==========================
         Events Grid
    =========================== --}}
            <div class="row row-10" id="events-grid">

                @forelse ($events as $event)
                    <div class="col-12 col-sm-6 col-lg-4 event-item" data-category="{{ $event->category->id ?? '' }}"
                        data-city="{{ $event->city ?? '' }}"
                        data-date="{{ \Carbon\Carbon::parse($event->start_date)->format('Y-m-d') }}"
                        data-search="{{ strtolower(
                            $event->title .
                                ' ' .
                                ($event->category->name ?? '') .
                                ' ' .
                                ($event->city ?? '') .
                                ' ' .
                                ($event->user->name ?? '') .
                                ' ' .
                                ($event->description ?? ''),
                        ) }}">

                        @include('components.event-card', [
                            'event' => $event,
                            'favoriteEventIds' => $favoriteEventIds,
                        ])

                    </div>

                @empty

                    <div class="col-12">

                        <div class="events__empty">

                            <i class="fa-regular fa-calendar-xmark"></i>

                            <h2>
                                No Events Yet
                            </h2>

                            <p>
                                There are no events available at the moment.
                            </p>

                            @auth

                                <a href="{{ url('/create-event') }}" class="events__create-button">
                                    Create Event
                                </a>

                            @endauth

                        </div>

                    </div>
                @endforelse

            </div>


            {{-- ==========================
         Filtered Empty State
    =========================== --}}
            <div class="events__empty events__empty--filtered" id="events-empty-filtered" hidden>

                <i class="fa-regular fa-calendar-xmark"></i>

                <h2>
                    No events found
                </h2>

                <p>
                    Try changing your search or filters.
                </p>

                <button type="button" class="events__empty-reset" id="events-empty-reset">
                    Reset filters
                </button>

            </div>

        </div>

    </section>

@endsection

@push('scripts')
    @vite('resources/js/events.js')
    <script src="{{ asset('js/favorite.js') }}"></script>
@endpush
