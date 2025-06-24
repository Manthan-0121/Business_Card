<?php
include "./includes/header.php";
//select total cards
$sel_sql = "SELECT count(*) as tot_cards FROM tbl_business_info";

if ($_SESSION['role'] == 2) {
    $sel_sql .= " WHERE user_id  = :id";
}
$stmt = $conn->prepare($sel_sql);
if ($_SESSION['role'] == 2) {
    $stmt->bindParam(':id', $_SESSION['uid'], PDO::PARAM_INT);
}
$stmt->execute();
$total_cards = $stmt->fetch(PDO::FETCH_ASSOC);

// //select total users
$sel_sql = "SELECT count(*) as tot_users FROM tbl_user;";
$stmt = $conn->prepare($sel_sql);
$stmt->execute();
$total_users = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <section class="section">
                <div class="row ">
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="card">
                            <div class="card-statistic-4">
                                <div class="align-items-center justify-content-between">
                                    <div class="row ">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                            <div class="card-content">
                                                <h5 class="font-15">Total Cards</h5>
                                                <h2 class="mb-3 font-18"><?php echo $total_cards['tot_cards']; ?></h2>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                            <div class="banner-img">
                                                <img src="assets/img/banner/1.png" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    if ($_SESSION["role"] == 1) {
                    ?>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="card">
                                <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                <div class="card-content">
                                                    <h5 class="font-15"> Total Users</h5>
                                                    <h2 class="mb-3 font-18"><?php echo $total_users['tot_users']; ?></h2>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                <div class="banner-img">
                                                    <img src="assets/img/banner/2.png" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </section>
        </div>
    </section>
</div>


<?php
include "./includes/footer.php";
?>