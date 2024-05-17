<?php
include 'connect.php';

session_start();

// Redirect to login page if user is not logged in
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
    $updateStmt->bindParam(':totalFine', $totalAmountDue, PDO::PARAM_INT);
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
    <title>Library System</title>
    <link rel="stylesheet" href="homepage.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/f71ea3c766.js" crossorigin="anonymous"></script>
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
		}
		
		.btn.btn-primary{
			background-color: transparent;
			padding-left: 30px;
			padding-right: 30px;
			border: none;
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
    </style>
	
</head>

<body>
    <div class="homepage">
        <nav class="navbar" style="background-color: #ffffff;">
            <div class="container-fluid">
                <a href="homepage.php">
                    <img class="uosmlogo" src="https://pbs.twimg.com/media/GH4yUpQWcAE3WHh?format=jpg&name=900x900" id="28:81"/>    
                </a>

                <div class="nav justify-content-center">
                <form action="bookpage_search.php" method="GET" class="search-form">
                    <div class="d-flex align-items-center">
                        <div class="search-container">
                            <input type="text" name="search" value="<?php if(isset($_GET['search'])){echo $_GET['search'];}?>" placeholder="Enter any details of a book" class="searchinput" id="searchInput">
                            <button type="submit" class="searchbutton" id="searchBtn">
                                <img class="search-img" src="https://pbs.twimg.com/media/GH4zgZ_XYAAVjAL?format=png&name=240x240" alt="Search">
                            </button>
                        </div>
                    </div>
                </form>
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
			
	<div id="myModal" class="modal">
		<div class="modal-content" id="box">
			<div id="logoutHeader" class="modal-header"><h2>Logout</h2></div>
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

    <ul class="nav justify-content-center header">
        <li class="nav-item">
            <a class="nav-link active header-text" href="homepage.php">Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active header-text" href="bookpage.php">Books</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active header-text" href="history.php" style="position:relative;">
                History
                <?php if ($numDueBooks > 0): ?> <!--if no overdue books, don't show notif icon-->
                    <span class="icon-history__badge" id="history-notif"><?php echo $numDueBooks; ?></span>
                <?php endif; ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active header-text" href="contact.php"  style="color: #00c2cb;background-color: #ffffff;">Contact</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active header-text" href="about.php">About</a>
        </li>
    </ul>

        
    </div>

    <div>
        <h2 style="font-family: Montserrat; text-align:center; font-weight: 700; color: #00c2cb; margin-top:25px;">Contact Us</h2>
        <div style="border: 2px solid black; margin:20px 250px 50px 250px; height:300px; border-radius: 10px;">
            <p style="font-family: Montserrat; text-align:center; font-weight: 700; font-size:30px">Contact a librarian</p>
            <?php 
				$get_librarian_query = $conn->prepare("SELECT * FROM `user` JOIN user_type ON user_type.UserTypeID = `user`.UserTypeID WHERE `user`.UserTypeID = '6'");
				$get_librarian_query->execute();
				if ($get_librarian_query->rowCount() > 0) {
					while ($fetch_librarian = $get_librarian_query->fetch(PDO::FETCH_ASSOC)) {
			?>
						<div style="line-height:13px;">
							<p style="font-family: Montserrat; text-align:center; font-weight: 700; font-size:20px">Name: <?=$fetch_librarian['FirstName'] . " " . $fetch_librarian['LastName']; ?></p>
							<p style="font-family: Montserrat; text-align:center; font-weight: 700; font-size:20px">Email: <?=$fetch_librarian['Email']; ?></p>
						</div>
						<br>
			<?php
					}
				} else {
			?>
						<div></div>
			<?php
				}
			?>
            <div></div>
        </div>
    </div>

    <div style="background-color: #00c2cb; width:100%; height:75px; display:flex; align-items: center; justify-content: center; color: white;">
    <div style="padding-left:80px; padding-right:80px; font-family: Montserrat; font-size: 13px;"><i class="fa fa-envelope"></i><b>&nbsp;<?= $_SESSION['LibraryEmail']?></b></div>
    <div style="padding-left:150px; padding-right:100px; font-family: Montserrat; font-size: 13px;"><i class="fa fa-phone"></i><b>&nbsp;<?= $_SESSION['LibraryPhoneNo']?></b></div>
    <div style="font-size: 11px; padding-left:100px; padding-right:10px; text-align: center; font-family: Montserrat; "><i class="fa fa-location-dot"></i><b>&nbsp;33, Eko Galleria, C0301, C0302, C0401, Blok C, Taman, Persiaran Eko Botani,<br> 79100 Iskandar Puteri, Johor</b></div>
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
</body>
</html>
