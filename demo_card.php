<!-- business card css -->
<!-- Bootstrap css -->
<link href="assets/templates/css/bootstrap.min.css" media="screen" rel="stylesheet" />
<!-- Slick slider css -->
<link rel="stylesheet" href="assets/templates/css/slick.css" />
<link rel="stylesheet" href="assets/templates/css/slick-theme.css" />
<!-- Swiper slider css -->
<link rel="stylesheet" href="assets/templates/css/swiper.min.css" />
<!--font Awesome css -->
<link rel="stylesheet" href="assets/templates/css/font-awesome.css" />
<!-- Wow animations css -->
<link rel="stylesheet" href="assets/templates/css/animate.min.css" />

<!-- Main custom css -->
<link media="screen" rel="stylesheet" href="assets/templates/css/style.css" />

<!-- Shivraj css -->
<link media="screen" rel="stylesheet" href="assets/templates/css/shivraj.css" />


<!-- button left side -->
<label>Other Images</label>
<div class="form-group" id="image_container" style="width: 330px;">
</div>

<button type="button" class="btn btn-primary" onclick="addImageInput()">Add</button>

<!-- preview right side -->
<div class="imgs" id="preview_gallery" style="height: 200px; width: 300px;">
    <!-- Old images will be displayed here -->
    <div class="img" data-input-id="old1">
        <img src="assets/templates/img/slider/img1.png" alt="Old Image 1" height="100px" width="100px" />
    </div>
    <div class="img" data-input-id="old2">
        <img src="assets/templates/img/slider/img2.png" alt="Old Image 2" height="100px" width="100px" />
    </div>
    <div class="img" data-input-id="old3">
        <img src="assets/templates/img/slider/img3.png" alt="Old Image 3" height="100px" width="100px" />
    </div>
</div>


<!-- js -->
<!-- Jquery Library File -->
<script src="assets/templates/js/jquery-3.4.1.js"></script>
<!-- Bootstrap js file -->
<script src="assets/templates/js/bootstrap.min.js"></script>
<!-- Slick slider js file -->
<script src="assets/templates/js/slick.min.js"></script>
<!-- Swiper slider js file -->
<script src="assets/templates/js/swiper.min.js"></script>
<!-- Wow animation js file -->
<script src="assets/templates/js/wow.min.js"></script>
<!-- Main js file -->
<script src="assets/templates/js/script.js "></script>


<script>
    $(".imgs").slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 3,
        autoplay: true,
        autoplaySpeed: 2000,
        dots: true,
        arrows: false,
        responsive: [{
                breakpoint: 800,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                },
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                },
            },
        ],
    });
</script>

<script>
    function addImageInput() {
        let inputHtml = `
    <div class="input-group mb-2">
        <input type="file" class="form-control image-input" accept="image/*">
        <button type="button" class="btn btn-danger" onclick="removeInput(this)">
            <i class="bi bi-trash"></i>
        </button>
    </div>`;
        document.getElementById('image_container').insertAdjacentHTML('beforeend', inputHtml);
    }
    
    function removeInput(button) {
        const inputGroup = button.closest('.input-group');
        const fileInput = inputGroup.querySelector('input[type="file"]');
        const previewGallery = document.getElementById('preview_gallery');

        // Remove preview image linked to this input if exists
        const inputId = fileInput.getAttribute('data-input-id');
        if (inputId) {
            const previewToRemove = previewGallery.querySelector(`.img[data-input-id="${inputId}"]`);
            if (previewToRemove) previewToRemove.remove();
        }

        // Remove the input group
        inputGroup.remove();

        // Reinitialize slick slider after removal
        reinitializeSlickSlider();

        // If no file inputs remain or none have files, show old images again
        if (!anyFileSelected()) {
            showOldImages(true);
        }
    }

    let imageInputCounter = 0;

    document.getElementById('image_container').addEventListener('change', function(e) {
        if (e.target && e.target.matches('input.image-input[type="file"]')) {
            const fileInput = e.target;
            const previewGallery = document.getElementById('preview_gallery');
            const files = fileInput.files;

            if (!fileInput.hasAttribute('data-input-id')) {
                imageInputCounter++;
                fileInput.setAttribute('data-input-id', imageInputCounter);
            }
            const inputId = fileInput.getAttribute('data-input-id');

            // Remove old preview for this input
            const existingPreview = previewGallery.querySelector(`.img[data-input-id="${inputId}"]`);
            if (existingPreview) existingPreview.remove();

            if (files && files[0]) {
                // Hide old images when first new image is selected
                showOldImages(false);

                const reader = new FileReader();
                reader.onload = function(event) {
                    const imgHtml = `
                    <div class="img" data-input-id="${inputId}">
                        <img src="${event.target.result}" alt="Preview Image" height="100px" width="100px" />
                    </div>`;

                    // Append the new image preview to the gallery
                    previewGallery.insertAdjacentHTML('beforeend', imgHtml);

                    // Reinitialize slick slider after adding new image
                    reinitializeSlickSlider();
                };
                reader.readAsDataURL(files[0]);
            } else {
                if (existingPreview) existingPreview.remove();
                reinitializeSlickSlider();

                // If no files are selected, show old images
                if (!anyFileSelected()) {
                    showOldImages(true);
                }
            }
        }
    });

    // Function to reinitialize slick slider
    function reinitializeSlickSlider() {
        // Destroy existing slick slider
        $('.imgs').slick('unslick');

        // Reinitialize with same settings
        $(".imgs").slick({
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 3,
            autoplay: true,
            autoplaySpeed: 2000,
            dots: true,
            arrows: false,
            responsive: [{
                    breakpoint: 800,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2,
                    },
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    },
                },
            ],
        });
    }

    // Helper: check if any file input has a selected file
    function anyFileSelected() {
        const inputs = document.querySelectorAll('#image_container input.image-input[type="file"]');
        return Array.from(inputs).some(input => input.files && input.files.length > 0);
    }

    // Helper: show or hide old images
    function showOldImages(show) {
        const oldImages = document.querySelectorAll('#preview_gallery .img:not([data-input-id])');
        oldImages.forEach(imgDiv => {
            imgDiv.style.display = show ? 'block' : 'none';
        });

        // Reinitialize slick slider after showing/hiding old images
        reinitializeSlickSlider();
    }

    // Initialize by showing old images
    document.addEventListener('DOMContentLoaded', function() {
        showOldImages(true);
    });
</script>