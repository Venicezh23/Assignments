<?php
    include 'connect.php';

    session_start();

    $conn->exec("SET time_zone = '+08:00'");
    $currentDateTime = date('Y-m-d H:i:s');

    if ($_SESSION['userType'] != "Librarian" && $_SESSION['userType'] != "Admin")  {
        header('location: homepage.php');
        exit();
    }
    if (empty($_SESSION['ID']))  {
        header('location: login.php');
        exit();
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Borrow</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <link rel="stylesheet" href="database.css">
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
            text-align: center;
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

        .btn.btn-secondary-modal{
            color: black;
            background-color: white;
            padding-left: 20px;
            padding-right: 20px;
            border: none;
            font-size: 1.3rem;
            font-weight: 550;
        }

        .btn.btn-primary-modal{
            color: #FFFFFF;
            background-color: transparent;
            padding-left: 30px;
            padding-right: 30px;
            border: none;
            font-size: 1.3rem;
            font-weight: 550;
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
		
		ol.help {
			color: black !important;
		}
		
		ul.help{
			color: black !important;
		}
	
        .table-height {
            max-height: 450px; 
        }

        .history-table th:nth-child(4) {
            min-width: 100px; 
        }

        .history-table th:nth-child(5) {
            min-width: 80px; 
        }

        .history-table th:nth-child(6),
        .history-table td:nth-child(6) {
            min-width: 250px; 
        }

        .history-table th:nth-child(3),
        .history-table td:nth-child(3),
        .history-table th:nth-child(7),
        .history-table td:nth-child(7),
        .history-table th:nth-child(8),
        .history-table td:nth-child(8),
        .history-table th:nth-child(9),
        .history-table td:nth-child(9),
        .history-table th:nth-child(10),
        .history-table td:nth-child(10){
            min-width: 150px; 
        }

        .history-table th:nth-child(2),
        .history-table td:nth-child(2),
        .history-table th:nth-child(5),
        .history-table td:nth-child(5) {
            text-align: center;
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
            text-align: center;
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

            .btn.btn-primary2{
            background-color: transparent;
            padding-left: 30px;
            padding-right: 30px;
            border: none;
            color: white;
            font-size: 1.3rem;
            font-weight: 550;
            }
			
			.btn.btn-primary{
			background-color: transparent;
			padding-left: 30px;
			padding-right: 30px;
			border: none;
		}

        .container2 {
            display: flex;
            justify-content: center;
        }

        .modal-header2 {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
            text-align: center;
            position: relative;
            margin-bottom: 1.2rem;
            display: inline-block;
            overflow-wrap: break-word;
            justify-content: center;
        }

        .modal-header2 h2 {
            font-family: 'Montserrat';
            font-weight: 700;
            font-size: 2.8rem;
            color: #002439;
        }

        .group-48,
        .group-49 {
            margin: 0 1.2rem 0.1rem 0rem;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center; /* Align items vertically */
            padding: 0.6rem 0.1rem 0.7rem 0;
            width: 7.6rem;
            height: fit-content;
            box-sizing: border-box;
        }

        .group-48 {
            border-radius: 1.3rem;
            border: 0.1rem solid #002439;
            background: #FFFFFF;
            box-shadow: 0rem 0.3rem 0.3rem 0rem rgba(0, 0, 0, 0.25);
        }

        .group-49 {
            border-radius: 1.3rem;
            box-shadow: 0rem 0.4rem 0.3rem 0rem rgba(0, 0, 0, 0.25);
            background: linear-gradient(90deg, #002439, #00C2CB);
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
                    margin: 15% auto; /* 8% from the top and centered */
                    padding: 20px;
                    border: 1px solid #888;
                    width: 80%; /* Could be more or less, depending on screen size */
                    height: fit-content;
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
                    <a href="dashboard.php" class="nav-item nav-link"><i class="fa fa-chart-bar me-2"></i>Dashboard</a>
                    <a href="user_database.php" class="nav-item nav-link"><i class="fa-solid fa-users me-2"></i>Patrons</a>
                    <a href="book_database.php" class="nav-item nav-link"><i class="fa-solid fa-book me-2"></i>Books</a>
                    <a href="borrow.php" class="nav-item nav-link active"><i class="fa-solid fa-hand-holding me-2"></i>Issue</a>
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
                <div id = "myModal" class="modal">
                    <div class="modal-content" id = "box">
                        <div class="modal-header2"><h2>Register Borrow</h2></div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="label-text">Patron's ID</label>
                                <!-- <input type="text" placeholder="Enter the patron's ID" class="input-box" id="patron-input" name="patron-input" required></input> -->
                                <?php
                                    $sql = "SELECT userid, id FROM user;";
                                    $result = $conn->query($sql);

                                    if ($result->rowCount() > 0) {
                                        echo '<select class="select-box" id="patron-input" name="patron-input" required>';
                                        echo '<option value="" disabled selected>Select a student</option>';
                                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                            echo '<option value="' . $row["userid"] . '">' . $row["id"] . '</option>';
                                        }
                                        echo '</select>';
                                    } else {
                                        echo "No existing users.";
                                    }
                                ?>
                            </div>
                            <div class="mb-3">
                                <label class="label-text">Call Number</label>
                                <?php
                                    $sql = "SELECT bookid, callnumber FROM book WHERE status='Available';";
                                    $result = $conn->query($sql);

                                    if ($result->rowCount() > 0) {
                                        echo '<select class="select-box" id="call-num-input" name="call-num-input" required>';
                                        echo '<option value="" disabled selected>Choose a call number</option>';
                                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                            echo '<option value="' . $row["bookid"] . '">' . $row["callnumber"] . '</option>';
                                        }
                                        echo '</select>';
                                    } else {
                                        echo "No available books.";
                                    }
                                ?>
                            </div>
                        </div>
                        
                        <div class="container2">
                            <div class="group-48">
                            <button id="cancelRegisterBorrow" class="btn btn-secondary">Cancel</button>
                            </div>
                            <div class="group-49">
                            <button id="confirmRegisterBorrow" class="btn btn-primary2">Yes</button>
                            </div>
                        </div>
                        
                    </div>
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


            <!-- Issued and Overdue Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-3">
                    <div class="col"> 
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa-solid fa-file-export fa-3x icon-primary"></i>
                            <div class="ms-3 icon-text">
                                <p class="mb-2">Issued Books</p>
                                <?php
                                    $select_issued = $conn->prepare("SELECT COUNT(*) AS Count FROM `book_issued` WHERE `DateReturn` = '0000-00-00 00:00:00';");
                                    $select_issued->execute();
                                    if($select_issued->rowCount() > 0){
                                        while($fetch_issued = $select_issued->fetch(PDO::FETCH_ASSOC)){
                                ?>
                                        <h6 class="mb-0"><?= $fetch_issued['Count']; ?></h6>
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
                            <i class="fa-solid fa-clock-rotate-left fa-3x icon-primary"></i>
                            <div class="ms-3 icon-text">
                                <p class="mb-2">Overdue Books</p>
                                <?php
                                    $select_overdue = $conn->prepare("SELECT COUNT(*) AS Count FROM `book_issued` WHERE `DueDate` < '$currentDateTime' AND `DateReturn` = '0000-00-00 00:00:00';");
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
                    
                    <div class="col-sm-6">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa-solid fa-book-open-reader fa-3x icon-primary"></i>
                            <a id="registerBorrow" href="#" class="register-button">Register Borrow</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Issued and Overdue End -->


            <!-- Table issued and return Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light text-center rounded p-4">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h6 class="mb-0 header-graph-text">Issued Today</h6>
                            </div>

                            <div class="table-responsive table-height">
                                <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>IssuedID</th>
                                        <th>Patron's Name</th>
                                        <th>Patron's ID</th>
                                        <th>Book Title</th>
                                        <th>Issued's Time</th>
                                        <th>Due Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $select_issued_user = $conn->prepare("SELECT `book`.*, `user`.*, `book_issued`.*, CONCAT(`LastName`,`FirstName`) AS `Name` FROM `user` JOIN `book_issued`ON `user`.`UserID`=`book_issued`.`UserID` JOIN `book` ON `book`.`BookID`=`book_issued`.`BookID` WHERE DATE(DateBorrow) = CURDATE() and DateReturn = \"0000-00-00 00:00:00\"; ");
                                        $select_issued_user->execute();
                                        if($select_issued_user->rowCount() > 0){
                                            while($fetch_issued_user = $select_issued_user->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                        <tr>
                                            <td><?= $fetch_issued_user['BookIssuedID']; ?></td>
                                            <td><?= $fetch_issued_user['Name']; ?></td>
                                            <td><?= $fetch_issued_user['ID']; ?></td>
                                            <td><?= $fetch_issued_user['Title']; ?></td>
                                            <td><?= $fetch_issued_user['DateBorrow']; ?></td>
                                            <td><?= $fetch_issued_user['DueDate']; ?></td>
                                        </tr>
                                    <?php
                                    }
                                    }
                                    ?>
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light text-center rounded p-4">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h6 class="mb-0 header-graph-text">Returned Today</h6>
                            </div>

                            <div class="table-responsive table-height">
                                <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>IssuedID</th>
                                        <th>Patron's Name</th>
                                        <th>Patron's ID</th>
                                        <th>Book Title</th>
                                        <th>Returned's Time</th>
                                        <th>Due Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $select_returned_user = $conn->prepare("SELECT `book`.*, `user`.*, `book_issued`.*, CONCAT(`LastName`,`FirstName`) AS `Name` FROM `user` JOIN `book_issued`ON `user`.`UserID`=`book_issued`.`UserID` JOIN `book` ON `book`.`BookID`=`book_issued`.`BookID` WHERE DATE(DateBorrow) = CURDATE() and DateReturn != \"0000-00-00 00:00:00\"; ");
                                        $select_returned_user->execute();
                                        if($select_returned_user->rowCount() > 0){
                                            while($fetch_returned_user = $select_returned_user->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                        <tr>
                                            <td><?= $fetch_returned_user['BookIssuedID']; ?></td>
                                            <td><?= $fetch_returned_user['Name']; ?></td>
                                            <td><?= $fetch_returned_user['ID']; ?></td>
                                            <td><?= $fetch_returned_user['Title']; ?></td>
                                            <td><?= $fetch_returned_user['DateReturn']; ?></td>
                                            <td><?= $fetch_returned_user['DueDate']; ?></td>
                                        </tr>
                                    <?php
                                    }
                                    }
                                    ?>
                                </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Table issued and return End -->


            <!-- Recent History -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <form action="" method="GET" class="search-form">
                            <div class="d-flex align-items-center">
                                <div class="search-container">
                                    <input type="text" name="search" value="<?php if(isset($_GET['search'])){echo $_GET['search'];}?>" placeholder="Enter any details of an issued" class="searchinput" id="searchInput">
                                    <button type="submit" class="searchbutton" id="searchBtn">
                                        <img class="search-img" src="https://pbs.twimg.com/media/GH4zgZ_XYAAVjAL?format=png&name=240x240" alt="Search">
                                    </button>
                                </div>
                                <div class="dropdown-sort"> 
                                    <img disabled class="sort-button" data-bs-toggle="dropdown" src="https://cdn-icons-png.freepik.com/256/14224/14224547.png?ga=GA1.1.1201796896.1698075133&" alt="" style="width: 40px; height: 40px; cursor:pointer;">
                                    <div class="dropdown-menu dropdown-menu-end border-0">
                                        <a id="allreservekBtn" class="dropdown-item">All Issued</a>
                                        <a id="unavailableBtn" class="dropdown-item">Returned</a>
                                        <a id="borrowedBtn" class="dropdown-item">No Return</a>
                                        <a id="noBorrowBtn" class="dropdown-item">Overdue</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0 header-graph-text">
                            <?php
                            if (isset($_GET['filter'])){
                                $filtervalues = $_GET['filter'];
                            ?>
                                <?= $filtervalues?> History
                            <?php
                            }else{
                            ?>
                                All Issued History
                            <?php
                            }
                            ?>
                            <button class="download-btn" id="download-btn" onclick="exportToExcel();">
                                <i class="fa-solid fa-file-arrow-down fa-lg"></i>
                            </button>
                        </h6>
                    </div>
                    <div class="table-responsive">
                    <table class="data-table history-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>IssuedID</th>
                                        <th>Patron's Name</th>
                                        <th>Patron's ID</th>
                                        <th>Book ID</th>
                                        <th>Book Title</th>
                                        <th>Call Number</th>
                                        <th>Issued's Time</th>
                                        <th>Returned's Time</th>
                                        <th>Due Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(isset($_GET['search'])){
                                $searchvalues = $_GET['search'];
                                $select_returned_user = $conn->prepare("SELECT `book`.*, `user`.*, `book_issued`.*, CONCAT(`user`.`LastName`, ' ', `user`.`FirstName`) AS `Name` FROM `user` JOIN `book_issued` ON `user`.`UserID` = `book_issued`.`UserID` JOIN `book` ON `book`.`BookID` = `book_issued`.`BookID` WHERE CONCAT(`book_issued`.`BookIssuedID`, CONCAT(`user`.`LastName`, ' ', `user`.`FirstName`), `user`.`ID`, `book`.`BookID`, `book`.`Title`, `book`.`CallNumber`, `book_issued`.`DateBorrow`, `book_issued`.`DateReturn`, `book_issued`.`DueDate`) LIKE '%$searchvalues%'; ");
                                $select_returned_user->execute();
                                if($select_returned_user->rowCount() > 0){
                                    while($fetch_returned_user = $select_returned_user->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                        <tr>
                                            <td class="returnBtnCell">
                                                <form id="book_return_<?= $fetch_returned_user['BookIssuedID']; ?>" action="book_return.php" method="post">
                                                    <input type="hidden" name="book-id" value="<?= $fetch_returned_user['BookID']; ?>">
                                                    <input type="hidden" name="user-id" value="<?= $fetch_returned_user['UserID']; ?>">
                                                    <input type="hidden" name="book-issued-id" value="<?= $fetch_returned_user['BookIssuedID']; ?>">
                                                    <input type="hidden" name="reserve-exist" value="<?php echo $reserve_exist; ?>">
                                                    <button type="button" style="display:none;" class="table-button" style="margin-right: 10px;" onclick="returnFunction(<?= $fetch_returned_user['BookIssuedID']; ?>)"><i class="fa-solid fa-arrow-right-to-bracket" width="20px" height="20px"></i></button>
                                                </form>
                                            </td>
                                            <td><?= $fetch_returned_user['BookIssuedID']; ?></td>
                                            <td><?= $fetch_returned_user['Name']; ?></td>
                                            <td><?= $fetch_returned_user['ID']; ?></td>
                                            <td><?= $fetch_returned_user['BookID']; ?></td>
                                            <td><?= $fetch_returned_user['Title']; ?></td>
                                            <td><?= $fetch_returned_user['CallNumber']; ?></td>
                                            <td><?= $fetch_returned_user['DateBorrow']; ?></td>
                                            <td class="returnCell"><?= $fetch_returned_user['DateReturn']; ?></td>
                                            <td class="overdueCell"><?= $fetch_returned_user['DueDate']; ?></td>
                                        </tr>
                                    <?php
                                    }
                                    }
                                    }elseif (isset($_GET['filter'])){
                                        $filtervalues = $_GET['filter'];
                                        if ($filtervalues=="Returned"){
                                            $select_returned_user = $conn->prepare("SELECT `book`.*, `user`.*, `book_issued`.*, CONCAT(`LastName`,`FirstName`) AS `Name` FROM `user` JOIN `book_issued`ON `user`.`UserID`=`book_issued`.`UserID` JOIN `book` ON `book`.`BookID`=`book_issued`.`BookID` WHERE `book_issued`.`DateReturn` != '0000-00-00 00:00:00'; ");
                                        }else if ($filtervalues=="No Return"){
                                            $select_returned_user = $conn->prepare("SELECT `book`.*, `user`.*, `book_issued`.*, CONCAT(`LastName`,`FirstName`) AS `Name` FROM `user` JOIN `book_issued`ON `user`.`UserID`=`book_issued`.`UserID` JOIN `book` ON `book`.`BookID`=`book_issued`.`BookID` WHERE `book_issued`.`DateReturn` = '0000-00-00 00:00:00';");
                                        }else if ($filtervalues=="Overdue"){
                                            $select_returned_user = $conn->prepare("SELECT `book`.*, `user`.*, `book_issued`.*, CONCAT(`LastName`,`FirstName`) AS `Name` FROM `user` JOIN `book_issued`ON `user`.`UserID`=`book_issued`.`UserID` JOIN `book` ON `book`.`BookID`=`book_issued`.`BookID` WHERE (`book_issued`.`DueDate` < '$currentDateTime' AND `book_issued`.`DateReturn` = '0000-00-00 00:00:00') OR `book_issued`.`DueDate`<`book_issued`.`DateReturn`;");
                                        }
                                        $select_returned_user->execute();
                                        if($select_returned_user->rowCount() > 0){
                                            while($fetch_returned_user = $select_returned_user->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                        <tr>
                                            <td class="returnBtnCell">
                                                <form id="book_return_<?= $fetch_returned_user['BookIssuedID']; ?>" action="book_return.php" method="post">
                                                    <input type="hidden" name="book-id" value="<?= $fetch_returned_user['BookID']; ?>">
                                                    <input type="hidden" name="user-id" value="<?= $fetch_returned_user['UserID']; ?>">
                                                    <input type="hidden" name="book-issued-id" value="<?= $fetch_returned_user['BookIssuedID']; ?>">
                                                    <input type="hidden" name="reserve-exist" value="<?php echo $reserve_exist; ?>">
                                                    <button type="button" style="display:none;" class="table-button" style="margin-right: 10px;" onclick="returnFunction(<?= $fetch_returned_user['BookIssuedID']; ?>)"><i class="fa-solid fa-arrow-right-to-bracket" width="20px" height="20px"></i></button>
                                                </form>
                                            </td>
                                            <td><?= $fetch_returned_user['BookIssuedID']; ?></td>
                                            <td><?= $fetch_returned_user['Name']; ?></td>
                                            <td><?= $fetch_returned_user['ID']; ?></td>
                                            <td><?= $fetch_returned_user['BookID']; ?></td>
                                            <td><?= $fetch_returned_user['Title']; ?></td>
                                            <td><?= $fetch_returned_user['CallNumber']; ?></td>
                                            <td><?= $fetch_returned_user['DateBorrow']; ?></td>
                                            <td class="returnCell"><?= $fetch_returned_user['DateReturn']; ?></td>
                                            <td class="overdueCell"><?= $fetch_returned_user['DueDate']; ?></td>
                                        </tr>
                                    <?php
                                    }
                                    }
                                    }else{
                                        $select_returned_user = $conn->prepare("SELECT `book`.*, `user`.*, `book_issued`.*, CONCAT(`LastName`,`FirstName`) AS `Name` FROM `user` JOIN `book_issued`ON `user`.`UserID`=`book_issued`.`UserID` JOIN `book` ON `book`.`BookID`=`book_issued`.`BookID`; ");
                                        $reserve_exist = false;
                                        $select_returned_user->execute();
                                        if($select_returned_user->rowCount() > 0){
                                            while($fetch_returned_user = $select_returned_user->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                        <tr>
                                            <td class="returnBtnCell">
                                                <form id="book_return_<?= $fetch_returned_user['BookIssuedID']; ?>" action="book_return.php" method="post">
                                                    <input type="hidden" name="book-id" value="<?= $fetch_returned_user['BookID']; ?>">
                                                    <input type="hidden" name="user-id" value="<?= $fetch_returned_user['UserID']; ?>">
                                                    <input type="hidden" name="book-issued-id" value="<?= $fetch_returned_user['BookIssuedID']; ?>">
                                                    <input type="hidden" name="reserve-exist" value="<?php echo $reserve_exist; ?>">
                                                    <button type="button" style="display:none;" class="table-button" style="margin-right: 10px;" onclick="returnFunction(<?= $fetch_returned_user['BookIssuedID']; ?>)"><i class="fa-solid fa-arrow-right-to-bracket" width="20px" height="20px"></i></button>
                                                </form>
                                            </td>
                                            <td><?= $fetch_returned_user['BookIssuedID']; ?></td>
                                            <td><?= $fetch_returned_user['Name']; ?></td>
                                            <td><?= $fetch_returned_user['ID']; ?></td>
                                            <td><?= $fetch_returned_user['BookID']; ?></td>
                                            <td><?= $fetch_returned_user['Title']; ?></td>
                                            <td><?= $fetch_returned_user['CallNumber']; ?></td>
                                            <td><?= $fetch_returned_user['DateBorrow']; ?></td>
                                            <td class="returnCell"><?= $fetch_returned_user['DateReturn']; ?></td>
                                            <td class="overdueCell"><?= $fetch_returned_user['DueDate']; ?></td>
                                        </tr>
                                    <?php
                                    }
                                    }
                                    }
                                    ?>
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
            <!-- Recent Sales End -->


        </div>
        <!-- Content End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top" ><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>

    <script>
        function exportToExcel() {
            /* Select the table */
            var table = document.querySelector('.data-table.history-table');

            /* Specify the indices of the columns you want to export */
            var columnIndices = [1, 2, 3, 4, 5, 6, 7, 8, 9]; // Adjust as needed

            /* Extract the headers and rows for the specified columns */
            var headers = [];
            var rows = [];
            var headerRow = table.querySelector('thead tr');
            headerRow.querySelectorAll('th').forEach(function(th, index) {
                if (columnIndices.includes(index)) {
                    headers.push(th.innerText);
                }
            });
            table.querySelectorAll('tbody tr').forEach(function(tr) {
                var rowData = [];
                tr.querySelectorAll('td').forEach(function(td, index) {
                    if (columnIndices.includes(index)) {
                        rowData.push(td.innerText);
                    }
                });
                rows.push(rowData);
            });

            /* Convert extracted data to worksheet */
            var ws = XLSX.utils.aoa_to_sheet([headers, ...rows]);

            /* Modify the worksheet to set the phone number column type to text */
            ws['!cols'] = [
                { wch: 5 }, // Column width for No.
                { wch: 20 }, // Column width for Name
                { wch: 15 }, // Column width for ID
                { wch: 5 }, // Column width for BookID
                { wch: 40 }, // Column width for Title
                { wch: 25 }, // Column width for CallNumber
                { wch: 20 }, // Column width for Issued's Time
                { wch: 20 }, // Column width for Returned's Time
                { wch: 20 }, // Column width for Due Date
            ];

            /* Create workbook */
            var wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

            /* Save to file */
            var filename = "issued.xlsx";
            XLSX.writeFile(wb, filename);
        }
    </script>
    
    <script>
        $(document).ready(function() {
            $('.dropdown-item').click(function() {
                var filter = $(this).text(); // Get the text content of the clicked item
                if (filter != "All Issued") {
                    var href = 'borrow.php?filter=' + encodeURIComponent(filter); 
                    window.location.href = href; // Redirect to the new link
                } else{
                    var href = 'borrow.php'
                    window.location.href = href; // Redirect to the new link
                }
            });
        });
    </script>

    <script>
        var currentDateTime = new Date("<?php echo $currentDateTime; ?>");

        var cells_return = document.querySelectorAll('.returnCell');
        var cells_overdue = document.querySelectorAll('.overdueCell');
        // var cells_returnBtn = document.querySelectorAll('.returnBtnCell');
        var cells_returnBtn = document.querySelectorAll('.table-button');
        cells_overdue.forEach(function(cell, index) {
            var cellDateTime = new Date(cell.innerText.trim());
            if (cellDateTime < currentDateTime && cells_return[index].innerText.trim() === "0000-00-00 00:00:00") {
                cell.innerHTML = "<i class=\"fa-solid fa-circle-exclamation\"></i> " + cell.innerText.trim();
                cell.style.color = '#ed4937';
            }
        });

        cells_return.forEach(function(cell, index) {
            if (cell.innerText.trim() === "0000-00-00 00:00:00") {
                // Display the corresponding return button
                cells_returnBtn[index].style.display = 'block';
                cell.innerHTML = "<i class=\"fa-solid fa-hourglass-start\"></i> Pending...";
                cell.style.color = '#bfbcbb';
            }
        });
    </script>

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
        document.getElementById("registerBorrow").addEventListener("click", function(event) {
            // Prevent the default action of the anchor tag
            event.preventDefault();
            // Display the confirmation modal
            var modal = document.getElementById("myModal");
            modal.style.display = "block";
        });

        document.getElementById("cancelRegisterBorrow").addEventListener("click", function() {
            var modal = document.getElementById("myModal");
            modal.style.display = "none";
        });

        document.getElementById('confirmRegisterBorrow').addEventListener('click', function() {
            // Retrieve input values
            var patronId = document.getElementById('patron-input').value;
            var bookid = document.getElementById('call-num-input').value;

            // Create FormData object and append input values
            var formData = new FormData();
            formData.append('user-id', patronId);
            formData.append('book-id', bookid);
            formData.append('reserve-exist', false);

            // Send POST request to book_issue.php
            fetch('book_issue.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === 'Book borrowed successfully.') {
                    var modal = document.getElementById("myModal");
                    modal.style.display = "none";
                    location.reload();
                } else {
                    console.error('Error:', data);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        function returnFunction(bookIssuedID) {
            var formData = new FormData(document.getElementById('book_return_' + bookIssuedID));

            fetch('book_return.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === 'Book returned successfully.') {
                    console.log("Successful");
                    location.reload();
                } else {
                    console.error('Error:', data);
                }
            })
            .catch(error => console.error('Error:', error));
        }


        // Close the modal when clicking outside of it
        window.addEventListener("click", function(event) {
            var modal = document.getElementById("myModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        });
    </script>
	
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