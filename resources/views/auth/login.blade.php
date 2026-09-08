<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Eventify</title>

    <link rel="stylesheet" href="{{ asset('css/var.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

</head>

<body>

    <main class="auth">

        <div class="auth__container">

            <div class="auth__header">

                <h1 class="auth__title">
                    Welcome Back
                </h1>

                <p class="auth__subtitle">
                    Login to your Eventify account.
                </p>

            </div>


            <form class="auth__form" action="{{ route('login.store') }}" method="POST">

                @csrf


                {{-- Email --}}

                <div class="auth__field">

                    <label class="auth__label" for="email">
                        Email
                    </label>

                    <input class="auth__input" type="email" id="email" name="email" value="{{ old('email') }}"
                        placeholder="Enter your email" required>

                    @error('email')
                        <span class="auth__error">
                            {{ $message }}
                        </span>
                    @enderror

                </div>


                {{-- Password --}}

<div class="auth__field">

    <label class="auth__label" for="password">
        Password
    </label>

    <div class="auth__password-wrapper">

        <input
            class="auth__input auth__input--password"
            type="password"
            id="password"
            name="password"
            placeholder="Enter your password"
            required
        >

        <button
            type="button"
            class="auth__password-toggle"
            data-password-toggle
            data-target="password"
            aria-label="Show password"
            aria-pressed="false"
        >
            <svg
                class="auth__password-icon"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
            >
                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            </svg>
        </button>

    </div>

    @error('password')
        <span class="auth__error">
            {{ $message }}
        </span>
    @enderror

</div>


                {{-- Button --}}

                <button class="auth__button" type="submit">
                    Login
                </button>

            </form>


            <p class="auth__footer">

                Don't have an account?

                <a href="{{ route('register') }}" class="auth__link">
                    Register
                </a>

            </p>

        </div>

    </main>
<script src="{{ asset('js/password-toggle.js') }}"></script>
</body>

</html>
