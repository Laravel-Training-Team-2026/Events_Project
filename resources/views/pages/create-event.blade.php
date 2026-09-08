@extends('layouts.base')

@section('title', 'Create Event')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/create-event.css') }}">
    <link rel="stylesheet" href="{{ asset('css/breadcrumb.css') }}">
@endpush

@section('content')

    <section class="create-event">

        <div class="container">
@include('components.breadcrumb', [
    'items' => [
        [
            'label' => 'Home',
            'url' => route('home'),
        ],
        [
            'label' => 'Create Event',
        ],
    ],
])
            {{-- Page Header --}}
            <div class="create-event__header">

                <h1 class="create-event__heading">
                    Create New Event
                </h1>

                <p class="create-event__subtitle">
                    Create your event and share it with people
                </p>

            </div>


            {{-- Event Form --}}
            <form class="create-event__form" method="POST" action="{{ url('/events') }}" enctype="multipart/form-data">

                @csrf

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="create-event__errors">

                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>
                @endif


                {{-- Event Information --}}
                <div class="create-event__card">

                    <h2 class="create-event__title">
                        Event Information
                    </h2>


                    {{-- Event Title --}}
                    <div class="create-event__field">

                        <label for="event-title" class="create-event__label">
                            Event Title
                        </label>

                        <input type="text" id="event-title" name="title" class="create-event__input"
                            placeholder="Enter event title" value="{{ old('title') }}" required>

                    </div>


                    {{-- Category --}}
                    <div class="create-event__field">

                        <label for="event-category" class="create-event__label">
                            Category
                        </label>

                        <select id="event-category" name="category_id" class="create-event__input create-event__select"
                            required>

                            <option value="">
                                Select Category
                            </option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach

                        </select>

                    </div>


                    {{-- Description --}}
                    <div class="create-event__field">

                        <label for="event-description" class="create-event__label">
                            Description
                        </label>

                        <textarea id="event-description" name="description" class="create-event__input create-event__textarea"
                            placeholder="Describe your event" required>{{ old('description') }}</textarea>

                    </div>

                </div>


                {{-- Event Image --}}
                <div class="create-event__card">

                    <h2 class="create-event__title">
                        Event Image
                    </h2>

                    <label for="event-image" class="create-event__upload" id="event-image-upload">

                        {{-- Upload Content --}}
                        <div class="create-event__upload-content" id="event-upload-content">

                            <i class="fa-solid fa-cloud-arrow-up create-event__upload-icon"></i>

                            <span class="create-event__upload-title">
                                Upload Event Image
                            </span>

                            <span class="create-event__upload-text">
                                Click here to choose an image
                            </span>

                        </div>


                        {{-- Image Preview --}}
                        <img src="" alt="Event image preview" class="create-event__preview"
                            id="event-image-preview">


                        {{-- File Input --}}
                        <input type="file" id="event-image" name="image" class="create-event__file" accept="image/*"
                            required>

                    </label>

                </div>



                {{-- Date & Time --}}
                <div class="create-event__card">

                    <h2 class="create-event__title">
                        Date & Time
                    </h2>


                    <div class="row">

                        {{-- Start Date --}}
                        <div class="col-md-6">

                            <div class="create-event__field">

                                <label for="start-date" class="create-event__label">
                                    Start Date
                                </label>

                                <input type="date" id="start-date" name="start_date" class="create-event__input"
                                    value="{{ old('start_date') }}" required>

                            </div>

                        </div>


                        {{-- End Date --}}
                        <div class="col-md-6">

                            <div class="create-event__field">

                                <label for="end-date" class="create-event__label">
                                    End Date
                                </label>

                                <input type="date" id="end-date" name="end_date" class="create-event__input"
                                    value="{{ old('end_date') }}" required>

                            </div>

                        </div>

                    </div>


                    <div class="row">

                        {{-- Start Time --}}
                        <div class="col-md-6">

                            <div class="create-event__field">

                                <label for="start-time" class="create-event__label">
                                    Start Time
                                </label>

                                <input type="time" id="start-time" name="start_time" class="create-event__input"
                                    value="{{ old('start_time') }}" required>

                            </div>

                        </div>


                        {{-- End Time --}}
                        <div class="col-md-6">

                            <div class="create-event__field">

                                <label for="end-time" class="create-event__label">
                                    End Time
                                </label>

                                <input type="time" id="end-time" name="end_time" class="create-event__input"
                                    value="{{ old('end_time') }}" required>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Location --}}
                <div class="create-event__card">

                    <h2 class="create-event__title">
                        Location
                    </h2>


                    {{-- Location --}}
                    <div class="create-event__field">

                        <label for="event-location" class="create-event__label">
                            Location
                        </label>

                        <input type="text" id="event-location" name="location" class="create-event__input"
                            placeholder="Enter event location" value="{{ old('location') }}" required>

                    </div>


                    {{-- City --}}
                    <div class="create-event__field">

                        <label for="event-city" class="create-event__label">
                            City
                        </label>

                        <select id="event-city" name="city" class="create-event__input create-event__select" required>

                            <option value="">
                                Select City
                            </option>

                            @foreach ($cities as $city)
                                <option value="{{ $city }}" {{ old('city') === $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- Ticket Information --}}
                <div class="create-event__card">

                    <h2 class="create-event__title">
                        Ticket Information
                    </h2>


                    <div class="row">

                        {{-- Price --}}
                        <div class="col-md-6">

                            <div class="create-event__field">

                                <label for="ticket-price" class="create-event__label">
                                    Price
                                </label>

                                <input type="number" id="ticket-price" name="price" class="create-event__input"
                                    placeholder="0" min="0" step="1" value="{{ old('price') }}" required>

                            </div>

                        </div>


                        {{-- Capacity --}}
                        <div class="col-md-6">

                            <div class="create-event__field">

                                <label for="event-capacity" class="create-event__label">
                                    Capacity
                                </label>

                                <input type="number" id="event-capacity" name="capacity" class="create-event__input"
                                    placeholder="Number of attendees" min="1" value="{{ old('capacity') }}"
                                    required>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Submit --}}
                <div class="create-event__action">

                    <button type="submit" class="create-event__button">
                        Create Event
                    </button>

                </div>

            </form>

        </div>

    </section>


@endsection

@push('scripts')
    <script src="{{ asset('js/create-event.js') }}"></script>
@endpush
