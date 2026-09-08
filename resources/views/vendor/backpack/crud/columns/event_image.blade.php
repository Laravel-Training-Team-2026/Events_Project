@php
    $imagePath = data_get($entry, $column['name']);
@endphp

@if ($imagePath)
    <button
        type="button"
        class="event-image-preview"
        data-event-image-url="{{ Storage::disk('public')->url($imagePath) }}"
        data-event-image-alt="{{ $entry->title }}">
        <img
            src="{{ Storage::disk('public')->url($imagePath) }}"
            alt="{{ $entry->title }}"
            style="max-height: {{ $column['height'] }}; width: {{ $column['width'] }};">
    </button>
@else
    -
@endif
