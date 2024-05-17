<?php
    include 'connect.php';
    
    session_start();

    $conn->exec("SET time_zone = '+08:00'");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (empty($_SESSION['ID'])) {
        header('location: login.php');
        exit();
    }

    $userid = $_SESSION['ID'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $author = $_POST['author'];
        $image = $_POST['image'];
        $title = $_POST['title'];
        $isbn = $_POST['isbn'];
        $publisher = $_POST['publisher'];
        $year = $_POST['year'];
        $category = $_POST['category'];
        $edition = $_POST['edition'];
    }

    try {
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
                    $daysOverdue = $returnDate->diff($dueDate)->days; // Calculate the difference in days
                    
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
                    $totalAmountDue += $daysOverdue;
                    $count++; // Increment the count of current overdue books
                }
            } else if ($fineRow['IsPaid'] == 0) { // If the fine is unpaid
                $totalAmountDue += $fineRow['FineAmount']; // Accumulate the fine amount
                $count++; // Increment the count of current overdue books
            }
        }
    
        // Update TotalFine column in the user table
        $updateStmt = $conn->prepare("UPDATE `user` SET TotalFine = :totalFine WHERE UserID = :userID");
        $updateStmt->bindParam(':totalFine', $totalAmountDue, PDO::PARAM_INT);
        $updateStmt->bindParam(':userID', $_SESSION['ID'], PDO::PARAM_INT);
        $updateStmt->execute();
        
        //check for number of books that are almost overdue
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
    <title>Book Page</title>
    <link rel="stylesheet" href="book_details.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
       .dropdown {
			position: relative;
			display: inline-block;
			background-color:transparent;
			color:transparent;
			border:none;
		  }
	  
		  .dropdown img {
			cursor: pointer;
			background-color:transparent;
			color:transparent;
			border:none;
		  }
	  
		  .dropdown-content {
			display: none;
			position: absolute;
			background-color: #f9f9f9;
			min-width: 160px;
			box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
			z-index: 1;
		  }
	  
		  .userpic {
			border-radius: 2rem;
			cursor: pointer;
			height: 2.5rem;
			width: 2.5rem;
			margin-right: 5rem;
			object-fit: cover;
			vertical-align: top;
			background-color:transparent;
			color:transparent;
			border:none;
			}
			
		  .dropdown-content a {
			color: black;
			padding: 12px 16px;
			text-decoration: none;
			display: block;
		  }
	  
		  .dropdown-content a:hover {background-color: rgb(210, 210, 210)}
		  
		  .disabled-dropdown-content {
				/* Add your styles for disabled dropdown content here */
				opacity: 0.5; /* Example: reduce opacity */
				pointer-events: none; /* Prevents interactions with the element */
			}
	  
		  #logout:hover {
			  color: white;
			  background-color: red;
		  }
		 
		.modal {

		  background: #FFFFFF;
		  position: absolute;
		  top: 5.3rem;
		  right: 5.8rem;
		  width: 20.0rem;
		  height: 15.1rem;
		}
		
		#box{
			width: 50%;
		}
		
		.modal-header {
			position: relative;
			margin-bottom: 1.2rem;
			display: inline-block;
			overflow-wrap: break-word;
		}
		
		.modal-header h2 {
			font-family: 'Montserrat';
			font-weight: 700;
			font-size: 2.8rem;
			color: #002439;
		}
		
		.modal-body{
			position: relative;
			margin: 0 0 1.7rem 0.1rem;
			display: inline-block;
			overflow-wrap: break-word;
		}
		
		.modal-body p{
			font-family: 'Montserrat';
		    font-weight: 400;
		    font-size: 1.5rem;
		    color: #002439;
		}
		
		.container{
			position: relative;
			display: flex;
			flex-direction: row;
			width: 16.4rem;
			box-sizing: border-box;
		}
		
		.btn.btn-secondary{
			color: black;
			background-color: white;
			padding-left: 20px;
			padding-right: 20px;
			border: none;
            font-size: 1.3rem;
            font-weight: 550;
		}
		
		.btn.btn-primary{
			background-color: transparent;
			padding-left: 30px;
			padding-right: 30px;
			border: none;
            font-size: 1.3rem;
            font-weight: 550;
		}
		
		.group-48{
			box-shadow: 0rem 0.3rem 0.3rem 0rem rgba(0, 0, 0, 0.25);
			border-radius: 1.3rem;
			border: 0.1rem solid #002439;
			background: #FFFFFF;
			position: relative;
			margin: 0 1.2rem 0.1rem 0;
			display: flex;
			flex-direction: row;
			justify-content: center;
			padding: 0.6rem 0.1rem 0.7rem 0;
			width: 7.6rem;
			height: fit-content;
			box-sizing: border-box;
		}
		
		.group-49{
			box-shadow: 0rem 0.4rem 0.3rem 0rem rgba(0, 0, 0, 0.25);
			border-radius: 1.3rem;
			background: linear-gradient(90deg, #002439, #00C2CB);
			position: relative;
			margin-top: 0.1rem;
			display: flex;
			flex-direction: row;
			justify-content: center;
			padding: 0.7rem 0.1rem 0.7rem 0;
			width: 7.6rem;
			height: fit-content;
			box-sizing: border-box;
			box-sizing: border-box;
		}

        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        /* Modal content */
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
            border-radius: 10px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            -webkit-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            -moz-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            -o-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        }

        /* Modal header */
        .modal-header {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 800;
        }

        /* Modal body */
        .modal-body {
            padding: 10px;
            text-align: center;
        }

        /* Modal footer */
        .modal-footer {
            padding: 10px 0;
            border-top: 1px solid #ddd;
            text-align: center;
        }
        .message-prompt {
            align-items: center;
            color: #788800;
            display: flex;
            font-family: Montserrat, 'Source Sans Pro';
            font-size: 1.2rem;
            font-weight: 600;
            height: 1.8rem;
            justify-content: center;
            left: 10.5rem;
            line-height: 1.2175;
            position: absolute;
            white-space: nowrap;
            width: 4.1rem;
        }
        .icon-button__badge {
		position: absolute;
		top: -7px; /* Adjust the positioning of the badge as per your design */
		left: 30px; /* Adjust the positioning of the badge as per your design */
		width: 20px;
		height: 20px;
		background: red;
		color: #ffffff;
		display: flex;
		justify-content: center;
		align-items: center;
		border-radius: 50%;
	}
    .pay-dropdown-icon-badge {
    position: relative;
}

