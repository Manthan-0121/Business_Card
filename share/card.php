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

                <style>
                    .popup-overlay {
                        display: none;
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0, 0, 0, 0.7);
                        z-index: 1000;
                    }

                    /* Popup Box */
                    .qr-popup {
                        display: none;
                        position: fixed;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        background: white;
                        padding: 25px;
                        border-radius: 10px;
                        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
                        z-index: 1001;
                        text-align: center;
                        max-width: 90%;
                        width: 300px;
                    }

                    .popup-title {
                        margin-top: 0;
                        color: #333;
                    }

                    .close-btn {
                        position: absolute;
                        top: 10px;
                        right: 15px;
                        font-size: 24px;
                        cursor: pointer;
                        color: #666;
                    }

                    .close-btn:hover {
                        color: #333;
                    }

                    #qrCodeCanvas {
                        margin: 15px auto;
                        display: block;
                    }

                    .current-url {
                        word-break: break-all;
                        font-size: 14px;
                        color: #666;
                        margin-top: 15px;
                    }
                </style>
            </head>

            <body>
                <header class="header" id="header">
                    <nav class="navbar container">
                        <div class="menu" id="menu">
                            <ul class="menu-list">
                                <li class="menu-item">
                                    <div id="qrTrigger" class="menu-link">
                                        <i class="bi bi-qr-code"></i>
                                    </div>
                                </li>
                                <li class="menu-item">
                                    <div onclick="exportPDF()" class="menu-link">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </div>
                                </li>
                                <li class="menu-item copy-btn" data-id="<?php echo $business_id; ?>">
                                    <div class="menu-link">
                                        <i class="bi bi-clipboard"></i>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </header>

                <div class="main" id="businessCard" style="background-color: var(--theme-color-black);">
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

                <!-- Popup Elements -->
                <div class="popup-overlay" id="popupOverlay"></div>
                <div class="qr-popup" id="qrPopup">
                    <span class="close-btn" id="closePopup">&times;</span>
                    <h3 class="popup-title">Scan to visit this page</h3>
                    <canvas id="qrCodeCanvas"></canvas>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

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


                    document.addEventListener('DOMContentLoaded', function() {
                        const qrTrigger = document.getElementById('qrTrigger');
                        const popupOverlay = document.getElementById('popupOverlay');
                        const qrPopup = document.getElementById('qrPopup');
                        const closePopup = document.getElementById('closePopup');
                        const qrCodeCanvas = document.getElementById('qrCodeCanvas');

                        const businessCard = document.getElementById('businessCard');

                        function generateQRCode() {
                            let url = window.location.href;

                            if (url.startsWith('file://')) {
                                url = "https://example.com"; // Replace with your actual URL
                                alert("Note: For demonstration, using example.com. On a live website, this will show your actual URL.");
                            }

                            qrCodeCanvas.getContext('2d').clearRect(0, 0, qrCodeCanvas.width, qrCodeCanvas.height);

                            QRCode.toCanvas(qrCodeCanvas, url, {
                                width: 200,
                                margin: 2,
                                color: {
                                    dark: '#000000',
                                    light: '#ffffff'
                                }
                            }, function(error) {
                                if (error) {
                                    console.error("QR Code generation error:", error);
                                    currentUrlElement.textContent = "Error generating QR code: " + error.message;
                                }
                            });

                            popupOverlay.style.display = 'block';
                            qrPopup.style.display = 'block';
                        }

                        qrTrigger.addEventListener('click', generateQRCode);

                        closePopup.addEventListener('click', function() {
                            popupOverlay.style.display = 'none';
                            qrPopup.style.display = 'none';
                        });

                        popupOverlay.addEventListener('click', function() {
                            popupOverlay.style.display = 'none';
                            qrPopup.style.display = 'none';
                        });

                        window.exportPDF = function() {

                            const opt = {
                                margin: 0.5,
                                filename: 'business-card.pdf',
                                image: {
                                    type: 'jpeg',
                                    quality: 0.98
                                },
                                html2canvas: {
                                    scale: 2
                                },
                                jsPDF: {
                                    unit: 'in',
                                    format: 'a4',
                                    orientation: 'portrait'
                                }
                            };

                            html2pdf().set(opt).from(businessCard).save();
                        }
                    });

                    document.addEventListener('DOMContentLoaded', function() {
                        document.addEventListener('click', function(e) {
                            if (e.target.closest('.copy-btn')) {
                                const button = e.target.closest('.copy-btn');
                                const token = button.getAttribute('data-id');
                                const shareUrl = window.location.href ;

                                navigator.clipboard.writeText(shareUrl)
                                    .then(() => {
                                        const icon = button.querySelector('i');
                                        console.log('Share link copied to clipboard:', shareUrl);
                                        icon.classList.replace('fa-clipboard', 'fa-check');
                                        alert('Share link copied to clipboard!');
                                        setTimeout(() => {
                                            icon.classList.replace('fa-check', 'fa-clipboard');
                                        }, 2000);
                                    })
                                    .catch(err => {
                                        console.error('Copy failed:', err);
                                        alert('Error: Could not copy link.');
                                    });
                            }
                        });

                        if (typeof feather !== 'undefined') {
                            feather.replace();
                        }
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