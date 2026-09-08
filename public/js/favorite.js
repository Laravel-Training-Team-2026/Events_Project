document.addEventListener("DOMContentLoaded", function () {

    const favoriteForms = document.querySelectorAll(
        ".event-card__favorite-form"
    );

    const csrfTokenElement = document.querySelector(
        'meta[name="csrf-token"]'
    );

    if (!csrfTokenElement) {
        console.error("CSRF token not found.");
        return;
    }

    const csrfToken = csrfTokenElement.getAttribute("content");


    favoriteForms.forEach(function (form) {

        form.addEventListener("submit", async function (event) {

            event.preventDefault();

            const button = form.querySelector(
                ".event-card__favorite"
            );

            const icon = form.querySelector(
                ".event-card__favorite-icon"
            );

            if (!button || button.disabled) {
                return;
            }

            button.disabled = true;

            button.classList.add(
                "event-card__favorite--loading"
            );


            try {

                const response = await fetch(form.action, {

                    method: "POST",

                    headers: {

                        "X-CSRF-TOKEN": csrfToken,

                        "Accept": "application/json",

                        "X-Requested-With": "XMLHttpRequest"

                    },

                    body: new FormData(form)

                });


                if (!response.ok) {
                    throw new Error(
                        "Favorite request failed."
                    );
                }


                const data = await response.json();


                if (!data.success) {
                    throw new Error(
                        "Favorite action was not successful."
                    );
                }


                /* =================================
                   ADD TO FAVORITES
                ================================= */

                if (data.isFavorite) {

                    button.classList.add(
                        "event-card__favorite--active"
                    );

                    button.setAttribute(
                        "aria-label",
                        "Remove from favorites"
                    );

                    button.setAttribute(
                        "aria-pressed",
                        "true"
                    );


                    if (icon) {
                        icon.textContent = "★";
                    }


                    /*
                     * Next request = DELETE
                     */

                    form.action = data.destroy_url;


                    let methodInput = form.querySelector(
                        'input[name="_method"]'
                    );


                    if (!methodInput) {

                        methodInput = document.createElement(
                            "input"
                        );

                        methodInput.type = "hidden";
                        methodInput.name = "_method";

                        form.appendChild(methodInput);

                    }


                    methodInput.value = "DELETE";
                }


                /* =================================
                   REMOVE FROM FAVORITES
                ================================= */

                else {

                    /*
                     * Check if we are on My Favorites page.
                     *
                     * The favorites page has .favorites__grid
                     */

                    const favoritesGrid = document.querySelector(
                        ".favorites__grid"
                    );


                    if (favoritesGrid) {

                        /*
                         * Get the whole event card
                         */

                        const eventCard = form.closest(
                            ".event-card"
                        );


                        if (eventCard) {

                            eventCard.remove();

                        }


                        /*
                         * If there are no cards left,
                         * show the empty state.
                         */

                        const remainingCards =
                            favoritesGrid.querySelectorAll(
                                ".event-card"
                            );


                        if (remainingCards.length === 0) {

                            const container =
                                document.querySelector(
                                    ".favorites .container"
                                );


                            if (container) {

                                const emptyState =
                                    document.createElement(
                                        "div"
                                    );

                                emptyState.className =
                                    "favorites__empty";

                                emptyState.innerHTML = `
                                    <div class="favorites__empty-icon">
                                        <i class="fa-regular fa-heart"></i>
                                    </div>

                                    <h2 class="favorites__empty-title">
                                        No Favorites Yet
                                    </h2>

                                    <p class="favorites__empty-text">
                                        You haven't saved any events yet.
                                        Explore events and add the ones you love to your favorites.
                                    </p>

                                    <a href="/Events_Project/public/events"
                                       class="favorites__empty-link">
                                        Explore Events
                                    </a>
                                `;

                                favoritesGrid.remove();

                                container.appendChild(
                                    emptyState
                                );

                            }

                        }

                    }

                    /*
                     * If we are NOT on the Favorites page,
                     * just change the star normally.
                     */

                    else {

                        button.classList.remove(
                            "event-card__favorite--active"
                        );

                        button.setAttribute(
                            "aria-label",
                            "Add to favorites"
                        );

                        button.setAttribute(
                            "aria-pressed",
                            "false"
                        );


                        if (icon) {
                            icon.textContent = "☆";
                        }


                        /*
                         * Next request = POST
                         */

                        form.action = data.store_url;


                        const methodInput =
                            form.querySelector(
                                'input[name="_method"]'
                            );


                        if (methodInput) {
                            methodInput.remove();
                        }

                    }

                }


            } catch (error) {

                console.error(
                    "Favorite error:",
                    error
                );

            } finally {

                /*
                 * The button may already have been removed
                 * from the page, so check before changing it.
                 */

                if (document.body.contains(button)) {

                    button.disabled = false;

                    button.classList.remove(
                        "event-card__favorite--loading"
                    );

                }

            }

        });

    });

});