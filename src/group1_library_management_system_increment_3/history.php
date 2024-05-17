<?php
    include 'connect.php';
    session_start();

    if (empty($_SESSION['ID'])) {
        header('location: login.php');
        exit();
    }

    $conn->exec("SET time_zone = '+08:00'");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
    
    if(isset($_POST['reserveID'])){
        $id = $_POST['reserveID'];
        $sql = "UPDATE `book_reserve` SET `DateReserve` = '0000-00-00 00:00:00' WHERE ReserveID = :id";
        $logMessage = "[" . date('Y-m-d H:i:s') . "] User ID: " . $_SESSION['ID'] . " have cancel a reservation of a book: $id.";
        file_put_contents('login_log.txt', $logMessage . PHP_EOL, FILE_APPEND);
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        header("location: history.php");
        exit();
    }

    if(isset($_POST['extendBorrow'])){
        $id = $_POST['extendBorrow'];
        $sql = "UPDATE `book_issued` SET `DueDate` = DATE_ADD(`DueDate`, INTERVAL :edays DAY) WHERE BookIssuedID = :id";
        $logMessage = "[" . date('Y-m-d H:i:s') . "] User ID: " . $_SESSION['ID'] . " have extend their duedate: $id.";
        file_put_contents('login_log.txt', $logMessage . PHP_EOL, FILE_APPEND);
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':edays', $_SESSION['ExtendDay'], PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        header("location: history.php");
        exit();
    }

    if(isset($_POST['returnBook'])){
        $id = $_POST['returnBook'];
        $user_id = $_SESSION['ID'];
        $book_id = $_POST['book-id'];
        $current_time = date('Y-m-d H:i:s');
        $sql = "UPDATE `book_issued` SET `DateReturn` = '$current_time' WHERE BookIssuedID = :id AND UserID = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->execute()){
            $sql2 = "UPDATE book SET status='Available' WHERE bookid=:book_id";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bindParam(':book_id', $book_id, PDO::PARAM_INT);
            if ($stmt2->execute()){
                echo "Book returned successfully.";
                $logMessage = "[" . date('Y-m-d H:i:s') . "] User ID: " . $_SESSION['ID'] . " have return a book: $book_id.";
                file_put_contents('login_log.txt', $logMessage . PHP_EOL, FILE_APPEND);
            }
            else{
                echo "Error: ". $sql2 . "<br>" . $stmt2->errorInfo();
            }
        }
        else{
            echo "Error: ". $sql . "<br>" . $stmt->errorInfo();
        }
        header("location: history.php");
        exit();
    }

    if(isset($_POST['borrowBook'])){
        $id = $_POST['borrowBook'];
        $user_id = $_SESSION['ID'];
        $book_id = $_POST['book-id'];
        $currentDate = date('Y-m-d H:i:s');
        $newDate2 = date('Y-m-d H:i:s', strtotime($currentDate . ' +'.$_SESSION['ReturnDay'].' days'));
    
        $sql = "INSERT INTO book_issued (userid, bookid, dateborrow, datereturn, duedate) 
                VALUES (:user_id, :book_id, :currentDate, '0000-00-00 00:00:00', :newDate2)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
        $stmt->bindParam(':currentDate', $currentDate, PDO::PARAM_STR);
        $stmt->bindParam(':newDate2', $newDate2, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            $sql2 = "UPDATE book SET status='Unavailable' WHERE bookid=:book_id";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bindParam(':book_id', $book_id, PDO::PARAM_INT);
            
            if ($stmt2->execute()) {
                $sql3 = "UPDATE book_reserve SET allowborrow='0', Borrowed='1' WHERE reserveid=:reserve_id";
                $stmt3 = $conn->prepare($sql3);
                $stmt3->bindParam(':reserve_id', $id, PDO::PARAM_INT);

                if ($stmt3->execute()) {
                    echo "Book borrowed successfully.";
                    $logMessage = "[" . date('Y-m-d H:i:s') . "] User ID: " . $_SESSION['ID'] . " have borrow a book: $book_id.";
                    file_put_contents('login_log.txt', $logMessage . PHP_EOL, FILE_APPEND);
                }
                else {
                    echo "Error: ". $sql3 . "<br>" . $stmt2->errorInfo();
                }
            } else {
                echo "Error: ". $sql2 . "<br>" . $stmt2->errorInfo();
            }
        } else {
            echo "Error: ". $sql . "<br>" . $stmt->errorInfo();
        }
        header("location: history.php");
        exit();
    }
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library System</title>
    <link rel="stylesheet" href="history.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" />
    <script src="https://kit.fontawesome.com/f71ea3c766.js" crossorigin="anonymous"></script>
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
                        $select_name = $conn->prepare("SELECT CONCAT(LEFT(`LastName`, 1), LEFT(`FirstName`, 1)) AS `short_name` FROM `user` WHERE `UserID` = '".$_SESSION['ID']."';");
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


        <ul class="nav justify-content-center header">
            <li class="nav-item">
                <a class="nav-link active header-text" href="homepage.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active header-text" href="bookpage.php">Books</a>
            </li>
            <li class="nav-item">
				<a class="nav-link active header-text" href="history.php" style="color: #00c2cb;background-color: #ffffff; position:relative;">
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
            <div class="top">
                <div class="group-76">
                <div class="you-have-read">
                    You have read 
                </div>
                <?php
                    $select_read_books = $conn->prepare("SELECT COUNT(*) AS Count FROM `book_issued` WHERE `UserID`='" . $_SESSION['ID'] . "';");
                    $select_read_books->execute();
                    if($select_read_books->rowCount() > 0){
                        while($fetch_read_book = $select_read_books->fetch(PDO::FETCH_ASSOC)){
                ?>
                        <div class="book-29"><?= $fetch_read_book['Count']; ?></div>
                <?php
                        }
                    }else{
                        ?>
                        <div class="book-29">0</div>
                        <?php
                    }
                ?>
                <div class="books-in-total">
                    books in total !
                </div>
                </div>
                <img class="top-img" src="https://img.freepik.com/free-vector/education-learning-concept-love-reading-people-reading-students-studying-preparing-examination-library-book-lovers-readers-modern-literature-flat-cartoon-vector-illustration_1150-60938.jpg?w=1060&t=st=1710948943~exp=1710949543~hmac=debce59ac0a5fdb37ecc293e17df5e591038a621d822f6b06988146d1e89493b">
            </div>
    
            <div class="container-41">
                <button class="borrowed" onclick="showBorrowedPage()" id="borrowBtn" style="text-decoration: underline;">Borrowed</button>
                <button class="reserved"onclick="showReservedPage()" id="reserveBtn">Reserved</button>
            </div>

            <!-- Borrowed -->
            <div style="width: 100%;" id="borrowedPage">
                <?php
                    $current_time = date('Y-m-d H:i:s');
                    $select_borrow_books = $conn->prepare("SELECT DISTINCT `book`.`Title`, GROUP_CONCAT(`author`.Name) AS `Authors`, `book`.`Image`,  `book_issued`.* FROM `book` JOIN `author` ON `book`.`BookID` = `author`.`BookID` JOIN `book_issued` ON `book`.`BookID` = `book_issued`.`BookID` WHERE `book_issued`.`UserID`='" . $_SESSION['ID'] . "' GROUP BY `book_issued`.`BookIssuedID` ORDER BY `DateReturn` ASC,`DueDate`ASC;");
                    $select_borrow_books->execute();
                    $extendLimit = ($_SESSION['ExtendDay'] * $_SESSION['ExtendLimit']) +  $_SESSION['ReturnDay'];
                    if($select_borrow_books->rowCount() > 0){
                        while($fetch_borrow_book = $select_borrow_books->fetch(PDO::FETCH_ASSOC)){
                ?>
                    <div class="borrowed-box">
                        <div class="container-fluid">
                            <div class="d-flex">
                                <img src="<?= $fetch_borrow_book['Image']; ?>" alt="" title="<?= $fetch_borrow_book['Title']; ?>" class="book-pic">
                                
                                <div>
                                    <div class="title"><?= $fetch_borrow_book['Title']; ?></div>
                                    <div class="author"><?= $fetch_borrow_book['Authors']; ?></div>

                                    <?php
                                        $extendDateLimit = new DateTime($fetch_borrow_book['DateBorrow']);
                                        $extendDateLimit->add(new DateInterval('P' . $extendLimit . 'D'));
                                        $dueDate = new DateTime($fetch_borrow_book['DueDate']);
                                        if($fetch_borrow_book['DateReturn'] == "0000-00-00 00:00:00" && ($current_time >= $fetch_borrow_book['DueDate'])) {
                                            ?>
                                            <div id = "returnModal<?= $fetch_borrow_book['BookIssuedID']; ?>" class="modal">
                                                <div class="modal-content" id = "returnBox">
                                                    <div id = "returnHeader" class="modal-header"><h2>Return</h2></div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to return <?= $fetch_borrow_book['Title']; ?>?</p>
                                                    </div>
                                                    <div class="container">
                                                        <div class="group-48">
                                                            <button onclick="hideReturnPage(event,<?= $fetch_borrow_book['BookIssuedID']; ?>)" class="btn btn-secondary">No</button>
                                                        </div>
                                                        <form class="group-49" method="post">
                                                            <input type="hidden" name="returnBook" value="<?= $fetch_borrow_book['BookIssuedID']; ?>">
                                                            <input type="hidden" name="book-id" value="<?= $fetch_borrow_book['BookID']; ?>">
                                                            <button type="submit" class="btn btn-primary">Yes</button>
                                                        </form>
                                                    </div>
                                                    
                                                </div>	
                                            </div>
                                            <button onclick="showReturnPage(event,<?= $fetch_borrow_book['BookIssuedID']; ?>)" class="return-btn" >
                                                Return
                                                <i class="fa-solid fa-hand-holding" style="color: white;"></i>
                                            </button>
                                            <?php
                                            } else if ($fetch_borrow_book['DateReturn'] == "0000-00-00 00:00:00" && ($dueDate >= $extendDateLimit)) {
                                            ?>
                                            <div id = "returnModal<?= $fetch_borrow_book['BookIssuedID']; ?>" class="modal">
                                                <div class="modal-content" id = "returnBox">
                                                    <div id = "returnHeader" class="modal-header"><h2>Return</h2></div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to return <?= $fetch_borrow_book['Title']; ?>?</p>
                                                    </div>
                                                    <div class="container">
                                                        <div class="group-48">
                                                            <button onclick="hideReturnPage(event,<?= $fetch_borrow_book['BookIssuedID']; ?>)" class="btn btn-secondary">No</button>
                                                        </div>
                                                        <form class="group-49" method="post">
                                                            <input type="hidden" name="returnBook" value="<?= $fetch_borrow_book['BookIssuedID']; ?>">
                                                            <input type="hidden" name="book-id" value="<?= $fetch_borrow_book['BookID']; ?>">
                                                            <button type="submit" class="btn btn-primary">Yes</button>
                                                        </form>
                                                    </div>
                                                    
                                                </div>	
                                            </div>
                                            <button onclick="showReturnPage(event,<?= $fetch_borrow_book['BookIssuedID']; ?>)" class="return-btn" >
                                                Return
                                                <i class="fa-solid fa-hand-holding" style="color: white;"></i>
                                            </button>
                                        <?php
                                        }elseif($fetch_borrow_book['DateReturn'] == "0000-00-00 00:00:00") {
                                            ?>
                                            <div id = "returnModal<?= $fetch_borrow_book['BookIssuedID']; ?>" class="modal">
                                                <div class="modal-content" id = "returnBox">
                                                    <div id = "returnHeader" class="modal-header"><h2>Return</h2></div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to return <?= $fetch_borrow_book['Title']; ?>?</p>
                                                    </div>
                                                    <div class="container">
                                                        <div class="group-48">
                                                            <button onclick="hideReturnPage(event,<?= $fetch_borrow_book['BookIssuedID']; ?>)" class="btn btn-secondary">No</button>
                                                        </div>
                                                        <form class="group-49" method="post">
                                                            <input type="hidden" name="returnBook" value="<?= $fetch_borrow_book['BookIssuedID']; ?>">
                                                            <input type="hidden" name="book-id" value="<?= $fetch_borrow_book['BookID']; ?>">
                                                            <button type="submit" class="btn btn-primary">Yes</button>
                                                        </form>
                                                    </div>
                                                </div>	
                                            </div>
                                            <button onclick="showReturnPage(event,<?= $fetch_borrow_book['BookIssuedID']; ?>)" class="return-btn" >
                                                Return
                                                <i class="fa-solid fa-hand-holding" style="color: white;"></i>
                                            </button>
                                            <div id = "extendModal<?= $fetch_borrow_book['BookIssuedID']; ?>" class="modal">
                                                <div class="modal-content" id = "extendBox">
                                                    <div id = "extendHeader" class="modal-header"><h2>Extend Date</h2></div>
                                                    <div class="modal-body"><p>
                                                    Are you sure you want to extend your borrowing date?</p>
                                                    </div>
                                                    <div class="container">
                                                        <div class="group-48">
                                                            <button onclick="hideComfirmationExtendPage(event,<?= $fetch_borrow_book['BookIssuedID']; ?>)" class="btn btn-secondary">No</button>
                                                        </div>
                                                        <form class="group-49" method="post">
                                                            <input type="hidden" name="extendBorrow" value="<?= $fetch_borrow_book['BookIssuedID']; ?>">
                                                            <button type="submit" class="btn btn-primary">Yes</button>
                                                        </form>
                                                    </div>
                                                </div>	
                                            </div>
                                            <button onclick="showComfirmationExtendPage(event,<?= $fetch_borrow_book['BookIssuedID']; ?>)" class="extend-btn">
                                                Extend
                                                <i class="fa-regular fa-calendar-plus"></i>
                                            </button>
                                        <?php
                                        }else{?>
                                            <div class="date-box">
                                                <div class="date-registered">Return Date:</div>
                                                &nbsp;
                                                <div class="date"><?= $fetch_borrow_book['DateReturn']; ?></div>
                                            </div>
                                            <div class="date-box">
                                                <div class="date-registered">Due Date:</div>
                                                &nbsp;
                                                <div class="date"><?= $fetch_borrow_book['DueDate']; ?></div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                </div>
                            </div>
                        </div>
                        <?php
                            $currentDateDue = new DateTime();
                            $dueDate = new DateTime($fetch_borrow_book['DueDate']);
                            $countInDue = $dueDate->diff($currentDateDue)->days; 
                            // havent return and overdue
                            if(($fetch_borrow_book['DateReturn'] == "0000-00-00 00:00:00") && ($current_time >= $fetch_borrow_book['DueDate'])){
                                ?>
                                <div class="detail-box">
                                    <div class="clock-box" style="background-color: #E27171">
                                        <div class="clock-icon"><i class="fa-solid fa-clock-rotate-left" style="color: #ffffff;"></i></div>
                                        &nbsp;
                                        <div class="due">Overdue</div><b>:</b>
                                        &nbsp;
                                        <div class="due-date"><?=$fetch_borrow_book['DueDate']?></div>
                                    </div>
                                    
                                    <div class="date-box">
                                        <div class="date-registered">Date Borrowed:</div>
                                        &nbsp;
                                        <div class="date"><?= $fetch_borrow_book['DateBorrow']; ?></div>
                                    </div>
                                </div>
                            <?php
                            }else if (($fetch_borrow_book['DateReturn'] == "0000-00-00 00:00:00") && $countInDue < 3){
                                ?>
                                <div class="detail-box">
                                    <div class="clock-box" style="background-color: #f5a142;">
                                        <div class="clock-icon"><i class="fa-solid fa-clock-rotate-left" style="color: #ffffff;"></i></div>
                                        &nbsp;
                                        <div class="due">Due in</div><b>:</b>
                                        &nbsp;
                                        <div class="due-date"><?= $countInDue ?> days</div>
                                    </div>

                                    <div class="date-box">
                                        <div class="date-registered">Date Borrowed:</div>
                                        &nbsp;
                                        <div class="date"><?= $fetch_borrow_book['DateBorrow']; ?></div>
                                    </div>
                                </div>
                            <?php
                            //havent return
                            }else if($fetch_borrow_book['DateReturn'] == "0000-00-00 00:00:00") {
                            ?>
                            <div class="detail-box">
                                <div class="clock-box" style="background-color: #A9C980;">
                                    <div class="clock-icon"><i class="fa-solid fa-clock-rotate-left" style="color: #ffffff;"></i></div>
                                    &nbsp;
                                    <div class="due">Due Date</div><b>:</b>
                                    &nbsp;
                                    <div class="due-date"><?= $fetch_borrow_book['DueDate']; ?></div>
                                </div>

                                <div class="date-box">
                                    <div class="date-registered">Date Borrowed:</div>
                                    &nbsp;
                                    <div class="date"><?= $fetch_borrow_book['DateBorrow']; ?></div>
                                </div>
                            </div>
                        <?php
                            // return before duedate
                            }else if($fetch_borrow_book['DateReturn'] <= $fetch_borrow_book['DueDate']){
                                ?>
                                <div class="detail-box">
                                    <div class="clock-box" style="background-color: #00C2CB">
                                        <div class="clock-icon"><i class="fa-solid fa-clock-rotate-left" style="color: #ffffff;"></i></div>
                                        &nbsp;
                                        <div class="due">Returned</div>
                                    </div>

                                    <div class="date-box">
                                        <div class="date-registered">Date Borrowed:</div>
                                        &nbsp;
                                        <div class="date"><?= $fetch_borrow_book['DateBorrow']; ?></div>
                                    </div>
                                </div>
                         <?php
                            }
                            //return after duedate
                            $select_paid_books = $conn->prepare("SELECT * FROM `book_issued` JOIN `fine` ON `book_issued`.`BookIssuedID` = `fine`.`BookIssuedID` WHERE `fine`.`BookIssuedID` = :bookIssuedID;");
                            $select_paid_books->bindParam(':bookIssuedID', $fetch_borrow_book['BookIssuedID'], PDO::PARAM_INT);
                            $select_paid_books->execute();
                            if($select_paid_books->rowCount() > 0){
                                $fetch_paid_book = $select_paid_books->fetch(PDO::FETCH_ASSOC);
                                //haven't paid fine
                                if(($fetch_borrow_book['DateReturn'] >= $fetch_borrow_book['DueDate']) && $fetch_paid_book['IsPaid'] == 1){
                                    ?>
                                    <div class="detail-box">
                                        <div class="clock-box" style="background-color: #00C2CB">
                                            <div class="clock-icon"><i class="fa-solid fa-clock-rotate-left" style="color: #ffffff;"></i></div>
                                            &nbsp;
                                            <div class="due">Overdue and Paid</div>
                                        </div>
                                        
                                        <div class="date-box">
                                            <div class="date-registered">Date Borrowed:</div>
                                            &nbsp;
                                            <div class="date"><?= $fetch_borrow_book['DateBorrow']; ?></div>
                                        </div>
                                        <div class="date-box">
                                            <div class="date-registered">Date Paid:</div>
                                            &nbsp;
                                            <div class="date"><?= $fetch_paid_book['DateCompleteFine']; ?></div>
                                        </div>
                                    </div>
                                <?php
                                //paid fine
                                }else if($fetch_borrow_book['DateReturn'] >= $fetch_borrow_book['DueDate']){
                                    $returnDate = new DateTime($fetch_borrow_book['DateReturn']);
                                    $dueDate = new DateTime($fetch_borrow_book['DueDate']);
                                    $count = ($returnDate->diff($dueDate))->days;
                                    ?>
                                    <div class="detail-box">
                                        <div class="clock-box" style="background-color: #E27171">
                                            <div class="clock-icon"><i class="fa-solid fa-clock-rotate-left" style="color: #ffffff;"></i></div>
                                            &nbsp;
                                            <div class="due">Overdue by</div>
                                            &nbsp;
                                            <div class="due-date"><?= $count ?> days</div>
                                        </div>
                                        
                                        <div class="date-box">
                                            <div class="date-registered">Date Borrowed:</div>
                                            &nbsp;
                                            <div class="date"><?= $fetch_borrow_book['DateBorrow']; ?></div>
                                        </div>
                                        <div class="date-box">
                                            <div class="date-registered">Date Paid:</div>
                                            &nbsp;
                                            <div class="date">Pending...</div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            
                        ?>
                    </div>
                <?php
                    }
                }
                ?>
            </div>
            <!-- borrowed -->

            <!-- reserved -->
            <div style="width: 100%; display: none;" id="reservedPage">
                <?php
                    $current_time = date('Y-m-d H:i:s');
                    $select_reserve_books = $conn->prepare("SELECT DISTINCT `book`.`Title`, GROUP_CONCAT(`author`.Name) AS `Authors`, `book`.`Image`, `book`.`Status`, `book_reserve`.* FROM `book` JOIN `author` ON `book`.`BookID` = `author`.`BookID` JOIN `book_reserve` ON `book`.`BookID` = `book_reserve`.`BookID` WHERE `book_reserve`.`UserID`='" . $_SESSION['ID'] . "' GROUP BY `book_reserve`.`ReserveID` ORDER BY `book_reserve`.`AllowBorrow` DESC, `book_reserve`.`Borrowed` ASC, `book_reserve`.`DateReserve` DESC; ");
                    $select_reserve_books->execute();
                    if($select_reserve_books->rowCount() > 0){
                        while($fetch_reserve_book = $select_reserve_books->fetch(PDO::FETCH_ASSOC)){
                ?>
                    <div class="borrowed-box">
                        <div class="container-fluid">
                            <div class="d-flex">
                                <img src="<?= $fetch_reserve_book['Image']; ?>" alt="" title="<?= $fetch_reserve_book['Title']; ?>" class="book-pic">
                                
                                <div>
                                    <div class="title"><?= $fetch_reserve_book['Title']; ?></div>
                                    <div class="author"><?= $fetch_reserve_book['Authors']; ?></div>
                                    <?php
                                    if($fetch_reserve_book['AllowBorrow'] == "1" && $fetch_reserve_book['Borrowed'] == '0' && $fetch_reserve_book['DateReserve'] != "0000-00-00 00:00:00") {
                                    ?>
                                        <div id = "reserveModal<?= $fetch_reserve_book['ReserveID']; ?>" class="modal">
                                            <div class="modal-content" id = "reserveBox">
                                                <div id = "reserveHeader" class="modal-header"><h2>Cancelation</h2></div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to cancel your <?= $fetch_reserve_book['Title']; ?> reservation?</p>
                                                </div>
                                                <div class="container">
                                                    <div class="group-48">
                                                        <button onclick="hideComfirmationCancelPage(event, <?= $fetch_reserve_book['ReserveID']; ?>)" class="btn btn-secondary">No</button>
                                                    </div>
                                                    <form class="group-49" method="post">
                                                        <input type="hidden" name="reserveID" value="<?= $fetch_reserve_book['ReserveID']; ?>">
                                                        <button type="submit" class="btn btn-primary">Yes</button>
                                                    </form>
                                                </div>
                                            </div>	
                                        </div>
                                        <div id = "borrowModal<?= $fetch_reserve_book['ReserveID']; ?>" class="modal">
                                            <div class="modal-content" id = "borrowBox">
                                                <div id = "borrowHeader" class="modal-header"><h2>Borrowing book</h2></div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to borrow this book?</p>
                                                </div>
                                                <div class="container">
                                                    <div class="group-48">
                                                        <button onclick="hideBorrowPage(event, <?= $fetch_reserve_book['ReserveID']; ?>)" class="btn btn-secondary">No</button>
                                                    </div>
                                                    <form class="group-49" method="post">
                                                        <input type="hidden" name="borrowBook" value="<?= $fetch_reserve_book['ReserveID']; ?>">
                                                        <input type="hidden" name="book-id" value="<?= $fetch_reserve_book['BookID']; ?>">
                                                        <button type="submit" class="btn btn-primary">Yes</button>
                                                    </form>
                                                </div>
                                            </div>	
                                        </div>
                                        <button onclick="showBorrowPage(event, <?= $fetch_reserve_book['ReserveID']; ?>)" class="return-btn">
                                            Borrow
                                            <i class="fa-solid fa-calendar-check" style="color: white;"></i>
                                        </button>
                                        <button onclick="showComfirmationCancelPage(event, <?= $fetch_reserve_book['ReserveID']; ?>)" class="extend-btn">
                                            Cancel
                                            <i class="fa-solid fa-ban"></i>
                                        </button>
                                    <?php
                                    }else if($fetch_reserve_book['AllowBorrow'] == "0" && $fetch_reserve_book['Borrowed'] == '0' && $fetch_reserve_book['DateReserve'] != "0000-00-00 00:00:00"){
                                    ?>
                                        <div id = "reserveModal<?= $fetch_reserve_book['ReserveID']; ?>" class="modal">
                                            <div class="modal-content" id = "reserveBox">
                                                <div id = "reserveHeader" class="modal-header"><h2>Cancelation</h2></div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to cancel your <?= $fetch_reserve_book['Title']; ?> reservation?</p>
                                                </div>
                                                <div class="container">
                                                    <div class="group-48">
                                                        <button onclick="hideComfirmationCancelPage(event, <?= $fetch_reserve_book['ReserveID']; ?>)" class="btn btn-secondary">No</button>
                                                    </div>
                                                    <form class="group-49" method="post">
                                                        <input type="hidden" name="reserveID" value="<?= $fetch_reserve_book['ReserveID']; ?>">
                                                        <button type="submit" class="btn btn-primary">Yes</button>
                                                    </form>
                                                </div>
                                            </div>	
                                        </div>
                                        <button onclick="showComfirmationCancelPage(event, <?= $fetch_reserve_book['ReserveID']; ?>)" class="extend-btn">
                                            Cancel
                                            <i class="fa-solid fa-ban"></i>
                                        </button>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                        if($fetch_reserve_book['DateReserve'] == "0000-00-00 00:00:00") {
                        ?>
                            <div class="detail-box">
                                <div class="clock-box" style="background-color: #C5C6C7">
                                    <div class="clock-icon"><i class="fa-solid fa-clock-rotate-left" style="color: #ffffff;"></i></div>
                                    &nbsp;
                                    <div class="due">Canceled</div>
                                </div>
                            </div>
                        <?php
                        }else if($fetch_reserve_book['AllowBorrow'] == "1" && $fetch_reserve_book['Borrowed'] == '0') {
                        ?>
                            <div class="detail-box">
                                <div class="clock-box" style="background-color: #A9C980;">
                                    <div class="clock-icon"><i class="fa-solid fa-clock-rotate-left" style="color: #ffffff;"></i></div>
                                    &nbsp;
                                    <div class="due">Available Now !</div>
                                </div>

                                <div class="date-box">
                                    <div class="date-registered">Date Reserved:</div>
                                    &nbsp;
                                    <div class="date"><?= $fetch_reserve_book['DateReserve']; ?></div>
                                </div>
                            </div>
                        <?php
                        }else if($fetch_reserve_book['AllowBorrow'] == "0" && $fetch_reserve_book['Borrowed'] == '0') {
                        ?>
                            <div class="detail-box">
                                <div class="clock-box" style="background-color: #00C2CB">
                                    <div class="clock-icon"><i class="fa-solid fa-clock-rotate-left" style="color: #ffffff;"></i></div>
                                    &nbsp;
                                    <div class="due">Pending . . .</div>
                                </div>

                                <div class="date-box">
                                    <div class="date-registered">Date Reserved:</div>
                                    &nbsp;
                                    <div class="date"><?= $fetch_reserve_book['DateReserve']; ?></div>
                                </div>
                            </div>
                        <?php
                        }else {
                        ?>
                            <div class="detail-box">
                                <div class="clock-box" style="background-color: #C5C6C7">
                                    <div class="clock-icon"><i class="fa-solid fa-clock-rotate-left" style="color: #ffffff;"></i></div>
                                    &nbsp;
                                    <div class="due">Reserved and Borrowed</div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>

                    </div>
                <?php
                    }
                }
                ?>
            </div>
            <!-- reserved -->
        </div>
        <div style="background-color: #00c2cb; width:100%; height:75px; display:flex; align-items: center; justify-content: center; color: white;">
        <div style="padding-left:80px; padding-right:80px; font-family: Montserrat; font-size: 13px;"><i class="fa fa-envelope"></i><b>&nbsp;<?= $_SESSION['LibraryEmail']?></b></div>
        <div style="padding-left:150px; padding-right:100px; font-family: Montserrat; font-size: 13px;"><i class="fa fa-phone"></i><b>&nbsp;<?= $_SESSION['LibraryPhoneNo']?></b></div>
        <div style="font-size: 11px; padding-left:100px; padding-right:10px; text-align: center; font-family: Montserrat; "><i class="fa fa-location-dot"></i><b>&nbsp;33, Eko Galleria, C0301, C0302, C0401, Blok C, Taman, Persiaran Eko Botani,<br> 79100 Iskandar Puteri, Johor</b></div>
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

        // Proceed with logout when the confirm button is clicked
        document.getElementById("confirmLogout").addEventListener("click", function() {
            window.location.href = "login.php"; // Redirect to logout page
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

        function showBorrowedPage() {
            document.getElementById("reservedPage").style.display = "none";
            document.getElementById("borrowedPage").style.display = "block";
            document.getElementById("reserveBtn").style.textDecoration = "none";
            document.getElementById("borrowBtn").style.textDecoration = "underline";
        }

        function showReservedPage() {
            document.getElementById("borrowedPage").style.display = "none";
            document.getElementById("reservedPage").style.display = "block";
            document.getElementById("borrowBtn").style.textDecoration = "none";
            document.getElementById("reserveBtn").style.textDecoration = "underline";
        }

        function hideComfirmationExtendPage(event, extendId) {
            event.preventDefault();
            var modal = document.getElementById("extendModal" + extendId);
            modal.style.display = "none";
        }
        function showComfirmationExtendPage(event, extendId) {
            event.preventDefault();
            var modal = document.getElementById("extendModal" + extendId);
            modal.style.display = "block";
        }

        function hideComfirmationCancelPage(event, reserveId) {
            event.preventDefault();
            var modal = document.getElementById("reserveModal" + reserveId);
            modal.style.display = "none";
        }
        function showComfirmationCancelPage(event, reserveId) {
            event.preventDefault();
            var modal = document.getElementById("reserveModal" + reserveId);
            modal.style.display = "block";
        }

        function hideReturnPage(event, returnId){
            event.preventDefault();
            var modal = document.getElementById("returnModal" + returnId);
            modal.style.display = "none";
        }
        function showReturnPage(event, returnId){
            event.preventDefault();
            var modal = document.getElementById("returnModal" + returnId);
            modal.style.display = "block";
        }
        function hideBorrowPage(event, borrowId){
            event.preventDefault();
            var modal = document.getElementById("borrowModal" + borrowId);
            modal.style.display = "none";
        }
        function showBorrowPage(event, borrowId){
            event.preventDefault();
            var modal = document.getElementById("borrowModal" + borrowId);
            modal.style.display = "block";
        }
    </script>
</body>
</html>