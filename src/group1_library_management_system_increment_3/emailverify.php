<?php
include_once("connect-login.php");

session_start();

date_default_timezone_set('Asia/Kuala_Lumpur');
mysqli_query($conn, "SET time_zone = '+08:00'");

if(empty($_SESSION['ID'])){
	header('location: login.php');
	exit(); 
}

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
    } else if($_SESSION['Login'] == "No"){
        header("location: login.php");
        exit();
    } else{
        header("location: login.php");
        exit();
    }
}

if(isset($_POST['otp_verify'])){
	$otp = $_POST['otp']; 
	$select_query = mysqli_query($conn, "SELECT * FROM ( SELECT * FROM `user_otp_login` WHERE UserID = '".$_SESSION['ID']."' ORDER BY `expiry_time` DESC LIMIT 1 ) AS sorted_table WHERE otp='" . $_POST["otp"] . "' AND is_expired = '0' AND NOW() <= expiry_time; ");
	$count = mysqli_num_rows($select_query);
	
	if($count > 0){
		$update_query = mysqli_query($conn, "UPDATE user_otp_login SET is_expired = '1' WHERE otp = '" . $_POST["otp"] . "'");
        $update_verification_query = mysqli_query($conn, "UPDATE `user` SET IsVerified = '1' WHERE UserID = '".$_SESSION['ID']."'; ");

        if($_SESSION['userType'] == "Librarian" && $_SESSION['Reset'] == "No"){
            $_SESSION['Login'] = "Yes";
            $_SESSION['Reset'] = "Yes";
            header("location: dashboard.php");
            exit();
        } else if($_SESSION['userType'] == "Admin" && $_SESSION['Reset'] == "No"){
            $_SESSION['Login'] = "Yes";
            $_SESSION['Reset'] = "Yes";
            header("location: admin_user_database.php");
            exit();
        } else if($_SESSION['Reset'] == "No"){
            $_SESSION['Login'] = "Yes";
            $_SESSION['Reset'] = "Yes";
            header("location: homepage.php");
            exit();
        } else if($_SESSION['Reset'] == "Yes"){
            header("location: reset_password.php");
            exit();
        }else{
            header("location: login.php");
            exit();
        }
	} else {
		$error_message = "Invalid OTP!";
	}
}

if(isset($_POST['resend_otp'])){
    header('Location: send_otp_code.php');
    exit;
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
        <div class="box-email">
            <div class="emailver">Email Verification</div>
            <div class="message">Enter the 6-digit code sent to your email.</div>
				<form method="POST" action="emailverify.php"> 
                    <div id="pwd" class="inputs">
                        <input class="input" type="text"
                            inputmode="numeric" maxlength="1" />
                        <input class="input" type="text"
                            inputmode="numeric" maxlength="1" />
                        <input class="input" type="text"
                            inputmode="numeric" maxlength="1" />
                        <input class="input" type="text"
                            inputmode="numeric" maxlength="1" />
                        <input class="input" type="text"
                            inputmode="numeric" maxlength="1" />
                        <input class="input" type="text"
                            inputmode="numeric" maxlength="1" />
                        <input type = "hidden" id="otpInput" name="otp">
                    </div>
                    <div style="display: none;" id='errorMessage' class="error-message"><?= $error_message?></div>
					<div><input id = "comfirmBtn" class="btn" type="submit" name="otp_verify" value="CONFIRM"/></div>
                    <button type="submit" class="resend-btn" name="resend_otp" id="resendButton">RESEND CODE</button>
				</form>
                <form method="POST">
                    <div class="forgot-password-link"><input class="cancel-btn" type="submit" name="cancel_verify" value="Cancel"/></div>
                </form>
        </div>
      </div>
  </div>
  <script>
    //show error message
    var errorText = document.getElementById("errorMessage");
    <?php if(isset($error_message)): ?>
        errorText.style.display = "block";
    <?php endif; ?>

    //the input for otp
    var inputOTP = document.getElementById("otpInput");
    var getTextBtn = document.getElementById("comfirmBtn");

    const inputs = document.getElementById("pwd");

    var inputElements = inputs.getElementsByTagName("input");
    var content = "";

    inputs.addEventListener("input", function (e) {
        const target = e.target;
        const val = target.value;
    
        if (isNaN(val)) {
            target.value = "";
            return;
        }
    
        if (val != "") {
            const next = target.nextElementSibling;
            if (next) {
                next.focus();
            }
        }
    });
    
    inputs.addEventListener("keyup", function (e) {
        const target = e.target;
        const key = e.key.toLowerCase();
    
        if (key == "backspace" || key == "delete") {
            target.value = "";
            const prev = target.previousElementSibling;
            if (prev) {
                prev.focus();
            }
            return;
        }
    });

    getTextBtn.addEventListener("click", function(e){
      for (var i = 0; i < inputElements.length; i++) {
        content += inputElements[i].value;
      }
      inputOTP.value = content;
    });
    

    // diasble resend code button for 1 minute
    var resendBtn = document.getElementById("resendButton");

    //run the disablebutton when the page is load
    window.onload = function() {
            disableButton();
        };
    
    function disableButton() {
        resendBtn.disabled = true;

        var duration = 60; // Duration in seconds
        var timer = setInterval(function() {
            duration--;
            if (duration >= 0) {
                resendBtn.style.opacity = '0.5';
                resendBtn.innerHTML = "RESEND CODE in " + duration + " s";
            }
            if (duration === 0) {
                clearInterval(timer);
                resendBtn.style.opacity = '1';
                resendBtn.innerHTML = "RESEND CODE";
                resendBtn.disabled = false;
            }
        }, 1000);
    }

</script>
</body>
</html>