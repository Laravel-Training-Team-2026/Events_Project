@extends('layouts.base')

@section('title', 'Contact Us - Eventify')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
    <link rel="stylesheet" href="{{ asset('css/breadcrumb.css') }}">
@endpush

@section('content')

<section class="contact">

    <div class="container">
@include('components.breadcrumb', [
    'items' => [
        [
            'label' => 'Home',
            'url' => route('home'),
        ],
        [
            'label' => 'Contact',
        ],
    ],
])
        <div class="contact__header">

            <p class="contact__eyebrow">
                Contact Eventify
            </p>

            <h1 class="contact__title">
                We'd Love to Hear From You
            </h1>

            <p class="contact__subtitle">
                Have a question, suggestion, or need help?
                Get in touch with the Eventify team.
            </p>

        </div>

        @if (session('success'))
            <div class="contact__success">
                {{ session('success') }}
            </div>
        @endif
        <div class="contact__content">

            <div class="contact__info">

                <h2 class="contact__heading">
                    Get in Touch
                </h2>

                <p class="contact__text">
                    Whether you have a question about an event,
                    need help with your account, or simply want
                    to share your feedback, we're here to help.
                </p>


                <div class="contact__details">

                    <div class="contact__detail">

                        <div class="contact__icon">
                            <i class="fa-solid fa-envelope"></i>
                        </div>

                        <div>

                            <h3 class="contact__detail-title">
                                Email
                            </h3>

                            <p class="contact__detail-text">
                                support@eventify.com
                            </p>

                        </div>

                    </div>


                    <div class="contact__detail">

                        <div class="contact__icon">
                            <i class="fa-solid fa-phone"></i>
                        </div>

                        <div>

                            <h3 class="contact__detail-title">
                                Phone
                            </h3>

                            <p class="contact__detail-text">
                                +970 599 000 000
                            </p>

                        </div>

                    </div>


                    <div class="contact__detail">

                        <div class="contact__icon">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>

                        <div>

                            <h3 class="contact__detail-title">
                                Location
                            </h3>

                            <p class="contact__detail-text">
                                Palestine
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <div class="contact__form-wrapper">

                <h2 class="contact__heading">
                    Send Us a Message
                </h2>

                <form
    class="contact__form"
    action="{{ route('contact.store') }}"
    method="POST"
>
    @csrf

                    <div class="contact__field">

                        <label class="contact__label" for="name">
                            Name
                        </label>

                        <input
                            class="contact__input"
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', Auth::check() ? Auth::user()->name : '') }}"
                            placeholder="Enter your name"
                            required
                        >
                        @error('name')
                            <span class="contact__error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    <div class="contact__field">

                        <label class="contact__label" for="email">
                            Email
                        </label>

                        <input
                            class="contact__input"
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}"
                            placeholder="Enter your email"
                            required
                        >
                        @error('email')
                            <span class="contact__error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    <div class="contact__field">

                        <label class="contact__label" for="subject">
                            Subject
                        </label>

                        <input
                            class="contact__input"
                            type="text"
                            id="subject"
                            name="subject"
                            value="{{ old('subject') }}"
                            placeholder="Enter the subject"
                            required
                        >
                        @error('subject')
                            <span class="contact__error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    <div class="contact__field">

                        <label class="contact__label" for="message">
                            Message
                        </label>

                        <textarea
                            class="contact__textarea"
                            id="message"
                            name="message"
                            rows="6"
                            placeholder="Write your message..."
                            required
                        >{{ old('message') }}</textarea>

                        @error('message')
                            <span class="contact__error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    <button
                        type="submit"
                        class="contact__button">

                        Send Message

                    </button>

                </form>

            </div>

        </div>

    </div>

</section>

@endsection