.pay-dropdown-icon-badge .dropdown-button__badge {
    position: absolute;
    width: 20px;
    height: 20px;
    background: red;
    color: #ffffff;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    top: 14px;
    right: 55px; /* Adjust the position as needed */
}

.icon-history__badge{
		position: relative;
		top: -7px; /* Adjust the positioning of the badge as per your design */
		width: 20px;
		height: 20px;
		background: red;
		color: #ffffff;
		display: flex;
		justify-content: center;
		align-items: center;
		border-radius: 50%;
	}
    </style>
</head>

<body>
    <div class="homepage">

        <nav class="navbar" style="background-color: #ffffff;">

            <div class="container-fluid">
                <a href="homepage.php">
                    <img class="uosmlogo" src="https://pbs.twimg.com/media/GH4yUpQWcAE3WHh?format=jpg&name=900x900" id="28:81"/>    
                </a>
                
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
					<div class="dropdown-logout">
						<button class="userpic">
							<img src="https://pbs.twimg.com/media/GH4y2jfWkAAteDX?format=png&name=360x360" id="dropdownMenu" alt="User Dropdown" class="userpic" />
                            <?php if ($count > 0): ?> <!--if no overdue books, don't show notif icon-->
								<span class="icon-button__badge"><?php echo $count; ?></span>
							<?php endif; ?>
                        </button>
						<div class="dropdown-content-logout" id="dropdownContent">
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

            <div id="borrowSuccess" class="modal">
                <?php
                    function isWeekend($date) {
                        $dayOfWeek = date('w', strtotime($date));
                        if ($dayOfWeek == 0 || $dayOfWeek == 6) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                    
                    // Function to check if a date is a holiday
                    function isHoliday($date, $holidays) {
                        return in_array(date('Y-m-d', strtotime($date)), $holidays);
                    }
                    
                    // Function to calculate the new date while considering holidays and weekends
                    function calculateReturnDate($holidays) {
                        $currentDate = strtotime(date('Y-m-d H:i:s')); // Get the current timestamp
                        $daysToAdd = 0;
                    
                        while ($daysToAdd < $_SESSION['ExtendDay']) { 
                            $date = date('Y-m-d', $currentDate);
                    
                            // Check if the date falls on a weekend or holiday
                            if (isWeekend($date) || isHoliday($date, $holidays)) {
                                // If it does, move to the next day
                                $currentDate = strtotime('+1 day', $currentDate);
                                continue;
                            }
                    
                            // Increment the days added excluding weekends and holidays
                            $daysToAdd++;
                            // Move to the next day
                            $currentDate = strtotime('+1 day', $currentDate);
                        }
                    
                        // Calculate the new date by adding only the non-holiday and non-weekend days
                        return date('Y-m-d H:i:s', $currentDate);
                    }                    
                    
                    // Define your array of holidays
                    $holidays = array(
                        '2024-01-01',
                        '2024-04-26',
                        '2024-05-01',
                        '2024-05-22',
                        '2024-05-23',
                        '2024-05-30',
                        '2024-05-31',
                        '2024-06-03',
                        '2024-06-04',
                        '2024-06-16',
                        '2024-06-17'
                    );
                    
                    $newDate2 = calculateReturnDate($holidays);
                ?>
                <div class="modal-content" id = "box">
                    <div id = "borrowHeader" class="modal-header"><h2>Borrow Successful</h2></div>
                    <div class="modal-body">
                        <p>Please return the book by '<?php echo $newDate2; ?>'.</p>
                    </div>
                    
                    <div class="container">
                        <div class="group-49" style="margin-left:50px;">
                        <button onclick="confirmBorrowSuccess()" class="btn btn-primary">Close</button>
                        </div>
                    </div>
                </div>	
            </div>

            <div id="reserveSuccess" class="modal">
                <div class="modal-content" id = "box">
                    <div id = "reserveHeader" class="modal-header"><h2>Reserve Successful</h2></div>
                    <div class="modal-body"><p>
                    You have successfully reserved this book.</p>
                    </div>
                    
                    <div class="container">
                        <div class="group-49" style="margin-left:50px;">
                        <button onclick="confirmReserveSuccess()" class="btn btn-primary">Close</button>
                        </div>
                    </div>
                </div>	
            </div>

            <div id="returnSuccess" class="modal">
                <div class="modal-content" id = "box">
                    <div id = "returnHeader" class="modal-header"><h2>Return Successful</h2></div>
                    <div class="modal-body"><p>
                    The book have been successfully returned.</p>
                    </div>
                    
                    <div class="container">
                        <div class="group-49" style="margin-left:50px;">
                        <button onclick="confirmReturnSuccess()" class="btn btn-primary">Close</button>
                        </div>
                    </div>
                </div>	
            </div>

            <div id="extendSuccess" class="modal">
                <?php
                    function calculateReturnDate2($holidays, $dueDate) {
                        // $currentDate = strtotime(date('Y-m-d H:i:s')); // Get the current timestamp
                        $currentDate = strtotime($dueDate);
                        $daysToAdd = 0;
                    
                        while ($daysToAdd < $_SESSION['ExtendDay']) {
                            $date = date('Y-m-d', $currentDate);
                    
                            // Check if the date falls on a weekend or holiday
                            if (isWeekend($date) || isHoliday($date, $holidays)) {
                                // If it does, move to the next day
                                $currentDate = strtotime('+1 day', $currentDate);
                                continue;
                            }
                    
                            // Increment the days added excluding weekends and holidays
                            $daysToAdd++;
                            // Move to the next day
                            $currentDate = strtotime('+1 day', $currentDate);
                        }
                    
                        // Calculate the new date by adding only the non-holiday and non-weekend days
                        return date('Y-m-d H:i:s', $currentDate);
                    }                    
                    
                    // Define your array of holidays
                    $holidays = array(
                        '2024-01-01',
                        '2024-04-26',
                        '2024-05-01',
                        '2024-05-22',
                        '2024-05-23',
                        '2024-05-30',
                        '2024-05-31',
                        '2024-06-03',
                        '2024-06-04',
                        '2024-06-16',
                        '2024-06-17'
                    );

                    $getExtendedDate = $conn->prepare("SELECT DueDate FROM `book_issued` JOIN `book` ON `book`.`BookID`=`book_issued`.`BookID` WHERE `book_issued`.`UserID`=:userid AND `book`.`ISBN`=:isbn AND `book_issued`.`DateReturn`='0000-00-00 00:00:00';");
                    $getExtendedDate->bindParam(':isbn', $isbn, PDO::PARAM_INT);
                    $getExtendedDate->bindParam(':userid', $userid, PDO::PARAM_INT);
                    $getExtendedDate->execute();

                    $row = $getExtendedDate->fetch(PDO::FETCH_ASSOC);
                    $dueDate = $row['DueDate'];
                    $newDueDate = calculateReturnDate2($holidays,$dueDate);
                ?>
                <div class="modal-content" id = "box">
                    <div id = "extendHeader" class="modal-header"><h2>Extend Successful</h2></div>
                    <div class="modal-body"><p>
                    Your return duedate have been extended until <?php echo date('Y-m-d', strtotime($newDueDate)); ?>.</p>
                    </div>
                    
                    <div class="container">
                        <div class="group-49" style="margin-left:50px;">
                        <button onclick="confirmExtendSuccess()" class="btn btn-primary">Close</button>
                        </div>
                    </div>
                </div>	
            </div>

            <div id="cancelReserveSuccess" class="modal">
                <div class="modal-content" id = "box">
                    <div id = "cancelReserveHeader" class="modal-header"><h2>Cancellation Successful</h2></div>
                    <div class="modal-body"><p>
                    You have no longer made reservation to this book.</p>
                    </div>
                    
                    <div class="container">
                        <div class="group-49" style="margin-left:50px;">
                        <button onclick="confirmCancelReserveSuccess()" class="btn btn-primary">Close</button>
                        </div>
                    </div>
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
                        <button onclick="toLogout()" class="btn btn-primary">Yes</button>
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

            <div id = "myModalBorrow" class="modal">
                <?php
                    $checkBorrowQuery = $conn->prepare("SELECT * FROM `book_reserve` JOIN `book` ON `book_reserve`.`BookID`=`book`.`BookID` WHERE `book`.`ISBN` = :isbn AND `UserID` = :userid AND `book_reserve`.`AllowBorrow` = '1' AND `book_reserve`.`Borrowed` = '0' AND `book_reserve`.`datereserve` <> '0000-00-00 00:00:00'");
                    $checkBorrowQuery->bindParam(':isbn', $isbn, PDO::PARAM_INT);
                    $checkBorrowQuery->bindParam(':userid', $userid, PDO::PARAM_INT);
                    $checkBorrowQuery->execute();
                    if ($checkBorrowQuery->rowCount() > 0) {
                        $row = $checkBorrowQuery->fetch(PDO::FETCH_ASSOC);
                        $reserveExist = true;
                    ?>
                        <div class="modal-content" id = "box">
                            <div id = "borrowHeader" class="modal-header">Borrowing "<?php echo $title; ?>"</div>
                            <div class="modal-body"><p>
                            Are you sure you want to borrow this book?</p>
                            </div>
                            
                            <div class="container">
                                <div class="group-48">
                                <button id="cancelBorrow" class="btn btn-secondary">Cancel</button>
                                </div>
                                <div class="group-49">
                                    <form id="book_issue" action="book_issue.php" method="post">
                                        <input type="hidden" name="user-id" value="<?php echo $_SESSION['ID']; ?>">
                                        <input type="hidden" name="book-id" value="<?php echo $row['BookID']; ?>">
                                        <input type="hidden" name="reserve-exist" value="<?php echo $reserveExist; ?>">
                                        <button type="button" onclick="borrowFunction()" class="btn btn-primary">Yes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    
                    else{
                        $reserveExist = false;
                        $getBookID = $conn->prepare("SELECT * FROM `book` WHERE `book`.`ISBN` = :isbn AND `Status`='Available' Limit 1");
                        $getBookID->bindParam(':isbn', $isbn, PDO::PARAM_INT);
                        $getBookID->execute();
                        if ($getBookID->rowCount() > 0) {
                                $row = $getBookID->fetch(PDO::FETCH_ASSOC);
                                ?>
                                <div class="modal-content" id = "box">
                                    <div id = "borrowHeader" class="modal-header">Borrowing "<?php echo $title; ?>"</div>
                                    <div class="modal-body"><p>
                                    Are you sure you want to borrow this book?</p>
                                    </div>
                                    
                                    <div class="container">
                                        <div class="group-48">
                                        <button id="cancelBorrow" class="btn btn-secondary">Cancel</button>
                                        </div>
                                        <div class="group-49">
                                            <form id="book_issue" action="book_issue.php" method="post">
                                                <input type="hidden" name="user-id" value="<?php echo $_SESSION['ID']; ?>">
                                                <input type="hidden" name="book-id" value="<?php echo $row['BookID']; ?>">
                                                <input type="hidden" name="reserve-exist" value="<?php echo $reserveExist; ?>">
                                                <button type="button" onclick="borrowFunction()" class="btn btn-primary">Yes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            
                        }
                    }
                ?> 	 
            </div>
                <div id = "myModalReserve" class="modal">
                    <?php 
                        $getBookID = $conn->prepare("SELECT BookID FROM `book` WHERE ISBN=:isbn AND Status='Unavailable' AND (BookID) NOT IN (SELECT BookID FROM `book_reserve` WHERE AllowBorrow<>'0' AND Borrowed<>'1') LIMIT 1;");
                        $getBookID->bindParam(':isbn', $isbn, PDO::PARAM_INT);
                        $getBookID->execute();
                        $row = $getBookID->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <div class="modal-content" id = "box">
                        <div id = "reserveHeader" class="modal-header">Reserving "<?php echo $title; ?>"</div>
                        <div class="modal-body"><p>
                        Are you sure you want to reserve this book?</p>
                        </div>
                        
                        <div class="container">
                            <div class="group-48">
                            <button onclick="hideMyModalReserve()" id="cancelReserve" class="btn btn-secondary">Cancel</button>
                            </div>
                            <div class="group-49">
                                <form id="book_reserve" action="book_reserve.php" method="post">
                                    <input type="hidden" name="user-id" value="<?php echo $_SESSION['ID']; ?>">
                                    <input type="hidden" name="book-id" value="<?php echo $row['BookID']; ?>">
                                    <button type="button" onclick="reserveFunction()" class="btn btn-primary">Yes</button>
                                </form>
                            </div>
                        </div>
                    </div>	
                </div>


            <div id = "myModalReturn" class="modal">
                <?php
                    $checkBorrowQuery = $conn->prepare("SELECT * FROM `book_reserve` JOIN `book` ON `book_reserve`.`BookID`=`book`.`BookID` WHERE ISBN = :isbn AND `book_reserve`.`AllowBorrow` = '0' AND `book_reserve`.`Borrowed` = '0' AND `book_reserve`.`datereserve` <> '0000-00-00 00:00:00'");
                    $checkBorrowQuery->bindParam(':isbn', $isbn, PDO::PARAM_INT);
                    $checkBorrowQuery->execute();
                    if ($checkBorrowQuery->rowCount() > 0) {
                        $reserveExist = true;
                    }
                    else{
                        $reserveExist = false;
                    }

                    $borrowQuery = $conn->prepare("SELECT * FROM book_issued JOIN `book` ON `book_issued`.`BookID`=`book`.`BookID` WHERE UserID = :userid AND DateReturn='0000-00-00 00:00:00' AND ISBN = :isbn;");
                    $borrowQuery->bindParam(':userid', $userid, PDO::PARAM_INT);
                    $borrowQuery->bindParam(':isbn', $isbn, PDO::PARAM_INT);
                    $borrowQuery->execute();
                    $results = $borrowQuery->fetchAll(PDO::FETCH_ASSOC);
                    if ($borrowQuery->rowCount() > 0) {
                        foreach ($results as $row) {
                ?>
                
                <div class="modal-content" id = "box">
                    <div id = "returnHeader" class="modal-header">Returning "<?php echo $title; ?>"</div>
                    <div class="modal-body"><p>
                    Are you sure you want to return this book?</p>
                    </div>
                    
                    <div class="container">
                        <div class="group-48">
                            <button onclick="hideMyModalReturn()" id="cancelReturn" class="btn btn-secondary">Cancel</button>
                        </div>
                        
                        <div class="group-49">
                            <form id="book_return" action="book_return.php" method="post">
                                <input type="hidden" name="user-id" value="<?php echo $_SESSION['ID']; ?>">
                                <input type="hidden" name="book-id" value="<?php echo $row['BookID']; ?>">
                                <input type="hidden" name="book-issued-id" value="<?php echo $row['BookIssuedID']; ?>">
                                <input type="hidden" name="reserve-exist" value="<?php echo $reserveExist; ?>">
                                <button type="button" onclick="returnFunction()" class="btn btn-primary">Yes</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
                        }
                    } 
                ?>	
            </div>

            <div id = "myModalExtend" class="modal">
                <?php
                    $current_time = date('Y-m-d H:i:s');
                    $extendQuery = $conn->prepare("SELECT * FROM `book_issued` WHERE (BookID) IN (SELECT `book`.`BookID` FROM `book` WHERE `book`.`ISBN` = :isbn AND `Status`='Unavailable') AND `book_issued`.`UserID`=:userid AND DateReturn='0000-00-00 00:00:00' AND :current_time <= DueDate;");
                    $extendQuery->bindParam(':userid', $userid, PDO::PARAM_INT);
                    $extendQuery->bindParam(':isbn', $isbn, PDO::PARAM_INT);
                    $extendQuery->bindParam(':current_time', $current_time, PDO::PARAM_STR);
                    $extendQuery->execute();
                    $results = $extendQuery->fetchAll(PDO::FETCH_ASSOC);
                    
                    if ($extendQuery->rowCount() > 0) {
                        foreach ($results as $row) {
                ?>
                
                <div class="modal-content" id = "box">
                    <div id = "extendHeader" class="modal-header">Extending "<?php echo $title; ?>"</div>
                    <div class="modal-body"><p>
                    Are you sure you want to extend the duedate of this book?</p>
                    </div>
                    
                    <div class="container">
                        <div class="group-48">
                            <button onclick="hideMyModalExtend()" id="cancelExtend" class="btn btn-secondary">Cancel</button>
                        </div>
                        
                        <div class="group-49">
                            <form id="book_extend" action="book_extend.php" method="post">
                                <input type="hidden" name="user-id" value="<?php echo $_SESSION['ID']; ?>">
                                <input type="hidden" name="book-issued-id" value="<?php echo $row['BookIssuedID']; ?>">
                                <button type="button" onclick="extendFunction()" class="btn btn-primary">Yes</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
                        }
                    } 
                ?>	
            </div>

            <div id = "myModalCancelReserve" class="modal">
                
                <?php 
                    $cancelReserveQuery = $conn->prepare("SELECT * FROM `book` JOIN `book_reserve` ON `book`.`BookID` = `book_reserve`.`BookID` WHERE `book`.`ISBN`= :isbn AND ((`book`.`Status` = 'Unavailable' OR `book`.`Status` = 'Reserved')  AND `book_reserve`.`UserID` = :userid AND `book_reserve`.`AllowBorrow` = '0' AND `book_reserve`.`Borrowed` = '0' AND `book_reserve`.`DateReserve` <> '0000-00-00 00:00:00');");
                    $cancelReserveQuery->bindParam(':isbn', $isbn, PDO::PARAM_INT);
                    $cancelReserveQuery->bindParam(':userid', $userid, PDO::PARAM_INT);
                    $cancelReserveQuery->execute();
                    $row = $cancelReserveQuery->fetch(PDO::FETCH_ASSOC);
                ?>
                
                <div class="modal-content" id = "box">
                    <div id = "cancelReserveHeader" class="modal-header">Cancelling book reservation</div>
                    <div class="modal-body"><p>
                    Are you sure you want to cancel the reservation for this book?</p>
                    </div>
                    
                    <div class="container">
                        <div class="group-48">
                            <button onclick="hideMyModalCancel()" id="cancelReserveCancel" class="btn btn-secondary">Cancel</button>
                        </div>
                        
                        <div class="group-49">
                            <form id="book_reserve_cancel" action="book_reserve_cancel.php" method="post">
                                <input type="hidden" name="user-id" value="<?php echo $_SESSION['ID']; ?>">
                                <input type="hidden" name="book-reserve-cancel-id" value="<?php echo $row['BookID']; ?>">
                                <button type="button" onclick="cancelReserveFunction()" class="btn btn-primary">Yes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

          </nav>


        <ul class="nav justify-content-center header">
            <li class="nav-item">
                <a class="nav-link active header-text" href="homepage.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active header-text" href="bookpage.php" style="color: #00c2cb;background-color: #ffffff;">Books</a>            </li>
            <li class="nav-item">
                <a class="nav-link active header-text" href="history.php" style="position:relative;">
                    History
                    <?php if ($numDueBooks > 0): ?> <!--if no overdue books, don't show notif icon-->
                            <span class="icon-history__badge" id="history-notif"><?php echo $numDueBooks; ?></span>
                    <?php endif; ?>
				</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active header-text" href="contact.php">Contact</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active header-text" href="about.php">About</a>
            </li>
        </ul>

        <div class="group-available-books">
            <div class="book">
                <img src="<?php echo $image; ?>" class="image-book">
            </div>
            <div class="details">
                <div class="title-container">
                    <?php echo $title; ?>
                </div>
                <div style="display: flex;">
                    <div style="width:500px;">
                        <div style="display: flex;margin-bottom: 15px;">
                            <div class="subdetails" style="width: 170px;">Author</div><div class="answer"><?php echo $author; ?></div>
                        </div>
                        <div style="display: flex;margin-bottom: 15px;">
                            <div class="subdetails" style="width: 170px;">ISBN</div><div class="answer"><?php echo $isbn; ?></div>
                        </div>
                        <div style="display: flex;margin-bottom: 15px;">
                            <div class="subdetails" style="width: 170px;">Publisher</div><div class="answer"><?php echo $publisher; ?></div>
                        </div>
                        <div style="display: flex;margin-bottom: 15px;">
                            <div class="subdetails" style="width: 170px;">Published Year</div><div class="answer"><?php echo $year; ?></div>
                        </div>
                        <div style="display: flex;margin-bottom: 15px;">
                            <div class="subdetails" style="width: 170px;">Category</div><div class="answer"><?php echo $category; ?></div>
                        </div>
                        <div style="display: flex;margin-bottom: 15px;">
                            <div class="subdetails" style="width: 170px;">Edition</div><div class="answer"><?php echo $edition; ?></div>
                        </div>
                    </div>
                    <div class="vl" style="padding-right:25px;"></div>
                    <div class="subdetails" style="padding-left: 5rem;">
                        <?php
                            $check_borrow_limit = $conn->prepare("SELECT * FROM `book_issued` WHERE DateReturn = '0000-00-00 00:00:00' AND UserID = :userid;");
                            $check_borrow_limit->bindParam(':userid', $userid, PDO::PARAM_INT);
                            $check_borrow_limit->execute();

                            $select_books_amount = $conn->prepare("SELECT subquery.Title, subquery.Authors, COUNT(*) AS BookCount FROM (SELECT `book`.`Title`, GROUP_CONCAT(`author`.Name ORDER BY `author`.AuthorID) AS `Authors` FROM `book` JOIN `author` ON `book`.`BookID` = `author`.`BookID` WHERE `book`.`Title` = :title AND (`book`.`Status` = 'Available')  GROUP BY `book`.`Title`, `author`.`BookID`) AS subquery GROUP BY subquery.Title, subquery.Authors HAVING subquery.Authors LIKE :author;");
                            $select_books_amount->bindParam(':title', $title, PDO::PARAM_STR);
                            $select_books_amount->bindParam(':author', $author, PDO::PARAM_STR);
                            $select_books_amount->execute();

                            if ($select_books_amount->rowCount() > 0) {
                                $result = $select_books_amount->fetch(PDO::FETCH_ASSOC);
                                $bookCount = $result['BookCount'];
                            } else {
                                $bookCount = 0;
                            }

                            $check_status = $conn->prepare("SELECT `Status` FROM `book` WHERE `book`.`ISBN`= :isbn AND `book`.`Status` = 'Available';");
                            $check_status->bindParam(':isbn', $isbn, PDO::PARAM_INT);
                            $check_status->execute();

                            $check_status_reserved = $conn->prepare("SELECT * FROM `book` JOIN `book_reserve` ON `book`.`BookID` = `book_reserve`.`BookID` WHERE `book`.`ISBN`= :isbn AND (`book`.`Status` = 'Available' OR (`book`.`Status` = 'Reserved' AND `book_reserve`.`UserID` = :userid AND `book_reserve`.`AllowBorrow` = '1'));");
                            $check_status_reserved->bindParam(':isbn', $isbn, PDO::PARAM_INT);
                            $check_status_reserved->bindParam(':userid', $userid, PDO::PARAM_INT);
                            $check_status_reserved->execute();

                            $check_status_reserved_before = $conn->prepare("SELECT * FROM `book` JOIN `book_reserve` ON `book`.`BookID` = `book_reserve`.`BookID` WHERE `book`.`ISBN`= :isbn AND (`book`.`Status` = 'Unavailable' AND `book_reserve`.`UserID` = :userid AND `book_reserve`.`AllowBorrow` = '0' AND `book_reserve`.`Borrowed` = '0' AND `book_reserve`.`DateReserve` <> '0000-00-00 00:00:00');");
                            $check_status_reserved_before->bindParam(':isbn', $isbn, PDO::PARAM_INT);
                            $check_status_reserved_before->bindParam(':userid', $userid, PDO::PARAM_INT);
                            $check_status_reserved_before->execute();          
                            
                            $for_other_reserved_user = $conn->prepare("SELECT * FROM `book_reserve` JOIN `book` ON `book_reserve`.`BookID`=`book`.`BookID` WHERE book.ISBN=:isbn AND UserID=:userid AND DateReserve <> '0000-00-00 00:00:00' AND AllowBorrow='0' AND Borrowed='0';");
                            $for_other_reserved_user->bindParam(':isbn', $isbn, PDO::PARAM_INT);
                            $for_other_reserved_user->bindParam(':userid', $userid, PDO::PARAM_INT);
                            $for_other_reserved_user->execute();   

                            $display_borrow = "none";
                            if ($check_borrow_limit->rowCount() >= $_SESSION['BorrowLimit'] ) {
                                // borrow button
                                $borrowLimitExceed = true;
                                $isReservedOrAvailable = false;
                                $isUnavailable = false;
                                $isUnavailableAfter = false;
                                $display_borrow = "none";
                                $display_cancel = false;
                            }
                            else if ($check_status->rowCount() > 0) {
                                // borrow button
                                $borrowLimitExceed = false;
                                $isReservedOrAvailable = true;
                                $isUnavailable = false;
                                $isUnavailableAfter = false;
                                $display_borrow = "none";
                                $display_cancel = false;
                            } 
                            else if ($check_status_reserved->rowCount() > 0) {
                                // borrow button
                                $borrowLimitExceed = false;
                                $isReservedOrAvailable = true;
                                $isUnavailable = false;
                                $isUnavailableAfter = false;
                                $display_borrow = "block";
                                $display_cancel = false;
                            } 
                            else if ($check_status_reserved_before->rowCount() > 0) {
                                // cancel button
                                $borrowLimitExceed = false;
                                $isReservedOrAvailable = false;
                                $isUnavailable = false;
                                $isUnavailableAfter = true;
                                $display_borrow = "none";
                                $display_cancel = false;
                            } 
                            else if ($for_other_reserved_user->rowCount() > 0) {
                                // cancel button
                                $borrowLimitExceed = false;
                                $isReservedOrAvailable = false;
                                $isUnavailable = false;
                                $isUnavailableAfter = false;;
                                $display_borrow = "none";
                                $display_cancel = true;
                            } 
                            else{
                                //reserve button
                                $borrowLimitExceed = false;
                                $isReservedOrAvailable = false;
                                $isUnavailable = true;
                                $isUnavailableAfter = false;
                                $display_borrow = "none";
                                $display_cancel = false;
                            }
                        ?>
                        <p>Status</p>
                        <p class="answer"><i><?php echo $bookCount ?> unit(s) available.</i></p>

                        <div id="borroBookLimitMessage" style="display:<?php echo ($borrowLimitExceed ? 'block' : 'none'); ?>;">
                            <p>You have exceeded the borrow limit of <?php echo $_SESSION['BorrowLimit'] ?></p>
                        </div>

                        <div id="borrowBook" style="display:<?php echo ($isReservedOrAvailable ? 'block' : 'none'); ?>;">
                            <button id="borrow-book" name="Borrow" class="borrow-button">Borrow</button>
                            <p class="answer" style="display: <?= $display_borrow?>;font-size: 11px; color:black;"><i>* Available to borrow after reservation.</i></p>
                        </div>

                        <div id="reserveBook" style="display:<?php echo ($isUnavailable ? 'block' : 'none'); ?>;">
                            <button id="reserve-book" name="Reserve" class="reserve-button">Reserve</button>
                        </div>

                        <div id="cancelReserveBook" style="display:<?php echo (($isUnavailableAfter || $display_cancel) ? 'block' : 'none'); ?>;">
                            <button id="cancel-reserve-book" name="Cancel" class="cancel-reserve-button">Cancel Reservation</button>
                        </div>

                        <div id="returnAndExtend" style="display:none;">
                            <button id="extend-book" name="Extend" class="extend-button">Extend</button><br>
                            <button id="return-book" name="Return" class="return-button">Return</button>
                        </div>

                        <?php
                        $current_time = date('Y-m-d H:i:s');
                        $borrowQuery = $conn->prepare("SELECT * FROM book_issued JOIN `book` ON `book_issued`.`BookID`=`book`.`BookID` WHERE UserID = :userid AND `book`.`ISBN` = :isbn AND `book_issued`.`DateReturn` = '0000-00-00 00:00:00';");
                        $borrowQuery->bindParam(':userid', $userid, PDO::PARAM_INT);
                        $borrowQuery->bindParam(':isbn', $isbn, PDO::PARAM_INT);
                        $borrowQuery->execute();
                        $borrowedBooks = $borrowQuery->fetchAll(PDO::FETCH_ASSOC);

                        $extendLimitExceeded = false;
                        
                        $extendLimit = $_SESSION['ExtendDay'] * $_SESSION['ExtendLimit'];

                        foreach ($borrowedBooks as $book) {
                            $returnDate = calculateReturnDate3($holidays, $book['DueDate'], $book['DateBorrow']);

                                // Check if $returnDate is null
                            if (!is_null($returnDate)) {

                                // Check if the calculated return date exceeds the extend limit
                                if ($returnDate > $extendLimit) {
                                    $extendLimitExceeded = true;
                                }
                            }
                        }

                        function calculateReturnDate3($holidays, $dueDate, $dateBorrowed) {
                            $currentDate = strtotime($dueDate); // Start from the DueDate
                            $startDate = strtotime($dateBorrowed); // Get the timestamp of the DateBorrowed
                            $numOfDays = 0;
                        
                            while ($currentDate > $startDate) { // Loop until reaching the DateBorrowed
                                $date = date('Y-m-d', $currentDate);
                        
                                // Check if the date falls on a weekend or holiday
                                if (isWeekend($date) || isHoliday($date, $holidays)) {
                                    // If it does, move to the previous day
                                    $currentDate = strtotime('-1 day', $currentDate);
                                    continue;
                                }
                        
                                // Increment the days added excluding weekends and holidays
                                $numOfDays++;
                                // Move to the previous day
                                $currentDate = strtotime('-1 day', $currentDate);
                            }
                        
                            return $numOfDays;
                        }
                        
                        $holidays = array(
                            '2024-01-01',
                            '2024-04-26',
                            '2024-05-01',
                            '2024-05-22',
                            '2024-05-23',
                            '2024-05-30',
                            '2024-05-31',
                            '2024-06-03',
                            '2024-06-04',
                            '2024-06-16',
                            '2024-06-17'
                        );

                        // Calculate the extend limit
                        $extendLimit = $_SESSION['ExtendDay'] * $_SESSION['ExtendLimit'];

                        foreach ($borrowedBooks as $book) {
                            if ($book['DateReturn'] == "0000-00-00 00:00:00" && $book['DueDate'] <= $current_time) {
                                ?>
                                <script>
                                    const reserveBook = document.getElementById('reserveBook');
                                    reserveBook.style.display = "none";
                                    reserveBook.innerHTML = '<button id="reserve-book" name="Reserve" class="reserve-button">Reserve</button>';

                                    const borrowBook = document.getElementById('borrowBook');
                                    borrowBook.style.display = "none";
                                    borrowBook.innerHTML = '<button id="borrow-book" name="Borrow" class="borrow-button">Borrow</button>';
                                    
                                    document.getElementById('borroBookLimitMessage').style.display = "none";
                                    const returnAndExtend = document.getElementById('returnAndExtend');
                                    returnAndExtend.style.display = "block";
                                    returnAndExtend.innerHTML = '<button id="return-book" name="Return" class="return-button">Return</button><p class="answer" style="font-size: 11px; color:red;"><i>* Overdue: <?=$book['DueDate']?>. Please return the book.</i></p>';
                                </script>
                                
                                <?php
                            } elseif ($book['DateReturn'] == "0000-00-00 00:00:00") {
                                ?>
                                <script>
                                    const reserveBook = document.getElementById('reserveBook');
                                    reserveBook.style.display = "none";
                                    reserveBook.innerHTML = '<button id="reserve-book" name="Reserve" class="reserve-button">Reserve</button>';

                                    const borrowBook = document.getElementById('borrowBook');
                                    borrowBook.style.display = "none";
                                    borrowBook.innerHTML = '<button id="borrow-book" name="Borrow" class="borrow-button">Borrow</button>';
                                    
                                    document.getElementById('borroBookLimitMessage').style.display = "none";
                                    const returnAndExtend = document.getElementById('returnAndExtend');
                                    returnAndExtend.style.display = "block";

                                    const returnDate = <?php echo $returnDate; ?>;
                                    const extendLimit = <?php echo $extendLimit; ?>;
                                    const extendLimitExceeded = <?php echo ($extendLimitExceeded ? 'true' : 'false'); ?>;

                                    if (!extendLimitExceeded) {
                                        returnAndExtend.innerHTML = '<button id="extend-book" name="Extend" class="extend-button">Extend</button><br><button id="return-book" name="Return" class="return-button">Return</button>';
                                    } else {
                                        returnAndExtend.innerHTML = '<p>You have exceeded the extended limit.</p><button id="return-book" name="Return" class="return-button">Return</button>';
                                    }
                                </script>
                                <?php
                            } 
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById("dropdownMenu").addEventListener("click", function() {
            var dropdownContent = document.getElementById("dropdownContent");
            dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
        });

        document.getElementById("logout").addEventListener("click", function(event) {
                // Prevent the default action of the anchor tag
                event.preventDefault();
                // Display the confirmation modal
                var modal = document.getElementById("myModal");
                modal.style.display = "block";
        });

        document.getElementById("borrow-book").addEventListener("click", function(event) {
            // Prevent the default action of the anchor tag
            event.preventDefault();
            // Display the confirmation modal
            var modal = document.getElementById("myModalBorrow");
            modal.style.display = "block";
        });

        document.getElementById("reserve-book").addEventListener("click", function(event) {
            // Prevent the default action of the anchor tag
            event.preventDefault();
            // Display the confirmation modal
            var modal = document.getElementById("myModalReserve");
            modal.style.display = "block";
        });

        document.getElementById("return-book").addEventListener("click", function(event) {
            // Prevent the default action of the anchor tag
            event.preventDefault();
            // Display the confirmation modal
            var modal = document.getElementById("myModalReturn");
            modal.style.display = "block";
        });

        document.getElementById("extend-book").addEventListener("click", function(event) {
            // Prevent the default action of the anchor tag
            event.preventDefault();
            // Display the confirmation modal
            var modal = document.getElementById("myModalExtend");
            modal.style.display = "block";
        });

        document.getElementById("cancel-reserve-book").addEventListener("click", function(event) {
            // Prevent the default action of the anchor tag
            event.preventDefault();
            // Display the confirmation modal
            var modal = document.getElementById("myModalCancelReserve");
            modal.style.display = "block";
        });

        // Close the modal when the cancel button is clicked
        document.getElementById("cancelLogout").addEventListener("click", function() {
            var modal = document.getElementById("myModal");
            modal.style.display = "none";
        });

        document.getElementById("cancelBorrow").addEventListener("click", function() {
            var modal = document.getElementById("myModalBorrow");
            modal.style.display = "none";
        });

        document.getElementById("cancelReserve").addEventListener("click", function() {
            var modal = document.getElementById("myModalReserve");
            modal.style.display = "none";
        });

        function hideMyModalReserve(){
            var modal = document.getElementById("myModalReserve");
            modal.style.display = "none";
        }

        function hideMyModalReturn(){
            var modal = document.getElementById("myModalReturn");
            modal.style.display = "none";
        }

        function hideMyModalExtend(){
            var modal = document.getElementById("myModalExtend");
            modal.style.display = "none";
        }

        function hideMyModalCancel(){
            var modal = document.getElementById("myModalCancelReserve");
            modal.style.display = "none";
        }

        function toLogout(){
            window.location.href = "login.php"; // Redirect to logout page
        }

        function confirmBorrowSuccess(){
            location.reload();
            document.getElementById("borrowSuccess").style.display = "none";
        }

        function confirmReserveSuccess(){
            location.reload();
            document.getElementById("reserveSuccess").style.display = "none";
        }

        function confirmReturnSuccess(){
            location.reload();
            document.getElementById("returnSuccess").style.display = "none";
        }

        function confirmExtendSuccess(){
            location.reload();
            document.getElementById("extendSuccess").style.display = "none";
        }

        function confirmCancelReserveSuccess(){
            location.reload();
            document.getElementById("cancelReserveSuccess").style.display = "none";
        }

        function borrowFunction() {
            var formData = new FormData(document.getElementById('book_issue'));
            
            fetch('book_issue.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === 'Book borrowed successfully.') {
                    var modal = document.getElementById("myModalBorrow");
                    modal.style.display = "none";
                    document.getElementById("borrowSuccess").style.display = "block";
                    document.getElementById("borrowBook").style.display = "none";
                    document.getElementById("reserveBook").style.display = "none";
                    document.getElementById("returnAndExtend").style.display = "block";
                } else {
                    console.error('Error:', data);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function reserveFunction() {
            var formData = new FormData(document.getElementById('book_reserve'));
            
            fetch('book_reserve.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === 'Book reserved successfully.') {
                    var modal = document.getElementById("myModalReserve");
                    modal.style.display = "none";
                    document.getElementById("reserveSuccess").style.display = "block";
                } else {
                    console.error('Error:', data);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function returnFunction() {
            var formData = new FormData(document.getElementById('book_return'));
            
            fetch('book_return.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === 'Book returned successfully.') {
                    var modal = document.getElementById("myModalReturn");
                    modal.style.display = "none";
                    document.getElementById("returnSuccess").style.display = "block";
                    document.getElementById("borrowBook").style.display = "block";
                    document.getElementById("reserveBook").style.display = "none";
                    document.getElementById("returnAndExtend").style.display = "none";
                } else {
                    console.error('Error:', data);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function extendFunction() {
            var formData = new FormData(document.getElementById('book_extend'));
            
            fetch('book_extend.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === 'Book extended successfully.') {
                    var modal = document.getElementById("myModalExtend");
                    modal.style.display = "none";
                    document.getElementById("extendSuccess").style.display = "block";
                } else {
                    console.error('Error:', data);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function cancelReserveFunction() {
            var formData = new FormData(document.getElementById('book_reserve_cancel'));
            
            fetch('book_reserve_cancel.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === 'Book reservation cancelled successfully.') {
                    var modal = document.getElementById("myModalCancelReserve");
                    modal.style.display = "none";
                    document.getElementById("cancelReserveSuccess").style.display = "block";
                } else {
                    console.error('Error:', data);
                }
            })
            .catch(error => console.error('Error:', error));
        }

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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
