<?php
include("./includes/header.php");
if ($_SESSION['role'] != "1") {
    echo "<script>window.location.href = 'index.php';</script>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_create_icon'])) {
    $social_category_id = $_POST['social_category_id'];

    // Image upload
    $icon_image = $_FILES['icon_image']['name'];
    $icon_image_tmp = $_FILES['icon_image']['tmp_name'];

    if (empty($icon_image)) {
        $icon_image = $_POST['old_icon_image'];
    } else {
        if (isset($_POST['old_icon_image'])) {
            $old_icon_image = $_POST['old_icon_image'];
            if (file_exists('./assets/templates/img/social/' . $old_icon_image)) {
                unlink('./assets/templates/img/social/' . $old_icon_image);
            }
        }
        $icon_image = $_FILES['icon_image']['name'];
        $icon_image_tmp = $_FILES['icon_image']['tmp_name'];
        $icon_image_ext = pathinfo($icon_image, PATHINFO_EXTENSION);
        $icon_image_new = uniqid('icon_', true) . '.' . $icon_image_ext;
        $icon_image = $icon_image_new;
        move_uploaded_file($icon_image_tmp, './assets/templates/img/social/' . $icon_image_new);
    }

    $update_sql = "UPDATE tbl_icons SET social_category_id = :social_category_id, icon = :icon WHERE id = :id";
    $update_res = $conn->prepare($update_sql);
    $update_res->bindParam(':social_category_id', $social_category_id, PDO::PARAM_INT);
    $update_res->bindParam(':icon', $icon_image, PDO::PARAM_STR);
    $update_res->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
    $update_res->execute();
    if ($update_res->rowCount() > 0) {
        $_SESSION['success'] = "Social Icon updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update Social Icon!";
    }

    echo "<script>window.location.href = 'show_social_icons.php';</script>";
    exit;
}
$sel_sql_icon = "SELECT tbl_icons.id AS tbl_icon_id,tbl_icons.icon AS tbl_icon_icon,tbl_icons.created_at AS tbl_icon_created_at, tbl_social_category.platform_name AS tbl_social_category_platform_name FROM tbl_icons INNER JOIN tbl_social_category ON tbl_icons.social_category_id = tbl_social_category.id WHERE tbl_icons.id = :id";
$sel_res_icon = $conn->prepare($sel_sql_icon);
$sel_res_icon->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$sel_res_icon->execute();
$sel_res_icon = $sel_res_icon->fetch(PDO::FETCH_ASSOC);
if (!$sel_res_icon) {
    echo "<script>alert('Icon not found!'); window.location.href = 'show_social_icons.php';</script>";
    exit;
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Update Social Icon</h4>
                        </div>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $sel_res_icon['tbl_icon_id']; ?>">
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
                                            echo '<option value="' . $row['id'] . '" ' . ($row['platform_name'] == $sel_res_icon['tbl_social_category_platform_name'] ? 'selected' : '') . '>' . $row['platform_name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <!-- Image Upload -->
                                <div class="form-group">
                                    <label>Icon Image</label>
                                    <div class="input-group mb-2">
                                        <input type="file" class="form-control" name="icon_image" accept="image/*">
                                    </div>
                                    <small class="text-muted">Recommended size: 50x50px or SVG</small>
                                </div>

                                <div class="form-group">
                                    <label>Current Icon</label>
                                    <div class="input-group mb-2">
                                        <img src="./assets/templates/img/social/<?php echo $sel_res_icon['tbl_icon_icon']; ?>" alt="<?php echo $sel_res_icon['tbl_social_category_platform_name']; ?>" class="img-fluid" style="width: 50px; height: 50px;">

                                        <input type="hidden" name="old_icon_image" value="<?php echo $sel_res_icon['tbl_icon_icon']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-primary mr-1" type="submit" name="btn_create_icon">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
include("./includes/footer.php");
?>