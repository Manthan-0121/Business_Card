<?php

include "./includes/header.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $update_business_sql = "UPDATE tbl_business_info SET name=:name,business_category_id=:category_id, contact_no=:contect_no,email=:email,address_line_1=:address_line_1,address_line_2=:address_line_2,city=:city,state=:state,zip=:zip,country=:country WHERE id=:business_id";

    // print_r($_POST);
    $update_business_stmt = $conn->prepare($update_business_sql);
    $update_business_stmt->bindParam(':name', $_POST['business_name'], PDO::PARAM_STR);
    $update_business_stmt->bindParam(':category_id', $_POST['business_category'], PDO::PARAM_INT);
    $update_business_stmt->bindParam(':contect_no', $_POST['business_contact'], PDO::PARAM_STR);
    $update_business_stmt->bindParam(':email', $_POST['business_email'], PDO::PARAM_STR);
    $update_business_stmt->bindParam(':address_line_1', $_POST['address_line1'], PDO::PARAM_STR);
    $update_business_stmt->bindParam(':address_line_2', $_POST['address_line2'], PDO::PARAM_STR);
    $update_business_stmt->bindParam(':city', $_POST['city'], PDO::PARAM_STR);
    $update_business_stmt->bindParam(':state', $_POST['state'], PDO::PARAM_STR);
    $update_business_stmt->bindParam(':zip', $_POST['pincode'], PDO::PARAM_STR);
    $update_business_stmt->bindParam(':country', $_POST['country'], PDO::PARAM_STR);
    $update_business_stmt->bindParam(':business_id', $_POST['business_id'], PDO::PARAM_INT);

    if ($update_business_stmt->execute()) {
        $_SESSION['success'] = "Business card updated successfully!";
        echo "<script>window.location.href = 'show_cards.php';</script>";
    } else {
        echo "<script>alert('Failed to update business card. Please try again.');</script>";
    }
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

        <?php
        $res = $chk_token_stmt->fetch(PDO::FETCH_ASSOC);
        $business_id = $res['id'];

        $full_sel_sql = "SELECT buss.name AS business_name, buss.description AS business_about, buss.contact_no AS business_contact, buss.email AS business_email, buss.address_line_1 AS business_address1, buss.address_line_2 AS business_address2, buss.city AS business_city, buss.state AS business_state, buss.zip AS business_zip, buss.country AS business_country, buss.logo AS business_logo, bucat.id AS business_category_id,bucat.name AS business_category FROM tbl_business_info AS buss INNER JOIN tbl_business_category AS bucat ON bucat.id = buss.business_category_id WHERE buss.id = ?";
        $full_sel_stmt = $conn->prepare($full_sel_sql);
        $full_sel_stmt->bindParam(1, $business_id, PDO::PARAM_INT);
        $full_sel_stmt->execute();
        $full_res = $full_sel_stmt->fetch(PDO::FETCH_ASSOC);
        ?>



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
                left: -160px;
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

        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <div class="row business-card-container">
                        <div class="col-md-5 card-form-container">
                            <form method="post" id="business_card_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/
                            form-data">
                                <input type="hidden" name="business_id" value="<?php echo $business_id; ?>">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Edit Business Card</h4>
                                    </div>
                                    <div class="card-body">
                                        <hr>
                                        <label>Basic Information</label>
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" oninput="business_name_preview()" value="<?php echo $full_res['business_name']; ?>" name="business_name" id="business_name_l" class="form-control">
                                            <label>Contact No</label>
                                            <input type="text" class="form-control" oninput="business_contact_preview()" name="business_contact" id="business_contact_l" value="<?php echo $full_res['business_contact']; ?>">

                                            <label>Email</label>
                                            <input type="email" name="business_email" class="form-control" id="business_email_l" oninput="business_email_preview()" value="<?php echo $full_res['business_email']; ?>">

                                            <hr>
                                            <label>Business Category</label>
                                            <select class="form-control" id="business_category" onchange="updateCategoryPreview()" name="business_category">
                                                <?php
                                                $sel_sql = "SELECT * FROM tbl_business_category WHERE status = 1";
                                                $sel_res = $conn->prepare($sel_sql);
                                                $sel_res->execute();
                                                $sel_res = $sel_res->fetchAll(pdo::FETCH_ASSOC);
                                                foreach ($sel_res as $row) {
                                                    echo '<option value="' . $row['id'] . '"' . ($row['id'] == $full_res['business_category_id'] ? ' selected' : '') . '>' . $row['name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <hr>
                                        <label>Address</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Address line 1" id="address_line1" oninput="updateAddressPreview1()" name="address_line1" value="<?php echo $full_res['business_address1']; ?>">
                                            <input type="text" class="form-control mt-1" placeholder="Address line 2" id="address_line2" oninput="updateAddressPreview1()" name="address_line2" value="<?php echo $full_res['business_address2']; ?>">
                                            <input type="text" class="form-control mt-1" placeholder="City" id="city" oninput="updateAddressPreview1()" name="city" value="<?php echo $full_res['business_city']; ?>">
                                            <input type="text" class="form-control mt-1" placeholder="State" id="state" oninput="updateAddressPreview1()" name="state" value="<?php echo $full_res['business_state']; ?>">
                                            <input type="text" class="form-control mt-1" placeholder="Country" id="country" oninput="updateAddressPreview1()" name="country" value="<?php echo $full_res['business_country']; ?>">
                                            <input type="text" class="form-control mt-1" placeholder="Pin-code" id="pincode" oninput="updateAddressPreview1()" name="pincode" value="<?php echo $full_res['business_zip']; ?>">
                                        </div>
                                    </div>
                                    <div class="card-footer text-right">
                                        <input type="submit" class="btn btn-primary mr-1" value="Update" id="update_button">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-7 preview-container">
                            <!-- preview card -->
                            <div class="business-card-preview">
                                <div class="content">
                                    <div class="top">
                                        <div class="name" id="business_name_r" value="<?php echo $full_res['business_name']; ?>">Joe Hastings</div>
                                        <div class="profession" id="business_category_preview" value="<?php echo $full_res['business_category']; ?>">Front end developer</div>
                                    </div>
                                    <div class="bottom">
                                        <div class="left">
                                            <div class="email" id="business_email_r" value="<?php echo $full_res['business_email']; ?>">joehastings1991@gmail.com</div>
                                            <div class="phone" id="business_contact_r" value="<?php echo $full_res['business_contact']; ?>">7795123456</div>
                                        </div>
                                        <div class="right">
                                            <div class="location" id="full_address" value="<?php echo $full_res['business_address1'] . ', ' . $full_res['business_address2'] . ', ' . $full_res['business_city'] . ', ' . $full_res['business_state'] . ', ' . $full_res['business_country'] . ' - ' . $full_res['business_zip']; ?>">123 rk road,<br> Shital park,<br> Rajkot - 360005 <br>Gujarat, India </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="background">
                                    <div class="slice"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>


        <script src="assets/js/preview.js"></script>
<?php
    } else {
        // echo "<script>window.location.href = 'index.php';</script>";
    }
} else {
    // echo "<script>window.location.href = 'index.php';</script>";
}
?>
<?php
include "./includes/footer.php";
?>