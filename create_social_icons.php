<?php
include('./includes/header.php');
?>
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create a new Social Icon</h4>
                        </div>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <!-- Category Dropdown -->
                                <div class="form-group">
                                    <label>Select Category</label>
                                    <select class="form-control" name="social_category_id" required>
                                        <option value="">Select Category</option>
                                        <?php
                                        $sel_sql = "SELECT * FROM tbl_social_category";
                                        $sel_res = $conn->prepare($sel_sql);
                                        $sel_res->execute();
                                        $sel_res = $sel_res->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($sel_res as $row) {
                                            echo '<option value="' . $row['id'] . '">' . $row['platform_name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <!-- Image Upload -->
                                <div class="form-group">
                                    <label>Icon Image</label>
                                    <div class="input-group mb-2">
                                        <input type="file" class="form-control" name="icon_image" accept="image/*" required>
                                    </div>
                                    <small class="text-muted">Recommended size: 50x50px or SVG</small>
                                </div>
                            </div>

                            <div class="card-footer text-right">
                                <button class="btn btn-primary mr-1" type="submit" name="btn_create_icon">Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
include('./includes/footer.php');
?>

<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_create_icon'])) {
        $social_category_id = $_POST['social_category_id'];

        // Image upload
        $icon_image = $_FILES['icon_image']['name'];
        $icon_image_tmp = $_FILES['icon_image']['tmp_name'];
        $icon_image_size = $_FILES['icon_image']['size'];
        $icon_image_error = $_FILES['icon_image']['error'];

        if ($icon_image_error === 0) {
            if ($icon_image_size < 1000000) { // 1MB limit
                $icon_image_new_name = uniqid('', true) . "." . pathinfo($icon_image, PATHINFO_EXTENSION);
                $icon_image_destination = './assets/templates/img/social/' . $icon_image_new_name;
                move_uploaded_file($icon_image_tmp, $icon_image_destination);

                // Insert into database
                $insert_sql = "INSERT INTO tbl_icons (social_category_id, icon) VALUES (:social_category_id, :icon_image)";
                $insert_res = $conn->prepare($insert_sql);
                if ($insert_res->execute(['social_category_id' => $social_category_id, 'icon_image' => $icon_image_new_name])) {
                    $_SESSION['success'] = "Social Icon created successfully.";
                    echo "<script>window.location.href='show_social_icons.php';</script>";
                } else {
                    $_SESSION['error'] = "Failed to create Social Icon.";
                    // Clean up uploaded file if insert fails
                    if (file_exists($icon_image_destination)) {
                        unlink($icon_image_destination);
                    }
                }
            } else {
                $_SESSION['error'] = "File size is too large. Maximum size is 1MB.";
            }
        } else {
            $_SESSION['error'] = "Error uploading file. Please try again.";
        }
    }
?>