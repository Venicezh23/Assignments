<?php
include_once('connect-login.php');

if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

session_start();
//echo $_SESSION['patronID'];

date_default_timezone_set('Asia/Kuala_Lumpur');
mysqli_query($conn, "SET time_zone = '+08:00'");

use PHPMailer\PHPMailer\PHPException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once 'plugins/PHPMailer/src/Exception.php';
require_once 'plugins/PHPMailer/src/PHPMailer.php';
require_once 'plugins/PHPMailer/src/SMTP.php';


if(empty($_SESSION['ID'])){
    header('location: login.php');
    exit(); //librarian stays logged in

}

$qrcode = $_SESSION['QrCodePay'];

$sql = "SELECT b.Title, bi.DueDate, bi.DateReturn, f.FineAmount, u.TotalFine, f.BookIssuedID, f.FineType, u.Email
        FROM book_issued bi 
        JOIN book b ON bi.BookID = b.BookID
        JOIN fine f ON bi.BookIssuedID = f.BookIssuedID
        JOIN user u ON f.UserID = u.UserID
        WHERE bi.UserID = ?
        AND f.IsPaid = '0'";

//prepare statement
$stmt = $conn->prepare($sql);
//bind parameter
$stmt->bind_param("s", $_SESSION['patronID']);
//execute
$stmt->execute();
//get result
$result = $stmt->get_result();

if(!$result){
	die("Invalid query: " . $conn->error);
}

$totalAmountDue = 0; //init 
$latestOverdueFineAmount = $_SESSION['OverdueFineAmount'];
$patronEmail = '';

