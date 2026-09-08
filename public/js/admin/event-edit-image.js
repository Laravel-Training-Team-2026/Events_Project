document.addEventListener('click', function (event) {
    const removeButton = event.target.closest('[data-event-image-remove]');

    if (!removeButton) {
        return;
    }

    const field = removeButton.closest('[data-event-image-field]');

    if (!field) {
        return;
    }

    field.querySelector('[data-event-image-current]')?.remove();

    if (!field.querySelector('[data-event-image-remove-value]')) {
        const removedImage = document.createElement('input');
        removedImage.type = 'hidden';
        removedImage.name = field.dataset.fieldName;
        removedImage.value = '';
        removedImage.dataset.eventImageRemoveValue = 'true';
        field.appendChild(removedImage);
    }
});

document.addEventListener('change', function (event) {
    if (!event.target.matches('[data-event-image-input]')) {
        return;
    }

    const field = event.target.closest('[data-event-image-field]');
    field?.querySelector('[data-event-image-remove-value]')?.remove();
});
