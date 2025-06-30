<?php
include './includes/header.php';
if ($_SESSION['role'] == "1") {
    echo "<script>window.location.href = 'index.php';</script>";
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission
    $business_name = $_POST['business_name'];
    $business_contact = $_POST['business_contact'];
    $business_email = $_POST['business_email'];
    $business_category = $_POST['business_category'];
    $address_line1 = $_POST['address_line1'];
    $address_line2 = $_POST['address_line2'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $country = $_POST['country'];
    $pincode = $_POST['pincode'];
    $template_id = 2;
    $link_token = bin2hex(random_bytes(16));
    $user_id = $_SESSION['uid'];
    // Insert into database
    $business_info_insert_sql = "INSERT INTO `tbl_business_info`(`name`, `user_id`, `business_category_id`, `contact_no`, `email`, `link_token`, `address_line_1`, `address_line_2`, `city`, `state`, `zip`, `country`, `status`, `created_at`, `template_id`) VALUES (:name, :user_id, :business_category_id, :contact_no, :email, :link_token, :address_line_1, :address_line_2, :city, :state, :zip, :country, 1, NOW(), :template_id)";

    $business_info_insert_stmt = $conn->prepare($business_info_insert_sql);
    $business_info_insert_stmt->bindParam(':name', $business_name, PDO::PARAM_STR);
    $business_info_insert_stmt->bindParam(':business_category_id', $business_category, PDO::PARAM_INT);
    $business_info_insert_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $business_info_insert_stmt->bindParam(':contact_no', $business_contact, PDO::PARAM_STR);
    $business_info_insert_stmt->bindParam(':email', $business_email, PDO::PARAM_STR);
    $business_info_insert_stmt->bindParam(':link_token', $link_token, PDO::PARAM_STR);
    $business_info_insert_stmt->bindParam(':address_line_1', $address_line1, PDO::PARAM_STR);
    $business_info_insert_stmt->bindParam(':address_line_2', $address_line2, PDO::PARAM_STR);
    $business_info_insert_stmt->bindParam(':city', $city, PDO::PARAM_STR);
    $business_info_insert_stmt->bindParam(':state', $state, PDO::PARAM_STR);
    $business_info_insert_stmt->bindParam(':zip', $pincode, PDO::PARAM_STR);
    $business_info_insert_stmt->bindParam(':country', $country, PDO::PARAM_STR);
    $business_info_insert_stmt->bindParam(':template_id', $template_id, PDO::PARAM_INT);
    $business_info_insert_stmt->execute();
    if ($business_info_insert_stmt->rowCount() > 0) {
        $business_last_insert_id = $conn->lastInsertId();
        // Redirect to the business card view page or show success message
        $_SESSION['success'] = "Business card created successfully!";
        echo "<script>window.location.href = 'show_cards.php';</script>";
    } else {
        // Handle error
        $_SESSION['error'] = "Failed to create business card. Please try again.";
        echo "<script>window.location.href = 'create_card.php';</script>";
    }
}


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
                                    <label>Contact No</label>
                                    <input type="text" class="form-control" oninput="business_contact_preview()" name="business_contact" id="business_contact_l">

                                    <label>Email</label>
                                    <input type="email" name="business_email" class="form-control" id="business_email_l" oninput="business_email_preview()">

                                    <hr>
                                    <label>Business Category</label>
                                    <select class="form-control" id="business_category" onchange="updateCategoryPreview()" name="business_category">
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
                                    <input type="text" class="form-control" placeholder="Address line 1" id="address_line1" oninput="updateAddressPreview1()" name="address_line1">
                                    <input type="text" class="form-control mt-1" placeholder="Address line 2" id="address_line2" oninput="updateAddressPreview1()" name="address_line2">
                                    <input type="text" class="form-control mt-1" placeholder="City" id="city" oninput="updateAddressPreview1()" name="city">
                                    <input type="text" class="form-control mt-1" placeholder="State" id="state" oninput="updateAddressPreview1()" name="state">
                                    <input type="text" class="form-control mt-1" placeholder="Country" id="country" oninput="updateAddressPreview1()" name="country">
                                    <input type="text" class="form-control mt-1" placeholder="Pin-code" id="pincode" oninput="updateAddressPreview1()" name="pincode">
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <input type="submit" class="btn btn-primary mr-1" value="Save" id="save_button">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-7 preview-container">
                    <!-- preview card -->
                    <div class="business-card-preview">
                        <div class="content">
                            <div class="top">
                                <div class="name" id="business_name_r">Joe Hastings</div>
                                <div class="profession" id="business_category_preview">Front end developer</div>
                            </div>
                            <div class="bottom">
                                <div class="left">
                                    <div class="email" id="business_email_r">joehastings1991@gmail.com</div>
                                    <div class="phone" id="business_contact_r">7795123456</div>
                                </div>
                                <div class="right">
                                    <div class="location" id="full_address">123 rk road,<br> Shital park,<br> Rajkot - 360005 <br>Gujarat, India </div>
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

<?php
include './includes/footer.php';
?>

<script src="assets/js/preview.js"></script>

