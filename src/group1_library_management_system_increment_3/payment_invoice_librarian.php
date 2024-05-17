<?php
include_once("connect-login.php");

require('fpdf/fpdf.php');

if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

session_start();
//echo $_SESSION['patronID'];

//to add timezone
date_default_timezone_set('Asia/Kuala_Lumpur');
mysqli_query($conn, "SET time_zone = '+08:00'");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="payment_invoice.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Invoice</title>
</head>	
<body>

<div class="pay-invoice-v-2">
  <div class="white-with-transparent-background-3">
  </div>
  <div class="container-1">
    <div class="invoiceHeader">
      INVOICE
    </div>
    <div class="container-3">
		Please click on 'Print Invoice' to print your invoice. Otherwise, You may exit payment and will be redirected to the homepage.
    </div>
	
	
	<div class="container-2"> <!--pdf will open in another window-->
		<button onclick="window.open('invoicePDF_librarian.php', '_blank')" id = "printInvoiceBtn" class="print-invoice" <!--onclick="return goBackOne()"-->
		PRINT INVOICE</button>
		<button id = "exitPayment" class="exitPayment" onclick="redirectPaymentSuccess()">EXIT PAYMENT</button>
		<!--<input type="hidden" name="payment_method" id="payment_method" value="">-->
	</div>
  </div>
</div>

<script>
	//redirectPaymentSuccess ==> go to payment_success.php
	function redirectPaymentSuccess() {
		window.location.href = 'payment_success_librarian.php';
	}
</script>

</body>
</html>