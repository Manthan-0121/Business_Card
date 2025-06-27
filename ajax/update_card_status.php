<?php
require_once '../includes/config.php';
if (isset($_POST['token']) && isset($_POST['status'])) {
    $token = $_POST['token'];
    $status = $_POST['status'];

    if (!empty($token) && ($status == 0 || $status == 1)) {
        // Update the card status in the database
        $query = "UPDATE tbl_business_info SET status = :status WHERE link_token = :token";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":status", $status, PDO::PARAM_INT);
        $stmt->bindParam(":token", $token, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo "1";
        } else {
            echo "0 Failed to update status";
        }
    } else {
        echo "0 not found";
    }
} else {
    echo "0 Invalid request";
}
?>