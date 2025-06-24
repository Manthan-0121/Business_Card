<?php
include "./includes/header.php";
?>

<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create a new Business Category</h4>
                        </div>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" id="txt_business_name" class="form-control" name="txt_business_name" required>
                                    <div class="invalid-feedback">
                                        Business Category already exists
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
        $("#txt_business_name").on("keyup", function() {
            var name = $(this).val();

            $.ajax({
                url: "ajax/chk_business_category.php",
                method: "POST",
                data: {
                    name: name
                },
                success: function(data) {
                    if (data == 1) {
                        $("#txt_business_name").addClass("is-invalid");
                        $("#btn_create").attr("disabled", true);
                    } else {
                        $("#txt_business_name").removeClass("is-invalid");
                        $("#btn_create").attr("disabled", false);
                    }
                }
            })
        });       
    })
</script>

<?php

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_create'])){    
        $name = $_POST['txt_business_name'];
        $status = $_POST['status'];

        $ins_sql = "INSERT INTO tbl_business_category (name, status) VALUES (:name, :status)";
        $stmt = $conn->prepare($ins_sql);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":status", $status);
        $stmt->execute();

        echo $ins_sql;
        if($stmt->rowCount() > 0){
            $_SESSION['success'] = "Business Category created successfully!";
            echo "<script>window.location.href = 'show_business_category.php';</script>";
        }else{
            echo "<script>alert('Failed to create Business Category!')</script>";
        }
    }
?>