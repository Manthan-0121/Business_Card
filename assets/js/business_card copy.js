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

document.addEventListener('DOMContentLoaded', function () {
    // Initialize carousel with existing images
    updateCarousel();

    // Add event delegation for file input changes
    document.getElementById('image_container').addEventListener('change', function (e) {
        if (e.target.matches('input[type="file"]')) {
            updateCarousel();
        }
    });
});

function addImageInput() {
    const inputHtml = `
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
    const input = inputGroup.querySelector('input');

    // If it's a DB-stored image (text input), add a hidden field to track deletion
    if (input.type === 'text') {
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'deleted_images[]';
        hiddenInput.value = input.value;
        document.getElementById('image_container').appendChild(hiddenInput);
    }

    inputGroup.remove();
    updateCarousel();
}

function updateCarousel() {
    const carouselInner = document.querySelector('#carouselExampleIndicators3 .carousel-inner');
    carouselInner.innerHTML = '';

    // 1. Add existing DB images (from text inputs)
    const dbImages = document.querySelectorAll('input[name="other_images_text[]"]');
    dbImages.forEach((input, index) => {
        if (input.value) {
            const isActive = index === 0 ? 'active' : '';
            carouselInner.innerHTML += `
                <div class="carousel-item ${isActive}">
                    <img class="d-block w-75 mlc" src="assets/img/business_other/${input.value}" alt="DB Image">
                </div>
            `;
        }
    });

    // 2. Add newly uploaded files (from file inputs)
    const fileInputs = document.querySelectorAll('input[name="other_images[]"]');
    fileInputs.forEach((input, index) => {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const isActive = dbImages.length === 0 && index === 0 ? 'active' : '';
                carouselInner.innerHTML += `
                    <div class="carousel-item ${isActive}">
                        <img class="d-block w-75 mlc" src="${e.target.result}" alt="New Image">
                    </div>
                `;
                // Refresh carousel if first image
                if (carouselInner.querySelectorAll('.carousel-item').length === 1) {
                    $('#carouselExampleIndicators3').carousel();
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    });

    // If no images, show a placeholder
    if (dbImages.length === 0 && fileInputs.length === 0) {
        carouselInner.innerHTML = `
            <div class="carousel-item active">
                <img class="d-block w-75 mlc" src="assets/templates/img/slider/img1.png" alt="No Images">
            </div>
        `;
    }

    // Reinitialize Bootstrap carousel
    $('#carouselExampleIndicators3').carousel();
}



// other web links functionality
// ================= WEB LINKS MANAGEMENT =================
let linkCounter = 0;

// Initialize on page load
document.addEventListener('DOMContentLoaded', function () {
    updateLinksPreview();
});


// Add new link field (with optional existing DB values)
function addWebLink(title = '', subtitle = '', url = '', dbId = '') {
    linkCounter++;
    const linkId = 'link_' + linkCounter;

    const linkHtml = `
    <div class="mb-3 link-group" id="${linkId}" data-db-id="${dbId}">
        <div class="d-flex justify-content-between align-items-center">
            <label>Link ${linkCounter}</label>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeLink('${linkId}')">
                <i class="bi bi-trash"></i>
            </button>
        </div>
        <input type="text" class="form-control mb-1 link-title" 
               name="other_links[${linkCounter}][title]" 
               placeholder="Title" 
               value="${escapeHtml(title)}" 
               oninput="updateLinksPreview()">
        <input type="text" class="form-control mb-1 link-subtitle" 
               name="other_links[${linkCounter}][subtitle]" 
               placeholder="Sub-title" 
               value="${escapeHtml(subtitle)}" 
               oninput="updateLinksPreview()">
        <input type="url" class="form-control link-url" 
               name="other_links[${linkCounter}][url]" 
               placeholder="http://example.com" 
               value="${escapeHtml(url)}" 
               oninput="updateLinksPreview()">
        ${dbId ? `<input type="hidden" name="existing_links[]" value="${dbId}">` : ''}
    </div>`;

    document.getElementById('webLinksContainer').insertAdjacentHTML('beforeend', linkHtml);
    updateLinksPreview();
}

// Remove a link field
function removeLink(id) {
    const linkGroup = document.getElementById(id);
    if (linkGroup.dataset.dbId) {
        const deleteInput = `<input type="hidden" name="deleted_links[]" value="${linkGroup.dataset.dbId}">`;
        document.getElementById('webLinksContainer').insertAdjacentHTML('beforeend', deleteInput);
    }
    linkGroup.remove();
    renumberLinks();
    updateLinksPreview();
}

// Renumber links sequentially after deletion
function renumberLinks() {
    const labels = document.querySelectorAll('#webLinksContainer label');
    labels.forEach((label, index) => {
        label.textContent = `Link ${index + 1}`;
    });
    linkCounter = labels.length;
}

// Update the live preview section
function updateLinksPreview() {
    const previewContainer = document.getElementById('linksPreview');

    // Clear only dynamically generated previews
    const dynamicPreviews = previewContainer.querySelectorAll('.dynamic-preview');
    dynamicPreviews.forEach(preview => preview.remove());

    // Add updated previews for each link
    document.querySelectorAll('.link-group').forEach(group => {
        const title = group.querySelector('.link-title').value || 'Title';
        const subtitle = group.querySelector('.link-subtitle').value || 'Sub-title';
        const url = group.querySelector('.link-url').value || '#';

        const linkHtml = `
        <a href="${escapeHtml(url)}" class="links__inner dynamic-preview" target="_blank">
            <div class="icon">
                <i class="far fa-link"></i>
            </div>
            <div class="text">
                <h2>${escapeHtml(title)}</h2>
                <p>${escapeHtml(subtitle)}</p>
            </div>
        </a>`;

        previewContainer.insertAdjacentHTML('beforeend', linkHtml);
    });
}

// Helper function to prevent XSS
function escapeHtml(unsafe) {
    return unsafe.toString()
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}