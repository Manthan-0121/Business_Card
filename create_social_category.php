<?php
include "./includes/header.php";
if ($_SESSION['role'] != "1") {
    echo "<script>window.location.href = 'index.php';</script>";
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create a new Social Category</h4>
                        </div>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Platform Name</label>
                                    <input type="text" id="txt_platform_name" class="form-control" name="txt_platform_name" required>
                                    <div class="invalid-feedback">
                                        Social Category already exists
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" name="status">
                                        <option selected value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-primary mr-1" id="btn_create" type="submit" name="btn_create">Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
include "./includes/footer.php";
?>

<script>
    $(document).ready(function() {
        $("#txt_platform_name").on("keyup", function() {
            var platform_name = $(this).val();
            $.ajax({
                url: "ajax/check_social_category.php",
                type: "POST",
                data: {
                    platform_name: platform_name
                },
                success: function(data) {
                    if (data == 1) {
                        $("#btn_create").prop("disabled", true);
                        $("#txt_platform_name").addClass("is-invalid");
                    } else {
                        $("#btn_create").prop("disabled", false);
                        $("#txt_platform_name").removeClass("is-invalid");
                    }
                }
            });
        });
    })
</script>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $platform_name = $_POST['txt_platform_name'];
    $status = $_POST['status'];

    $ins_sql = "INSERT INTO tbl_social_category (platform_name, status) VALUES (:platform_name, :status)";
    $stmt = $conn->prepare($ins_sql);
    $stmt->bindParam(':platform_name', $platform_name);
    $stmt->bindParam(':status', $status);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = "Social Category created successfully";
        echo "<script>window.location.href = 'show_social_category.php';</script>";
    } else {
        $_SESSION['error'] = "Something went wrong";
        echo "<script>window.location.href = 'create_social_category.php';</script>";
    }
}
?>