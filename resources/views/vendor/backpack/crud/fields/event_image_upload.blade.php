@php
    $field['wrapper'] = $field['wrapper'] ?? $field['wrapperAttributes'] ?? [];
    $currentImage = $field['value'] ?? null;
    $disk = $field['disk'] ?? 'public';
    $prefix = $field['prefix'] ?? '';
    $imageUrl = $currentImage ? asset(\Storage::disk($disk)->url($prefix.$currentImage)) : null;
@endphp

@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')

    <div class="event-edit-image-field" data-event-image-field data-field-name="{{ $field['name'] }}">
        @if ($imageUrl)
            <div class="event-edit-image-preview" data-event-image-current>
                <img src="{{ $imageUrl }}" alt="Current event image">
                <button type="button" class="event-edit-image-remove" data-event-image-remove aria-label="Remove current image" title="Remove current image">
                    &times;
                </button>
            </div>
        @endif

        <input
            type="file"
            name="{{ $field['name'] }}"
            accept="image/*"
            data-event-image-input
            @include('crud::fields.inc.attributes', ['default_class' => 'form-control'])
        >
    </div>

    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')
