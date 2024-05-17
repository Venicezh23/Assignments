<?php
include_once("connect-login.php");

require('fpdf/fpdf.php');

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

$sqlGetUserInfo = "SELECT ID, FirstName, LastName, TotalFine FROM user
				WHERE user.UserID = '" . $_SESSION['ID'] . "'";

$resultUser = $conn->query($sqlGetUserInfo);
$userInfo = mysqli_fetch_assoc($resultUser);

if(!$resultUser){
	die("Invalid query: " . $conn->error);
}

// Fetch and print the book titles from the database
$sql = "SELECT title, FineAmount, FineType, PaymentType FROM fine
        JOIN user ON user.UserID = fine.userID
        JOIN book_issued ON book_issued.BookIssuedID = fine.BookIssuedID
        JOIN book ON book.BookID = book_issued.BookID
        WHERE fine.userID = '" . $_SESSION['ID'] . "' AND IsPaid='0' AND DateCompleteFine IS NOT NULL;";
$result = mysqli_query($conn, $sql);

$getPaymentType = "SELECT PaymentType FROM fine
					JOIN user ON user.UserID = fine.userID
					JOIN book_issued ON book_issued.BookIssuedID = fine.BookIssuedID
					JOIN book ON book.BookID = book_issued.BookID
					WHERE fine.userID = '" . $_SESSION['ID'] . "'";
					
$resultPayment = $conn->query($getPaymentType);
$paymentInfo = mysqli_fetch_assoc($resultPayment);

if(!$resultUser){
	die("Invalid query: " . $conn->error);
}


$pdf = new FPDF('P', 'mm', "A4"); //potrait, mm as unit, A4 size
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 20);

$pdf->Image('logo.png', $pdf->GetX(), $pdf->GetY(), 60, 20); //width, height
$pdf->Cell(158,10,'',0,0); //empty cell, width 80mm to the left, height 10mm, 0 no border, 0 cursor stay on same line after adding cell
$pdf->Cell(59,25,'INVOICE',0,0); //cell, width 59mm, height 5mm, has text "invoice", no border, stay on same line
$pdf->Cell(80,25,'',0,1); //width 59mm, height 10mm, 0 no border, 1 cursor move to beginning of next line

/*pay to, librarian name, invoice ID, number*/
$pdf->SetFont('Arial', 'B', 12); //set font
$pdf->Cell(20, 5, 'Pay To', 0,0); //content
$pdf->Cell(10, 5, '', 0,0); //empty space
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(50, 5, 'University of Southampton Malaysia', 0,0);
$pdf->Cell(50, 5, '', 0,1);

//address
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(30, 5, '', 0,0);
$pdf->Cell(70, 5, '3, Eko Galleria, C0301, C0302, C0401, Blok C,', 0, 1);

$pdf->Cell(30, 5, '', 0,0); //empty space
$pdf->Cell(90, 5, 'Taman Persiaran Eko Botani,', 0, 1);
$pdf->Cell(30, 5, '', 0,0); //empty space
$pdf->Cell(70, 5, '79100', 0, 1);
$pdf->Cell(30, 5, '', 0,0); //empty space
$pdf->Cell(70, 5, 'Iskandar Puteri,', 0, 1);
$pdf->Cell(30, 5, '', 0,0); //empty space
$pdf->Cell(70, 5, 'Johor.', 0, 0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 5, '', 0,0);
$pdf->Cell(20, 5, 'Invoice ID', 0,0);
$pdf->Cell(10, 5, '', 0,0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(20, 5, '123456', 0,0);
$pdf->Cell(30, 5, '', 0,1); //new line

//$pdf->Cell(0, 5, '', 0, 0); // Move to the next line before adding the line
$pdf->Cell(130, 5, '', 0,0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 5, 'Date', 0,0);
$pdf->SetFont('Arial', '', 12);
$currentDate = date('d/m/Y');
$pdf->Cell(34, 5, $currentDate, 0, 1); //new line

//bank, bank name, time, time value
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(20, 5, 'Bank', 0, 0);
$pdf->Cell(10, 5, '', 0,0); //empty space
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(70, 5, 'Maybank', 0,0);
$pdf->Cell(30, 5, '', 0,0); //empty space
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(20, 5, 'Time', 0,0);
$pdf->Cell(10, 5, '', 0,0); //empty space
$pdf->SetFont('Arial', '', 12);
$currentTime = date('H:i:s A');
$pdf->Cell(34, 5, $currentTime, 0, 1); //new line

$pdf->Cell(0, 5, '', 0, 1); // Move to the next line before adding the line
$pdf->SetLineWidth(0.5); // Set the line width
$pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->GetX() + 190, $pdf->GetY()); // Draw a line
$pdf->Cell(0, 5, '', 0, 1); // Move to the next line after adding the line

//billed to, student name + id, method of payment
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 5, 'Billed To', 0, 0);
$pdf->Cell(10, 5, '', 0,0);
$pdf->SetFont('Arial', '', 12);

//get id's name
$userName = $userInfo['FirstName'] . " " . $userInfo['LastName'];
$pdf->Cell(70, 5, $userName, 0,1);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 5, '', 0, 0);
$userID = $userInfo['ID']; //get student ID
$pdf->Cell(30, 5, $userID, 0, 1);

