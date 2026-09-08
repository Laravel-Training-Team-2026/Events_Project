document.addEventListener("DOMContentLoaded", function () {

    const removeButton = document.getElementById(
        "remove-image-button"
    );

    const removeInput = document.getElementById(
        "remove-image"
    );

    const currentImagePreview = document.getElementById(
        "current-image-preview"
    );

    const uploadImageArea = document.getElementById(
        "upload-image-area"
    );

    const imageInput = document.getElementById(
        "event-image"
    );

    const previewImage = document.getElementById(
        "preview-image"
    );


    /*
    |--------------------------------------------------------------------------
    | Remove Current Image
    |--------------------------------------------------------------------------
    */

    if (removeButton) {

        removeButton.addEventListener("click", function () {

            /*
            | Mark image for deletion
            */

            if (removeInput) {
                removeInput.value = "1";
            }


            /*
            | Hide current image
            */

            if (currentImagePreview) {
                currentImagePreview.hidden = true;
            }


            /*
            | Show upload area
            */

            if (uploadImageArea) {
                uploadImageArea.hidden = false;
            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Select New Image
    |--------------------------------------------------------------------------
    */

    if (imageInput) {

        imageInput.addEventListener("change", function () {

            const file = imageInput.files[0];

            if (!file) {
                return;
            }


            /*
            | New image means we do not want to remove it
            */

            if (removeInput) {
                removeInput.value = "0";
            }


            /*
            | Show selected image preview
            */

            const reader = new FileReader();

            reader.onload = function (event) {

                /*
                | If the old preview was hidden,
                | show it again.
                */

                if (currentImagePreview) {

                    currentImagePreview.hidden = false;

                    if (previewImage) {
                        previewImage.src = event.target.result;
                    }

                    if (removeButton) {
                        removeButton.hidden = false;
                    }

                }
                else {

                    /*
                    | If there was no old image,
                    | create a preview dynamically.
                    */

                    const imageArea =
                        document.querySelector(
                            ".create-event__image-area"
                        );

                    if (!imageArea) {
                        return;
                    }


                    const preview =
                        document.createElement("div");

                    preview.className =
                        "create-event__image-preview";

                    preview.id =
                        "current-image-preview";


                    preview.innerHTML = `
                        <img
                            src="${event.target.result}"
                            alt="Selected event image"
                            class="create-event__preview-image"
                            id="preview-image"
                        >

                        <button
                            type="button"
                            class="create-event__image-remove"
                            id="remove-image-button"
                            aria-label="Remove selected image"
                        >
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    `;


                    imageArea.insertBefore(
                        preview,
                        uploadImageArea
                    );


                    /*
                    | Replace references
                    */

                    const newRemoveButton =
                        document.getElementById(
                            "remove-image-button"
                        );

                    newRemoveButton.addEventListener(
                        "click",
                        function () {

                            removeInput.value = "1";

                            preview.remove();

                            imageInput.value = "";

                            uploadImageArea.hidden = false;

                        }
                    );

                }

            };

            reader.readAsDataURL(file);

            /*
            | Hide upload area while preview is shown
            */

            if (uploadImageArea) {
                uploadImageArea.hidden = true;
            }

        });

    }

});