<?php
include_once("connect-login.php");

session_start();

date_default_timezone_set('Asia/Kuala_Lumpur');
mysqli_query($conn, "SET time_zone = '+08:00'");

use PHPMailer\PHPMailer\PHPException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once 'plugins/PHPMailer/src/Exception.php';
require_once 'plugins/PHPMailer/src/PHPMailer.php';
require_once 'plugins/PHPMailer/src/SMTP.php';

// Generate OTP
$otp = rand(100000, 999999);
// Send OTP

$message = '<div>
    <p><b>Hello!</b></p>
    <p>You are receiving this email because we received an OTP request for your account.</p>
    <br>
    <p>Your OTP is: <b>'.$otp.'</b></p>
    <br>
    <p>If you did not request OTP, no further action is required.</p>
    </div>';

$mail = new PHPMailer(true);
$mail->IsSMTP();
$mail->SMTPAuth = TRUE;
$mail->SMTPSecure = 'STARTTLS'; // tls or ssl
$mail->Port     = "587";
$mail->Username = "***library email***";
$mail->Password = "***library password***";
$mail->SetFrom("***library email***", "UOSM Library");
$mail->Host     = "smtp-mail.outlook.com";
$mail->Mailer   = "smtp";
$mail->AddAddress($_SESSION['Email']);
$mail->Subject = "OTP to Login";
$mail->IsHTML(true);    
$mail->SMTPDebug = 1; // Set to 0 for production
$mail->Body = $message;

try {
    if($mail->send()){
        $current_time = date('Y-m-d H:i:s');
        $otp_expiry_time = date('Y-m-d H:i:s', strtotime('+5 minutes')); // Example: OTP expires in 5 minutes
        $insert_query = mysqli_query($conn, "INSERT INTO user_otp_login (UserID, otp, is_expired, create_at, expiry_time) VALUES ('" . $_SESSION['ID'] . "', '$otp', '0', '$current_time', '$otp_expiry_time')");
        
        $logMessage = "[" . date('Y-m-d H:i:s') . "] User ID: " . $_SESSION['ID'] . " have requested an OTP Code.";
        file_put_contents('login_log.txt', $logMessage . PHP_EOL, FILE_APPEND);

        header('Location: emailverify.php');
        exit();
    } else {
        $error_message = "Email not delivered";
    }
} catch (Exception $e) {
    $error_message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>