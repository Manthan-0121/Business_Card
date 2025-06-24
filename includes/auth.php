<?php
if(isset($_SESSION['uid']) == null && isset($_SESSION['role']) == null){
    header("Location: login.php");
    exit();
}
?>