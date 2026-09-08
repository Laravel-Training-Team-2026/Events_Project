document.addEventListener('DOMContentLoaded', function () {
    const search = document.getElementById('event-search');
    const city = document.getElementById('event-city');
    const date = document.getElementById('event-date');
    const categoryButtons = Array.from(document.querySelectorAll('.events__category'));
    const items = Array.from(document.querySelectorAll('.event-item'));
    const count = document.getElementById('events-count');
    const resultsNote = document.getElementById('events-results-note');
    const reset = document.getElementById('events-reset');
    const empty = document.getElementById('events-empty-filtered');
    const emptyReset = document.getElementById('events-empty-reset');
    const categoryList = document.querySelector('[data-category-list]');
    const categoryScrollPrevious = document.querySelector('[data-category-scroll-previous]');
    const categoryScrollNext = document.querySelector('[data-category-scroll-next]');
    let selectedCategory = document.querySelector('.events__category.is-active')?.dataset.category || '';

    function updateCategoryScrollButtons() {
        if (!categoryList || !categoryScrollPrevious || !categoryScrollNext) return;

        const hasOverflow = categoryList.scrollWidth > categoryList.clientWidth + 1;
        const atStart = categoryList.scrollLeft <= 1;
        const atEnd = categoryList.scrollLeft + categoryList.clientWidth >= categoryList.scrollWidth - 1;

        categoryScrollPrevious.hidden = !hasOverflow;
        categoryScrollNext.hidden = !hasOverflow;
        categoryScrollPrevious.disabled = atStart;
        categoryScrollNext.disabled = atEnd;
    }

    function scrollCategories(direction) {
        if (!categoryList) return;

        categoryList.scrollBy({
            left: direction * Math.max(categoryList.clientWidth * 0.75, 180),
            behavior: 'smooth',
        });
    }

    function dateAtStartOfDay(value) {
        const parts = value.split('-').map(Number);
        return new Date(parts[0], parts[1] - 1, parts[2]);
    }

    function matchesDate(eventDate, selectedDate) {
        if (!selectedDate) return true;

        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const target = dateAtStartOfDay(eventDate);

        if (selectedDate === 'today') return target.getTime() === today.getTime();

        if (selectedDate === 'tomorrow') {
            const tomorrow = new Date(today);
            tomorrow.setDate(today.getDate() + 1);
            return target.getTime() === tomorrow.getTime();
        }

        if (selectedDate === 'week') {
            const endOfWeek = new Date(today);
            endOfWeek.setDate(today.getDate() + (7 - today.getDay()));
            return target >= today && target <= endOfWeek;
        }

        if (selectedDate === 'month') {
            return target.getFullYear() === today.getFullYear()
                && target.getMonth() === today.getMonth()
                && target >= today;
        }

        return true;
    }

    function hasActiveFilters() {
        return Boolean(search.value.trim() || city.value || date.value || selectedCategory);
    }

    function updateResults() {
        const term = search.value.trim().toLowerCase();
        let visible = 0;

        items.forEach(function (item) {
            const matches = (!term || item.dataset.search.includes(term))
                && (!selectedCategory || item.dataset.category === selectedCategory)
                && (!city.value || item.dataset.city === city.value)
                && matchesDate(item.dataset.date, date.value);

            item.hidden = !matches;
            if (matches) visible += 1;
        });

        count.textContent = visible + ' ' + (visible === 1 ? 'event' : 'events');
        resultsNote.textContent = hasActiveFilters()
            ? 'Showing events that match your selection.'
            : 'A considered selection of events near you.';
        reset.hidden = !hasActiveFilters();
        empty.hidden = !(items.length && visible === 0);
    }

    function resetFilters() {
        search.value = '';
        city.value = '';
        date.value = '';
        selectedCategory = '';

        categoryButtons.forEach(function (button) {
            const active = button.dataset.category === '';
            button.classList.toggle('is-active', active);
            button.setAttribute('aria-pressed', String(active));
        });

        updateResults();
    }

    search.addEventListener('input', updateResults);
    city.addEventListener('change', updateResults);
    date.addEventListener('change', updateResults);
    reset.addEventListener('click', resetFilters);
    emptyReset.addEventListener('click', resetFilters);

    categoryScrollPrevious?.addEventListener('click', function () {
        scrollCategories(-1);
    });

    categoryScrollNext?.addEventListener('click', function () {
        scrollCategories(1);
    });

    categoryList?.addEventListener('scroll', updateCategoryScrollButtons, { passive: true });
    window.addEventListener('resize', updateCategoryScrollButtons);

    categoryButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            selectedCategory = button.dataset.category;

            categoryButtons.forEach(function (categoryButton) {
                const active = categoryButton === button;
                categoryButton.classList.toggle('is-active', active);
                categoryButton.setAttribute('aria-pressed', String(active));
            });

            updateResults();
        });
    });

    document.querySelectorAll('[data-select-control]').forEach(function (control) {
        const select = control.querySelector('select');

        control.addEventListener('click', function (event) {
            control.classList.add('is-open');

            if (event.target !== select) {
                select.focus();
                select.click();
            }
        });

        select.addEventListener('keydown', function (event) {
            if (['Enter', ' ', 'ArrowDown', 'ArrowUp'].includes(event.key)) {
                control.classList.add('is-open');
            }
        });

        select.addEventListener('change', function () {
            control.classList.remove('is-open');
        });

        select.addEventListener('blur', function () {
            control.classList.remove('is-open');
        });
    });

    updateResults();
    updateCategoryScrollButtons();
});
