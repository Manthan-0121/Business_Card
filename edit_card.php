<?php
include "./includes/header.php";

if ($_SESSION['role'] == "1") {
    echo "<script>window.location.href = 'index.php';</script>";
}
?>
<?php
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $chk_token_sql = "SELECT id FROM tbl_business_info WHERE link_token = ?";
    $chk_token_stmt = $conn->prepare($chk_token_sql);
    $chk_token_stmt->bindParam(1, $token, PDO::PARAM_STR);
    $chk_token_stmt->execute();
    if ($chk_token_stmt->rowCount() > 0) {
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

        <?php
        $res = $chk_token_stmt->fetch(PDO::FETCH_ASSOC);
        $business_id = $res['id'];

        $full_sel_sql = "SELECT buss.name AS business_name, buss.description AS business_about, buss.contact_no AS business_contact, buss.email AS business_email, buss.address_line_1 AS business_address1, buss.address_line_2 AS business_address2, buss.city AS business_city, buss.state AS business_state, buss.zip AS business_zip, buss.country AS business_country, buss.logo AS business_logo, bucat.id AS business_category_id,bucat.name AS business_category FROM tbl_business_info AS buss INNER JOIN tbl_business_category AS bucat ON bucat.id = buss.business_category_id WHERE buss.id = ?";
        $full_sel_stmt = $conn->prepare($full_sel_sql);
        $full_sel_stmt->bindParam(1, $business_id, PDO::PARAM_INT);
        $full_sel_stmt->execute();
        $full_res = $full_sel_stmt->fetch(PDO::FETCH_ASSOC);
        ?>

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
                                            <input type="text" oninput="business_name_preview()" name="business_name" id="business_name_l" value="<?php echo htmlspecialchars($full_res['business_name']); ?>" class="form-control">

                                            <label>About</label>
                                            <textarea name="about" oninput="business_about()" class="form-control" id="business_about_l"><?php echo htmlspecialchars($full_res['business_about']); ?></textarea>

                                            <label>Contact No</label>
                                            <input type="text" class="form-control" oninput="business_contact_preview()" name="business_contact" id="business_contact_l" value="<?php echo htmlspecialchars($full_res['business_contact']); ?>">

                                            <label>Email</label>
                                            <input type="email" name="business_email" class="form-control" id="business_email_l" oninput="business_email_preview()" value="<?php echo htmlspecialchars($full_res['business_email']); ?>">

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
                                                    echo '<option value="' . $row['id'] . ' " ' . ($row['id'] == $full_res['business_category_id'] ? 'selected' : '') . '>' . $row['name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <hr>
                                        <label>Address</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Address line 1" id="address_line1" oninput="updateAddressPreview()" value="<?php echo htmlspecialchars($full_res['business_address1']); ?>" name="address_line1">
                                            <input type="text" class="form-control mt-1" placeholder="Address line 2" id="address_line2" oninput="updateAddressPreview()" value="<?php echo htmlspecialchars($full_res['business_address2']); ?>" name="address_line2">
                                            <input type="text" class="form-control mt-1" placeholder="City" id="city" oninput="updateAddressPreview()" value="<?php echo htmlspecialchars($full_res['business_city']); ?>" name="city">
                                            <input type="text" class="form-control mt-1" placeholder="State" id="state" oninput="updateAddressPreview()" value="<?php echo htmlspecialchars($full_res['business_state']); ?>" name="state">
                                            <input type="text" class="form-control mt-1" placeholder="Country" id="country" oninput="updateAddressPreview()" value="<?php echo htmlspecialchars($full_res['business_country']); ?>" name="country">
                                            <input type="text" class="form-control mt-1" placeholder="Pin-code" id="pincode" oninput="updateAddressPreview()" value="<?php echo htmlspecialchars($full_res['business_zip']); ?>" name="pincode">
                                        </div>

                                        <hr>
                                        <label>Social links</label>
                                        <div class="form-group">
                                            <div class="form-group">
                                                <div id="platformInputs" class="mt-3">
                                                    <?php
                                                    $sel_social_sql = "SELECT sc.id as sc_id, sc.platform_name,sl.link AS social_link, i.icon AS social_icon FROM tbl_social_links sl INNER JOIN tbl_social_category sc ON sc.id = sl.social_category_id LEFT JOIN tbl_icons i ON i.social_category_id = sc.id WHERE sl.business_info_id = ?";

                                                    $sel_social_stmt = $conn->prepare($sel_social_sql);
                                                    $sel_social_stmt->bindParam(1, $business_id, PDO::PARAM_INT);
                                                    $sel_social_stmt->execute();
                                                    if ($sel_social_stmt->rowCount() > 0) {
                                                        $social_link_ids = [];
                                                        while ($social_res = $sel_social_stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    ?>
                                                            <div class="form-group platform-input" data-id="<?php echo $social_res['sc_id']; ?>">
                                                                <div class="input-group mb-2">
                                                                    <input type="hidden" name="platform_ids[]" value="<?php echo $social_res['sc_id']; ?>">
                                                                    <input type="text" name="platform_links[<?php echo $social_res['sc_id']; ?>]" class="form-control" placeholder="<?php echo $social_res['platform_name']; ?> Link" value="<?php echo $social_res['social_link']; ?>" required>
                                                                    <button type="button" class="btn btn-danger remove-platform-btn">
                                                                        <i class="bi bi-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                    <?php
                                                        $social_link_ids[] = $social_res['sc_id'];
                                                        }
                                                    }
                                                    ?>
                                                </div>

                                                <label>Select Platform</label>
                                                <select class="form-control" id="platformDropdown">
                                                    <option value="">Select Platform</option>
                                                    <?php
                                                    $sel_sql = "SELECT * FROM tbl_social_category WHERE status = 1 AND id NOT IN (SELECT social_category_id FROM tbl_social_links WHERE business_info_id = ?)";
                                                    $sel_res = $conn->prepare($sel_sql);
                                                    $sel_res->bindParam(1, $business_id, PDO::PARAM_INT);
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
                                        <input type="submit" class="btn btn-primary mr-1" value="Update" id="update_button">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-8">
                            <!-- preview card -->
                            <div class="main preview">
                                <div class="banner">
                                    <img id="logo_preview" src="<?php echo $full_res['business_logo'] ?>" alt="Logo Preview">
                                </div>

                                <div class="details">
                                    <h2 id="business_name_r"><?php echo $full_res['business_name'] ?></h2>
                                    <h3 id="business_category_preview"><?php echo $full_res['business_category'] ?></h3>
                                    <p><span id="address_preview"><?php echo $full_res['business_address1'] . ', ' . $full_res['business_city'] . ', ' . $full_res['business_state'] . ', ' . $full_res['business_country'] ?></span></p>
                                    <!-- <p>Just Digital Gurus | <span>Rajkot, Gujarat, India</span></p> -->
                                </div>

                                <div class="social-media">
                                    <ul id="social_links_default">
                                        <?php
                                        // $sel_sql = "SELECT icon FROM `tbl_icons` LIMIT 6";
                                        // $sel_res = $conn->prepare($sel_sql);
                                        // $sel_res->execute();
                                        // $sel_res = $sel_res->fetchAll(pdo::FETCH_ASSOC);

                                        // foreach ($sel_res as $row) {
                                        //     echo '<li><a href="#" target="_blank"><img src="assets/templates/img/social/' . $row['icon'] . '" alt="" /></a></li>';
                                        // }
                                        ?>
                                    </ul>
                                    <ul id="social_links_custom">
                                        <?php
                                        $sel_social_sql = "SELECT sc.id AS sc_id, sc.platform_name, sl.link AS social_link, i.icon AS social_icon FROM tbl_social_links sl INNER JOIN tbl_social_category sc ON sc.id = sl.social_category_id LEFT JOIN tbl_icons i ON i.social_category_id = sc.id WHERE sl.business_info_id = ?";

                                        $sel_social_stmt = $conn->prepare($sel_social_sql);
                                        $sel_social_stmt->bindParam(1, $business_id, PDO::PARAM_INT);
                                        $sel_social_stmt->execute();
                                        if ($sel_social_stmt->rowCount() > 0) {
                                            while ($social_res = $sel_social_stmt->fetch(PDO::FETCH_ASSOC)) {
                                        ?>
                                                <li class="social-icon" data-id="<?php echo $social_res['sc_id'] ?>">
                                                    <a href="<?php echo $social_res['social_link'] ?>" target="_blank">
                                                        <img src="<?php echo BASE_URL . "assets/templates/img/social/" . $social_res['social_icon'] ?>" alt="<?php echo $social_res['platform_name'] ?>" />
                                                    </a>
                                                </li>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>

                                <div class="about">
                                    <h2>About Me</h2>
                                    <div id="business_about_r">
                                        <p>
                                            <?php echo $full_res['business_about'] ?>
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
    } else {
        echo "<script>window.location.href = 'index.php';</script>";
    }
} else {
    echo "<script>window.location.href = 'index.php';</script>";
}
?>
<?php
include "./includes/footer.php";
?>