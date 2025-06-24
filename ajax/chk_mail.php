<?php
include("./includes/config.php");

if (isset($_POST["txt_email"])) {
    $txt_email = $_POST["txt_email"];

    $query = "SELECT id FROM tbl_user WHERE email = ':email'";
    $stmt = $conn->prepare($query);
    $email = $stmt->bindParam(":email", $txt_email,PDO::PARAM_STR);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo 1;
    }else{
        echo 0;
    }
    
}
