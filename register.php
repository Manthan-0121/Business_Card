<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Business Card</title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="assets/css/app.min.css">
    <link rel="stylesheet" href="assets/bundles/bootstrap-social/bootstrap-social.css">
    <!-- Template CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel='shortcut icon' type='image/x-icon' href='assets/img/favicon.ico' />
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4>Register</h4>
                            </div>
                            <div id="link_send_msg"></div>
                            <div class="card-body">
                                <form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>" id="registrationForm">
                                    <div class="form-group">
                                        <label for="first_name">First Name</label><span class="text-danger">*</span>
                                        <input id="first_name" type="text" class="form-control" name="first_name" required autofocus>
                                        <div class="invalid-feedback">Please enter your first name</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="middle_name">Middle Name</label>
                                        <input id="middle_name" type="text" class="form-control" name="middle_name">
                                    </div>

                                    <div class="form-group">
                                        <label for="last_name">Last Name</label><span class="text-danger">*</span>
                                        <input id="last_name" type="text" class="form-control" name="last_name" required>
                                        <div class="invalid-feedback">Please enter your last name</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email</label><span class="text-danger">*</span>
                                        <input id="email" type="email" class="form-control" name="email" required>
                                        <div class="invalid-feedback">Please enter a valid email</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="mobile">Mobile</label>
                                        <input id="mobile" type="text" class="form-control" name="mobile">
                                        <div class="invalid-feedback">Please enter your mobile number</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <span class="text-danger">*</span>
                                        <div class="input-group">
                                            <input id="password" type="password" class="form-control" name="password" required>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary toggle-password" type="button" onclick="togglePassword('password')">
                                                    Show
                                                </button>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback">Please enter a password</div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                                            Register
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="mt-5 text-muted text-center">
                            Already have an account? <a href="login.php">Login Here</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <!-- General JS Scripts -->
    <script src="assets/js/app.min.js"></script>
    <!-- Template JS File -->
    <script src="assets/js/scripts.js"></script>
    <!-- Custom JS File -->
    <script src="assets/js/custom.js"></script>

    <script src="assets/js/myapp.js"></script>


    <!-- show password toggle -->
    <script>
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const button = input.nextElementSibling.querySelector('button, .toggle-password');
            if (input.type === 'password') {
                input.type = 'text';
                button.textContent = 'Hide';
            } else {
                input.type = 'password';
                button.textContent = 'Show';
            }
        }
    </script>

    <!-- email exist chk -->
    <script>
        $('#email').on('keyup', function() {
            let chk_txt_email = document.getElementById("email").value;
            $.ajax({
                type: "POST",
                url: "./ajax/chk_mail.php",
                data: {
                    txt_email: chk_txt_email.toLowerCase()
                },
                success: function(data) {
                    if (data == 1) {
                        $('#email').addClass('is-invalid');
                    } else {
                        $('#email').removeClass('is-invalid');
                    }
                }
            });
        });
    </script>
</body>

</html>

<!-- register store and send mail -->
<?php
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $password = md5($_POST['password']);

    $sql = "INSERT INTO tbl_user (first_name, middle_name, last_name, email, mobile, password) VALUES (:fname, :mname, :lname, :email, :mobile, :pwd)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":fname", $first_name);
    $stmt->bindParam(":mname", $middle_name);
    $stmt->bindParam(":lname", $last_name);
    $stmt->bindParam(":email", strtolower($email));
    $stmt->bindParam(":mobile", $mobile);
    $stmt->bindParam(":pwd", $password);
    $stmt->execute();
    $ls_id = $conn->lastInsertId();
    $token = bin2hex(random_bytes(15));


    //add date 
    $now = new DateTime();
    $now->add(new DateInterval('PT10M'));
    $timePlus10 = $now->format('Y-m-d H:i:s');

    //insert tbl_verification 
    $ins_verify_link = "INSERT INTO tbl_verification (user_id, verification_time, token) VALUES (:uid, :vtime, :token)";
    $stmt = $conn->prepare($ins_verify_link);
    $stmt->bindParam(":uid", $ls_id);
    $stmt->bindParam(":vtime", $timePlus10);
    $stmt->bindParam(":token", $token);
    $stmt->execute();

    // send verification mail
    // $mail = new PHPMailer(true);

    //     try {
    //         // Server settings
    //         $mail->isSMTP();
    //         $mail->Host       = 'smtp.gmail.com';
    //         $mail->SMTPAuth   = true;
    //         $mail->Username   = 'manthan.jdg@gmail.com';
    //         $mail->Password   = 'hsdnmgitkcsfcwcw';
    //         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    //         $mail->Port       = 587;

    //         // Recipients
    //         $mail->setFrom('manthan.jdg@gmail.com', 'Manthan Mistry');
    //         $mail->addAddress("$email", "$first_name $last_name");
    //         $verify_link = "http://localhost/manthan/JDG_UPDATE/12_05_2025/Task_02/verify.php?token=$token";;
    //         // Content
    //         $mail->isHTML(true);
    //         $mail->Subject = 'Email Verification Required';
    //         $mail->Body    = "
    //             <!DOCTYPE html>
    // <html lang=\"en\">
    //   <head>
    //     <meta charset=\"UTF-8\" />
    //     <title>Email Verification</title>
    //   </head>
    //   <body style=\"font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;\">
    //     <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
    //       <tr>
    //         <td align=\"center\" style=\"padding: 20px 0;\">
    //           <table width=\"600\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);\">
    //             <tr>
    //               <td style=\"padding: 30px;\">
    //                 <h2 style=\"color: #333333;\">Hello, $first_name $last_name</h2>
    //                 <p style=\"color: #555555; font-size: 16px;\">
    //                   Thank you for registering. To complete your registration, please verify your email address by clicking the button below:
    //                 </p>
    //                 <p style=\"text-align: center; margin: 30px 0;\">
    //                   <a href=\"$verify_link\" style=\"background-color: #007bff; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;\">
    //                     Verify Email
    //                   </a>
    //                 </p>
    //                 <p style=\"color: #555555; font-size: 14px;\">
    //                   If the button doesn’t work, copy and paste this URL into your browser:
    //                 </p>
    //                 <p style=\"word-break: break-all;\">
    //                   <a href=\"$verify_link\" style=\"color: #007bff;\">$verify_link</a>
    //                 </p>
    //                 <hr style=\"border: none; border-top: 1px solid #dddddd; margin: 30px 0;\" />
    //                 <p style=\"color: #888888; font-size: 13px;\">
    //                   If you did not request this, please ignore this email.
    //                 </p>
    //               </td>
    //             </tr>
    //             <tr>
    //               <td style=\"background-color: #f0f0f0; text-align: center; padding: 15px; font-size: 12px; color: #999999;\">
    //                 &copy; 2025 Business Card. All rights reserved.
    //               </td>
    //             </tr>
    //           </table>
    //         </td>
    //       </tr>
    //     </table>
    //   </body>
    // </html>
    //         ";
    //         $mail->AltBody = "Hello $first_name,\nPlease verify your email using this link: $verify_link\nIf you did not request this, ignore this email.";

    //         $mail->send();
?>
    <script>
        let link_send_msg = document.getElementById("link_send_msg");

        link_send_msg.innerHTML = `
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    Verification link has been sent to your email.
                </div>
            `;
    </script>
    <?php
    // } catch (Exception $e) {
    //     echo "<script>console.log('Email could not be sent. Error: {$mail->ErrorInfo}')</script>";
    // }
    ?>
<?php
}
?>