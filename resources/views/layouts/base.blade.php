<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Eventify - Discover Local Events')</title>

    {{-- Font Awesome --}}
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    {{-- Global CSS --}}
    <link rel="stylesheet" href="{{ asset('css/grids.css') }}">
    <link rel="stylesheet" href="{{ asset('css/var.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">

    {{-- Page-specific CSS --}}
    @stack('styles')
</head>

<body>

    <x-navbar />

    @if (session('success'))
        <div class="container" style="padding-top: 20px;">
            <div role="status" style="padding: 12px 16px; border: 1px solid #b7d8b7; border-radius: var(--radius-md); background: #eef8ee; color: #2f6b2f; font-size: 13px; font-weight: 600;">
                <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="container" style="padding-top: 20px;">
            <div role="alert" style="padding: 12px 16px; border: 1px solid #e0a3a3; border-radius: var(--radius-md); background: #fff3f3; color: #c94b4b; font-size: 13px; font-weight: 600;">
                <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <main class="main">
        @yield('content')
    </main>

    {{-- Page-specific JavaScript --}}
    @stack('scripts')

    <x-footer />

</body>

</html>
