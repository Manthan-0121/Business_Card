//business name variable 
let business_name_l = document.getElementById("business_name_l");
let business_name_r = document.getElementById("business_name_r");

//business about variable 
let business_about_l = document.getElementById("business_about_l");
let business_about_r = document.getElementById("business_about_r");

//business contact variable
let business_contact_l = document.getElementById("business_contact_l");
let business_contact_r = document.getElementById("business_contact_r");
let business_contact_r_link = document.getElementById("business_contact_r_link");

//business email variable
let business_email_l = document.getElementById("business_email_l");
let business_email_r = document.getElementById("business_email_r");
let business_email_r_link = document.getElementById("business_email_r_link");

//business name value change
function business_name() {
    if (business_name_l.value == "") {
        business_name_r.innerHTML = "John Doe";
    } else {
        business_name_r.innerHTML = business_name_l.value;
    }
}

//business about value change
function business_about() {
    if (business_about_l.value == "") {
        business_about_r.innerHTML = `<p>
                                    I'm Leslie Murphy, a seasoned Business Development Executive
        with a proven track record of driving revenue growth and
                                    forging strategic partnerships.With 4 years of experience,
            I am committed to helping businesses thrive in today's
                                    competitive landscape.
                                </p > `;
    } else {
        business_about_r.innerHTML = business_about_l.value;
    }
}

// business contact value change
function business_contact() {
    if (business_contact_l.value == "") {
        business_contact_r.innerHTML = "+ 9876543211";
        business_contact_r_link.href = "tel:+9876543211";
    } else {
        business_contact_r.innerHTML = business_contact_l.value;
        business_contact_r_link.href = "tel:+" + business_contact_l.value;
    }
}

//business email value change
function business_email() {
    if (business_email_l.value == "") {
        business_email_r.innerHTML = "contactme@domain.com";
        business_email_r_link.href = "mailto:contactme@domain.com";
    } else {
        business_email_r.innerText = business_email_l.value;
        business_email_r_link.href = "mailto:" + business_email_l.value;
    }
}

// business image change
function previewLogo(input) {
    const file = input.files[0];
    const preview = document.getElementById("logo_preview");

    if (file) {
        const fileType = file.type;
        const validTypes = ["image/jpeg", "image/png", "image/gif"];

        if (validTypes.includes(fileType)) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            alert("Please select a valid image file (JPG, PNG, GIF).");
            input.value = ""; // reset file input
            preview.src = "assets/templates/img/home/bg-image.png"; // reset preview
        }
    }
}

//business category value change
function updateCategoryPreview() {
    const selectedCategory = document.getElementById("business_category");
    const previewHeading = document.getElementById("business_category_preview");

    previewHeading.innerText = selectedCategory.options[selectedCategory.selectedIndex].text;
}

// business address change
function updateAddressPreview() {
    const address_line1 = document.getElementById("address_line1").value;
    const address_line2 = document.getElementById("address_line2").value;
    const city = document.getElementById("city").value;
    const state = document.getElementById("state").value;
    const country = document.getElementById("country").value;
    const pincode = document.getElementById("pincode").value;
    const parts = [address_line1, address_line2, city, state, country, pincode].filter(Boolean);
    const preview = parts.join(", ");

    document.getElementById("address_preview").innerText = preview || "Rajkot, Gujarat, India";

    let fullAddress = '';
    if (address_line1) fullAddress += address_line1 + ',<br />';
    if (address_line2) fullAddress += address_line2 + ',<br />';
    if (city || pincode) fullAddress += (city ? city : '') + (pincode ? ' - ' + pincode : '') + '<br />';
    if (state || country) fullAddress += [state, country].filter(Boolean).join(', ');

    document.getElementById("full_address").innerHTML = fullAddress || `
        738, R.K. World Tower,<br />
        Ring Road, Rajkot - 360005<br />
        Gujarat, India`;
}


// business address change
function updateAddressPreview1() {
    // Get all address input values
    const address_line1 = document.getElementById("address_line1").value;
    const address_line2 = document.getElementById("address_line2").value;
    const city = document.getElementById("city").value;
    const state = document.getElementById("state").value;
    const country = document.getElementById("country").value;
    const pincode = document.getElementById("pincode").value;

    // Build the full address with line breaks
    let fullAddress = '';

    if (address_line1) fullAddress += address_line1 + '<br>';
    if (address_line2) fullAddress += address_line2 + '<br>';

    // Combine city and pincode if either exists
    if (city || pincode) {
        fullAddress += city || '';
        if (city && pincode) fullAddress += ' - ';
        if (pincode) fullAddress += pincode;
        fullAddress += '<br>';
    }

    // Combine state and country if either exists
    if (state || country) {
        if (state && country) {
            fullAddress += state + ', ' + country;
        } else {
            fullAddress += state || country;
        }
    }

    // Set default if no address entered
    if (!fullAddress.trim()) {
        fullAddress = '123 rk road,<br>Shital park,<br>Rajkot - 360005<br>Gujarat, India';
    }

    // Update the preview element
    const previewElement = document.getElementById("full_address");
    if (previewElement) {
        previewElement.innerHTML = fullAddress;
    }
}

// business social links change

//business add and remove image field  in below
// function addImageInput() {
//     const container = document.getElementById("image_container");

//     const wrapper = document.createElement("div");
//     wrapper.className = "input-group mb-2";

//     const input = document.createElement("input");
//     input.type = "file";
//     input.className = "form-control";

//     const removeBtn = document.createElement("button");
//     removeBtn.type = "button";
//     removeBtn.className = "btn btn-danger";
//     removeBtn.innerHTML = "<i class=\"bi bi-trash\"></i>";
//     removeBtn.onclick = function () {
//         removeInput(removeBtn);
//     };

//     wrapper.appendChild(input);
//     wrapper.appendChild(removeBtn);
//     container.appendChild(wrapper);
// }

// function removeInput(button) {
//     const wrapper = button.parentNode;
//     wrapper.remove();
// }

function addImageInput() {
    const container = document.getElementById("image_container");

    const wrapper = document.createElement("div");
    wrapper.className = "input-group mb-2";

    const input = document.createElement("input");
    input.type = "file";
    input.className = "form-control";
    input.onchange = function () {
        previewImages(input);
    };

    const removeBtn = document.createElement("button");
    removeBtn.type = "button";
    removeBtn.className = "btn btn-danger";
    removeBtn.innerHTML = "<i class=\"bi bi-trash\"></i>";
    removeBtn.onclick = function () {
        removeInput(removeBtn);
    };

    wrapper.appendChild(input);
    wrapper.appendChild(removeBtn);
    container.appendChild(wrapper);
}

function removeInput(button) {
    const wrapper = button.parentNode;
    wrapper.remove();
}

let hasUploaded = false;

function previewImages(input) {
    const files = input.files;
    const previewContainer = document.getElementById("preview_gallery");

    if (!hasUploaded && files.length > 0) {
        previewContainer.innerHTML = "";
        hasUploaded = true;
    }

    for (let file of files) {
        if (file.type.startsWith("image/")) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const div = document.createElement("div");
                div.className = "img";
                const img = document.createElement("img");
                img.src = e.target.result;
                img.alt = "";
                div.appendChild(img);
                previewContainer.appendChild(div);
            };
            reader.readAsDataURL(file);
        }
    }
}