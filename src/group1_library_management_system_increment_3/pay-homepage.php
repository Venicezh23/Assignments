<?php
include 'connect.php';
include_once('connect-mysqli.php');

session_start();

date_default_timezone_set('Asia/Kuala_Lumpur');
mysqli_query($conn_mysqli, "SET time_zone = '+08:00'");


if (empty($_SESSION['ID'])) {
    header('location: login.php');
    exit();
}

try {
    $conn->exec("SET time_zone = '+08:00'");
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve data for books with overdue days from the book_issued table
    $stmt = $conn->prepare("SELECT BookIssuedID, DueDate, DateReturn
                            FROM book_issued
                            WHERE UserID = :userID
                            AND DateReturn > DueDate");
    $stmt->bindParam(':userID', $_SESSION['ID'], PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all rows at once

    // Calculate total fine amount only for unpaid fines
    $totalAmountDue = 0;
    $count = 0; // Initialize the count of overdue books
	$latestOverdueFineAmount = $_SESSION['OverdueFineAmount'];

    foreach ($rows as $row) {
        // Fetch corresponding fine information for each book
        $fineStmt = $conn->prepare("SELECT IsPaid, FineAmount
                                    FROM fine
                                    WHERE UserID = :userID
                                    AND BookIssuedID = :bookIssuedID");
        $fineStmt->bindParam(':userID', $_SESSION['ID'], PDO::PARAM_INT);
        $fineStmt->bindParam(':bookIssuedID', $row['BookIssuedID'], PDO::PARAM_INT);
        $fineStmt->execute();
        $fineRow = $fineStmt->fetch(PDO::FETCH_ASSOC);

        if (!$fineRow) { // If no corresponding fine row exists
            // Calculate fine for this book
            $dueDate = new DateTime($row["DueDate"]);
            $returnDate = new DateTime($row["DateReturn"]);
            if ($returnDate > $dueDate) { // Only if the return date is later than the due date
                $daysOverdue = ($returnDate->diff($dueDate)->days) * $latestOverdueFineAmount; // Calculate the difference in days
                
                // Insert new row into the fine table
                $insertStmt = $conn->prepare("INSERT INTO fine (UserID, BookIssuedID, FineType, FineAmount, IsPaid, DateFined, DateCompleteFine) 
                                              VALUES (:userID, :bookIssuedID, :fineType, :fineAmount, :isPaid, :dateFined, :dateCompleteFine)");
                $insertStmt->bindParam(':userID', $_SESSION['ID'], PDO::PARAM_INT);
                $insertStmt->bindParam(':bookIssuedID', $row['BookIssuedID'], PDO::PARAM_INT);
                $insertStmt->bindValue(':fineType', 'Overdue', PDO::PARAM_STR); // You can set the fine type as per your requirement
                $insertStmt->bindValue(':fineAmount', $daysOverdue, PDO::PARAM_INT); // Fine amount based on overdue days
                $insertStmt->bindValue(':isPaid', '0', PDO::PARAM_STR); // Default value for IsPaid
                $dueDate->add(new DateInterval('P1D')); // Add 1 day to the DueDate
                $insertStmt->bindValue(':dateFined', $dueDate->format('Y-m-d H:i:s'), PDO::PARAM_STR); // Default value for DateFined
                $insertStmt->bindValue(':dateCompleteFine', null, PDO::PARAM_NULL); // Default value for DateCompleteFine
                $insertStmt->execute();

                // Accumulate the fine amount
                $totalAmountDue += $daysOverdue * $latestOverdueFineAmount;
                $count++; // Increment the count of current overdue books
            }
        } else if ($fineRow['IsPaid'] == 0) { // If the fine is unpaid
            $totalAmountDue += $fineRow['FineAmount'] * $latestOverdueFineAmount; // Accumulate the fine amount
            $count++; // Increment the count of current overdue books
        }
    }
	
	//find non overdue books and add to count and total amount
	$fineTypeStmt = $conn->prepare("SELECT IsPaid, FineAmount
                                    FROM fine
                                    WHERE UserID = :userID
                                    AND (FineType = 'Lost' OR FineType = 'Damaged')
									AND IsPaid = '0'");
	$fineTypeStmt->bindParam(':userID', $_SESSION['ID'], PDO::PARAM_INT);
	$fineTypeStmt->execute();
	$fineTypeRow = $fineTypeStmt->fetchAll(PDO::FETCH_ASSOC);
	foreach($fineTypeRow as $fiRow){
		$count++;
		$totalAmountDue += $fiRow['FineAmount'];
	}

    // Update TotalFine column in the user table
    $updateStmt = $conn->prepare("UPDATE `user` SET TotalFine = :totalFine WHERE UserID = :userID");
    $updateStmt->bindParam(':totalFine', $totalAmountDue, PDO::PARAM_STR);
    $updateStmt->bindParam(':userID', $_SESSION['ID'], PDO::PARAM_INT);
    $updateStmt->execute();
	
	//check for number of books that are almost overdue
	//sql query --> which books are due in 3 days (diff)
	//get rows
	//count
	//return result
	$currentDateTime = date('Y-m-d H:i:s');
	$threeDaysLater = date('Y-m-d H:i:s', strtotime('+3 days', strtotime($currentDateTime)));

	$getDueBooksStmt = $conn->prepare("SELECT UserID, DueDate FROM book_issued
										WHERE UserID = :userID 
										AND DueDate BETWEEN :currentDateTime AND :threeDaysLater");
	$getDueBooksStmt->bindParam(':userID', $_SESSION['ID'], PDO::PARAM_INT);
	$getDueBooksStmt->bindParam(':currentDateTime', $currentDateTime, PDO::PARAM_STR);
	$getDueBooksStmt->bindParam(':threeDaysLater', $threeDaysLater, PDO::PARAM_STR);
	$getDueBooksStmt->execute();

	// Fetch the results
	$dueBooks = $getDueBooksStmt->fetchAll(PDO::FETCH_ASSOC);

	// Count the number of rows returned
	$numDueBooks = count($dueBooks);	

} catch (PDOException $e) {
    // Handle database connection errors
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="pay-homepage.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Payment Homepage</title>
	
<style>
body {
    position: relative; /* Ensure dropdown menu works properly */
}

.dropdown-logout {
    position: relative;
    display: inline-block;
}

.dropdown-logout button {
    background-color: transparent;
    border: none;
}

.dropdown-logout img {
    cursor: pointer;
}

.dropdown-content-logout {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    right: 0; /* Adjust position */
    top: 100%; /* Adjust position */
}

.dropdown-content-logout a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-content-logout a:hover {
    background-color: rgb(210, 210, 210);
}

#logout:hover {
    color: white;
    background-color: red;
}

.user-white-1-kKR {
  border-radius: 2rem;
  flex-shrink: 0;
  height: 2.5rem;
  margin-right: 2.3rem;
  object-fit: cover;
  vertical-align: top;
  width: 2.4rem;
  background-color: transparent;
  color: transparent;
  border: none;
}

</style>
</head>
<body>

<div class="main-body">
        <nav class="navbar" style="background-color: #ffffff;">
            <div class="container-fluid">
                <a href="homepage.php">
                    <img class="uosmlogo" src="https://pbs.twimg.com/media/GH4yUpQWcAE3WHh?format=jpg&name=900x900" id="28:81"/>    
                </a>
                
                <div class="nav justify-content-center">
                    <span class="profile-text" style="color: #002439;">Payment Homepage</span>
                </div>
				<div class="d-flex" role="search">
                    <?php
                        $select_name = $conn->prepare("SELECT *,CONCAT(LEFT(`LastName`, 1), LEFT(`FirstName`, 1)) AS `short_name` FROM `user` WHERE `UserID` = '".$_SESSION['ID']."';");
                        $select_name->execute();
                        if($select_name->rowCount() > 0){
                            while($fetch_name = $select_name->fetch(PDO::FETCH_ASSOC)){
                    ?>
                    <div class="name-text"><?= $fetch_name['short_name']; ?></div>
                    <?php
                    }
                    }else{
                        ?>
                        <div class="name-text">name</div>
                    <?php
                    }
                    ?>
					<div class="dropdown">
						<button class="userpic"> 
							<img src="https://pbs.twimg.com/media/GH4y2jfWkAAteDX?format=png&name=360x360" id="dropdownMenu" alt="User Dropdown" class="userpic" />
							<?php if ($count > 0): ?> <!--if no overdue books, don't show notif icon-->
								<span class="icon-button__badge"><?php echo $count; ?></span>
							<?php endif; ?>
						</button>
						<div class="dropdown-content" id="dropdownContent">
							<a href="profile.php">Profile</a>
							<a href="send_otp_code.php">Reset Password</a>
							<a href="pay-homepage.php" class="pay-dropdown-icon-badge <?php if ($count == 0) echo 'disabled-dropdown-content'; ?>" <?php if ($count == 0) echo 'onclick="return false;"'; ?>>
								Pay Fine
								<?php if ($count > 0): ?>
									<span class="dropdown-button__badge"><?php echo $count; ?></span>
								<?php endif; ?>
							</a>
							<a id="logout" href="#">Log Out</a>
						</div>
					</div>
					<button style="border-radius: 50%; background-color: #00c2cb; color: white; width: 40px; height: 40px; border: none; font-size: 25px;" id="help">?</button>
				</div>

			</div>
			
            <div id = "myModal" class="modal">
                <div class="modal-content" id = "box">
                    <div id = "logoutHeader" class="modal-header"><h2>Logout</h2></div>
                    <div class="modal-body"><p>
                    Are you sure you want to logout?</p>
                    </div>
                    
                    <div class="container">
                        <div class="group-48">
                        <button id="cancelLogout" class="btn btn-secondary">Cancel</button>
                        </div>
                        <div class="group-49">
                        <button id="confirmLogout" class="btn btn-primary">Yes</button>
                        </div>
                    </div>
                    
                </div>	
            </div>
            <div id="myModalHelp" class="modal">
            <div class="modal-content" id="box" style="margin: 6% auto;">
                <div id="helpHeader" class="modal-header"><h2>Help and FAQs</h2></div>
                <div class="modal-body" style="text-align: left;">
                    <h4>How and when to borrow a book?</h4>
                    <ol>
                        <li>Click on any one book in the bookpage.</li>
                        <li>If the book you clicked is showing you that it is available, then it will display the button "Borrow".</li>
                        <li>Click the button "Borrow".</li>
                        <li>A prompt message will show up to confirm your request to borrow.</li>
                        <li>Proceed by clicking "Yes".</li>
                        <li>A successful prompt message will show up, and the page will be reloaded with the buttons "Extend" and "Return".</li>
                    </ol>
                    <h4>How and when to reserve a book?</h4>
                    <ol>
                        <li>Click on any one book in the bookpage.</li>
                        <li>If the book you clicked is showing you that it is unavailable, then it will display the button "Reserve".</li>
                        <li>Click the button "Reserve".</li>
                        <li>A prompt message will show up to confirm your request to reserve.</li>
                        <li>Proceed by clicking "Yes".</li>
                        <li>A successful prompt message will show up, and you can check the reserved book section in the history page to view if you can borrow the book.</li>
                    </ol>
                    <h4>How to extend the duedate of a book?</h4>
                    <ol>
                        <li>Click on any one book that you borrowed previously in the bookpage.</li>
                        <li>If the book is overdue for return, then it will display the button "Return" only, otherwise the buttons "Extend" and "Return" will show up.</li>
                        <li>Click the button "Extend".</li>
                        <li>A prompt message will show up to confirm your request to extend the duedate.</li>
                        <li>Proceed by clicking "Yes".</li>
                        <li>A successful prompt message will tell you that the duedate has been extended by two weeks.</li>
                    </ol>
                    <h4>How to return a book?</h4>
                    <ol>
                        <li>Click on any one book that you borrowed previously in the bookpage.</li>
                        <li>If the book is overdue for return, then it will display the button "Return" only, otherwise the buttons "Extend" and "Return" will show up.</li>
                        <li>Click the button "Return".</li>
                        <li>A prompt message will show up to confirm your request to return.</li>
                        <li>Proceed by clicking "Yes".</li>
                        <li>A successful prompt message will show up, and the page will be reloaded with the button "Borrow".</li>
                        <li>Depending on whether you returned the book late, you will be notified to pay the overdue fine from the usericon on the top right.</li>
                    </ol>
                    <h4>What are the accepted payments for paying overdue fines?</h4>
                    <ul><li>Credit-card and cash payment are accepted.</li></ul>
                    <h4>How long can you extend the duedate of a book?</h4>
                    <ul><li>2 weeks.</li></ul>
                </div>
                <div class="container" style="width:9.6rem;">
                    <div class="group-48">
                    <button id="closeHelp" class="btn btn-secondary">Cancel</button>
                    </div>
                </div>
            </div>	
        </div>

        </nav>

        <div style="text-align: center; padding:20px;">
		<table border="1">
			<!--<caption>Simple Table Example</caption>-->
			<thead>
				<tr>
					<th>No.</th>
					<th>Book title</th>
					<th>Fine Type</th>
					<th>Day(s) Overdue</th>
					<th>Amount(RM)</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$sql = "SELECT b.Title, bi.DueDate, bi.DateReturn, f.FineAmount, f.FineType
						FROM book_issued bi 
						JOIN book b ON bi.BookID = b.BookID
						JOIN fine f ON bi.BookIssuedID = f.BookIssuedID
						WHERE bi.UserID = '" . $_SESSION['ID'] . "'
						AND IsPaid = '0'"; //join book, fine and book_issued

				$result = $conn_mysqli->query($sql);
				
				if(!$result){
					die("Invalid query: " . $conn_mysqli->error);
				}
				
				$totalAmountDue = 0; //init 
				$counter = 1; //init for table counter
				$currentOverdueFineAmount = $_SESSION['OverdueFineAmount'];
				
				//gets difference of due date and date returned to calculated overdue days
				while ($row = $result->fetch_assoc()){
					//need to check the payment type - if overdue need to * currentOverdueFineAmount
					//else, just add $row["FineAmount"]; as normal
					$fineType = $row['FineType'];
					
					if ($fineType == 'Overdue') { //overdue books
						$fineAmount = $row["FineAmount"] * $currentOverdueFineAmount;
						//$fineAmount = $row["FineAmount"];
						$dueDate = new DateTime($row["DueDate"]);
						$returnDate = new DateTime($row["DateReturn"]);
						if ($returnDate > $dueDate) { //only if the return date is more than due date
							$daysOverdue = $returnDate->diff($dueDate)->days; // Calculates the difference in days
						} else {
							$daysOverdue = 0;
						}
						 
						$amountDue = $daysOverdue;
						$totalAmountDue += $fineAmount; //accumulate here
						
						// Display the row only if daysOverdue is greater than 0
						if ($daysOverdue > 0) {
							echo "<tr>
									<td>" . $counter++ . "</td>
									<td>" . $row["Title"]. "</td>
									<td>" . $row["FineType"] . "</td>
									<td>" . $daysOverdue . "</td>
									<td>" . $fineAmount . "</td>
								  </tr>";
						}
					} else { //non-overdue books
						$fineAmount = $row["FineAmount"];
						$totalAmountDue += $fineAmount; //accumulate here
						
						echo "<tr>
									<td>" . $counter++ . "</td>
									<td>" . $row["Title"]. "</td>
									<td>" . $row["FineType"] . "</td>
									<td>" . 'NA' . "</td>
									<td>" . $fineAmount . "</td>
								  </tr>";
					}
				}
				
				echo "</tbody>
					  </table>";

				$conn_mysqli->close();

				// Display total amount due below the table
				echo "<div class='total-rmx-xx'>
						TOTAL: RM" . $totalAmountDue . "
					  </div>";
				?>

		<div class="container-2">
			<!--go back to homepage button, redirect to homepage.php-->
			<button class="go-back-to-homepage" onclick="goBackToHome()">GO BACK TO HOMEPAGE</button>
			<button class="pay" onclick="goToPaymentMethod()">PROCEED TO PAYMENT</button>
		</div>
	</div>
	
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script>
        document.getElementById("dropdownMenu").addEventListener("click", function(event) {
			var dropdownContent = document.getElementById("dropdownContent");
			dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
			event.stopPropagation(); // Prevent event from bubbling up to document
		});


        document.getElementById("logout").addEventListener("click", function(event) {
            // Prevent the default action of the anchor tag
            event.preventDefault();
            // Display the confirmation modal
            var modal = document.getElementById("myModal");
            modal.style.display = "block";
        });

        // Close the modal when the cancel button is clicked
        document.getElementById("cancelLogout").addEventListener("click", function() {
            var modal = document.getElementById("myModal");
            modal.style.display = "none";
        });

        // Proceed with logout when the confirm button is clicked
        document.getElementById("confirmLogout").addEventListener("click", function() {
            window.location.href = "login.php"; // Redirect to logout page
        });

        document.getElementById("help").addEventListener("click", function(event) {
            // Prevent the default action of the anchor tag
            event.preventDefault();
            // Display the confirmation modal
            var modal = document.getElementById("myModalHelp");
            modal.style.display = "block";
        });

        // Close the modal when the cancel button is clicked
        document.getElementById("closeHelp").addEventListener("click", function() {
            var modal = document.getElementById("myModalHelp");
            modal.style.display = "none";
        });
        
        // Close the modal when clicking outside of it
        window.addEventListener("click", function(event) {
            var modal = document.getElementById("myModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        });
        // Close the modal when clicking outside of it
        window.addEventListener("click", function(event) {
            var modal = document.getElementById("myModalHelp");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        });
    </script>
	<script>
	function goBackToHome(){
        window.location.href = 'homepage.php';
    }
	
	function goToPaymentMethod(){
		window.location.href = 'payment_method.php';
	}
</script>
</body>
</html>