<?php
include("./includes/header.php");
if ($_SESSION['role'] != "1") {
    echo "<script>window.location.href = 'index.php';</script>";
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_edit'])) {
    $id = $_POST['id'];
    $name = $_POST['txt_business_name'];
    $status = $_POST['status'];

    $updt_sql = "UPDATE tbl_business_category SET name = :name, status = :status WHERE id = :id";
    $stmt = $conn->prepare($updt_sql);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":status", $status);
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = "Business Category updated successfully!";
        echo "<script>window.location.href = 'show_business_category.php';</script>";
    } else {
        echo "<script>alert('Failed to update Business Category!')</script>";
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sel_sql = "SELECT * FROM tbl_business_category WHERE id = :id";
    $stmt = $conn->prepare($sel_sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row_res = $stmt->fetch(PDO::FETCH_ASSOC);
?>
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Edit a new Business Category</h4>
                            </div>
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                <input type="hidden" name="id" value="<?php echo $row_res['id']; ?>" id="txt_id">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" id="txt_business_name" class="form-control" name="txt_business_name" value="<?php echo $row_res['name']; ?>" required>
                                        <div class="invalid-feedback" id="err_business_name">
                                            Business Category already exists
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
                                    <button class="btn btn-primary mr-1" id="btn_create" type="submit" name="btn_edit">Edit</button>
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
    <script>
        $(document).ready(function() {
            $("#txt_business_name").on("keyup", function() {
                var name = $(this).val();
                var id = $("#txt_id").val();
                $.ajax({
                    url: "ajax/chk_business_category.php",
                    method: "POST",
                    data: {
                        name: name,
                        id: id
                    },
                    success: function(data) {
                        $("#txt_business_name").addClass("is-invalid");
                        $("#btn_create").attr("disabled", true);
                        if (data == 0 || data == 2) {
                            $("#txt_business_name").removeClass("is-invalid");
                            $("#btn_create").attr("disabled", false);
                        } else if (data == 1) {
                            $("#txt_business_name").addClass("is-invalid");
                            $("#btn_create").attr("disabled", true);
                        } else {
                            $("#txt_business_name").addClass("is-invalid");
                            $("#err_business_name").html(data);
                            $("#btn_create").attr("disabled", true);
                        }
                    }
                })
            });
        })
    </script>

<?php

} else {
    echo "<script>window.location.href = 'show_business_category.php';</script>";
}
?>