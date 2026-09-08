@extends('layouts.base')

@section('title', 'About Us - Eventify')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/about.css') }}">
@endpush

@section('content')

<section class="about">

    <div class="container">

        <div class="about__header">

            <p class="about__eyebrow">
                About Eventify
            </p>

            <h1 class="about__title">
                Discover. Connect. Experience.
            </h1>

            <p class="about__subtitle">
                Eventify is a platform that makes discovering and
                exploring events simple, easy, and enjoyable.
            </p>

        </div>


        <div class="about__content">

            <div class="about__text">

                <h2 class="about__heading">
                    Who We Are
                </h2>

                <p class="about__paragraph">
                    Eventify is an event discovery platform designed
                    to help people find interesting events happening
                    around them.
                </p>

                <p class="about__paragraph">
                    From entertainment and travel to workshops,
                    social activities, and local experiences,
                    Eventify brings different events together
                    in one convenient place.
                </p>

            </div>


            <div class="about__text">

                <h2 class="about__heading">
                    Our Mission
                </h2>

                <p class="about__paragraph">
                    Our mission is to connect people with experiences
                    they love and make discovering events easier.
                </p>

                <p class="about__paragraph">
                    We also help event organizers share their events
                    and reach people who are interested in what they offer.
                </p>

            </div>

        </div>


        <div class="about__features">

            <div class="about__feature">

                <i class="fa-solid fa-magnifying-glass"></i>

                <h3 class="about__feature-title">
                    Discover Events
                </h3>

                <p class="about__feature-text">
                    Find events based on your interests,
                    location, and preferences.
                </p>

            </div>


            <div class="about__feature">

                <i class="fa-solid fa-heart"></i>

                <h3 class="about__feature-title">
                    Save Favorites
                </h3>

                <p class="about__feature-text">
                    Save the events you love and easily
                    find them again later.
                </p>

            </div>


            <div class="about__feature">

                <i class="fa-solid fa-calendar-plus"></i>

                <h3 class="about__feature-title">
                    Create Events
                </h3>

                <p class="about__feature-text">
                    Create and share your own events
                    with the Eventify community.
                </p>

            </div>

        </div>

    </div>

</section>

@endsection