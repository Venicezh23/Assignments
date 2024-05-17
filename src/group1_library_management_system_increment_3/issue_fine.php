<?php 
session_start();

include 'connect-edit.php';

// To add timezone
date_default_timezone_set('Asia/Kuala_Lumpur');
mysqli_query($conn, "SET time_zone = '+08:00'");

$user_id = $_POST['book-issued-userid'];
$fine_type = $_POST['fine-type'];
$book_id = $_POST['book-issued-id'];
$fine_amount = $_POST['fine-price'];
$currentDate = date('Y-m-d H:i:s');

// Prepare the SQL statement using a prepared statement
$sql = "INSERT INTO fine (UserID, BookIssuedID, FineType, FineAmount, IsPaid, DateFined, DateCompleteFine, PaymentType)
        VALUES (?, ?, ?, ?, '0', ?, NULL, NULL)";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind parameters
$stmt->bind_param("iisds", $user_id, $book_id, $fine_type, $fine_amount, $currentDate);

// Execute the statement
if ($stmt->execute()) {
	
	// Update the user's TotalFine
	$sql2 = "UPDATE `user` SET TotalFine = TotalFine + ? WHERE UserID = ?";
	
	// Prepare the update statement
	$stmt2 = $conn->prepare($sql2);

	if (!$stmt2) {
		echo "Error preparing update statement: " . $conn->error;
	} else {
		// Bind parameters
		$stmt2->bind_param("di", $fine_amount, $user_id);

		// Execute the update statement
		if ($stmt2->execute()) {
            echo "Fine issued and total fine updated successfully.";
			$logMessage = "[" . date('Y-m-d H:i:s') . "] A total of Rm $fine_amount fine was issued to User ID: $user_id.";
    		file_put_contents('login_log.txt', $logMessage . PHP_EOL, FILE_APPEND);
		} else {
			echo "Error updating TotalFine: " . $stmt2->error;
		}

		// Close the update statement
		$stmt2->close();
	}
} else {
    echo "Error issuing fine: " . $stmt->error;
}

// Close the statement
$stmt->close();

// Close the connection
$conn->close();
?>
