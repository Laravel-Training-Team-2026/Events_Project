@extends('layouts.base')

@section('title', 'Eventify - Discover Local Events')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/event-card.css') }}">
@endpush

@section('content')

    {{-- Hero --}}
    @include('components.hero')


    {{-- Categories --}}
    @include('pages.categories')


    {{-- Popular Events --}}
    <section class="events">

        <div class="container">

            {{-- Section Header --}}
            <div class="section-header">

                <h2 class="section__title">
                    Popular Events
                </h2>

            </div>


            {{-- Events --}}
            @if ($events->count() > 0)

                <div class="row row-10" id="home-events-grid">

                    @foreach ($events as $event)
                        <div class="col-12 col-sm-6 col-lg-4 home-event-item"
                            data-search="{{ strtolower(
                                $event->title .
                                    ' ' .
                                    ($event->category->name ?? '') .
                                    ' ' .
                                    ($event->city ?? '') .
                                    ' ' .
                                    ($event->user->name ?? ''),
                            ) }}"
                            data-city="{{ strtolower($event->city ?? '') }}"
                            data-category="{{ strtolower($event->category->name ?? '') }}">

                            @include('components.event-card', [
                                'event' => $event,
                                'favoriteEventIds' => $favoriteEventIds ?? [],
                            ])

                        </div>
                    @endforeach

                </div>


                {{-- No Search Results --}}
                <div class="home-events__empty" id="home-search-empty" hidden>

                    <i class="fa-regular fa-calendar-xmark"></i>

                    <h3>
                        No Events Found
                    </h3>

                    <p>
                        Try searching for another event, category, or city.
                    </p>

                </div>


                {{-- See More --}}
                <div class="home-events__more">

                    <a href="{{ route('events.index') }}" class="home-events__more-link">
                        See More
                    </a>

                </div>
            @else
                <div class="home-events__empty">

                    <i class="fa-regular fa-calendar-xmark"></i>

                    <h3>
                        No Events Available
                    </h3>

                    <p>
                        There are no events available right now.
                    </p>

                </div>

            @endif

        </div>

    </section>


@endsection

@push('scripts')
    <script src="{{ asset('js/home.js') }}"></script>
    <script src="{{ asset('js/favorite.js') }}"></script>
@endpush
