<?php
include '../includes/config.php';
if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];
    $query = "SELECT id FROM `tbl_business_info` WHERE `link_token` = ? AND `status` = '1'";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(1, $token, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $business_id = $res['id'];

        $full_sel_sql = "SELECT buss.name AS business_name, buss.description AS business_about, buss.contact_no AS business_contact, buss.email AS business_email, buss.address_line_1 AS business_address1, buss.address_line_2 AS business_address2, buss.city AS business_city, buss.state AS business_state, buss.zip AS business_zip, buss.country AS business_country, buss.logo AS business_logo, bucat.name AS business_category FROM tbl_business_info AS buss INNER JOIN tbl_business_category AS bucat ON bucat.id = buss.business_category_id WHERE buss.id = ?";
        $full_sel_stmt = $conn->prepare($full_sel_sql);
        $full_sel_stmt->bindParam(1, $business_id, PDO::PARAM_INT);
        $full_sel_stmt->execute();
        $full_res = $full_sel_stmt->fetch(PDO::FETCH_ASSOC);
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="utf-8" />
            <meta content="IE=edge" http-equiv="X-UA-Compatible" />
            <meta content="width=device-width, initial-scale=1.0" name="viewport" />
            <meta content="" name="description" />
            <meta content="" name="keywords" />
            <title>Business Digital Card </title>
            <!-- Bootstrap css -->
            <link href="assets/css/bootstrap.min.css" media="screen" rel="stylesheet" />
            <!-- Slick slider css -->
            <link rel="stylesheet" href="assets/css/slick.css" />
            <link rel="stylesheet" href="assets/css/slick-theme.css" />
            <!-- Swiper slider css -->
            <link rel="stylesheet" href="assets/css/swiper.min.css" />
            <!--font Awesome css -->
            <link rel="stylesheet" href="assets/css/font-awesome.css" />
            <!-- Wow animations css -->
            <link rel="stylesheet" href="assets/css/animate.min.css" />

            <!-- Main custom css -->
            <link media="screen" rel="stylesheet" href="assets/css/style.css" />

            <!-- Shivraj css -->
            <link media="screen" rel="stylesheet" href="assets/css/shivraj.css" />
        </head>

        <body>
            <header class="header" id="header">
                <nav class="navbar container">
                    <div class="menu" id="menu">
                        <ul class="menu-list">
                            <li class="menu-item">
                                <a href="#" class="menu-link">
                                    <i class="bi bi-qr-code"></i>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="menu-link">
                                    <i class="bi bi-upload"></i>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="menu-link">
                                    <i class="bi bi-share"></i>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="menu-link">
                                    <div class="menu__btn">
                                        <p>
                                            Add to<br />
                                            Contact
                                        </p>
                                        <div class="menu__btn--icon">
                                            <img src="assets/img/home/btn.png" alt="" />
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>

            <div class="main">
                <div class="banner">
                    <img src="../<?php echo $full_res['business_logo'] ?>" alt="" />
                </div>

                <div class="details">
                    <h2><?php echo $full_res['business_name'] ?></h2>
                    <h3><?php echo $full_res['business_category'] ?></h3>
                    <p><?php echo $full_res['business_city'] . ", " . $full_res['business_state'] . ", " . $full_res['business_country'] ?></p>
                </div>

                <div class="social-media">
                    <ul>
                        <?php
                        $sel_social_sql = "SELECT sc.platform_name,sl.link AS social_link, i.icon AS social_icon FROM tbl_social_links sl INNER JOIN tbl_social_category sc ON sc.id = sl.social_category_id LEFT JOIN tbl_icons i ON i.social_category_id = sc.id WHERE sl.business_info_id = ?";

                        $sel_social_stmt = $conn->prepare($sel_social_sql);
                        $sel_social_stmt->bindParam(1, $business_id, PDO::PARAM_INT);
                        $sel_social_stmt->execute();
                        if ($sel_social_stmt->rowCount() > 0) {
                            while ($social_res = $sel_social_stmt->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                                <li>
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
                    <p>
                        <?php echo $full_res['business_about'] ?>
                    </p>
                </div>

                <div class="contact">
                    <h2>
                        <img src="assets/img/home/Component.png" alt="" /> Contact
                        Us
                    </h2>
                    <div class="contact__inner">
                        <a href="tel:+91<?php echo $full_res['business_contact'] ?>" class="box">
                            <div class="icon"><i class="bi bi-telephone"></i></div>
                            <div class="text">
                                <h3>Call us</h3>
                                <p>+91 <?php echo $full_res['business_contact'] ?></p>
                            </div>
                        </a>
                        <a href="mailto:<?php echo $full_res['business_email'] ?>" class="box">
                            <div class="icon"><i class="bi bi-envelope"></i></div>
                            <div class="text">
                                <h3>E-mail</h3>
                                <p><?php echo $full_res['business_email'] ?></p>
                            </div>
                        </a>
                        <div class="box">
                            <div class="icon"><i class="bi bi-geo-alt"></i></div>
                            <div class="text">
                                <h3>Address</h3>
                                <p>
                                    <?php echo $full_res['business_address1'] ?><br />
                                    <?php if (!empty($full_res['business_address2'])) {
                                        echo $full_res['business_address2'] . "<br />";
                                    } ?>
                                    <?php echo $full_res['business_city'] . ", " . $full_res['business_state'] . ", " . $full_res['business_country'] . " - " . $full_res['business_zip'] ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="imgs">
                    <?php
                    $slider_img_sel = "SELECT * FROM tbl_media WHERE business_info_id = $business_id";
                    $slider_img_stmt = $conn->prepare($slider_img_sel);
                    $slider_img_stmt->execute();
                    if ($slider_img_stmt->rowCount() > 0) {
                        while ($img_res = $slider_img_stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                            <div class="img">
                                <img src="<?php echo BASE_URL . "assets/img/business_other/" . $img_res['image'] ?>" alt="" />
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>

                <div class="links">
                    <div class="title">
                        <h2>Web Links</h2>
                        <p>Description</p>
                    </div>
                    <?php
                    $sel_other_links_sql = "SELECT * FROM tbl_other_links WHERE business_info_id = ?";
                    $sel_other_links_stmt = $conn->prepare($sel_other_links_sql);
                    $sel_other_links_stmt->bindParam(1, $business_id, PDO::PARAM_INT);
                    $sel_other_links_stmt->execute();
                    if ($sel_other_links_stmt->rowCount() > 0) {
                        while ($link_res = $sel_other_links_stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                        <a href="<?php echo $link_res['link'] ?>" class="links__inner" target="_blank">
                            <div class="icon">
                                <i class="far fa-link"></i>
                            </div>
                            <div class="text">
                                <h2><?php echo $link_res['link_title'] ?></h2>
                                <p><?php echo $link_res['link_sub_title'] ?></p>
                            </div>
                        </a>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <!-- Jquery Library File -->
            <script src="assets/js/jquery-3.4.1.js"></script>
            <!-- Bootstrap js file -->
            <script src="assets/js/bootstrap.min.js"></script>
            <!-- Slick slider js file -->
            <script src="assets/js/slick.min.js"></script>
            <!-- Swiper slider js file -->
            <script src="assets/js/swiper.min.js"></script>
            <!-- Wow animation js file -->
            <script src="assets/js/wow.min.js"></script>
            <!-- Main js file -->
            <script src="assets/js/script.js "></script>

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
        </body>

        </html>
    <?php
    } else {
    ?>
        <script>
            window.location.href = "404.php";
        </script>
    <?php
    }
} else {
    ?>
    <script>
        window.location.href = "404.php";
    </script>
<?php
}
?>