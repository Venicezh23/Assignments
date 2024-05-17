<?php
session_start();

if (!empty($_SESSION['ID'])) {
    // Log the logout activity
    $logMessage = "[" . date('Y-m-d H:i:s') . "] User ID: " . $_SESSION['ID'] . " logged out.";
    file_put_contents('login_log.txt', $logMessage . PHP_EOL, FILE_APPEND);

    // Destroy the session
    session_destroy();
}

include_once('connect-login.php');

date_default_timezone_set('Asia/Kuala_Lumpur');
// Set timezone to Asia/Kuala_Lumpur for MySQL connection
mysqli_query($conn, "SET time_zone = '+08:00'");

use PHPMailer\PHPMailer\PHPException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

// Include PHPMailer library files
require_once 'plugins/PHPMailer/src/Exception.php';
require_once 'plugins/PHPMailer/src/PHPMailer.php';
require_once 'plugins/PHPMailer/src/SMTP.php';

$_SESSION['Login'] = "No";
$_SESSION['Reset'] = "No";

if(isset($_POST['login'])){
  $info = $_POST['emailOrID'];
  $password = $_POST['password'];
  $password_hased = md5($password);
  $select_query = mysqli_query($conn, "SELECT `user`.*, `user_type`.`Type` FROM `user` JOIN `user_type`ON `user`.`UserTypeID`=`user_type`.`UserTypeID` WHERE ((Email ='$info' && Email LIKE '%@soton.ac.uk')|| ID = '$info') && Password='$password_hased';");
  $select_setting_query = mysqli_query($conn, "SELECT * FROM `setting`;");
  $res = mysqli_num_rows($select_query);
  $res_setting = mysqli_num_rows($select_setting_query);
  
  if($res > 0 && $res_setting > 0){
        $data = mysqli_fetch_array($select_query);
        $setting = mysqli_fetch_array($select_setting_query);

        $isVerified = $data['IsVerified'];
        $_SESSION['ID'] = $data['UserID'];
        $_SESSION['Email'] = $data['Email'];;
        $_SESSION['userType'] = $data['Type'];

        // Log successful login activity
        $logMessage = "[" . date('Y-m-d H:i:s') . "] User ID: " . $_SESSION['ID'] . " logged in successfully.";
        file_put_contents('login_log.txt', $logMessage . PHP_EOL, FILE_APPEND);

        $_SESSION['ReturnDay'] = $setting['ReturnDate'];
        $_SESSION['ExtendDay'] = $setting['ExtendDate'];
        $_SESSION['BorrowLimit'] = $setting['BorrowLimit'];
        $_SESSION['ExtendLimit'] = $setting['ExtendLimit'];
        $_SESSION['OverdueFineAmount'] = $setting['OverdueFine'];
        $_SESSION['StartTime'] = $setting['StartTime'];
        $_SESSION['EndTime'] = $setting['EndTime'];
        $_SESSION['LibraryEmail'] = $setting['Email'];
        $_SESSION['LibraryPhoneNo'] = $setting['PhoneNo'];
        $_SESSION['QrCodePay'] = $setting['QrCodePay'];

        if($isVerified == 0){
            $_SESSION['Reset'] = "No";
            header('Location: send_otp_code.php');
            exit();
        }else{
            if($_SESSION['userType'] == "Librarian"){
                $_SESSION['Login'] = "Yes";
                $_SESSION['Reset'] = "Yes";
                header("location: dashboard.php");
                exit();
            }else if($_SESSION['userType'] == "Admin"){
                $_SESSION['Login'] = "Yes";
                $_SESSION['Reset'] = "Yes";
                header("location: admin_user_database.php");
                exit();
            }else{
                $_SESSION['Login'] = "Yes";
                $_SESSION['Reset'] = "Yes";
                header("location: homepage.php");
                exit();
            }
        }
  } else {
        $error_message = "Invalid input. Please enter again.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Login</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" />
</head>
<body>
    <div style="width: 100%;">
        <img src="https://pbs.twimg.com/media/GIZs0bMbAAA5ZeK?format=png&name=small" width="275" height="230" style="padding-left:20px;margin-top:-3rem;">
        <div style="width: 60%; float: left;" class="leftside"> 
            <div class="bigtitle">WELCOME TO OUR <br> LIBRARY SYSTEM!</div>
            <div class="subtitle">Please fill in your details to login.</div>
        </div>
        <div class="rightside">
            <div class="login">Login</div>
            <div class="email">

                <form method="POST">
                    <div class="form-group">
                        <label for="emailOrID">Email/Student ID:</label><br>
                        <input required class="input-box" type="text" id="emailOrID" name="emailOrID" placeholder="Enter your email or student id">
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label><br>
                        <div class="password-input-container">
                            <input required class="input-box" type="password" id="password" name="password" placeholder="Enter your password">
                            <button class="eyeBtn" type="button" onmousedown="showPassword()" onmouseup="hidePassword()">
                                <img src="https://cdn-icons-png.freepik.com/256/5368/5368971.png?ga=GA1.1.1201796896.1698075133&" width="20px" height="20px">
                            </button>
                        </div>
                    </div>

                    <div class="forgot-password-link">
                        <a href="forgot_password.php">Forgot Password</a>
                    </div>

                    <div style="display: none;" id='errorMessage' class="error-message"><?= $error_message?></div>

                    <button name="login" class="login-button">Login</button>
                </form>

            </div>
        </div>
    </div>
    <script>
        var errorText = document.getElementById("errorMessage");
        var passwordInput = document.getElementById("password");
        var button = document.querySelector("button");

        function showPassword() {
            passwordInput.type = "text";
        }

        function hidePassword() {
            passwordInput.type = "password";
        }

        <?php if(isset($error_message)): ?>
            errorText.style.display = "block";
        <?php endif; ?>
    </script>
</body>
</html>
