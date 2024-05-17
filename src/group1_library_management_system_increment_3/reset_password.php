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

if(isset($_POST['cancel_verify'])){
    if($_SESSION['userType'] == "Librarian" && $_SESSION['Login'] == "Yes"){
        header("location: dashboard.php");
        exit();
    } else if($_SESSION['userType'] == "Admin" && $_SESSION['Login'] == "Yes"){
        header("location: admin_user_database.php");
        exit();
    } else if($_SESSION['Login'] == "Yes"){
        header("location: homepage.php");
        exit();
    }  else if($_SESSION['Login'] == "No"){
        header("location: login.php");
        exit();
    } else{
        header("location: login.php");
        exit();
    }
}

if(isset($_POST['comfirm'])){
    $newpwd = $_POST['newpassword'];
    $cmfpwd = $_POST['comfirmpassword'];

    //password is more than 7 and contain 3 numbers and contain both upper and lower case
    if(strlen($newpwd) < 6){
        $error_message = "Password must be at least 8 characters long.";
    } elseif (preg_match_all('/[0-9]/', $newpwd) < 3) {
        $error_message = "Password must contain 3 numbers.";
    } elseif (!preg_match('/[a-z]/', $newpwd) || !preg_match('/[A-Z]/', $newpwd)) {
        $error_message = "Password must contain both uppercase and lowercase letters.";
    } else if (preg_match_all('/[!@#$£%^&*(-)+=_{}[\]\\\\|:;\'<>,.?\/¬~]/', $newpwd) < 3) {
		$error_message = "Password must contain 3 special characters";
	} else if ($newpwd !== $cmfpwd){
        $error_message = "Both password are not the same!";
    } else{
        $message = '<div>
            <p><b>Hello!</b></p>
            <p>You are receiving this email because your <b>library account\'s password</b> have been successfully reset.</p>
            <br>
            <p>If you did not reset your password, please contact stofuosm.soton.ac.uk.</p>
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
        $mail->Subject = "Password was Reset";
        $mail->IsHTML(true);    
        $mail->SMTPDebug = 1; // Set to 0 for production
        $mail->Body = $message;

        try {
            if($mail->send()){
                $password_hashed = md5($newpwd);
                $update_query = mysqli_query($conn, "UPDATE `user` SET `Password`= '$password_hashed' WHERE `UserID` = '" . $_SESSION['ID'] . "'");
                
                $logMessage = "[" . date('Y-m-d H:i:s') . "] User ID: " . $_SESSION['ID'] . " have reset their password";     
                file_put_contents('login_log.txt', $logMessage . PHP_EOL, FILE_APPEND);
                
                if($_SESSION['userType'] == "Librarian" && $_SESSION['Login'] == "Yes"){
                    header("location: dashboard.php");
                    exit();
                } else if($_SESSION['userType'] == "Admin" && $_SESSION['Login'] == "Yes"){
                    header("location: admin_user_database.php");
                    exit();
                } else if($_SESSION['Login'] == "Yes"){
                    header("location: homepage.php");
                    exit();
                } else if($_SESSION['Login'] == "No"){
                    header("location: login.php");
                    exit();
                } else{
                    header("location: login.php");
                    exit();
                }
            } else {
                $error_message = "Email not delivered";
            }
        } catch (Exception $e) {
            $error_message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link rel="stylesheet" href="emailverify.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" />
</head>
<body>
  <div>
  <img src="https://pbs.twimg.com/media/GIZs0bMbAAA5ZeK?format=png&name=small" width="275" height="230" style="padding-left:20px;margin-top:-3rem;">
    <div class="container">
        <div class="box">
            <div class="emailver">Reset Password</div>
            <div class="message">Enter your new password.</div>
            <form method="POST"> 
                <div class="form-group">
                    <label for="password">New Password:</label><br>
                    <div class="password-input-container">
                        <input required class="input-box" type="password" id="newpassword" name="newpassword" placeholder="Enter new password">
                        <button class="eyeBtn" type="button" onmousedown="showNewPassword()" onmouseup="hideNewPassword()">
                            <img src="https://cdn-icons-png.freepik.com/256/5368/5368971.png?ga=GA1.1.1201796896.1698075133&" width="20px" height="20px">
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Comfirm New Password:</label><br>
                    <div class="password-input-container">
                        <input required class="input-box" type="password" id="comfirmpassword" name="comfirmpassword" placeholder="Confirm new password">
                        <button class="eyeBtn" type="button" onmousedown="showComfirmPassword()" onmouseup="hideComfirmPassword()">
                            <img src="https://cdn-icons-png.freepik.com/256/5368/5368971.png?ga=GA1.1.1201796896.1698075133&" width="20px" height="20px">
                        </button>
                    </div>
                </div>
                <div style="display: none;" id='errorMessage' class="error-message"><?= $error_message?></div>
                <div class="buttons">
                    <input id = "comfirmBtn" class="btn" type="submit" name="comfirm" value="CONFIRM"/>
                </div>
            </form>
            <form method="POST">
                <div class="forgot-password-link"><input class="cancel-btn" type="submit" name="cancel_verify" value="Cancel"/></div>
            </form>
        </div>
      </div>
  </div>
  <script>
    var newPasswordInput = document.getElementById("newpassword");
    function showNewPassword() {newPasswordInput.type = "text";}
    function hideNewPassword() {newPasswordInput.type = "password";}

    var comfirmPasswordInput = document.getElementById("comfirmpassword");
    function showComfirmPassword() {comfirmPasswordInput.type = "text";}
    function hideComfirmPassword() {comfirmPasswordInput.type = "password";}

    var errorText = document.getElementById("errorMessage");
    <?php if(isset($error_message)){ ?>
        errorText.style.display = "block";
    <?php } ?>

</script>
</body>
</html>