$pdf->Cell(0, 2, '', 0, 1); // Move to the next line after adding the line
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 5, 'Method of', 0, 0);
$pdf->Cell(10, 5, '', 0,0);
$pdf->SetFont('Arial', '', 12);

$paymentType = $paymentInfo['PaymentType'];
$pdf->Cell(70, 5, $paymentType, 0,1); //TBA - to pull from db
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 5, 'Payment', 0, 0); // Start a new cell for "Payment"
$pdf->Cell(10, 5, '', 0, 0); // Empty cell for spacing
$pdf->Cell(70, 5, '', 0, 1); // Empty cell to align with "Cash/Credit"

$pdf->Cell(0, 5, '', 0, 1); // Move to the next line after adding the line

$pdf->SetFont('Arial', 'B', 12);
/*heading of table*/
$pdf->Cell(10, 6, 'No', 1, 0, 'C');
$pdf->Cell(120, 6, 'Book Title', 1, 0, 'C');
$pdf->Cell(30, 6, 'Fine Type', 1, 0, 'C');
$pdf->Cell(30, 6, 'Amount(RM)', 1, 1, 'C');

/*table rows*/
$pdf->SetFont('Arial', '', 10);
$cnt = 1;
$currentOverdueFineAmount = $_SESSION['OverdueFineAmount'];
while ($row = mysqli_fetch_assoc($result)) {
	$title = $row['title'];
	$fineType = $row['FineType'];
	
	if ($fineType == 'Overdue') {
		$fineAmount = number_format(($row['FineAmount']*$currentOverdueFineAmount), 2); // Formats the fine amount to 2 decimal places
	} else {
		$fineAmount = number_format($row['FineAmount'], 2); 
	}
	
	$dataRow = array(array($cnt, $title, $fineType, $fineAmount));
	
	foreach($dataRow as $item){
		$cnt++;
		$cellWidth=120;
		$cellHeight=5;
		
		//check whether the text is overflowing
	if($pdf->GetStringWidth($item[1]) < $cellWidth){
		//if not, then do nothing
		$line=1;
	}else{
		//if it is, then calculate the height needed for wrapped cell
		//by splitting the text to fit the cell width
		//then count how many lines are needed for the text to fit the cell
		
		$textLength=strlen($item[1]);	//total text length
		$errMargin=10;		//cell width error margin, just in case
		$startChar=0;		//character start position for each line
		$maxChar=0;			//maximum character in a line, to be incremented later
		$textArray=array();	//to hold the strings for each line
		$tmpString="";		//to hold the string for a line (temporary)
		
		while($startChar < $textLength){ //loop until end of text
			//loop until maximum character reached
			while( 
			$pdf->GetStringWidth( $tmpString ) < ($cellWidth-$errMargin) &&
			($startChar+$maxChar) < $textLength ) {
				$maxChar++;
				$tmpString=substr($item[1],$startChar,$maxChar);
			}
			//move startChar to next line
			$startChar=$startChar+$maxChar;
			//then add it into the array so we know how many line are needed
			array_push($textArray,$tmpString);
			//reset maxChar and tmpString
			$maxChar=0;
			$tmpString='';
			
		}
		//get number of line
		$line=count($textArray);
	}
	
	//count
	//write the cells
	$pdf->Cell(10,($line * $cellHeight),$item[0],1,0); //adapt height to number of lines
	
	//TITLE
	//use MultiCell instead of Cell
	//but first, because MultiCell is always treated as line ending, we need to 
	//manually set the xy position for the next cell to be next to it.
	//remember the x and y position before writing the multicell
	$xPos=$pdf->GetX();
	$yPos=$pdf->GetY();
	$pdf->MultiCell($cellWidth,$cellHeight,$item[1],1); //adapt height to number of lines
	
	//return the position for next cell next to the multicell
	//and offset the x with multicell width
	$pdf->SetXY($xPos + $cellWidth , $yPos);
	
	//days overdue
	$pdf->Cell(30,($line * $cellHeight),$item[2],1,0, 'R');

	//amount
	$pdf->Cell(30,($line * $cellHeight),$item[3],1,1, 'R'); //adapt height to number of lines
	
	}
}

/*note + total fine amount get from user TotalFine*/
$pdf->Cell(0, 10, '', 0, 1); // Move to the next line after adding the line
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(145, 5, '', 0, 0);
$pdf->Cell(10, 5, 'Total: ', 0, 0);
$pdf->Cell(15, 5, '', 0, 0);
$userTotalFine = $userInfo['TotalFine'];
$userTotalFineFormatted = number_format($userTotalFine, 2); // Format to two decimal places
$pdf->Cell(15, 5, $userTotalFineFormatted, 0, 1);

$pdf->Cell(0, 10, '', 0, 1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 5, 'Note:', 0, 0); // Start a new cell for "Payment"
$pdf->Cell(0, 5, '', 0, 1); // Move to the next line after adding the line
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 5, 'For any queries or disputes,', 0, 1); // Start a new cell for "Payment"

$pdf->Cell(30, 5, 'please contact our librarian at',0,0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(120,5,'',0,0);
$pdf->Cell(20, 5, 'Thank you for your', 0,1);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(30, 5, 'uosmlibrary@soton.ac.uk', 0, 0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(140,5,'',0,0);
$pdf->Cell(20, 5, 'payment!', 0,1);

$pdf->Output(); //can be Output('filename.pdf', 'D'); == file will be downloaded forcefully
?>