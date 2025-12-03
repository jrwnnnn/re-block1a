// Update cover image preview in article creation/editing form
document.addEventListener("DOMContentLoaded", () => {
    const coverInput = document.querySelector("input[name='cover']");
    const coverPreview = document.getElementById("coverPreview");

    const updatePreview = () => {
        const url = coverInput.value.trim();
        if (url) {
            coverPreview.src = url;
            coverPreview.classList.remove("hidden");
        } else {
            coverPreview.classList.add("hidden");
            coverPreview.src = ""; // clear the image
        }
    };

    coverInput.addEventListener("input", updatePreview);

    updatePreview();
});