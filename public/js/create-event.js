document.addEventListener("DOMContentLoaded", function () {
    const imageInput = document.getElementById("event-image");
    const imagePreview = document.getElementById("event-image-preview");
    const uploadContent = document.getElementById("event-upload-content");
    const uploadBox = document.getElementById("event-image-upload");

    if (!imageInput || !imagePreview || !uploadContent || !uploadBox) {
        return;
    }

    imageInput.addEventListener("change", function () {
        const file = this.files[0];

        if (!file) {
            imagePreview.src = "";
            imagePreview.style.display = "none";
            uploadContent.style.display = "flex";

            return;
        }

        // Make sure the selected file is an image
        if (!file.type.startsWith("image/")) {
            this.value = "";

            imagePreview.src = "";
            imagePreview.style.display = "none";
            uploadContent.style.display = "flex";

            return;
        }

        // Create image preview
        const reader = new FileReader();

        reader.onload = function (event) {
            imagePreview.src = event.target.result;

            imagePreview.style.display = "block";

            uploadContent.style.display = "none";

            uploadBox.classList.add("create-event__upload--has-image");
        };

        reader.readAsDataURL(file);
    });
});
