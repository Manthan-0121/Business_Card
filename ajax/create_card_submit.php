<?php
require_once '../config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo json_encode($_POST["formData"]);
    exit;
}
