{{-- Hero --}}

<section class="hero">

<img src="{{ asset('image/img1.webp') }}" alt="" class="hero__bg">

<div class="hero__overlay"></div>

<div class="container">

    <div class="hero__inner">

        <p class="hero__eyebrow">
            Don't miss out!
        </p>

        <h1 class="hero__title">
            Explore the
            <span class="hero__title-highlight">vibrant events</span>
            happening locally and globally.
        </h1>

        {{-- Home Search --}}
        <form class="hero__search" id="home-search-form">

            {{-- Search --}}
            <div class="hero__search-field">

                <svg class="hero__search-icon"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    aria-hidden="true">

                    <circle cx="11" cy="11" r="7"
                        stroke="currentColor"
                        stroke-width="2" />

                    <line x1="16.5" y1="16.5"
                        x2="21" y2="21"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round" />

                </svg>

                <input
                    type="search"
                    id="home-event-search"
                    class="hero__search-input"
                    placeholder="Search Events, Categories, Location..."
                    aria-label="Search events"
                    autocomplete="off"
                >

            </div>

            <span class="hero__search-divider" aria-hidden="true"></span>

            {{-- City --}}
            <div class="hero__search-location">

                <svg class="hero__search-location-icon"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    aria-hidden="true">

                    <path
                        d="M12 21s-7-6.2-7-11a7 7 0 1 1 14 0c0 4.8-7 11-7 11z"
                        stroke="currentColor"
                        stroke-width="1.8" />

                    <circle
                        cx="12"
                        cy="10"
                        r="2.3"
                        stroke="currentColor"
                        stroke-width="1.8" />

                </svg>

                <select
                    id="home-event-city"
                    class="hero__search-select"
                    aria-label="Select city">

                    <option value="">All cities</option>
                    @foreach ($cities as $city)
                        <option value="{{ $city }}">{{ $city }}</option>
                    @endforeach

                </select>

            </div>

        </form>

    </div>

</div>

</section>
