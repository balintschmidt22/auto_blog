const coverImageInput = document.querySelector("input#image");
const coverPreviewContainer = document.querySelector("#cover_preview");
const coverPreviewImage = document.querySelector("img#cover_preview_image");

coverImageInput.onchange = (event) => {
    const [file] = coverImageInput.files;
    if (file) {
        coverPreviewContainer.classList.remove("d-none");
        coverPreviewImage.src = URL.createObjectURL(file);
    } else {
        coverPreviewContainer.classList.add("d-none");
    }
};
