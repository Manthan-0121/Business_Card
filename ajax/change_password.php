<?php

include '../includes/config.php';
session_start();
    if($_POST['old_password'] && $_POST['new_password'] && $_POST['confirm_password']) {
        
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if($new_password == $confirm_password) {
            $user_id = $_SESSION['uid'];
            $query = "SELECT password FROM tbl_user WHERE id = :uid";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':uid', $user_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $md5old_password = md5($old_password);
            if($row['password'] == $md5old_password) {
                $new_password = md5($new_password);
                $update_query = "UPDATE tbl_user SET password = :password WHERE id = :uid";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bindParam(':password', $new_password);
                $update_stmt->bindParam(':uid', $user_id);
                if($update_stmt->execute()) {
                    echo "Password changed successfully.";
                } else {
                    echo "Error updating password.";
                }
            } else {
                echo "Old password and new password is incorrect.";
            }
        } else {
            echo "New passwords and confirm password do not match.";
        }
    } else {
        echo "Please fill in all fields.";
    }
?>