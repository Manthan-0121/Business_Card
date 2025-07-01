<?php
include('./includes/config.php');
include 'phpqrcode/qrlib.php';

if (!isset($_GET['token'])) {
    echo "<script>window.location.href = 'show_cards.php';</script>";
    exit;
}

$token = $_GET['token'];
$chk_token_sql = "SELECT id, name, template_id FROM tbl_business_info WHERE link_token = :token";

$stmt = $conn->prepare($chk_token_sql);
$stmt->bindParam(':token', $token, PDO::PARAM_STR);
$stmt->execute();
$business_card = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$business_card) {
    echo "<script>window.location.href = 'show_cards.php';</script>";
    exit;
}
if($business_card['template_id'] == 1) {
    $data = BASE_URL.'share/card.php?token=' . urlencode($token);
}else{
    $data = BASE_URL.'share/card2.php?token=' . urlencode($token);
}

$tempFile = 'qrcode_' . uniqid() . '.png';
QRcode::png($data, $tempFile, QR_ECLEVEL_L, 5);

header('Content-Type: image/png');
header('Content-Disposition: attachment; filename="' . preg_replace('/[^a-zA-Z0-9]/', '_', $business_card['name']) . '_qr.png"');
readfile($tempFile);

unlink($tempFile);
exit;
