<?php
include("./includes/header.php");
if ($_SESSION['role'] != "1") {
    echo "<script>window.location.href = 'index.php';</script>";
}
if (isset($_POST['btn_edit_social']) && isset($_POST['btn_edit_social'])) {
    $id = $_POST['id'];
    $platform_name = $_POST['txt_social_name'];
    $status = $_POST['status'];

    $update_sql = "UPDATE tbl_social_category SET platform_name = :platform_name, status = :status WHERE id = :id";
    $stmt = $conn->prepare($update_sql);
    $stmt->bindParam(':platform_name', $platform_name, PDO::PARAM_STR);
    $stmt->bindParam(':status', $status, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Social Category updated successfully.";
        echo "<script>window.location.href = 'show_social_category.php';</script>";
    } else {
        $_SESSION['error'] = "Failed to update Social Category.";
        echo "<script>window.location.href = 'show_social_category.php';</script>";
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sel_sql = "SELECT * FROM tbl_social_category WHERE id = :id";
    $stmt = $conn->prepare($sel_sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row_res = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row_res) {
        echo "<script>window.location.href = 'show_social_category.php';</script>";
    } else {
?>
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Edit a new Social Category</h4>
                                </div>
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                    <input type="hidden" name="id" value="<?php echo $row_res['id']; ?>" id="txt_social_id">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" id="txt_social_name" class="form-control" name="txt_social_name" value="<?php echo $row_res['platform_name']; ?>" required>
                                            <div class="invalid-feedback" id="err_social_name">
                                                Social Category already exists
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control" name="status">
                                                <option <?php if ($row_res['status'] == 1) echo "selected"; ?> value="1">Active</option>
                                                <option <?php if ($row_res['status'] == 0) echo "selected"; ?> value="0">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="card-footer text-right">
                                        <button class="btn btn-primary mr-1" id="btn_edit_social" type="submit" name="btn_edit_social">Edit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
<?php
    }
} else {
    echo "<script>window.location.href = 'show_social_category.php';</script>";
}
?>

<?php
include("./includes/footer.php");
?>

<script>
    $(document).ready(function() {
        $("#txt_social_name").on("keyup", function() {
            var name = $(this).val();
            var id = $("#txt_social_id").val();
            $.ajax({
                type: "POST",
                url: "ajax/check_social_category.php",
                data: {
                    platform_name: name,
                    id: id
                },
                success: function(data) {
                    if (data == 1) {
                        $("#err_social_name").show();
                        $("#btn_edit_social").attr("disabled", true);
                    } else if (data == 2) {
                        $("#err_social_name").hide();
                        $("#btn_edit_social").attr("disabled", false);
                    } else {
                        $("#err_social_name").hide();
                        $("#btn_edit_social").attr("disabled", false);
                    }
                }
            });
        });
    });
</script>