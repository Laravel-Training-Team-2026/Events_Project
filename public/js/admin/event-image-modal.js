(() => {
    const modal = document.createElement('div');
    const content = document.createElement('div');
    const closeButton = document.createElement('button');
    const image = document.createElement('img');

    modal.id = 'event-image-modal';
    modal.className = 'event-image-modal';
    modal.hidden = true;

    content.className = 'event-image-modal__content';

    closeButton.type = 'button';
    closeButton.className = 'event-image-modal__close';
    closeButton.setAttribute('aria-label', 'Close image preview');
    closeButton.textContent = '×';

    image.className = 'event-image-modal__image';

    content.append(closeButton, image);
    modal.append(content);
    document.body.append(modal);

    const closeModal = () => {
        modal.hidden = true;
        image.removeAttribute('src');
    };

    document.addEventListener('click', (event) => {
        const preview = event.target.closest('.event-image-preview');

        if (!preview) {
            return;
        }

        image.src = preview.dataset.eventImageUrl;
        image.alt = preview.dataset.eventImageAlt || 'Event image';
        modal.hidden = false;
    });

    closeButton.addEventListener('click', closeModal);

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.hidden) {
            closeModal();
        }
    });
})();
