<?php
session_start();

if ($_SESSION['role'] != "1") {
    echo "<script>window.location.href = 'index.php';</script>";
}
if($_GET['id']) {
    include "./includes/config.php";
    if ($_GET['id']) {
        $id = $_GET['id'];

        $sel_sql = "SELECT * FROM tbl_icons WHERE id = :id";
        $stmt = $conn->prepare($sel_sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row_res = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row_res['id'] == $id) {
            // Delete the icon file from the server
            $icon_path = "./assets/templates/img/social/" . $row_res['icon'];
            if (file_exists($icon_path)) {
                unlink($icon_path);
            }
            $del_sql = "DELETE FROM tbl_icons WHERE id = :id";
            $stmt = $conn->prepare($del_sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $_SESSION['success'] = "Social Icon deleted successfully!";
                echo "<script>window.location.href = 'show_social_icons.php';</script>";
            } else {
                echo "<script>alert('Failed to delete Social Icon!')</script>";
            }
        }
    } else {
        echo "<script>window.location.href = 'show_social_icons.php';</script>";
    }
} else {
    echo "<script>window.location.href = 'show_social_icons.php';</script>";
}

?>