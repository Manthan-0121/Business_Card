<?php
include './includes/header.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['update_profile'])) {
    $uid = $_SESSION['uid'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $update_profile_sql = "UPDATE `tbl_user` SET first_name = :first_name, last_name = :last_name, email = :email, mobile = :mobile WHERE id = :id";
    $update_profile_stmt = $conn->prepare($update_profile_sql);
    $update_profile_stmt->bindParam(':first_name', $first_name);
    $update_profile_stmt->bindParam(':last_name', $last_name);
    $update_profile_stmt->bindParam(':email', $email);
    $update_profile_stmt->bindParam(':mobile', $mobile);
    $update_profile_stmt->bindParam(':id', $uid);
    $update_profile_stmt->execute();
    echo "<script>alert('Profile updated successfully.');</script>";
    }
    echo "<script>console.log('Profile updated successfully.');</script>";
}
$sel_profile_data_sql = "SELECT * FROM `tbl_user` WHERE id = " . $_SESSION['uid'];
$sel_profile_data_stmt = $conn->prepare($sel_profile_data_sql);
$sel_profile_data_stmt->execute();
$sel_profile_data = $sel_profile_data_stmt->fetch(PDO::FETCH_ASSOC);
if (!$sel_profile_data) {
    echo "<script>alert('Profile data not found.');</script>";
    exit;
}

?>

<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row mt-sm-4">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="padding-20">
                            <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                                <div class="card-header">
                                    <h4>Edit Profile</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-6 col-12">
                                            <label>First Name</label>
                                            <input type="text" class="form-control" name="first_name" value="<?php echo $sel_profile_data['first_name']; ?>">
                                            <div class="invalid-feedback">
                                                Please fill in the first name
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 col-12">
                                            <label>Last Name</label>
                                            <input type="text" class="form-control" name="last_name" value="<?php echo $sel_profile_data['last_name']; ?>">
                                            <div class="invalid-feedback">
                                                Please fill in the last name
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6 col-12">
                                            <label>Email</label>
                                            <input type="email" class="form-control" name="email" value="<?php echo $sel_profile_data['email']; ?>">
                                            <div class="invalid-feedback">
                                                Please fill in the email
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 col-12">
                                            <label>Phone</label>
                                            <input type="tel" class="form-control" name="mobile" value="<?php echo $sel_profile_data['mobile']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button class="btn btn-primary" type="submit" name="update_profile">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="padding-20">
                            <form method="post" class="needs-validation">
                                <div class="card-header">
                                    <h4>Change Password</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-6 col-12">
                                            <label>Old Password</label>
                                            <input type="password" class="form-control">
                                            <div class="invalid-feedback">
                                                Please fill in the old password
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 col-12">
                                            <label>New Password</label>
                                            <input type="password" class="form-control">
                                            <div class="invalid-feedback">
                                                Please fill in the last name
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6 col-12">
                                            <label>Conform Password</label>
                                            <input type="password" class="form-control">
                                            <div class="invalid-feedback">
                                                Please fill in the email
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 col-12">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
</section>

<?php
include './includes/footer.php';
?>