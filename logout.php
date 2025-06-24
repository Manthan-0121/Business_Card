<?php
session_start();
if(!isset($_SESSION['uid']) && !isset($_SESSION['role'])){
    header("Location: login.php");
    exit();
}
unset($_SESSION['uid']);
unset($_SESSION['role']);

header("Location: login.php");
exit();
?>