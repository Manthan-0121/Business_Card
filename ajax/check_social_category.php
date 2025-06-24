<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "../includes/config.php";

    $platform_name = $_POST['platform_name'];

    $sel_sql = "SELECT * FROM tbl_social_category WHERE platform_name = :platform_name";

    $stmt = $conn->prepare($sel_sql);
    $stmt->bindParam(':platform_name', $platform_name, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->rowCount();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($count > 0) {
        if($res['id'] == $_POST['id']){
            echo 2;
        } else {
            echo 1;
        }
    } else {
        echo 0;
    }
}