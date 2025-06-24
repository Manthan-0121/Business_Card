<?php
include "./includes/header.php";

if ($_SESSION['role'] == "1") {
    echo "<script>window.location.href = 'index.php';</script>";
}
?>

<?php
// Assuming you already have a database connection ($conn)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic Information
    $name = $_POST['business_name'] ?? '';
    $about = $_POST['about'] ?? '';
    $contact = $_POST['business_contact'] ?? '';
    $email = $_POST['business_email'] ?? '';
    $category_id = $_POST['business_category'] ?? '';

    // Address Information
    $address_line1 = $_POST['address_line1'] ?? '';
    $address_line2 = $_POST['address_line2'] ?? '';
    $city = $_POST['city'] ?? '';
    $state = $_POST['state'] ?? '';
    $country = $_POST['country'] ?? '';
    $pincode = $_POST['pincode'] ?? '';

    // Process logo upload
    $logo_path = '';
    if (isset($_FILES['logo_input']) && $_FILES['logo_input']['error'] === UPLOAD_ERR_OK) {
        $logo_tmp_name = $_FILES['logo_input']['tmp_name'];
        $logo_name = basename($_FILES['logo_input']['name']);
        $logo_ext = strtolower(pathinfo($logo_name, PATHINFO_EXTENSION));
        $allowed_ext = ['png', 'jpeg', 'jpg', 'gif'];

        if (in_array($logo_ext, $allowed_ext)) {
            $logo_new_name = uniqid('logo_', true) . '.' . $logo_ext;
            $logo_destination = 'assets/img/business_logo/' . $logo_new_name;

            if (move_uploaded_file($logo_tmp_name, $logo_destination)) {
                $logo_path = $logo_destination;
            }
        }
    }

    // Process slider images
    $slider_images = [];
    if (!empty($_FILES['slider_images'])) {
        foreach ($_FILES['slider_images']['name'] as $key => $name) {
            if ($_FILES['slider_images']['error'][$key] === UPLOAD_ERR_OK) {
                $tmp_name = $_FILES['slider_images']['tmp_name'][$key];
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                $allowed_ext = ['png', 'jpeg', 'jpg', 'gif'];

                if (in_array($ext, $allowed_ext)) {
                    $new_name = uniqid('slider_', true) . '.' . $ext;
                    $destination = 'assets/img/slider_img/' . $new_name;

                    if (move_uploaded_file($tmp_name, $destination)) {
                        $slider_images[] = $destination;
                    }
                }
            }
        }
    }

    // Process social links (assuming they're sent as arrays)
    $social_links = [];
    if (!empty($_POST['platform_ids'])) {
        foreach ($_POST['platform_ids'] as $key => $platform_id) {
            if (!empty($platform_id)) {
                $social_links[] = [
                    'platform_id' => $platform_id,
                    'url' => $_POST['platform_links'][$platform_id] ?? ''
                ];
            }
        }
    }

    // other links
    $other_links = [];
    if (!empty($_POST['other_links'])) {
        foreach ($_POST['other_links'] as $link) {
            $other_links[] = [
                'title' => $link['title'] ?? '',
                'sub_title' => $link['subtitle'] ?? '',
                'url' => $link['url']
            ];
        }
    }

    $business_info_insert_sql = "INSERT INTO tbl_business_info (`name`, `business_category_id`, `user_id`, `description`, `logo`, `contact_no`, `email`, `link_token`, `address_line_1`, `address_line_2`, `city`, `state`, `zip`, `country`) VALUES (:name, :business_category_id, :user_id, :description, :logo, :contact_no, :email, :link_token, :address_line_1, :address_line_2, :city, :state, :zip, :country)";

    $stmt = $conn->prepare($business_info_insert_sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':business_category_id', $category_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $_SESSION['uid'], PDO::PARAM_INT);
    $stmt->bindParam(':description', $about, PDO::PARAM_STR);
    $stmt->bindParam(':logo', $logo_path, PDO::PARAM_STR);
    $stmt->bindParam(':contact_no', $contact, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $link_token = bin2hex(random_bytes(16)); // Generate a random token
    $stmt->bindParam(':link_token', $link_token, PDO::PARAM_STR);
    $stmt->bindParam(':address_line_1', $address_line1, PDO::PARAM_STR);
    $stmt->bindParam(':address_line_2', $address_line2, PDO::PARAM_STR);
    $stmt->bindParam(':city', $city, PDO::PARAM_STR);
    $stmt->bindParam(':state', $state, PDO::PARAM_STR);
    $stmt->bindParam(':zip', $pincode, PDO::PARAM_STR);
    $stmt->bindParam(':country', $country, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $business_last_insert_id = $conn->lastInsertId();
        // $business_last_insert_id = 31; // For testing purposes, using a static ID

        //     // Insert social links
        $social_links_insert_sql = "INSERT INTO tbl_social_links (social_category_id, business_info_id, link) VALUES (:social_category_id, :business_info_id, :link)";
        for ($i = 0; $i < count($social_links); $i++) {
            $social_stmt = $conn->prepare($social_links_insert_sql);
            $social_stmt->bindParam(':social_category_id', $social_links[$i]['platform_id'], PDO::PARAM_INT);
            $social_stmt->bindParam(':business_info_id', $business_last_insert_id, PDO::PARAM_INT);
            $social_stmt->bindParam(':link', $social_links[$i]['url'], PDO::PARAM_STR);
            $social_stmt->execute();
        }
        $social_last_insert_id = $conn->lastInsertId();

        // insert other images
        $tbl_media_insert_sql = "INSERT INTO tbl_media (`image`, `business_info_id`) VALUES (:image, :business_info_id)";
        $media_stmt = $conn->prepare($tbl_media_insert_sql);
        if (isset($_FILES['other_images']) && !empty($_FILES['other_images']['name'][0])) {
            foreach ($_FILES['other_images']['name'] as $key => $name) {
                if ($_FILES['other_images']['error'][$key] === UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES['other_images']['tmp_name'][$key];
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    $allowed_ext = ['png', 'jpeg', 'jpg', 'gif'];
                    $new_name = uniqid('other_', true) . '.' . $ext;
                    $destination = 'assets/img/business_other/' . $new_name;
                    if (move_uploaded_file($tmp_name, $destination)) {
                        $media_stmt->bindParam(':image', $new_name, PDO::PARAM_STR);
                        $media_stmt->bindParam(':business_info_id', $business_last_insert_id, PDO::PARAM_INT);
                        $media_stmt->execute();
                    }
                }
            }
        }

        // Insert other links
        $tbl_other_links_insert_sql = "INSERT INTO tbl_other_links (`business_info_id`, `link_title`, `link_sub_title`, `link`) VALUES (:business_info_id, :link_title, :link_sub_title, :link)";
        $web_stmt = $conn->prepare($tbl_other_links_insert_sql);
        foreach ($other_links as $web_link) {

            $web_stmt->bindParam(':business_info_id', $business_last_insert_id, PDO::PARAM_INT);
            $web_stmt->bindParam(':link_title', $web_link['title'], PDO::PARAM_STR);
            $web_stmt->bindParam(':link_sub_title', $web_link['sub_title'], PDO::PARAM_STR);
            $web_stmt->bindParam(':link', $web_link['url'], PDO::PARAM_STR);
            $web_stmt->execute();
        }
        // echo "<script>alert('Business card created successfully!');</script>";
        $_SESSION['success'] = "Business card created successfully!";
        echo "<script>window.location.href = 'show_cards.php';</script>";
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}

?>

<!-- business card css -->
<!-- Bootstrap css -->
<link href="assets/templates/css/bootstrap.min.css" media="screen" rel="stylesheet" />
<!-- Slick slider css -->
<link rel="stylesheet" href="assets/templates/css/slick.css" />
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
<style>
    .mlc {
        margin-left: 95px;
    }
</style>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-4">
                    <form method="post" id="business_card_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
                        <div class="card">
                            <div class="card-header">
                                <h4>Create Business Card</h4>
                            </div>
                            <div class="card-body">
                                <hr>
                                <label>Basic Information</label>
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" oninput="business_name_preview()" name="business_name" id="business_name_l" class="form-control">

                                    <label>About</label>
                                    <textarea name="about" oninput="business_about()" class="form-control" id="business_about_l"></textarea>

                                    <label>Contact No</label>
                                    <input type="text" class="form-control" oninput="business_contact_preview()" name="business_contact" id="business_contact_l">

                                    <label>Email</label>
                                    <input type="email" name="business_email" class="form-control" id="business_email_l" oninput="business_email_preview()">

                                    <label>Logo</label>
                                    <input type="file" class="form-control" name="logo_input" id="logo_input" accept="image/png, image/jpeg, image/gif" onchange="previewLogo(this)">

                                    <label>Business Category</label>
                                    <select class="form-control" id="business_category" id="business_category" onchange="updateCategoryPreview()" name="business_category">
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
                                    <input type="text" class="form-control" placeholder="Address line 1" id="address_line1" oninput="updateAddressPreview()" name="address_line1">
                                    <input type="text" class="form-control mt-1" placeholder="Address line 2" id="address_line2" oninput="updateAddressPreview()" name="address_line2">
                                    <input type="text" class="form-control mt-1" placeholder="City" id="city" oninput="updateAddressPreview()" name="city">
                                    <input type="text" class="form-control mt-1" placeholder="State" id="state" oninput="updateAddressPreview()" name="state">
                                    <input type="text" class="form-control mt-1" placeholder="Country" id="country" oninput="updateAddressPreview()" name="country">
                                    <input type="text" class="form-control mt-1" placeholder="Pin-code" id="pincode" oninput="updateAddressPreview()" name="pincode">
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

                                <label>Other Images</label>
                                <div class="form-group" id="image_container">
                                </div>

                                <button type="button" class="btn btn-primary" onclick="addImageInput()"><i class="bi bi-plus"></i></button>

                                <hr>
                                <!-- Input Form Section -->
                                <label>Web Links</label>
                                <div class="form-group" id="webLinksContainer">
                                    <!-- Dynamic links will be added here -->
                                </div>
                                <button type="button" class="btn btn-primary mt-2" onclick="addWebLink()">
                                    <i class="bi bi-plus"></i>
                                </button>
                                <hr>
                            </div>
                            <div class="card-footer text-right">
                                <input type="submit" class="btn btn-primary mr-1" value="Save" id="save_button">
                            </div>
                        </div>
                    </form>
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
                            <ul id="social_links_default">
                                <?php
                                $sel_sql = "SELECT icon FROM `tbl_icons` LIMIT 6";
                                $sel_res = $conn->prepare($sel_sql);
                                $sel_res->execute();
                                $sel_res = $sel_res->fetchAll(pdo::FETCH_ASSOC);

                                foreach ($sel_res as $row) {
                                    echo '<li><a href="#" target="_blank"><img src="assets/templates/img/social/' . $row['icon'] . '" alt="" /></a></li>';
                                }
                                ?>
                            </ul>
                            <ul id="social_links_custom">
                                <!-- Custom social links will be added here dynamically -->
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

                        <div id="carouselExampleIndicators3" class="carousel slide text-center" data-ride="carousel">
                            <div class="carousel-inner ">
                                <div class="carousel-item active">
                                    <img class="d-block w-75 mlc" src="assets/templates/img/slider/img1.png" alt="First slide">
                                </div>
                                <div class="carousel-item">
                                    <img class="d-block w-75 mlc" src="assets/templates/img/slider/img2.png" alt="Second slide">
                                </div>
                                <div class="carousel-item">
                                    <img class="d-block w-75 mlc" src="assets/templates/img/slider/img3.png" alt="Third slide">
                                </div>
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleIndicators3" role="button"
                                data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleIndicators3" role="button"
                                data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>

                        <div class="links" id="linksPreview">
                            <div class="title">
                                <h2>Web Links</h2>
                                <p>Description</p>
                            </div>
                            <!-- Dynamic preview links will be added here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Jquery Library File -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<!-- Bootstrap js file -->
<script src="assets/templates/js/bootstrap.min.js"></script>
<!-- Swiper slider js file -->
<script src="assets/templates/js/swiper.min.js"></script>
<!-- Wow animation js file -->
<script src="assets/templates/js/wow.min.js"></script>
<!-- Main js file -->

<script src="assets/js/preview.js"></script>

<script src="assets/js/business_card.js"></script>

<?php

include "./includes/footer.php";

?>