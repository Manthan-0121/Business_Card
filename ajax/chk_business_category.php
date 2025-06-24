<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "../includes/config.php";

    $name = $_POST['name'];

    if($name == ""){
        echo "Please enter Business Category name";
    }else{
        $sel_sql = "SELECT * FROM tbl_business_category WHERE name = :name";
    
        $stmt = $conn->prepare($sel_sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->rowCount();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($count > 0) {
            if ($res['id'] == $_POST['id']) {
                echo 2;
            } else {
                echo 1;
            }
        } else {
            echo 0;
        }
    }
}
