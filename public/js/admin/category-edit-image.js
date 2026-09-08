document.addEventListener('click', function (event) {
    const removeButton = event.target.closest('[data-category-image-remove]');

    if (!removeButton) {
        return;
    }

    const field = removeButton.closest('[data-category-image-field]');

    if (!field) {
        return;
    }

    field.querySelector('[data-category-image-current]')?.remove();

    if (!field.querySelector('[data-category-image-remove-value]')) {
        const removedImage = document.createElement('input');
        removedImage.type = 'hidden';
        removedImage.name = field.dataset.fieldName;
        removedImage.value = '';
        removedImage.dataset.categoryImageRemoveValue = 'true';
        field.appendChild(removedImage);
    }
});

document.addEventListener('change', function (event) {
    if (!event.target.matches('[data-category-image-input]')) {
        return;
    }

    const field = event.target.closest('[data-category-image-field]');
    field?.querySelector('[data-category-image-remove-value]')?.remove();
});
