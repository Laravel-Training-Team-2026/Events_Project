<header class="header">

    <div class="container">

        <nav class="header__nav" id="header-nav">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="header__logo">
                <span class="header__logo-text">
                    Eventify
                </span>
            </a>


            {{-- Main Menu --}}
            <ul class="header__menu">

                <li>
                    <a href="{{ url('/') }}"
                        class="header__menu-link {{ request()->is('/') ? 'header__menu-link--active' : '' }}">
                        Home
                    </a>
                </li>

                <li>
                    <a href="{{ url('/events') }}"
                        class="header__menu-link {{ request()->is('events') ? 'header__menu-link--active' : '' }}">
                        Events
                    </a>
                </li>

                <li>
                    <a href="{{ url('/about') }}"
                        class="header__menu-link {{ request()->is('about') ? 'header__menu-link--active' : '' }}">
                        About
                    </a>
                </li>

                <li>
                    <a href="{{ url('/contact') }}"
                        class="header__menu-link {{ request()->is('contact') ? 'header__menu-link--active' : '' }}">
                        Contact
                    </a>
                </li>

                @auth

                    <li>
                        <a href="{{ route('favorites') }}"
                            class="header__menu-link {{ request()->is('favorites') ? 'header__menu-link--active' : '' }}">
                            Favorites
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('bookings.index') }}"
                            class="header__menu-link {{ request()->is('bookings') ? 'header__menu-link--active' : '' }}">
                            My Bookings
                        </a>
                    </li>

                @endauth

            </ul>


            {{-- User Actions --}}
            <div class="header__actions">

                @auth
                    {{-- Create Event --}}
                    <a href="{{ url('/create-event') }}" class="header__action-link">
                        Create Event
                    </a>

                    {{-- Logout --}}
                    <form action="{{ route('logout') }}" method="POST" class="header__logout-form">

                        @csrf

                        <button type="submit" class="header__action-link header__logout-button">
                            Logout
                        </button>

                    </form>
                @else
                    {{-- Login --}}
                    <a href="{{ route('login') }}" class="header__action-link">
                        Login
                    </a>

                @endauth

            </div>


            {{-- Mobile Toggle --}}
            <button type="button" class="header__toggle" id="header-toggle" aria-label="Toggle menu"
                aria-expanded="false">

                <span class="header__toggle-bar"></span>
                <span class="header__toggle-bar"></span>
                <span class="header__toggle-bar"></span>

            </button>

        </nav>

    </div>

</header>

