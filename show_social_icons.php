<?php
include("./includes/header.php");
if ($_SESSION['role'] != "1") {
    echo "<script>window.location.href = 'index.php';</script>";
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Social Icons</h4>
                        </div>
                        <?php
                        if (isset($_SESSION['success'])) {
                        ?>
                            <div class="show-tost alert alert-success alert-dismissible show fade">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert">
                                        <span>&times;</span>
                                    </button>
                                    <?php
                                    isset($_SESSION['success']) ? print_r($_SESSION['success']) : '';
                                    unset($_SESSION['success']);
                                    ?>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="dttable" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Icon</th>
                                            <th>Name</th>
                                            <th>Created</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT tbl_icons.id AS tbl_icon_id,tbl_icons.icon AS tbl_icon_icon,tbl_icons.created_at AS tbl_icon_created_at, tbl_social_category.platform_name AS tbl_social_category_platform_name FROM tbl_icons INNER JOIN tbl_social_category ON tbl_icons.social_category_id = tbl_social_category.id;";
                                        $stmt = $conn->prepare($query);
                                        $stmt->execute();
                                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        $i = 1;
                                        foreach ($result as $row) {
                                        ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><img src="./assets/templates/img/social/<?php echo $row['tbl_icon_icon']; ?>" alt="<?php echo $row['tbl_social_category_platform_name']; ?>" class="img-fluid" /></td>
                                                <td><?php echo $row['tbl_social_category_platform_name']; ?></td>
                                                <td><?php echo $row['tbl_icon_created_at']; ?></td>
                                                <td><a href="edit_social_icons.php?id=<?php echo $row['tbl_icon_id']; ?>" class="btn btn-sm btn-primary"><i data-feather="edit"></i></a></td>
                                                <td><a href="delete_social_icons.php?id=<?php echo $row['tbl_icon_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this?')"><i data-feather="trash-2"></i></a></td>
                                            </tr>
                                        <?php
                                            $i++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
include("./includes/footer.php");
?>