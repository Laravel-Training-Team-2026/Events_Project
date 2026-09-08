document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.querySelector(".hero__search-input");
    const citySelect = document.querySelector(".hero__search-select");

    const eventItems = Array.from(
        document.querySelectorAll(".home-event-item"),
    );

    const moreButton = document.getElementById("home-events-more-button");

    const moreContainer = document.getElementById("home-events-more");

    const emptyMessage = document.getElementById("home-search-empty");

    /*
    ==========================================
    Events - Show More
    ==========================================
    */

    const step = 3;

    let visibleCount = 6;

    function showMoreEvents() {
        const nextVisibleCount = visibleCount + step;

        eventItems.forEach(function (item, index) {
            if (index < nextVisibleCount) {
                item.hidden = false;
            }
        });

        visibleCount = Math.min(nextVisibleCount, eventItems.length);

        if (visibleCount >= eventItems.length && moreContainer) {
            moreContainer.hidden = true;
        }
    }

    /*
    ==========================================
    Search + City Filter
    ==========================================
    */

    function filterEvents() {
        const searchTerm = searchInput
            ? searchInput.value.trim().toLowerCase()
            : "";

        const selectedCity = citySelect
            ? citySelect.value.trim().toLowerCase()
            : "";

        let matchedCount = 0;

        eventItems.forEach(function (item, index) {
            const searchData = item.dataset.search || "";

            const eventCity = item.dataset.city || "";

            const matchesSearch =
                !searchTerm || searchData.includes(searchTerm);

            const matchesCity = !selectedCity || eventCity === selectedCity;

            const matches = matchesSearch && matchesCity;

            /*
            ==========================================
            إذا في Search أو City Filter
            نظهر كل النتائج المطابقة
            ==========================================
            */

            if (searchTerm || selectedCity) {
                item.hidden = !matches;

                if (matches) {
                    matchedCount++;
                }
            } else {

            /*
            ==========================================
            إذا ما في Filter
            نرجع لنظام 6 + 3
            ==========================================
            */
                item.hidden = index >= visibleCount;

                if (!item.hidden) {
                    matchedCount++;
                }
            }
        });

        /*
        ==========================================
        Empty Search Result
        ==========================================
        */

        if (emptyMessage) {
            emptyMessage.hidden =
                !(searchTerm || selectedCity) || matchedCount > 0;
        }

        /*
        ==========================================
        See More
        ==========================================
        */

        if (moreContainer) {
            if (searchTerm || selectedCity) {
                moreContainer.hidden = true;
            } else {
                moreContainer.hidden = visibleCount >= eventItems.length;
            }
        }
    }

    /*
    ==========================================
    See More Button
    ==========================================
    */

    if (moreButton) {
        moreButton.addEventListener("click", function () {
            showMoreEvents();
        });
    }

    /*
    ==========================================
    Search
    ==========================================
    */

    if (searchInput) {
        searchInput.addEventListener("input", function () {
            filterEvents();
        });
    }

    /*
    ==========================================
    City
    ==========================================
    */

    if (citySelect) {
        citySelect.addEventListener("change", function () {
            filterEvents();
        });
    }

    /*
    ==========================================
    Initial State
    ==========================================
    */

    filterEvents();
});
