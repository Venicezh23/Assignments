<?php
include_once("connect-login.php");

session_start();

date_default_timezone_set('Asia/Kuala_Lumpur');
mysqli_query($conn, "SET time_zone = '+08:00'");


if(isset($_POST['send_otp'])){
    $email = $_POST['email'];
    $_SESSION['Reset'] = "Yes";
    $select_query = mysqli_query($conn, "SELECT `user`.*, `user_type`.`Type` FROM `user` JOIN `user_type`ON `user`.`UserTypeID`=`user_type`.`UserTypeID` WHERE Email='$email'");
    $res = mysqli_num_rows($select_query);
    $data = mysqli_fetch_array($select_query);
    
    if($res > 0){
        $_SESSION['ID'] = $data['UserID'];
        $_SESSION['Email'] = $_POST['email'];
        header('Location: send_otp_code.php');
        exit();
    }else {
        $error_message = "Invalid email. Please enter again.";
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
            <div class="emailver">Forgot Password</div>
            <div class="message">Please enter your email to receive a verification code.</div>
            <form method="POST"> 
                <input required type="text" name="email" placeholder="Enter your email">
                <div class="buttons">
                    <input id = "comfirmBtn" class="btn" type="submit" name="send_otp" value="CONFIRM"/>
                </div>
                
                <div style="display: none;" id='errorMessage' class="error-message"><?= $error_message?></div>
                
            </form>
            <div class="forgot-password-link">
                <a href="login.php">Cancel</a>
            </div>
        </div>
      </div>
  </div>
  <script>
    var errorText = document.getElementById("errorMessage");
    
    <?php if(isset($error_message)): ?>
        errorText.style.display = "block";
    <?php endif; ?>

</script>
</body>
</html>