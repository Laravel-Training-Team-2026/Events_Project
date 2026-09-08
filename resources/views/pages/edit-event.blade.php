@extends('layouts.base')

@section('title', 'Edit Event')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/create-event.css') }}">
    <link rel="stylesheet" href="{{ asset('css/breadcrumb.css') }}">
@endpush

@section('content')

    <section class="create-event">

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
            'url' => route('events.index'),
        ],
        [
            'label' => $event->title,
            'url' => route('event.details', $event),
        ],
        [
            'label' => 'Edit Event',
        ],
    ],
])



            {{-- Page Header --}}
            <div class="create-event__header">

                <h1 class="create-event__heading">
                    Edit Event
                </h1>

                <p class="create-event__subtitle">
                    Update your event information
                </p>

            </div>




            {{-- Event Form --}}
            <form class="create-event__form" method="POST" action="{{ route('events.update', $event) }}"
                enctype="multipart/form-data">

                @csrf
                @method('PUT')


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
                            placeholder="Enter event title" value="{{ old('title', $event->title) }}" required>

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
                                    {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>
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
                            placeholder="Describe your event" required>{{ old('description', $event->description) }}</textarea>

                    </div>

                </div>

{{-- Event Image --}}
<div class="create-event__card">

    <h2 class="create-event__title">
        Event Image
    </h2>

    <input
        type="hidden"
        name="remove_image"
        id="remove-image"
        value="0"
    >

    {{-- Image Area --}}
    <div class="create-event__image-area">

        {{-- Current Image --}}
        @if ($event->image)

            <div
                class="create-event__image-preview"
                id="current-image-preview"
            >

                <img
                    src="{{ asset('storage/' . $event->image) }}"
                    alt="{{ $event->title }}"
                    class="create-event__preview-image"
                    id="preview-image"
                >

                <button
                    type="button"
                    class="create-event__image-remove"
                    id="remove-image-button"
                    aria-label="Remove current image"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>

            </div>

        @endif


        {{-- Upload New Image --}}
        <label
            for="event-image"
            class="create-event__upload"
            id="upload-image-area"
            @if ($event->image)
                hidden
            @endif
        >

            <i class="fa-solid fa-cloud-arrow-up create-event__upload-icon"></i>

            <span class="create-event__upload-title">
                Upload New Image
            </span>

            <span class="create-event__upload-text">
                Choose an image for your event
            </span>

            <input
                type="file"
                id="event-image"
                name="image"
                class="create-event__file"
                accept="image/*"
            >

        </label>

    </div>

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
                                    value="{{ old('start_date', $event->start_date) }}" required>

                            </div>

                        </div>


                        {{-- End Date --}}
                        <div class="col-md-6">

                            <div class="create-event__field">

                                <label for="end-date" class="create-event__label">
                                    End Date
                                </label>

                                <input type="date" id="end-date" name="end_date" class="create-event__input"
                                    value="{{ old('end_date', $event->end_date) }}" required>

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
                                    value="{{ old('start_time', $event->start_time) }}" required>

                            </div>

                        </div>


                        {{-- End Time --}}
                        <div class="col-md-6">

                            <div class="create-event__field">

                                <label for="end-time" class="create-event__label">
                                    End Time
                                </label>

                                <input type="time" id="end-time" name="end_time" class="create-event__input"
                                    value="{{ old('end_time', $event->end_time) }}" required>

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
                            placeholder="Enter event location" value="{{ old('location', $event->location) }}" required>

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
                                <option value="{{ $city }}" {{ old('city', $event->city) === $city ? 'selected' : '' }}>
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

                            <input
                                type="number"
                                id="ticket-price"
                                name="price"
                                class="create-event__input"
                                placeholder="0"
                                min="0"
                                step="any"
                                value="{{ old('price', $event->price) }}"
                                required
                            >

                            </div>

                        </div>


                        {{-- Capacity --}}
                        <div class="col-md-6">

                            <div class="create-event__field">

                                <label for="event-capacity" class="create-event__label">
                                    Capacity
                                </label>

                                <input type="number" id="event-capacity" name="capacity" class="create-event__input"
                                    placeholder="Number of attendees" min="1"
                                    value="{{ old('capacity', $event->capacity) }}" required>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Submit --}}
                <div class="create-event__action">

                    <button type="submit" class="create-event__button">
                        Update Event
                    </button>

                </div>

            </form>

        </div>

    </section>
@push('scripts')
    <script src="{{ asset('js/edit-event.js') }}"></script>
@endpush
@endsection