// Calculate total fine amount
while ($row = $result->fetch_assoc()){
	if($row['FineType'] == 'Overdue'){
		$fineAmount = $row["FineAmount"] * $latestOverdueFineAmount;
		$dueDate = new DateTime($row["DueDate"]);
		$returnDate = new DateTime($row["DateReturn"]);
		if ($returnDate > $dueDate) { //only if the return date is more than due date
			$daysOverdue = $returnDate->diff($dueDate)->days; // Calculates the difference in days
		} else {
			$daysOverdue = 0;
		}
		 
		$amountDue = $daysOverdue;
		$totalAmountDue += $fineAmount; //accumulate here
	} else {
		$fineAmount = $row["FineAmount"];
		$totalAmountDue += $fineAmount; //accumulate here
	}
	
	$patronEmail = $row['Email'];
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check which button was clicked
    $buttonClicked = $_POST['payment_method'];
	
	if ($buttonClicked == null || $buttonClicked == ''){
		echo "<script>alert('Please select a payment method!'); window.location.href = 'payment_method.php';</script>";
	} else {
		$message = "<div>
			<p>Hello!</p>
			<p>You have successfully made payment using $buttonClicked.</p>
			<p>Please do not reply to this email.</p>
			<p>If you did not make payment, please contact your librarian.</p>
			</div>";

		// Update fine table where isPaid = 0
		// ALSO: update paymentType (either cash, credit ...)
		//change fine query: only update the rows isPaid = 0 and matches userID with -- paymentType filled in + date completed payment
		//so in payment invoice can detect which rows are isPaid = 0 AND paymentType either Credit Card / Cash + date completed NOT NULL
		$updateFineQuery = "UPDATE fine SET DateCompleteFine = NOW(), PaymentType = ? WHERE UserID = ? AND IsPaid = '0'";
		$stmt = $conn->prepare($updateFineQuery);
		$paymentTypeValue = ($buttonClicked == 'Cash') ? 'Cash' : (($buttonClicked == 'QR Code') ? 'QR Code' : 'Credit Card');
		$stmt->bind_param("si", $paymentTypeValue, $_SESSION['patronID']);

		if ($stmt->execute()) { // Execute the prepared statement
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
				$mail->AddAddress($patronEmail);
				$mail->Subject = "Payment Invoice";
				$mail->IsHTML(true);    
				$mail->SMTPDebug = 0; // Set to 0 for production
				$mail->Body = $message;
				
				// Send the email
				try {
					  if($mail->send()){
						  //echo $_GET['patron'];
						  header("Location: payment_invoice_librarian.php");
						   //echo "<script>window.location.href = 'payment_invoice_librarian.php?patron=' + encodeURIComponent('" . $_GET['patron'] . "');</script>";
						  //redirectToPaymentSuccess(actualValue);
						  exit;
					  } else {
						  $error_message = "Email not delivered";
					  }
				  } catch (Exception $e) {
					  $error_message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
				  }
			
		} else {
			$error_message = "Error updating record: " . $conn->error;
		}
	}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="payment_method.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Payment</title>
	
	<style>
	.rectangle-44 {
	  background: url('<?php echo $qrcode; ?>') 50% / contain no-repeat;
	  margin-bottom: 0.4rem;
	  margin-top: 0.1rem;
	  align-self: flex-start;
	  width: 7.4rem;
	  height: 6.6rem;
	}
	</style>
</head>	
<body>
<div class="pay-fine-v-2">
  <div class="white-with-transparent-background-3">
  </div>
  <div class="container-1">
    <div class="payment">
      Choose a Payment Method
    </div>
    <div class="container-3">
		<!--credit card container-->
		<div class="container">
			<button type="button" name="cardBtn" id = "cardBtn" class="button" value="card">
				<div class="rectangle-42"></div>
				<span class="credit-card">Credit Card</span>
			</button>
		</div>
	  <!--cash container-->
		<div class="container-4">
			<button type="button" name="cash-btn" id="cash-btn" class="button" value="cash">
				<div class="rectangle-43"></div>
				<span class="cash">Cash</span>
			</button>
		</div>

		<!--QR container-->
		<div class="container-5">
			<button type="button" name="qr-btn" id="qr-btn" class="button" value="qr">
				<div class="rectangle-44"></div>
				<span class="qr">QR Code</span>
			</button>
		</div>
    </div>
	
	<?php
	 // Display total amount due below the table
	echo "<div class='total-rmx-xx'>
			TOTAL: RM" . $totalAmountDue . "
		  </div>";
	?>
	
	<div class="container-2">
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		  <!--go back to homepage button, redirect to homepage.php-->
			<button id = "goBackBtn" class="go-back-to-homepage" onclick="return goBackOne()">
			  GO BACK</button>
			<button id = "payBtn" class="pay" onclick="redirectToPaymentSuccess()">PAY</button>
			<input type="hidden" name="payment_method" id="payment_method" value="">
		</form>
	</div>
  </div>
</div>

<script>

    // Function to change image and text color when credit card button is clicked
    function changeCreditCardButton() {
        var creditCardBtn = document.getElementById('cardBtn');
        var imageElement = creditCardBtn.querySelector('.rectangle-42'); // Select the image element

		creditCardBtn.classList.add('highlight1');  
		document.getElementById('cash-btn').classList.remove('highlight2');

        // Change text color
        creditCardBtn.querySelector('.credit-card').style.color = 'white';

        // Change image source
        if (imageElement) { //white credit card image
            imageElement.style.background = "url('https://pbs.twimg.com/media/GJ7LtgXaEAAM2mB?format=png&name=240x240') 50% / contain no-repeat";
        }

		var cashBtn = document.getElementById('cash-btn');
		var imageElement2 = cashBtn.querySelector('.rectangle-43');
		
		//Change text colour back to original
		cashBtn.querySelector('.cash').style.color = '#000000';
		
		// Change image source back to original
        if (imageElement2) { //black cash image
            imageElement2.style.background = "url('https://pbs.twimg.com/media/GJ7M3TdagAA5r4A?format=png&name=small') 50% / contain no-repeat";
        }
		
		//revert qr btn
		var qrBtn = document.getElementById('qr-btn');
		var imageElement3 = qrBtn.querySelector('.rectangle-44');
		
		//Change text colour back to original
		qrBtn.querySelector('.qr').style.color = '#000000';

		// Change image source back to original
        if (imageElement3) { //black qr image
			var qrcode = "<?php echo $qrcode; ?>";
			imageElement3.style.background = "url(" + qrcode + ") 50% / contain no-repeat";
        }
    }
	
	function changeCashButton() {
		var cashBtn = document.getElementById('cash-btn');
		var imageElement = cashBtn.querySelector('.rectangle-43');
		
		cashBtn.classList.add('highlight2');  
		document.getElementById('cardBtn').classList.remove('highlight1');
		
		//change text colour
		cashBtn.querySelector('.cash').style.color = 'white';
		
		// Change image source
        if (imageElement) { //white cash image
            imageElement.style.background = "url('https://pbs.twimg.com/media/GJ7LkoVagAA6xbP?format=png&name=240x240') 50% / contain no-repeat";
        }

		var creditCardBtn = document.getElementById('cardBtn');
        var imageElement2 = creditCardBtn.querySelector('.rectangle-42'); // Select the image element

        // Change text color back to original
        creditCardBtn.querySelector('.credit-card').style.color = '#000000';

        // Change image source back to original
        if (imageElement2) { //black image
            imageElement2.style.background = "url('https://pbs.twimg.com/media/GJ7M7-wbgAA2kdD?format=png&name=small') 50% / contain no-repeat";
        }
		
		//revert qr btn
		var qrBtn = document.getElementById('qr-btn');
		var imageElement3 = qrBtn.querySelector('.rectangle-44');

		//Change text colour back to original
		qrBtn.querySelector('.qr').style.color = '#000000';
		
		// Change image source back to original
        if (imageElement3) { //black qr image
			var qrcode = "<?php echo $qrcode; ?>";
			imageElement3.style.background = "url(" + qrcode + ") 50% / contain no-repeat";
        }
	}
	
	function changeQRButton() {
		var qrBtn = document.getElementById('qr-btn');
		var imageElement = qrBtn.querySelector('.rectangle-44');
		 
		document.getElementById('cardBtn').classList.remove('highlight1');
		document.getElementById('cash-btn').classList.remove('highlight2');

		//change text colour
		qrBtn.querySelector('.qr').style.color = 'white';
		
		// Change image source
        if (imageElement) { //white cash image
			var qrcode = "<?php echo $qrcode; ?>";
			imageElement.style.background = "url(" + qrcode + ") 50% / contain no-repeat";
        }
		
		var creditCardBtn = document.getElementById('cardBtn');
        var imageElement2 = creditCardBtn.querySelector('.rectangle-42'); // Select the image element

        // Change text color back to original
        creditCardBtn.querySelector('.credit-card').style.color = '#000000';

        // Change image source back to original
        if (imageElement2) { //black image
            imageElement2.style.background = "url('https://pbs.twimg.com/media/GJ7M7-wbgAA2kdD?format=png&name=small') 50% / contain no-repeat";
        }
		
		var cashBtn = document.getElementById('cash-btn');
		var imageElement3 = cashBtn.querySelector('.rectangle-43');
		
		//Change text colour back to original
		cashBtn.querySelector('.cash').style.color = '#000000';
		
		// Change image source back to original
        if (imageElement3) { //black cash image
            imageElement3.style.background = "url('https://pbs.twimg.com/media/GJ7M3TdagAA5r4A?format=png&name=small') 50% / contain no-repeat";
        }
	}

	function setPaymentMethod(method) {
    	document.getElementById('payment_method').value = method;
	}

	// Event listener to set payment method when credit card button is clicked
	document.getElementById('cardBtn').addEventListener('click', function() {
		changeCreditCardButton();
		setPaymentMethod('Credit Card');
	});

	// Event listener to set payment method when cash button is clicked
	document.getElementById('cash-btn').addEventListener('click', function() {
		changeCashButton();
		setPaymentMethod('Cash');
	});

	// Event listener to set payment method when cash button is clicked
	document.getElementById('qr-btn').addEventListener('click', function() {
		changeQRButton();
		setPaymentMethod('QR Code');
	});


    function redirectToPaymentSuccess() {
        window.location.href = 'payment_invoice_librarian.php';
		return false; //prevents form from submitting when clicking on 'Go Back'
    }
    
    function goBackOne(){
        window.location.href = 'pay-homepage-librarian.php';
		return false; //prevents form from submitting when clicking on 'Go Back'
    }

</script>

</body>
</html>