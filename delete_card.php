<?php
session_start();
if ($_GET['token']) {
    include "./includes/config.php";
    if ($_GET['token']) {
        $token = $_GET['token'];
        $stmt = $conn->prepare("SELECT * FROM tbl_business_info WHERE link_token = ?");
        $stmt->bindParam(1, $token);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $stmt = $conn->prepare("DELETE FROM tbl_business_info WHERE link_token = ?");
            $stmt->bindParam(1, $token);
            if ($stmt->execute()) {
                $_SESSION['success'] = "Card deleted successfully!";
                echo "<script>window.location.href = 'show_cards.php';</script>";
            } else {
                $_SESSION['error'] = "Error deleting card!";
                echo "<script>window.location.href = 'show_cards.php';</script>";
            }
        } else {
            $_SESSION['error'] = "Card not found!";
        }
    } else {
        echo "<script>window.location.href = 'show_cards.php';</script>";
    }
} else {
    echo "<script>window.location.href = 'show_cards.php';</script>";
}
