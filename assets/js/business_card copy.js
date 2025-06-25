$(document).ready(function () {
    $('#platformDropdown').on('change', function () {
        let selectedOption = $(this).find('option:selected');
        let id = selectedOption.val();
        let name = selectedOption.data('name');
        let social_links_default = $('#social_links_default');
        let social_links_custom = $('#social_links_custom');

        if (id) {
            let inputHtml = `
    <div class="form-group platform-input" data-id="${id}">
        <div class="input-group mb-2">
            <input type="hidden" name="platform_ids[]" value="${id}">
            <input type="text" name="platform_links[${id}]" class="form-control" placeholder="${name} Link" required>
            <button type="button" class="btn btn-danger remove-platform-btn">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>`;

            // Check if the platform already exists
            $('#platformInputs').append(inputHtml);
            social_links_default.addClass('d-none');
            $.ajax({
                url: "ajax/select_icon.php",
                type: "POST",
                data: { id: id },
                success: function (response) {
                    social_links_custom.append(response);
                }
            })

            // Remove selected option from dropdown
            selectedOption.remove();
        }
    });

    $('#platformInputs').on('click', '.remove-platform-btn', function () {
        let inputGroup = $(this).closest('.platform-input');
        let id = inputGroup.data('id');
        let name = inputGroup.find('input[placeholder]').attr('placeholder').replace(' Link', '');

        // Remove input field
        inputGroup.remove();

        // Remove corresponding social icon from custom links
        $('#social_links_custom').find(`.social-icon[data-id="${id}"]`).remove();

        // Re-add to dropdown
        $('#platformDropdown').append(`<option value="${id}" data-name="${name}">${name}</option>`);

        // If no platform inputs are left, show default links
        if ($('#platformInputs .platform-input').length === 0) {
            $('#social_links_default').removeClass('d-none');
            $('#social_links_custom').empty(); // Optional: clear custom links completely
        }
    });

    $('#platformInputs').on('input', 'input[name^="platform_links["]', function () {
        let input = $(this);
        let rawValue = input.val();
        let id = input.closest('.platform-input').data('id');

        // Add https:// if missing and value is not empty
        let value = rawValue.trim();
        if (value && !/^https?:\/\//i.test(value)) {
            value = 'https://' + value;
        }

        // Simple URL validation
        const isValidUrl = /^(https?:\/\/)[^\s/$.?#].[^\s]*$/i.test(value);

        // Update the corresponding social icon link
        if (isValidUrl) {
            $(`#link_${id}`).attr('href', value);
            input.removeClass('is-invalid'); // optional: Bootstrap class for valid input
        } else {
            $(`#link_${id}`).attr('href', '#');
            input.addClass('is-invalid'); // optional: Bootstrap class for invalid input
        }
    });

});


// other image and slider functionality
let imageInputCounter = 0;

function addImageInput() {
    let inputHtml = `
    <div class="input-group mb-2">
        <input type="file" name="other_images[]" class="form-control image-input" accept="image/*">
        <button type="button" class="btn btn-danger" onclick="removeInput(this)">
            <i class="bi bi-trash"></i>
        </button>
    </div>`;
    document.getElementById('image_container').insertAdjacentHTML('beforeend', inputHtml);
}

function removeInput(button) {
    const inputGroup = button.closest('.input-group');
    inputGroup.remove();
    updateCarousel();
}

function updateCarousel() {
    const carouselInner = document.querySelector('#carouselExampleIndicators3 .carousel-inner');
    const inputs = document.querySelectorAll('#image_container input.image-input');
    const selectedFiles = Array.from(inputs).filter(input => input.files && input.files.length > 0);

    if (selectedFiles.length > 0) {
        // Clear existing carousel items
        carouselInner.innerHTML = '';

        // Add new items from selected files
        selectedFiles.forEach((input, index) => {
            const reader = new FileReader();
            reader.onload = function (event) {
                const isActive = index === 0 ? 'active' : '';
                const carouselItem = `
                    <div class="carousel-item ${isActive}">
                        <img class="d-block w-75 mlc" src="${event.target.result}" alt="Selected image ${index + 1}">
                    </div>`;
                carouselInner.insertAdjacentHTML('beforeend', carouselItem);

                // Initialize/reinitialize carousel after first image is added
                if (index === 0) {
                    $('#carouselExampleIndicators3').carousel();
                }
            };
            reader.readAsDataURL(input.files[0]);
        });
    } else {
        // Restore original slider images when no files are selected
        carouselInner.innerHTML = `
            <div class="carousel-item active">
                <img class="d-block w-75 mlc" src="assets/templates/img/slider/img1.png" alt="First slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-75 mlc" src="assets/templates/img/slider/img2.png" alt="Second slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-75 mlc" src="assets/templates/img/slider/img3.png" alt="Third slide">
            </div>`;
        $('#carouselExampleIndicators3').carousel();
    }
}

// Event listener for file inputs
document.getElementById('image_container').addEventListener('change', function (e) {
    if (e.target && e.target.matches('input.image-input[type="file"]')) {
        updateCarousel();
    }
});

// Initialize carousel with original images on page load
document.addEventListener('DOMContentLoaded', function () {
    $('#carouselExampleIndicators3').carousel();
});




// other web links functionality


let linkCounter = 0;

// Function to add new link fields
function addWebLink(title = '', subtitle = '', url = '') {
    linkCounter++;
    const linkId = 'link_' + linkCounter;

    const linkHtml = `
    <div class="mb-3 link-group" id="${linkId}">
        <div class="d-flex justify-content-between align-items-center">
            <label>Link ${linkCounter}</label>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeLink('${linkId}')">
                <i class="bi bi-trash"></i>
            </button>
        </div>
        <input type="text" class="form-control mb-1 link-title" name="other_links[${linkCounter}][title]" placeholder="Title" value="${title}" oninput="updatePreview()">
        <input type="text" class="form-control mb-1 link-subtitle" name="other_links[${linkCounter}][subtitle]" placeholder="Sub-title" value="${subtitle}" oninput="updatePreview()">
        <input type="url" class="form-control link-url" name="other_links[${linkCounter}][url]" placeholder="http://example.com" value="${url}" oninput="updatePreview()">
    </div>`;

    document.getElementById('webLinksContainer').insertAdjacentHTML('beforeend', linkHtml);
    updatePreview();
}

// Function to remove a link
function removeLink(id) {
    document.getElementById(id).remove();
    // Renumber remaining links
    const labels = document.querySelectorAll('#webLinksContainer label');
    labels.forEach((label, index) => {
        label.textContent = `Link ${index + 1}`;
    });
    linkCounter = labels.length;
    updatePreview();
}

// Function to update the live preview
function updatePreview() {
    const previewContainer = document.getElementById('linksPreview');
    // Clear existing dynamic links but keep the title section
    const existingLinks = previewContainer.querySelectorAll('.links__inner');
    existingLinks.forEach(link => link.remove());

    const linkGroups = document.querySelectorAll('.link-group');

    linkGroups.forEach(group => {
        const title = group.querySelector('.link-title').value || 'Title';
        const subtitle = group.querySelector('.link-subtitle').value || 'Sub-title';
        const url = group.querySelector('.link-url').value || '#';

        const linkHtml = `
        <a href="${url}" class="links__inner">
            <div class="icon">
                <i class="far fa-link"></i>
            </div>
            <div class="text">
                <h2>${title}</h2>
                <p>${subtitle}</p>
            </div>
        </a>`;

        // Insert after the title div
        const titleDiv = previewContainer.querySelector('.title');
        titleDiv.insertAdjacentHTML('afterend', linkHtml);
    });
}

// Initialize with empty state
document.addEventListener('DOMContentLoaded', function () {
    updatePreview();
});