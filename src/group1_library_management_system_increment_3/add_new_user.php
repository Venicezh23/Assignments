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
$id = mysqli_real_escape_string($conn, $_POST['id-input']);
$type = mysqli_real_escape_string($conn, $_POST['usertype-input']);
$email = mysqli_real_escape_string($conn, $_POST['email-input']);
$password = mysqli_real_escape_string($conn, $_POST['password-input']);
$password_hashed = md5($password); // Hashing password with MD5
$phnum = mysqli_real_escape_string($conn, $_POST['phnum-input']);

if (strpos($email, '@') != false) {
    echo "Please enter the email ID only.";
    exit;
}
$emailWithDomain = $email."@soton.ac.uk";

$idCheckQuery = "SELECT * FROM user WHERE id = '$id'";
$idResult = $conn->query($idCheckQuery);

if ($idResult->num_rows > 0) {
    echo "Student ID already exists in the database.";
    exit;
}

$emailCheckQuery = "SELECT * FROM user WHERE email = '$emailWithDomain'";
$emailResult = $conn->query($emailCheckQuery);

if ($emailResult->num_rows > 0) {
    echo "Email already exists in the database.";
    exit;
}

$sql = "INSERT INTO user (UserTypeID, ID, Email, LastName, FirstName, Password, PhoneNumber, TotalFine, RegisteredDate, IsVerified) 
        VALUES ('$type', '$id', '$emailWithDomain', '$lname', '$fname', '$password_hashed', '$phnum', '0', NOW(), '0')";

if ($conn->query($sql) === TRUE) {
    
    echo "New record created successfully.";
    $message = '<div>
            <p><b>Hello!</b></p>
            <p>You are receiving this email because you have been registered into the University of Southampton Malaysia\'s library system.</p>
            <br>
            <p>You may use your university email or ID to login.</p>
            <br>
            <p>Your password is: <b>'.$password.'</b></p>
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
        $mail->AddAddress($emailWithDomain);
        $mail->Subject = "Welcome to Southampton Library!";
        $mail->IsHTML(true);    
        $mail->SMTPDebug = 0; // Set to 0 for production
        $mail->Body = $message;
            
        try {
            if($mail->send()){
                $logMessage = "[" . date('Y-m-d H:i:s') . "] User ID: " . $_SESSION['ID'] . " have added a new User $id.";
                file_put_contents('login_log.txt', $logMessage . PHP_EOL, FILE_APPEND);
                exit();
            } else {
                $msg = "Email not delivered";
            }
            
        } catch (Exception $e){
            $msg="Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
