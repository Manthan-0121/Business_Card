<?php
include "./includes/header.php";
?>

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

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create Business Card</h4>
                        </div>
                        <div class="card-body">
                            <hr>
                            <label>Basic Information</label>
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" oninput="business_name()" id="business_name_l" class="form-control" required>

                                <label>About</label>
                                <textarea name="about" oninput="business_about()" class="form-control" id="business_about_l" required></textarea>

                                <label>Contact No</label>
                                <input type="text" class="form-control" oninput="business_contact()" id="business_contact_l">

                                <label>Email</label>
                                <input type="email" class="form-control" id="business_email_l" oninput="business_email()">

                                <label>Logo</label>
                                <input type="file" class="form-control" id="logo_input" accept="image/png, image/jpeg, image/gif" onchange="previewLogo(this)">

                                <label>Business Category</label>
                                <select class="form-control" id="business_category" id="business_category" onchange="updateCategoryPreview()">
                                    <?php
                                    $sel_sql = "SELECT * FROM tbl_business_category WHERE status = 1";
                                    $sel_res = $conn->prepare($sel_sql);
                                    $sel_res->execute();
                                    $sel_res = $sel_res->fetchAll(pdo::FETCH_ASSOC);
                                    foreach ($sel_res as $row) {
                                        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <hr>
                            <label>Address</label>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Address line 1" id="address_line1" oninput="updateAddressPreview()">
                                <input type="text" class="form-control mt-1" placeholder="Address line 2" id="address_line2" oninput="updateAddressPreview()">
                                <input type="text" class="form-control mt-1" placeholder="City" id="city" oninput="updateAddressPreview()">
                                <input type="text" class="form-control mt-1" placeholder="State" id="state" oninput="updateAddressPreview()">
                                <input type="text" class="form-control mt-1" placeholder="Country" id="country" oninput="updateAddressPreview()">
                                <input type="text" class="form-control mt-1" placeholder="Pin-code" id="pincode" oninput="updateAddressPreview()">
                            </div>

                            <hr>
                            <label>Social links</label>
                            <div class="form-group">
                                <div class="form-group">
                                    <div id="platformInputs" class="mt-3"></div>

                                    <label>Select Platform</label>
                                    <select class="form-control" id="platformDropdown">
                                        <option value="">Select Platform</option>
                                        <?php
                                        $sel_sql = "SELECT * FROM tbl_social_category WHERE status = 1";
                                        $sel_res = $conn->prepare($sel_sql);
                                        $sel_res->execute();
                                        $sel_res = $sel_res->fetchAll(pdo::FETCH_ASSOC);

                                        foreach ($sel_res as $row) {
                                            echo '<option value="' . $row['id'] . '" data-name="' . $row['platform_name'] . '">' . $row['platform_name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                            </div>
                            <hr>
                            <label>Web Links</label>
                            <div class="form-group">
                                <label>Link 1</label>
                                <input type="text" class="form-control" placeholder="Title">
                                <input type="text" class="form-control mt-1" placeholder="http://example.com">
                                <label>Link 2</label>
                                <input type="text" class="form-control" placeholder="Title">
                                <input type="text" class="form-control mt-1" placeholder="http://example.com">
                                <label>Link 3</label>
                                <input type="text" class="form-control" placeholder="Title">
                                <input type="text" class="form-control mt-1" placeholder="http://example.com">
                            </div>
                            <hr>
                            <label>Other Images</label>
                            <div class="form-group" id="image_container">
                                <div class="input-group mb-2">
                                    <input type="file" class="form-control">
                                    <button type="button" class="btn btn-danger" onclick="removeInput(this)"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>

                            <button type="button" class="btn btn-primary" onclick="addImageInput()"><i class="bi bi-plus"></i></button>

                            <hr>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary mr-1" type="submit">Submit</button>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <!-- preview card -->
                    <div class="main preview">
                        <div class="banner">
                            <img id="logo_preview" src="assets/templates/img/home/bg-image.png" alt="Logo Preview">
                        </div>

                        <div class="details">
                            <h2 id="business_name_r">John Doe</h2>
                            <h3 id="business_category_preview">Quality Assurance Analyst</h3>
                            <p><span id="address_preview">Rajkot, Gujarat, India</span></p>
                            <!-- <p>Just Digital Gurus | <span>Rajkot, Gujarat, India</span></p> -->
                        </div>

                        <div class="social-media">
                            <ul>
                                <?php
                                    $sel_sql = "SELECT icon FROM `tbl_icons` LIMIT 6";
                                    $sel_res = $conn->prepare($sel_sql);
                                    $sel_res->execute();
                                    $sel_res = $sel_res->fetchAll(pdo::FETCH_ASSOC);
                                    foreach ($sel_res as $row) {
                                        echo '<li><a href="#" target="_blank"><img src="assets/templates/img/social/' . $row['icon'] . '" alt="" /></a></li>';
                                    }
                                ?>
                                <!-- <li><a id="link_whatsapp" href="#" target="_blank"><img src="assets/templates/img/social/1.png" alt="" /></a></li>
                                <li><a id="link_linkedin" href="#" target="_blank"><img src="assets/templates/img/social/2.png" alt="" /></a></li>
                                <li><a id="link_instagram" href="#" target="_blank"><img src="assets/templates/img/social/3.png" alt="" /></a></li>
                                <li><a id="link_twitter" href="#" target="_blank"><img src="assets/templates/img/social/4.png" alt="" /></a></li>
                                <li><a id="link_youtube" href="#" target="_blank"><img src="assets/templates/img/social/5.png" alt="" /></a></li>
                                <li><a id="link_skype" href="#" target="_blank"><img src="assets/templates/img/social/6.png" alt="" /></a></li> -->
                            </ul>
                        </div>

                        <div class="about">
                            <h2>About Me</h2>
                            <div id="business_about_r">
                                <p>
                                    I'm Leslie Murphy, a seasoned Business Development Executive
                                    with a proven track record of driving revenue growth and
                                    forging strategic partnerships. With 4 years of experience,
                                    I am committed to helping businesses thrive in today's
                                    competitive landscape.
                                </p>
                            </div>
                        </div>

                        <div class="contact">
                            <h2>
                                <img src="assets/img/home/Component.png" alt="" /> Contact
                                Us
                            </h2>
                            <div class="contact__inner">
                                <a href="tel:+9876543211" id="business_contact_r_link" class="box">
                                    <div class="icon"><i class="bi bi-telephone"></i></div>
                                    <div class="text">
                                        <h3>Call us</h3>
                                        <p id="business_contact_r">+ 9876543211</p>
                                    </div>
                                </a>
                                <a href="mailto:contactme@domain.com" class="box" id="business_email_r_link">
                                    <div class="icon"><i class="bi bi-envelope"></i></div>
                                    <div class="text">
                                        <h3>E-mail</h3>
                                        <p id="business_email_r">contactme@domain.com</p>
                                    </div>
                                </a>
                                <div class="box">
                                    <div class="icon"><i class="bi bi-geo-alt"></i></div>
                                    <div class="text">
                                        <h3>Address</h3>
                                        <p id="full_address">
                                            738, R.K. World Tower,<br />
                                            Ring Road, Rajkot - 360005<br />
                                            Gujarat, India
                                        </p>
                                        <a href="#" class="box-btn" id="direction_btn" target="_blank">
                                            <i class="bi bi-cursor"></i> Direction
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="imgs" id="preview_gallery">
                            <div class="img"><img src="assets/templates/img/home/img1.png" alt="" /></div>
                            <div class="img"><img src="assets/templates/img/home/img1.png" alt="" /></div>
                            <div class="img"><img src="assets/templates/img/home/img2.png" alt="" /></div>
                            <div class="img"><img src="assets/templates/img/home/img2.png" alt="" /></div>
                            <div class="img"><img src="assets/templates/img/home/img3.png" alt="" /></div>
                            <div class="img"><img src="assets/templates/img/home/img3.png" alt="" /></div>
                            <div class="img"><img src="assets/templates/img/home/img3.png" alt="" /></div>
                            <div class="img"><img src="assets/templates/img/home/img3.png" alt="" /></div>
                        </div>

                        <div class="links">
                            <div class="title">
                                <h2>Web Links</h2>
                                <p>Description</p>
                            </div>
                            <a href="#" class="links__inner">
                                <div class="icon">
                                    <i class="far fa-link"></i>
                                </div>
                                <div class="text">
                                    <h2>Title</h2>
                                    <p>Sub-title</p>
                                </div>
                            </a>
                            <a href="#" class="links__inner">
                                <div class="icon">
                                    <i class="far fa-link"></i>
                                </div>
                                <div class="text">
                                    <h2>Title</h2>
                                    <p>Sub-title</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>




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
<script src="assets/js/preview.js"></script>

<script>
    const menuLinks = document.querySelectorAll(".menu-link");

    menuLinks.forEach((link) => {
        link.addEventListener("click", () => {
            menuLinks.forEach((link) => {
                link.classList.remove("is-active");
            });
            link.classList.add("is-active");
        });
    });

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

<?php

include "./includes/footer.php";

?>

<script>
    $(document).ready(function() {
        $('#platformDropdown').on('change', function() {
            let selectedOption = $(this).find('option:selected');
            let id = selectedOption.val();
            let name = selectedOption.data('name');
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
                $('#platformInputs').append(inputHtml);



                // Remove selected option from dropdown
                selectedOption.remove();
            }
        });

        $('#platformInputs').on('click', '.remove-platform-btn', function() {
            let inputGroup = $(this).closest('.platform-input');
            let id = inputGroup.data('id');
            let name = inputGroup.find('input[placeholder]').attr('placeholder').replace(' Link', '');

            // Remove input field
            inputGroup.remove();

            // Re-add to dropdown
            $('#platformDropdown').append(`<option value="${id}" data-name="${name}">${name}</option>`);
        });

    });
</script>