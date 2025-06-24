<?php
include('./includes/config.php');
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $business_card_select_sql = "SELECT binfo.id AS bid, binfo.name AS bname, binfo.logo AS blogo, binfo.status AS bstatus, binfo.link_token AS btoken, bc.name AS bcategory FROM tbl_business_info AS binfo INNER JOIN tbl_business_category AS bc ON binfo.business_category_id = bc.id WHERE binfo.link_token = :token";
    $stmt = $conn->prepare($business_card_select_sql);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();
    $business_card = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
        <title></title>
        <link rel="stylesheet" href="assets/css/app.min.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/css/components.css">
        <link rel="stylesheet" href="assets/css/custom.css">
        <link rel='shortcut icon' type='image/x-icon' href='assets/img/favicon.ico' />
    </head>

    <body>
        <div class="loader"></div>
        <div id="app">
            <section class="section">
                <div class="container mt-5">
                    <div class="page-error">
                        <div class="page-inner">
                            <h1>404</h1>
                            <div class="page-description">
                                The page you were looking for could not be found.
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <script src="assets/js/app.min.js"></script>
        <script src="assets/js/scripts.js"></script>
        <script src="assets/js/custom.js"></script>
    </body>


    </html>
<?php
}
?>