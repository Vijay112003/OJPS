<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Ensure PHPMailer is included

session_start();
include '../utils/db_connect.php';

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // Generate a random OTP
    $otp = rand(100000, 999999);

    // Store the OTP in the session for later verification
    $_SESSION['otp'] = $otp;

    // Prepare email
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0;                      // Disable verbose debug output
        $mail->isSMTP();                           // Set mailer to use SMTP
        $mail->Host       = 'smtp.gmail.com';      // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                  // Enable SMTP authentication
        $mail->Username   = 'your-email@gmail.com';// SMTP username
        $mail->Password   = 'your-password';       // SMTP password
        $mail->SMTPSecure = 'tls';                 // Enable TLS encryption, `ssl` also accepted
        $mail->Port       = 587;                   // TCP port to connect to

        //Recipients
        $mail->setFrom('no-reply@yourdomain.com', 'Your Name');
        $mail->addAddress($email);                 // Add a recipient

        // Content
        $mail->isHTML(true);                       // Set email format to HTML
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "<p>Your OTP for registration is <strong>$otp</strong>.</p>";

        $mail->send();
        echo 'otp_sent';
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        echo 'otp_failed';
    }
}
?>
