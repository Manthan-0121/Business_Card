    <?php
    include '../includes/config.php';
    session_start();
    if (isset($_GET['token']) && !empty($_GET['token'])) {
        $token = $_GET['token'];
        $query = "SELECT id FROM `tbl_business_info` WHERE `link_token` = ?";
        if (isset($_SESSION['uid']) == null && isset($_SESSION['role']) == null) {
            $query .= " AND `status` = 1";
        }
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
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title><?php echo htmlspecialchars($full_res['business_name']); ?> - Business Card</title>
                <link rel="stylesheet" href="../assets/css/style.css">
                <style>
                    @import url('https://fonts.googleapis.com/css?family=Raleway:400,700');

                    :root {
                        --fontPrimary: #58242a;
                        --fontSecondary: rgb(255, 0, 30);
                        --fontAlt: #ee473b;
                        --cardBgPrimary: #FFFFFF;
                        --gradBgFrom: #ef473a;
                        --gradBgTo: #cb2d3e;
                    }

                    .business-card-container {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100%;
                        padding: 20px;
                    }

                    .business-card-preview {
                        display: grid;
                        overflow: hidden;
                        position: relative;
                        line-height: 1.6;
                        width: 100%;
                        max-width: 600px;
                        height: 350px;
                        padding: 40px;
                        border-radius: 6px;
                        box-shadow: 0 5px 15px -5px rgba(0, 0, 0, 0.4);
                        margin-left: 20px;
                    }

                    .business-card-preview .content {
                        z-index: 2;
                        display: grid;
                        grid-template-columns: 1fr;
                        grid-template-rows: 1fr 1fr 1fr;
                        font-family: 'Raleway', sans-serif;
                        color: var(--fontPrimary);
                    }

                    .business-card-preview .top {
                        display: grid;
                        grid-column: 1 / 3;
                        grid-row: 1;
                        align-content: start;
                        grid-gap: 10px;
                        animation: inLeft;
                        animation-duration: 1s;
                    }

                    .business-card-preview .name {
                        font-size: 28px;
                        font-weight: 700;
                        line-height: 1;
                    }

                    .business-card-preview .profession {
                        color: black;
                        text-transform: uppercase;
                        font-size: 16px;
                        font-weight: 700;
                    }

                    .business-card-preview .bottom {
                        display: grid;
                        grid-column: 1 / 3;
                        grid-row: 3;
                        grid-template-columns: 1fr 1fr;
                        align-content: end;
                    }

                    .business-card-preview .bottom .left {
                        font-weight: 700;
                        font-size: 14px;
                        grid-column: 1;
                        animation: inLeft;
                        animation-duration: 1s;
                    }

                    .business-card-preview .bottom .right {
                        grid-column: 2;
                        align-self: end;
                        animation: inRight;
                        animation-duration: 1s;
                    }

                    .business-card-preview .website {
                        color: var(--fontSecondary);
                        font-weight: 400;
                    }

                    .business-card-preview .location {
                        white-space: nowrap;
                        text-align: right;
                        color: var(--fontAlt);
                        text-transform: uppercase;
                        font-size: 14px;
                    }

                    .business-card-preview .background {
                        z-index: 1;
                        background-color: var(--cardBgPrimary);
                        position: absolute;
                        top: 0;
                        left: 0;
                        bottom: 0;
                        right: 0;
                    }

                    .business-card-preview .slice {
                        top: -300px;
                        left: -80px;
                        position: absolute;
                        width: 600px;
                        height: 800px;
                        background: var(--gradBgFrom);
                        background: -webkit-linear-gradient(to right, var(--gradBgFrom), var(--gradBgTo));
                        background: linear-gradient(to right, var(--gradBgFrom), var(--gradBgTo));
                        transform: rotate(38deg);
                        animation: inRotate;
                        animation-duration: 1s;
                    }

                    /* Form adjustments */
                    .card-form-container {
                        padding-right: 20px;
                    }

                    .card-form-container .card {
                        height: 100%;
                    }

                    .preview-container {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100%;
                    }
                </style>
            </head>

            <body>
                <div class="business-card-preview">
                    <div class="content">
                        <div class="top">
                            <div class="name" id="business_name_r"><?php echo $full_res['business_name']; ?></div>
                            <div class="profession" id="business_category_preview"><?php echo $full_res['business_category']; ?></div>
                        </div>
                        <div class="bottom">
                            <div class="left">
                                <div class="email" id="business_email_r"><?php echo $full_res['business_email']; ?></div>
                                <div class="phone" id="business_contact_r"><?php echo $full_res['business_contact']; ?></div>
                            </div>
                            <div class="right">
                                <div class="location" id="full_address"><?php echo $full_res['business_address1']; ?><br><?php if (!empty($full_res['business_address2'])) { echo $full_res['business_address2'] . '<br>'; } ?><?php echo $full_res['business_city']; ?> - <?php echo $full_res['business_zip']; ?><br><?php echo $full_res['business_state']; ?>, <?php echo $full_res['business_country']; ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="background">
                        <div class="slice"></div>
                    </div>
                </div>
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