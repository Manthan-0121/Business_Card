<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "../includes/config.php";

    $id = $_POST['id'];

    $sel = "SELECT * FROM tbl_icons WHERE social_category_id = :id";
    $stmt = $conn->prepare($sel);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->rowCount();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($count > 0) {
        echo "<li class=\"social-icon\" data-id=\"{$id}\"><a id=\"link_{$id}\" href=\"#\" target=\"_blank\" ><img src=\"assets/templates/img/social/{$res['icon']}\" alt=\"\" /></a></li>";
    } else {
        echo 0;
    }
}
