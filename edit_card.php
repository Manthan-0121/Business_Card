<?php

include "./includes/header.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $business_name = $_POST['business_name'] ?? '';
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
    if (!empty($_FILES['logo_input']['name'])) {
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
    } else {
        $logo_path = $_POST['business_logo'] ?? '';
    }

    // Process slider images
    $slider_images = [];
    if (!empty(isset($_FILES['slider_images']))) {
        foreach ($_FILES['slider_images']['name'] as $key => $name) {
            if ($_FILES['slider_images']['error'][$key] === UPLOAD_ERR_OK) {
                $tmp_name = $_FILES['slider_images']['tmp_name'][$key];
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                $allowed_ext = ['png', 'jpeg', 'jpg', 'gif'];

                if (in_array($ext, $allowed_ext)) {
                    $new_name = uniqid('slider_', true) . '.' . $ext;
                    $destination = 'assets/img/business_other/' . $new_name;

                    if (move_uploaded_file($tmp_name, $destination)) {
                        $slider_images[] = $new_name;
                    }
                }
            }
        }
    }

    // Process social links
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

    // update query
    $update_sql_tbl_business_info = "
    UPDATE tbl_business_info 
    SET 
        `name` = :name,
        `business_category_id` = :business_category_id,
        `description` = :description,
        `logo` = :logo,
        `contact_no` = :contact_no,
        `email` = :email,
        `address_line_1` = :address_line_1,
        `address_line_2` = :address_line_2,
        `city` = :city,
        `state` = :state,
        `zip` = :zip,
        `country` = :country
    WHERE id = :business_id AND link_token = :token
    ";

    $update_sql_tbl_business_info_stmt = $conn->prepare($update_sql_tbl_business_info);
    $update_sql_tbl_business_info_stmt->bindParam(':name', $business_name, PDO::PARAM_STR);
    $update_sql_tbl_business_info_stmt->bindParam(':business_category_id', $category_id, PDO::PARAM_INT);
    $update_sql_tbl_business_info_stmt->bindParam(':description', $about, PDO::PARAM_STR);
    $update_sql_tbl_business_info_stmt->bindParam(':logo', $logo_path, PDO::PARAM_STR);
    $update_sql_tbl_business_info_stmt->bindParam(':contact_no', $contact, PDO::PARAM_STR);
    $update_sql_tbl_business_info_stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $update_sql_tbl_business_info_stmt->bindParam(':address_line_1', $address_line1, PDO::PARAM_STR);
    $update_sql_tbl_business_info_stmt->bindParam(':address_line_2', $address_line2, PDO::PARAM_STR);
    $update_sql_tbl_business_info_stmt->bindParam(':city', $city, PDO::PARAM_STR);
    $update_sql_tbl_business_info_stmt->bindParam(':state', $state, PDO::PARAM_STR);
    $update_sql_tbl_business_info_stmt->bindParam(':zip', $pincode, PDO::PARAM_STR);
    $update_sql_tbl_business_info_stmt->bindParam(':country', $country, PDO::PARAM_STR);
    $update_sql_tbl_business_info_stmt->bindParam(':business_id', $_POST['business_id'], PDO::PARAM_INT);
    $update_sql_tbl_business_info_stmt->bindParam(':token', $_POST['token'], PDO::PARAM_STR);

    if ($update_sql_tbl_business_info_stmt->execute()) {

        //update social links
        $chk_social_sql = "SELECT social_category_id FROM tbl_social_links WHERE business_info_id = ?";
        $chk_social_stmt = $conn->prepare($chk_social_sql);
        $chk_social_stmt->bindParam(1, $_POST['business_id'], PDO::PARAM_INT);
        $chk_social_stmt->execute();
        $existing_social_links = $chk_social_stmt->fetchAll(PDO::FETCH_ASSOC);


        // Extract just the IDs for comparison
        $existing_ids = array_column($existing_social_links, 'social_category_id');
        $new_ids = array_column($social_links, 'platform_id');

        // Condition 1: Update existing links (present in both)
        foreach ($social_links as $link) {
            if (in_array($link['platform_id'], $existing_ids)) {
                $update_sql = "UPDATE tbl_social_links 
                           SET link = :url 
                           WHERE business_info_id = :business_id 
                           AND social_category_id = :platform_id";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bindParam(':url', $link['url'], PDO::PARAM_STR);
                $update_stmt->bindParam(':business_id', $_POST['business_id'], PDO::PARAM_INT);
                $update_stmt->bindParam(':platform_id', $link['platform_id'], PDO::PARAM_INT);
                $update_stmt->execute();
            }
        }

        // Condition 2: Delete removed links (present in existing but not in new)
        $ids_to_delete = array_diff($existing_ids, $new_ids);
        foreach ($ids_to_delete as $id) {
            $delete_sql = "DELETE FROM tbl_social_links 
                  WHERE business_info_id = ? 
                  AND social_category_id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->execute([$_POST['business_id'], $id]);
        }

        // Condition 3: Add new links (present in new but not in existing)
        $ids_to_add = array_diff($new_ids, $existing_ids);
        foreach ($social_links as $link) {
            if (in_array($link['platform_id'], $ids_to_add)) {
                $insert_sql = "INSERT INTO tbl_social_links (business_info_id, social_category_id, link) 
                          VALUES (:business_id, :platform_id, :url)";
                $insert_stmt = $conn->prepare($insert_sql);
                $insert_stmt->bindParam(':business_id', $_POST['business_id'], PDO::PARAM_INT);
                $insert_stmt->bindParam(':platform_id', $link['platform_id'], PDO::PARAM_INT);
                $insert_stmt->bindParam(':url', $link['url'], PDO::PARAM_STR);
                $insert_stmt->execute();
            }
        }

        // slider image
        $existing_slider_sql = "SELECT id, image FROM tbl_media WHERE business_info_id = ?";
        $existing_slider_stmt = $conn->prepare($existing_slider_sql);
        $existing_slider_stmt->execute([$_POST['business_id']]);
        $existing_images = $existing_slider_stmt->fetchAll(PDO::FETCH_ASSOC);

        $existing_img_ids = array_column($existing_images, 'id');
        $keep_image_ids = $_POST['other_images_ids'] ?? [];

        // Condition 2: Delete removed images (present in existing but not in new)
        $ids_to_delete = array_diff($existing_img_ids, $keep_image_ids);
        foreach ($ids_to_delete as $id) {
            $delete_sql = "DELETE FROM tbl_media WHERE business_info_id = ? AND id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->execute([$_POST['business_id'], $id]);
        }

        foreach ($slider_images as $slider_img) {
            $insert_sql = "INSERT INTO tbl_media (business_info_id, image) VALUES (:business_id, :image)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bindParam(':business_id', $_POST['business_id'], PDO::PARAM_INT);
            $insert_stmt->bindParam(':image', $slider_img, PDO::PARAM_STR);
            $insert_stmt->execute();
        }


        $existing_links = [];
        $new_links = [];

        foreach ($_POST['other_links'] as $link) {
            if (isset($link['link_id'])) {
                $existing_links[] = [
                    'id' => $link['link_id'],
                    'title' => $link['title'],
                    'subtitle' => $link['subtitle'],
                    'url' => $link['url']
                ];
            } else {
                $new_links[] = [
                    'title' => $link['title'],
                    'subtitle' => $link['subtitle'],
                    'url' => $link['url']
                ];
            }
        }


        if (!empty($existing_links)) {
            $update_sql = "UPDATE tbl_other_links SET link_title = :title, link_sub_title = :subtitle, link = :url WHERE id = :id AND business_info_id = :business_id";

            $existing_ids = array_column($existing_links, 'id');

            $placeholders = implode(',', array_fill(0, count($existing_ids), '?'));

            $delete_sql = "DELETE FROM tbl_other_links WHERE business_info_id = ? AND id NOT IN ($placeholders)";

            $delete_stmt = $conn->prepare($delete_sql);
            $params = array_merge([$_POST['business_id']], $existing_ids);
            $delete_stmt->execute($params);

            $update_stmt = $conn->prepare($update_sql);

            foreach ($existing_links as $link) {
                $update_stmt->bindParam(':title', $link['title']);
                $update_stmt->bindParam(':subtitle', $link['subtitle']);
                $update_stmt->bindParam(':url', $link['url']);
                $update_stmt->bindParam(':id', $link['id']);
                $update_stmt->bindParam(':business_id', $_POST['business_id'], PDO::PARAM_INT);
                $update_stmt->execute();
            }
        }

        if (!empty($new_links)) {
            $insert_sql = "INSERT INTO tbl_other_links (business_info_id, link_title, link_sub_title, link) VALUES (:business_id, :title, :subtitle, :url)";

            $insert_stmt = $conn->prepare($insert_sql);

            foreach ($new_links as $link) {
                $insert_stmt->bindParam(':title', $link['title']);
                $insert_stmt->bindParam(':subtitle', $link['subtitle']);
                $insert_stmt->bindParam(':url', $link['url']);
                $insert_stmt->bindParam(':business_id', $_POST['business_id'], PDO::PARAM_INT);
                $insert_stmt->execute();
            }
        }

        $_SESSION['success'] = "Business card updated successfully!";
        echo "<script>window.location.href = 'show_cards.php';</script>";
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
                                <input type="hidden" name="business_logo" value="<?php echo htmlspecialchars($full_res['business_logo']); ?>">
                                <input type="hidden" name="business_id" value="<?php echo $business_id; ?>">
                                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
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
                                            <?php
                                            $slider_img_sel = "SELECT * FROM tbl_media WHERE business_info_id = $business_id";
                                            $slider_img_stmt = $conn->prepare($slider_img_sel);
                                            $slider_img_stmt->execute();
                                            if ($slider_img_stmt->rowCount() > 0) {
                                                $id = 0;
                                                while ($img_res = $slider_img_stmt->fetch(PDO::FETCH_ASSOC)) {
                                            ?>
                                                    <div class="input-group mb-2">
                                                        <input type="hidden" name="other_images_text[]" value="<?php echo $img_res['image']; ?>" readonly class="form-control image-input" accept="image/*">
                                                        <input type="hidden" name="other_images_ids[]" value="<?php echo $img_res['id']; ?>" readonly class="form-control image-input" accept="image/*">
                                                        <img src="<?php echo BASE_URL . "assets/img/business_other/" . $img_res['image'] ?>" alt="Image <?php echo $id + 1; ?>" class="img-thumbnail" style="width: 100px; height: 100px;">
                                                        <button type="button" class="btn btn-danger" onclick="removeInput(this)">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                            <?php
                                                    $id++;
                                                }
                                            }
                                            ?>
                                        </div>
                                        <button type="button" class="btn btn-primary" onclick="addImageInput()"><i class="bi bi-plus"></i></button>
                                        <hr>
                                        <!-- Input Form Section -->
                                        <label>Web Links</label>
                                        <div class="form-group" id="webLinksContainer">

                                            <?php
                                            $sel_other_links_sql = "SELECT * FROM tbl_other_links WHERE business_info_id = ?";
                                            $sel_other_links_stmt = $conn->prepare($sel_other_links_sql);
                                            $sel_other_links_stmt->bindParam(1, $business_id, PDO::PARAM_INT);
                                            $sel_other_links_stmt->execute();
                                            while ($link_res = $sel_other_links_stmt->fetch(PDO::FETCH_ASSOC)) {
                                                $dbId = $link_res['id'];
                                                $bytes = random_bytes(5);
                                                $linkId = "link_" . bin2hex($bytes);
                                                $title = $link_res['link_title'];
                                                $subtitle = $link_res['link_sub_title'];
                                                $url = $link_res['link'];
                                            ?>
                                                <div class="mb-3 link-group" id="<?php echo $linkId; ?>" data-db-id="<?php echo $dbId; ?>">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <label>Default Link</label>
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeLink('<?php echo $linkId; ?>')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text" class="form-control mb-1 link-title"
                                                        name="other_links[<?php echo bin2hex($bytes); ?>][title]"
                                                        placeholder="Title"
                                                        value="<?php echo htmlspecialchars($title); ?>"
                                                        oninput="updateLinksPreview()">
                                                    <input type="hidden" name="other_links[<?php echo bin2hex($bytes); ?>][link_id]" value="<?php echo $dbId; ?>">
                                                    <input type="text" class="form-control mb-1 link-subtitle"
                                                        name="other_links[<?php echo bin2hex($bytes); ?>][subtitle]"
                                                        placeholder="Sub-title"
                                                        value="<?php echo htmlspecialchars($subtitle); ?>"
                                                        oninput="updateLinksPreview()">
                                                    <input type="url" class="form-control link-url"
                                                        name="other_links[<?php echo bin2hex($bytes); ?>][url]"
                                                        placeholder="http://example.com"
                                                        value="<?php echo htmlspecialchars($url); ?>"
                                                        oninput="updateLinksPreview()">
                                                    <?php if ($dbId): ?>
                                                        <input type="hidden" name="existing_links[]" value="<?php echo $dbId; ?>">
                                                    <?php endif; ?>
                                                </div>
                                            <?php
                                            }
                                            ?>
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
                                        Contact
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
                                        <a href="mailto:<?php echo htmlspecialchars($full_res['business_email']) ?>" class="box" id="business_email_r_link">
                                            <div class="icon"><i class="bi bi-envelope"></i></div>
                                            <div class="text">
                                                <h3>E-mail</h3>
                                                <p id="business_email_r"><?php echo htmlspecialchars($full_res['business_email']) ?></p>
                                            </div>
                                        </a>
                                        <div class="box">
                                            <div class="icon"><i class="bi bi-geo-alt"></i></div>
                                            <div class="text">
                                                <h3>Address</h3>
                                                <p id="full_address">
                                                    <?php echo htmlspecialchars($full_res['business_address1']) ?><br />
                                                    <?php if (!empty($full_res['business_address2'])) echo htmlspecialchars($full_res['business_address2']) . "<br />" ?>
                                                    <?php echo htmlspecialchars($full_res['business_city']) ?> - <?php echo htmlspecialchars($full_res['business_zip']) ?><br />
                                                    <?php echo htmlspecialchars($full_res['business_state']) ?>, <?php echo htmlspecialchars($full_res['business_country']) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="carouselExampleIndicators3" class="carousel slide text-center mt-3" data-ride="carousel">
                                    <div class="carousel-inner ">
                                        <?php
                                        $slider_img_sel = "SELECT * FROM tbl_media WHERE business_info_id = $business_id";
                                        $slider_img_stmt = $conn->prepare($slider_img_sel);
                                        $slider_img_stmt->execute();
                                        if ($slider_img_stmt->rowCount() > 0) {
                                            $id = 0;
                                            while ($img_res = $slider_img_stmt->fetch(PDO::FETCH_ASSOC)) {
                                        ?>
                                                <div class="carousel-item <?php echo ($id == 0) ? 'active' : ''; ?>">
                                                    <img class="d-block w-75 mlc" src="<?php echo BASE_URL . "assets/img/business_other/" . $img_res['image'] ?>" alt="<?php echo $img_res['image'] ?>">
                                                </div>
                                        <?php
                                                $id++;
                                            }
                                        }
                                        ?>
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

        <script src="assets/js/business_card copy.js"></script>
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