<article class="event-card">

    {{-- Event Details Link --}}
    <a href="{{ route('event.details', $event) }}" class="event-card__link" aria-label="{{ $event->title }}">
    </a>


    {{-- ==========================
     IMAGE
=========================== --}}
    <div class="event-card__media">

        @if ($event->image)
            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="event-card__img">
        @else
            <img src="{{ asset('image/category1.webp') }}" alt="{{ $event->title }}" class="event-card__img">
        @endif


        {{-- CATEGORY --}}
        @if ($event->category)
            <span class="event-card__category">
                {{ $event->category->name }}
            </span>
        @endif


        {{-- ==========================
            FAVORITE
            =========================== --}}
        @auth

            @php
                $isFavorite = in_array($event->id, $favoriteEventIds ?? []);
            @endphp


            <form action="{{ $isFavorite ? route('favorites.destroy', $event) : route('favorites.store', $event) }}"
                method="POST" class="event-card__favorite-form" data-favorite-form>

                @csrf


                @if ($isFavorite)
                    @method('DELETE')
                @endif


                <button type="submit"
                    class="event-card__favorite
                    {{ $isFavorite ? 'event-card__favorite--active' : '' }}"
                    aria-label="{{ $isFavorite ? 'Remove from favorites' : 'Add to favorites' }}"
                    aria-pressed="{{ $isFavorite ? 'true' : 'false' }}" data-favorite-button>

                    <span class="event-card__favorite-icon">
                        {{ $isFavorite ? '★' : '☆' }}
                    </span>

                </button>

            </form>

        @endauth

    </div>


    {{-- ==========================
     BODY
=========================== --}}
    <div class="event-card__body">


        {{-- DATE --}}
        <div class="event-card__date">

            <span class="event-card__date-month">

                {{ strtoupper(\Carbon\Carbon::parse($event->start_date)->format('M')) }}

            </span>


            <span class="event-card__date-day">

                {{ \Carbon\Carbon::parse($event->start_date)->format('d') }}


                @if ($event->end_date != $event->start_date)
                    -
                    {{ \Carbon\Carbon::parse($event->end_date)->format('d') }}
                @endif

            </span>

        </div>


        {{-- ==========================
         EVENT INFO
    =========================== --}}
        <div class="event-card__info">


            {{-- TITLE --}}
            <h3 class="event-card__title">

                {{ $event->title }}

            </h3>


            {{-- ORGANIZER + DESCRIPTION --}}
            <p class="event-card__organizer">

                @if ($event->user)
                    {{ $event->user->name }} -
                @endif

                {{ \Illuminate\Support\Str::limit($event->description, 40) }}

            </p>


            {{-- TIME --}}
            <p class="event-card__time">

                {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}

                -

                {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}

            </p>


            {{-- ==========================
             META
        =========================== --}}
            <div class="event-card__meta">


                {{-- PRICE --}}
                <span class="event-card__price">

                    <i class="fa-solid fa-ticket-simple"></i>

                    {{ number_format($event->price, 2) }}

                </span>


                {{-- INTERESTED --}}
                <span class="event-card__interested">

                    <i class="fa-solid fa-users"></i>

                    {{ $event->interested_users_count ?? 0 }}

                    {{ \Illuminate\Support\Str::plural('interested', $event->interested_users_count ?? 0) }}

                </span>

            </div>

        </div>

    </div>

</article>
