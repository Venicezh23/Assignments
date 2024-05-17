<?php
include_once('connect-login.php');

if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

session_start();

if(empty($_SESSION['ID'])){
    header('location: login.php');
    exit(); 
}


//to add timezone
date_default_timezone_set('Asia/Kuala_Lumpur');
mysqli_query($conn, "SET time_zone = '+08:00'");

//update all isPaid = 0 rows

$updateFineQuery = "UPDATE fine SET IsPaid='1' WHERE UserID = ? AND IsPaid = '0' AND DateCompleteFine IS NOT NULL";
$stmt = $conn->prepare($updateFineQuery);
$stmt->bind_param("i", $_SESSION['ID']);

if ($stmt->execute()) { // Execute the prepared statement
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="payment_success.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Payment</title>
</head>    
<body>
<div class="pay-fine-success-1-v-3">
  <div class="white-with-transparent-background-3">
  </div>
  <div class="container">
    <div class="payment">
      Payment
    </div>
    <div class="payment-successful">
      PAYMENT SUCCESSFUL!
    </div>
    <p class="you-will-be-redirected-to-the-homepage-in-10-seconds">
      <span class="you-will-be-redirected-to-the-homepage-in-10-seconds-sub-0">
        You will be redirected to the homepage in <b>10 seconds</b>.
      </span>
    </p>
  </div>
</div>
<script>
    // Delayed redirect function
    setTimeout(function() {
        window.location.href = 'homepage.php';
    }, 10000); // 10 seconds delay
</script>
</body>
</html>
<?php
//update user's total fine to 0
	$updateUserTotalFine = "UPDATE user SET TotalFine = '0' WHERE UserID = ? ";
	$userStmt = $conn->prepare($updateUserTotalFine);
	$userStmt->bind_param("i", $_SESSION['ID']);
	$userStmt->execute();

  $logMessage = "[" . date('Y-m-d H:i:s') . "] User ID: " . $_SESSION['ID'] . " have paid their fines: $book_id.";
  file_put_contents('login_log.txt', $logMessage . PHP_EOL, FILE_APPEND);
    exit;
} else {
    $error_message = "Error updating record: " . $conn->error;
}
?>
