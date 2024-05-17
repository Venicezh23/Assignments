<?php
session_start();

include 'connect-edit.php';

use PHPMailer\PHPMailer\PHPException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

// Include PHPMailer library files
require_once 'plugins/PHPMailer/src/Exception.php';
require_once 'plugins/PHPMailer/src/PHPMailer.php';
require_once 'plugins/PHPMailer/src/SMTP.php';

$fname = mysqli_real_escape_string($conn, $_POST['fname-input']);
$lname = mysqli_real_escape_string($conn, $_POST['lname-input']);
$password = $_POST['password-input'];
$phnum = mysqli_real_escape_string($conn, $_POST['phnum-input']);
$id = mysqli_real_escape_string($conn, $_POST['user-id']);

$pwd_select = "SELECT * FROM user WHERE `UserID` = '$id';";
$result = $conn->query($pwd_select);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
    if ($row["Password"] !== $password) {
        // Password needs to be updated, hash it
        $password_hashed = md5($password);
        $sql = "UPDATE user SET FirstName='$fname', LastName='$lname', Password='$password_hashed', PhoneNumber='$phnum' WHERE UserID='$id'";
        if (mysqli_query($conn, $sql)) {
          echo "Record updated successfully.";
          $message = '<div>
          <p><b>Hello!</b></p>
          <p>You are receiving this email because your University of Southampton Malaysia\'s library system\'s account has been renewed by the librarian.</p>
          <br>
          <p>You may use your new password to login.</p>
          <br>
          <p>Your password is: <b>'.$password.'</b></p>
          <br>
          <p>Contact stofuosm@soton.ac.uk if u did not request for your account to be renewed.</p>
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
          $mail->AddAddress($row["Email"]);
          $mail->Subject = "Your Password has been Renewed.";
          $mail->IsHTML(true);    
          $mail->SMTPDebug = 0; // Set to 0 for production
          $mail->Body = $message;
              
          try {
              if($mail->send()){
                $logMessage = "[" . date('Y-m-d H:i:s') . "] User ID: " . $_SESSION['ID'] . " have updated User $id's password.";
                file_put_contents('login_log.txt', $logMessage . PHP_EOL, FILE_APPEND);
                  exit();
              } else {
                  $msg = "Email not delivered";
              }
              
          } catch (Exception $e){
              $msg="Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
          }
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    } else {
        // Password remains the same, no need to hash it again
        $sql = "UPDATE user SET FirstName='$fname', LastName='$lname', Password='$password', PhoneNumber='$phnum' WHERE UserID='$id'";
        if (mysqli_query($conn, $sql)) {
          echo "Record updated successfully.";
          $message = '<div>
          <p><b>Hello!</b></p>
          <p>You are receiving this email because your University of Southampton Malaysia\'s library system\'s account has been renewed by the librarian.</p>
          <br>
          <p>You may use your email or university ID to login.</p>
          <br>
          <p>Contact stofuosm@soton.ac.uk if u did not request for your account to be renewed.</p>
          </div>';
            
          $mail = new PHPMailer(true);
          $mail->IsSMTP();
          $mail->SMTPAuth = TRUE;
          $mail->SMTPSecure = 'STARTTLS'; // tls or ssl
          $mail->Port     = "587";
          $mail->Username = "venice.czh@hotmail.com";
          $mail->Password = "bLFjPt7J?3gkLRs";
          $mail->SetFrom("venice.czh@hotmail.com", "UOSM Library");
          $mail->Host     = "smtp-mail.outlook.com";
          $mail->Mailer   = "smtp";
          $mail->AddAddress($row["Email"]);
          $mail->Subject = "Your Account Details has been Renewed.";
          $mail->IsHTML(true);    
          $mail->SMTPDebug = 0; // Set to 0 for production
          $mail->Body = $message;
              
          try {
              if($mail->send()){
                    $logMessage = "[" . date('Y-m-d H:i:s') . "] User ID: " . $_SESSION['ID'] . " have updated User $id's details.";
                    file_put_contents('login_log.txt', $logMessage . PHP_EOL, FILE_APPEND);
                  exit();
              } else {
                  $msg = "Email not delivered";
              }
              
          } catch (Exception $e){
              $msg="Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
          }
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    }
    
} else {
    echo "No user found.";
}

mysqli_close($conn);
?>

