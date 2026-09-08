document.addEventListener("DOMContentLoaded", function () {

    const toggleButtons = document.querySelectorAll(
        "[data-password-toggle]"
    );

    toggleButtons.forEach(function (button) {

        button.addEventListener("click", function () {

            const targetId = button.dataset.target;

            const passwordInput = document.getElementById(
                targetId
            );

            if (!passwordInput) {
                return;
            }

            const isPasswordHidden =
                passwordInput.type === "password";

            passwordInput.type = isPasswordHidden
                ? "text"
                : "password";

            button.setAttribute(
                "aria-pressed",
                isPasswordHidden ? "true" : "false"
            );

            button.setAttribute(
                "aria-label",
                isPasswordHidden
                    ? "Hide password"
                    : "Show password"
            );

            button.classList.toggle(
                "is-visible",
                isPasswordHidden
            );

        });

    });

});