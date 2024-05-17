<?php

include 'connect.php';

session_start();

if ($_SESSION['userType'] != "Librarian" && $_SESSION['userType'] != "Admin")  {
    header('location: homepage.php');
    exit();
}
// Redirect to login page if user is not logged in
if (empty($_SESSION['ID']))  {
    header('location: login.php');
    exit();
}

$conn->exec("SET time_zone = '+08:00'");
$currentDateTime = date('Y-m-d H:i:s');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" />

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <script src="https://kit.fontawesome.com/f71ea3c766.js" crossorigin="anonymous"></script>

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
	
	<style>
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
			height: fit-content;
            }

            .modal-header2 {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
            text-align: center;
            position: relative;
            margin-bottom: 1.2rem;
            display: inline-block;
            overflow-wrap: break-word;
            }

            .modal-header2 h2 {
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

            .container2{
            position: relative;
            display: flex;
            flex-direction: row;
            width: 16.4rem;
            box-sizing: border-box;
			justify-content: center;
			margin: 0 auto;
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

            .btn.btn-primary2{
            background-color: transparent;
            padding-left: 30px;
            padding-right: 30px;
            border: none;
            color: white;
            font-size: 1.3rem;
            font-weight: 550;
            }

            .group-48{
            box-shadow: 0rem 0.3rem 0.3rem 0rem rgba(0, 0, 0, 0.25);
            border-radius: 1.3rem;
            border: 0.1rem solid #002439;
            background: #FFFFFF;
            position: relative;
            margin: 0 1.2rem 0.1rem 0rem;
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
                    font-family: 'Montserrat';
                    background-color: #fefefe;
                    margin: 8% auto; /* 8% from the top and centered */
                    padding: 20px;
                    border: 1px solid #888;
                    width: 80%; /* Could be more or less, depending on screen size */
                    height: 450px;
                    border-radius: 10px;
                    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
                    -webkit-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
                    -moz-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
                    -o-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
                }
				
		ol.help {
			color: black !important;
		}
		
		ul.help{
			color: black !important;
		}
		
		
	</style>
</head>

<body style="background-color:white">
    <div class="container-xxl position-relative bg-white d-flex p-0">

        <!-- Sidebar Start -->
        <div class="sidebar">
            <nav class="navbar">
                <a href="dashboard.php" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary" style="margin-left:-15px; margin-bottom:5rem;">
                        <img src="https://i.ibb.co/dKYBCpK/Group-85.png" style="width: 2.5rem;">
                        <img src="https://pbs.twimg.com/media/GLMn-BvakAA70Dg?format=png&name=4096x4096" style="width: 9rem;"/>
                    </h3>
                </a>

                <div class="navbar-nav w-100">
                    <a href="dashboard.php" class="nav-item nav-link active"><i class="fa fa-chart-bar me-2"></i>Dashboard</a>
                    <a href="user_database.php" class="nav-item nav-link"><i class="fa-solid fa-users me-2"></i>Patrons</a>
                    <a href="book_database.php" class="nav-item nav-link"><i class="fa-solid fa-book me-2"></i>Books</a>
                    <a href="borrow.php" class="nav-item nav-link"><i class="fa-solid fa-hand-holding me-2"></i>Issue</a>
                    <a href="reserve.php" class="nav-item nav-link"><i class="fa-solid fa-calendar-check me-2"></i>Reserve</a>
                    <a href="fine_database.php" class="nav-item nav-link"><i class="fa-solid fa-money-check-dollar"></i> Fines</a>
                    <a href="setting.php" class="nav-item nav-link"><i class="fa-solid fa-gear"></i>Setting</a>
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand navbar-light sticky-top px-4 py-0" style="background-color: white;">
                <a href="dashboard.php" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0">
                        <img src="https://pbs.twimg.com/media/GLMoBtWakAAyJmP?format=jpg&name=medium" style="width: 3rem;"/>
                    </h2>
                </a>
                <a class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars fa-2xl"></i>
                </a>

                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <img class="rounded-circle me-lg-2" data-bs-toggle="dropdown" src="https://pbs.twimg.com/media/GH4y2jfWkAAteDX?format=png&name=360x360" alt="" style="width: 40px; height: 40px; cursor:pointer;">
                        <div class="dropdown-menu dropdown-menu-end border-0">
                            <a href="profile_librarian.php" class="dropdown-item">Profile</a>
                            <a href="send_otp_code.php" class="dropdown-item">Reset Password</a>
                            <a href="login.php" class="dropdown-item" id="logout" >Log Out</a>
                        </div>
                    </div>
					<button style="border-radius: 50%; background-color: #00c2cb; color: white; width: 40px; height: 40px; border: none; font-size: 25px; margin-left: 15px" id="help">?</button>
                </div>
				
				<!--FAQ-->
				<div id="myModalHelp" class="modal">
					<div class="modal-content" id="box" style="margin: 6% auto;">
						<div id="helpHeader" class="modal-header"><h2>Help and FAQs</h2></div>
						<div class="modal-body" style="text-align: left;">
							<h4>Where can I add new users?</h4>
							<ul class="help"><li>Credit-card and cash payment are accepted.</li></ul>
							<h4>Which patron counts as 'Not Active'?</h4>
							<ul class="help"><li>Alumni, Withdrew, Transferred.</li></ul>
							<h4>What is an 'Unverified' user?</h4>
							<ul class="help"><li>The user has not logged in for the first time and authenticated their account via OTP.</li></ul>
							<h4>When does reservation occur?</h4>
							<ul class="help"><li>When a book has an inventory / stock of zero.</li></ul>
							<h4>Who takes priority in a reservation list?</h4>
							<ul class="help"><li>The user that has reserved first.</li></ul>
							<h4>What kind of fine can I issue?</h4>
							<ol class="help">
								<li>Damaged Book.</li>
								<li>Lost Book</li>
							</ol>
							<h4>Can a student pay just one fine at a time?</h4>
							<ul class="help"><li>No, a student must pay all fines at once.</li></ul>
							
							<h4>How can I view the inventory of each book?</h4>
							<ol class="help">
								<li>Click on the 'Books' section to the navigation bar on the left.</li>
								<li>To the right of the search bar, click on the icon that looks like boxes.</li>
								<li>In the table below, it will show the books that are available, issued, reserved, archived, and total stock.</li>
							</ol>
							
							<h4>How do I export a table to an excel file? (example: Patron list)</h4>
							<ol class="help">
								<li>Click on the 'Patrons' section in the navigation bar to the left. </li>
								<li>Below the search bar is the header 'All Patrons' and to the right is an icon with an arrow pointing down.</li>
								<li>Click on that icon and an excel will be automatically downloaded onto your device.</li>
							</ol>
							
							<h4>The library policy has changed where the duration a book can be borrowed is a month instead of 14 days. Where and how do I change this?</h4>
							<ol class="help">
								<li>Click the 'Settings' section in the navigation bar to the left.</li>
								<li>There is a line 'Book returned after', followed by an input box, and then 'days'.</li>
								<li>Enter the number of days that a book can be borrowed at one time. In this case, 30 days.</li>
								<li>Click on the button 'Save Settings' below.</li>
								<li>A successful prompt message will show up, and the borrow duration will be changed accordingly.</li>
							</ol>
						</div>
						<div class="container" style="width:9.6rem;">
							<div class="group-48">
							<button id="closeHelp" class="btn btn-secondary">Cancel</button>
							</div>
						</div>
					</div>	
				</div>
            </nav>
			
			
            <!-- Navbar End -->


            <!-- Sale & Revenue Start-->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa-solid fa-hand-holding fa-3x icon-primary"></i>
                            <div class="ms-3 icon-text">
                                <p class="mb-2">Issued</p>
                                <?php
                                    $select_issued_amount = $conn->prepare("SELECT COUNT(*) AS Count FROM book_issued WHERE DateReturn = '0000-00-00 00:00:00';");
                                    $select_issued_amount->execute();
                                    if($select_issued_amount->rowCount() > 0){
                                        while($fetch_issued_amount = $select_issued_amount->fetch(PDO::FETCH_ASSOC)){
                                ?>
                                        <h6 class="mb-0"><?= $fetch_issued_amount['Count']; ?></h6>
                                <?php
                                        }
                                    }else{
                                        ?>
                                        <h6 class="mb-0">0</h6>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-bar fa-3x icon-primary"></i>
                            <div class="ms-3 icon-text">
                                <p class="mb-2">Returned</p>
                                <?php
                                    $select_returned_amount = $conn->prepare("SELECT COUNT(*) AS Count FROM book_issued WHERE DateReturn != '0000-00-00 00:00:00';");
                                    $select_returned_amount->execute();
                                    if($select_returned_amount->rowCount() > 0){
                                        while($fetch_returned_amount = $select_returned_amount->fetch(PDO::FETCH_ASSOC)){
                                ?>
                                        <h6 class="mb-0"><?= $fetch_returned_amount['Count']; ?></h6>
                                <?php
                                        }
                                    }else{
                                        ?>
                                        <h6 class="mb-0">0</h6>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa-solid fa-book fa-3x icon-primary"></i>
                            <div class="ms-3 icon-text">
                                <p class="mb-2">Overdue</p>
                                <?php
                                    $select_overdue = $conn->prepare("SELECT COUNT(*) AS Count FROM `book_issued` WHERE `DueDate` < '$currentDateTime'");
                                    $select_overdue->execute();
                                    if($select_overdue->rowCount() > 0){
                                        while($fetch_overdue = $select_overdue->fetch(PDO::FETCH_ASSOC)){
                                ?>
                                        <h6 class="mb-0"><?= $fetch_overdue['Count']; ?></h6>
                                <?php
                                        }
                                    }else{
                                        ?>
                                        <h6 class="mb-0">0</h6>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa-solid fa-users fa-3x icon-primary"></i>
                            <div class="ms-3 icon-text">
                                <p class="mb-2">Patrons</p>
                                <?php
                                    $select_patrons_amount = $conn->prepare("SELECT COUNT(*) AS Count FROM `user` JOIN `user_type`ON `user`.`UserTypeID`=`user_type`.`UserTypeID` WHERE `user_type`.`Type` != 'Admin';");
                                    $select_patrons_amount->execute();
                                    if($select_patrons_amount->rowCount() > 0){
                                        while($fetch_patrons_amount = $select_patrons_amount->fetch(PDO::FETCH_ASSOC)){
                                ?>
                                        <h6 class="mb-0"><?= $fetch_patrons_amount['Count']; ?></h6>
                                <?php
                                        }
                                    }else{
                                        ?>
                                        <h6 class="mb-0">0</h6>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--Sale & Revenue End -->
			
			<!--Borrowed books-->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-3">
                    <div class="col">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa-solid fa-hand-holding fa-3x icon-primary"></i>
                            <div class="ms-3 icon-text">
                                <p class="mb-2">Most Borrowed Book (Of All Time)</p>
                                <?php
                                    $select_most_borrowed = $conn->prepare("SELECT Title, COUNT(*) As Count FROM book JOIN book_issued ON book_issued.BookID = book.BookID GROUP BY book.title ORDER BY COUNT(*) DESC LIMIT 1;");
                                    $select_most_borrowed ->execute();
                                    if($select_most_borrowed ->rowCount() > 0){
                                        while($fetch_most_borrowed  = $select_most_borrowed ->fetch(PDO::FETCH_ASSOC)){
                                ?>
                                        <h6 class="mb-0"><?= $fetch_most_borrowed['Title']; ?></h6>
										<p class="mb-2">Times Borrowed: <?= $fetch_most_borrowed['Count']; ?> </p>
                                <?php
                                        }
                                    }else{
                                        ?>
                                        <h6 class="mb-0">0</h6>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-bar fa-3x icon-primary"></i>
                            <div class="ms-3 icon-text">
                                <p class="mb-2">Least Borrowed Book (Of All Time)</p>
                                <?php
                                    $select_least_borrowed = $conn->prepare("SELECT Title, COUNT(*) As Count FROM book JOIN book_issued ON book_issued.BookID = book.BookID GROUP BY book.title ORDER BY COUNT(*) ASC LIMIT 1;");
                                    $select_least_borrowed->execute();
                                    if($select_least_borrowed->rowCount() > 0){
                                        while($fetch_least_borrowed = $select_least_borrowed->fetch(PDO::FETCH_ASSOC)){
                                ?>
                                        <h6 class="mb-0"><?= $fetch_least_borrowed['Title']; ?></h6>
										<p class="mb-2">Times Borrowed: <?= $fetch_least_borrowed['Count']; ?> </p>
										
                                <?php
                                        }
                                    }else{
                                        ?>
                                        <h6 class="mb-0">0</h6>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<!--borrowed books - according to month-->
			<div class="container-fluid pt-4 px-4">
                <div class="row g-3">
                    <div class="col">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa-solid fa-hand-holding fa-3x icon-primary"></i>
                            <div class="ms-3 icon-text">
                                <p class="mb-2">Most Borrowed Book (This Month)</p>
                                <?php
                                    $select_most_borrowed_month = $conn->prepare("SELECT book.Title, COUNT(*) As Count
																			FROM book
																			JOIN book_issued ON book_issued.BookID = book.BookID
																			WHERE MONTH(book_issued.DateBorrow) = MONTH(CURRENT_DATE())
																			AND YEAR(book_issued.DateBorrow) = YEAR(CURRENT_DATE())
																			GROUP BY book.title 
																			ORDER BY COUNT(*) DESC LIMIT 1;");
                                    $select_most_borrowed_month ->execute();
                                    if($select_most_borrowed_month ->rowCount() > 0){
                                        while($fetch_most_borrowed_month  = $select_most_borrowed_month ->fetch(PDO::FETCH_ASSOC)){
                                ?>
                                        <h6 class="mb-0"><?= $fetch_most_borrowed_month['Title']; ?></h6>
										<p class="mb-2">Times Borrowed: <?= $fetch_most_borrowed_month['Count']; ?> </p>
                                <?php
                                        }
                                    }else{
                                        ?>
                                        <h6 class="mb-0">0</h6>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-bar fa-3x icon-primary"></i>
                            <div class="ms-3 icon-text">
                                <p class="mb-2">Least Borrowed Book (This Month)</p>
                                <?php
                                    $select_least_borrowed_month = $conn->prepare("SELECT book.Title, COUNT(*) As Count
																			FROM book
																			JOIN book_issued ON book_issued.BookID = book.BookID
																			WHERE MONTH(book_issued.DateBorrow) = MONTH(CURRENT_DATE())
																			AND YEAR(book_issued.DateBorrow) = YEAR(CURRENT_DATE())
																			GROUP BY book.title 
																			ORDER BY COUNT(*) ASC LIMIT 1;");
                                    $select_least_borrowed_month ->execute();
                                    if($select_least_borrowed_month ->rowCount() > 0){
                                        while($fetch_least_borrowed_month  = $select_least_borrowed_month ->fetch(PDO::FETCH_ASSOC)){
                                ?>
                                        <h6 class="mb-0"><?= $fetch_least_borrowed_month['Title']; ?></h6>
										<p class="mb-2">Times Borrowed: <?= $fetch_least_borrowed_month['Count']; ?> </p>
                                <?php
                                        }
                                    }else{
                                        ?>
                                        <h6 class="mb-0">0</h6>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			
			<!--reserved books-->
			<div class="container-fluid pt-4 px-4">
                <div class="row g-3">
                    <div class="col">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa-solid fa-hand-holding fa-3x icon-primary"></i>
                            <div class="ms-3 icon-text">
                                <p class="mb-2">Most Reserved Book (Of All Time)</p>
                                <?php
                                    $select_most_reserved = $conn->prepare("SELECT book.Title, COUNT(*) As Count
																			FROM book
																			JOIN book_reserve ON book_reserve.BookID = book.BookID
																			GROUP BY book.title 
																			ORDER BY COUNT(*) DESC LIMIT 1;");
                                    $select_most_reserved ->execute();
                                    if($select_most_reserved ->rowCount() > 0){
                                        while($fetch_most_reserved  = $select_most_reserved ->fetch(PDO::FETCH_ASSOC)){
                                ?>
                                        <h6 class="mb-0"><?= $fetch_most_reserved['Title']; ?></h6>
										<p class="mb-2">Times Reserved: <?= $fetch_most_reserved['Count']; ?> </p>
                                <?php
                                        }
                                    }else{
                                        ?>
                                        <h6 class="mb-0">0</h6>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-bar fa-3x icon-primary"></i>
                            <div class="ms-3 icon-text">
                                <p class="mb-2">Least Reserved Book (Of All Time)</p>
                                <?php
                                    $select_least_reserved = $conn->prepare("SELECT book.Title, COUNT(*) As Count
																			FROM book
																			JOIN book_reserve ON book_reserve.BookID = book.BookID
																			GROUP BY book.title 
																			ORDER BY COUNT(*) ASC LIMIT 1;");
                                    $select_least_reserved ->execute();
                                    if($select_least_reserved ->rowCount() > 0){
                                        while($fetch_least_reserved  = $select_least_reserved ->fetch(PDO::FETCH_ASSOC)){
                                ?>
                                        <h6 class="mb-0"><?= $fetch_least_reserved['Title']; ?></h6>
										<p class="mb-2">Times Reserved: <?= $fetch_least_reserved['Count']; ?> </p>
                                <?php
                                        }
                                    }else{
                                        ?>
                                        <h6 class="mb-0">0</h6>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		
		
        <!-- Content End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top" ><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
	
	<script>
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