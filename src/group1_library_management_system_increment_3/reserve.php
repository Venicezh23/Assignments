<?php
    include 'connect.php';

    session_start();

    $conn->exec("SET time_zone = '+08:00'");
    
    if ($_SESSION['userType'] != "Librarian" && $_SESSION['userType'] != "Admin")  {
        header('location: homepage.php');
        exit();
    }
    // Redirect to login page if user is not logged in
    if (empty($_SESSION['ID']))  {
        header('location: login.php');
        exit();
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Reserve</title>
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
	
        .data-table th:nth-child(3),
        .data-table th:nth-child(8) {
            min-width: 100px; 
        }

        .data-table th:nth-child(4) {
            min-width: 80px; 
        }

        .data-table th:nth-child(5),
        .data-table td:nth-child(5) {
            min-width: 250px; 
        }

        .data-table th:nth-child(2),
        .data-table td:nth-child(2),
        .data-table th:nth-child(6),
        .data-table td:nth-child(6),
        .data-table th:nth-child(7),
        .data-table td:nth-child(7){
            min-width: 150px; 
        }

        .data-table th:nth-child(1),
        .data-table td:nth-child(1),
        .data-table th:nth-child(4),
        .data-table td:nth-child(4),
        .data-table th:nth-child(8),
        .data-table td:nth-child(8),
        .data-table th:nth-child(9),
        .data-table td:nth-child(9) {
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

            .container2 {
            display: flex;
            justify-content: center;
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
                    margin: 8% auto; /* 8% from the top and centered */
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
                    <a href="borrow.php" class="nav-item nav-link"><i class="fa-solid fa-hand-holding me-2"></i>Issue</a>
                    <a href="reserve.php" class="nav-item nav-link active"><i class="fa-solid fa-calendar-check me-2"></i>Reserve</a>
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
				
                <div id = "myModal" class="modal">
                    <div class="modal-content" id = "box">
                        <div class="modal-header2"><h2>Register Reservation</h2></div>
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
                                    $sql = "SELECT bookid, callnumber FROM book WHERE status='Unavailable';";
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
                            <button id="cancelRegisterReserve" class="btn btn-secondary">Cancel</button>
                            </div>
                            <div class="group-49">
                            <button id="confirmRegisterReserve" class="btn btn-primary2">Yes</button>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->

            <!-- Sale & Revenue Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-3">
                    <div class="col"> 
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa-solid fa-calendar-check fa-3x icon-primary"></i>
                            <div class="ms-3 icon-text">
                                <p class="mb-2">Reservation Pending</p>
                                <?php
                                    $select_reservation = $conn->prepare("SELECT COUNT(*) AS Count FROM `book_reserve` WHERE `DateReserve` != '0000-00-00 00:00:00' AND `Borrowed` != '1';");
                                    $select_reservation->execute();
                                    if($select_reservation->rowCount() > 0){
                                        while($fetch_reservation = $select_reservation->fetch(PDO::FETCH_ASSOC)){
                                ?>
                                        <h6 class="mb-0"><?= $fetch_reservation['Count']; ?></h6>
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
                            <i class="fa-solid fa-book-open-reader fa-3x icon-primary"></i>
                            <!-- <form action="add_reserve.php">
                                <input type="submit" id="registerUserBtn" value="Register Reservation" class="register-button"> 
                            </form> -->
                            <a id="registerReserve" href="#" class="register-button">Register Reservation</a>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Sale & Revenue End -->

            <!-- Recent History -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <form action="" method="GET" class="search-form">
                            <div class="d-flex align-items-center">
                                <div class="search-container">
                                    <input type="text" name="search" value="<?php if(isset($_GET['search'])){echo $_GET['search'];}?>" placeholder="Enter any details of a reservation" class="searchinput" id="searchInput">
                                    <button type="submit" class="searchbutton" id="searchBtn">
                                        <img class="search-img" src="https://pbs.twimg.com/media/GH4zgZ_XYAAVjAL?format=png&name=240x240" alt="Search">
                                    </button>
                                </div>
                                <div class="dropdown-sort"> 
                                    <img disabled class="sort-button" data-bs-toggle="dropdown" src="https://cdn-icons-png.freepik.com/256/14224/14224547.png?ga=GA1.1.1201796896.1698075133&" alt="" style="width: 40px; height: 40px; cursor:pointer;">
                                    <div class="dropdown-menu dropdown-menu-end border-0">
                                        <a id="allreservekBtn" class="dropdown-item">All Reservation</a>
                                        <a id="cancelreserveBtn" class="dropdown-item">Cancelled</a>
                                        <a id="availableBtn" class="dropdown-item">Available</a>
                                        <a id="unavailableBtn" class="dropdown-item">Unavailable</a>
                                        <a id="borrowedBtn" class="dropdown-item">Borrowed</a>
                                        <a id="noBorrowBtn" class="dropdown-item">No Borrow</a>
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
                                <?= $filtervalues?> Reservation History
                            <?php
                            }else{
                            ?>
                                All Reservation History
                            <?php
                            }
                            ?>
                            <button class="download-btn" id="download-btn" onclick="exportToExcel();">
                                <i class="fa-solid fa-file-arrow-down fa-lg"></i>
                            </button>
                        </h6>
                    </div>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ReserveID</th>
                                    <th>Patron's Name</th>
                                    <th>Patron's ID</th>
                                    <th>Book ID</th>
                                    <th>Book Title</th>
                                    <th>Call Number</th>
                                    <th>Reserve Date</th>
                                    <th>Allowed to Borrow</th>
                                    <th>Has Been Borrowed</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                if(isset($_GET['search'])){
                                $searchvalues = $_GET['search'];
                                $select_reserve_user = $conn->prepare("SELECT `book`.*, `user`.*, `book_reserve`.*, CONCAT(`user`.`LastName`, ' ', `user`.`FirstName`) AS `Name` FROM `user` JOIN `book_reserve` ON `user`.`UserID` = `book_reserve`.`UserID` JOIN `book` ON `book`.`BookID` = `book_reserve`.`BookID` WHERE CONCAT(`book_reserve`.`ReserveID`, CONCAT(`user`.`LastName`, ' ', `user`.`FirstName`), `user`.`ID`, `book`.`BookID`, `book`.`Title`, `book`.`CallNumber`, `book_reserve`.`DateReserve`, `book_reserve`.`AllowBorrow`, `book_reserve`.`Borrowed`) LIKE '%$searchvalues%'; ");
                                $select_reserve_user->execute();
                                if($select_reserve_user->rowCount() > 0){
                                    while($fetch_reserve_user = $select_reserve_user->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                        <tr>
                                            <td><?= $fetch_reserve_user['ReserveID']; ?></td>
                                            <td><?= $fetch_reserve_user['Name']; ?></td>
                                            <td><?= $fetch_reserve_user['ID']; ?></td>
                                            <td><?= $fetch_reserve_user['BookID']; ?></td>
                                            <td><?= $fetch_reserve_user['Title']; ?></td>
                                            <td><?= $fetch_reserve_user['CallNumber']; ?></td>
                                            <td class="dateReserve"><?= $fetch_reserve_user['DateReserve']; ?></td>
                                            <td class="cellAllow"><?= $fetch_reserve_user['AllowBorrow']; ?></td>
                                            <td class="cellBorrow"><?= $fetch_reserve_user['Borrowed']; ?></td>
                                        </tr>
                                    <?php
                                    }
                                    }
                                    }elseif (isset($_GET['filter'])){
                                        $filtervalues = $_GET['filter'];
                                        if ($filtervalues=="Cancelled"){
                                            $select_reserve_user = $conn->prepare("SELECT `book`.*, `user`.*, `book_reserve`.*, CONCAT(`LastName`,`FirstName`) AS `Name` FROM `user` JOIN `book_reserve`ON `user`.`UserID`=`book_reserve`.`UserID` JOIN `book` ON `book`.`BookID`=`book_reserve`.`BookID` WHERE `book_reserve`.DateReserve = '0000-00-00 00:00:00'; ");
                                        }else if ($filtervalues=="Available"){
                                            $select_reserve_user = $conn->prepare("SELECT `book`.*, `user`.*, `book_reserve`.*, CONCAT(`LastName`,`FirstName`) AS `Name` FROM `user` JOIN `book_reserve`ON `user`.`UserID`=`book_reserve`.`UserID` JOIN `book` ON `book`.`BookID`=`book_reserve`.`BookID` WHERE `book_reserve`.AllowBorrow = '1'; ");
                                        }else if ($filtervalues=="Unavailable"){
                                            $select_reserve_user = $conn->prepare("SELECT `book`.*, `user`.*, `book_reserve`.*, CONCAT(`LastName`,`FirstName`) AS `Name` FROM `user` JOIN `book_reserve`ON `user`.`UserID`=`book_reserve`.`UserID` JOIN `book` ON `book`.`BookID`=`book_reserve`.`BookID` WHERE `book_reserve`.AllowBorrow = '0'; ");
                                        }else if ($filtervalues=="Borrowed"){
                                            $select_reserve_user = $conn->prepare("SELECT `book`.*, `user`.*, `book_reserve`.*, CONCAT(`LastName`,`FirstName`) AS `Name` FROM `user` JOIN `book_reserve`ON `user`.`UserID`=`book_reserve`.`UserID` JOIN `book` ON `book`.`BookID`=`book_reserve`.`BookID` WHERE `book_reserve`.Borrowed = '1'; ");
                                        }else if ($filtervalues=="No Borrow"){
                                            $select_reserve_user = $conn->prepare("SELECT `book`.*, `user`.*, `book_reserve`.*, CONCAT(`LastName`,`FirstName`) AS `Name` FROM `user` JOIN `book_reserve`ON `user`.`UserID`=`book_reserve`.`UserID` JOIN `book` ON `book`.`BookID`=`book_reserve`.`BookID` WHERE `book_reserve`.Borrowed = '0'; ");
                                        }
                                        $select_reserve_user->execute();
                                        if($select_reserve_user->rowCount() > 0){
                                            while($fetch_reserve_user = $select_reserve_user->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                        <tr>
                                            <td><?= $fetch_reserve_user['ReserveID']; ?></td>
                                            <td><?= $fetch_reserve_user['Name']; ?></td>
                                            <td><?= $fetch_reserve_user['ID']; ?></td>
                                            <td><?= $fetch_reserve_user['BookID']; ?></td>
                                            <td><?= $fetch_reserve_user['Title']; ?></td>
                                            <td><?= $fetch_reserve_user['CallNumber']; ?></td>
                                            <td class="dateReserve"><?= $fetch_reserve_user['DateReserve']; ?></td>
                                            <td class="cellAllow"><?= $fetch_reserve_user['AllowBorrow']; ?></td>
                                            <td class="cellBorrow"><?= $fetch_reserve_user['Borrowed']; ?></td>
                                        </tr>
                                    <?php
                                    }
                                    }
                                    }else{
                                        $select_reserve_user = $conn->prepare("SELECT `book`.*, `user`.*, `book_reserve`.*, CONCAT(`LastName`,`FirstName`) AS `Name` FROM `user` JOIN `book_reserve`ON `user`.`UserID`=`book_reserve`.`UserID` JOIN `book` ON `book`.`BookID`=`book_reserve`.`BookID`; ");
                                        $select_reserve_user->execute();
                                        if($select_reserve_user->rowCount() > 0){
                                            while($fetch_reserve_user = $select_reserve_user->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                        <tr>
                                            <td><?= $fetch_reserve_user['ReserveID']; ?></td>
                                            <td><?= $fetch_reserve_user['Name']; ?></td>
                                            <td><?= $fetch_reserve_user['ID']; ?></td>
                                            <td><?= $fetch_reserve_user['BookID']; ?></td>
                                            <td><?= $fetch_reserve_user['Title']; ?></td>
                                            <td><?= $fetch_reserve_user['CallNumber']; ?></td>
                                            <td class="dateReserve"><?= $fetch_reserve_user['DateReserve']; ?></td>
                                            <td class="cellAllow"><?= $fetch_reserve_user['AllowBorrow']; ?></td>
                                            <td class="cellBorrow"><?= $fetch_reserve_user['Borrowed']; ?></td>
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
        </div>
        <!-- Content End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top" ><i class="bi bi-arrow-up"></i></a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>

    <script>
        function exportToExcel() {
            /* Select the table */
            var table = document.querySelector('.data-table');

            /* Specify the indices of the columns you want to export */
            var columnIndices = [0, 1, 2, 3, 4, 5, 6, 7, 8]; // Adjust as needed

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
                        var cellContent = td.innerHTML.trim();
                        if (cellContent.includes("fa-rectangle-xmark")) {
                            rowData.push("0");
                        } else if (cellContent.includes("fa-square-check")) {
                            rowData.push("1");
                        } else {
                            rowData.push(td.innerText);
                        }
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
                { wch: 20 }, // Column width for Reserve's Time
                { wch: 5 }, // Column width for Allow to Borrow
                { wch: 5 }, // Column width for Borrowed
            ];

            /* Create workbook */
            var wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

            /* Save to file */
            var filename = "reserve.xlsx";
            XLSX.writeFile(wb, filename);
        }
    </script>

    <script>
        $(document).ready(function() {
            $('.dropdown-item').click(function() {
                var filter = $(this).text(); // Get the text content of the clicked item
                if (filter != "All Reservation") {
                    var href = 'reserve.php?filter=' + encodeURIComponent(filter); 
                    window.location.href = href; // Redirect to the new link
                } else{
                    var href = 'reserve.php'
                    window.location.href = href; // Redirect to the new link
                }
            });
        });
    </script>

    <script>
        var cells_reserve = document.querySelectorAll('.dateReserve');
        cells_reserve.forEach(function(cell) {
            if (cell.innerText.trim() === "0000-00-00 00:00:00") {
                cell.innerHTML = "<i class=\"fa-solid fa-ban\"></i> Cancelled";
                cell.style.color = '#bfbcbb';
            }
        });

        var cells_allow_borrow = document.querySelectorAll('.cellAllow');
        cells_allow_borrow.forEach(function(cell) {
            if (cell.innerText.trim() === "0") {
                cell.innerHTML = "<i class=\"fa-regular fa-rectangle-xmark fa-xl\"></i>";
                cell.style.color = '#ed4937';
            }else{
                cell.innerHTML = "<i class=\"fa-regular fa-square-check fa-xl\"></i>";
                cell.style.color = 'green';
            }
        });

        var cells_borrowed = document.querySelectorAll('.cellBorrow');
        cells_borrowed.forEach(function(cell) {
            if (cell.innerText.trim() === "0") {
                cell.innerHTML = "<i class=\"fa-regular fa-rectangle-xmark fa-xl\"></i>";
                cell.style.color = '#ed4937';
            }else{
                cell.innerHTML = "<i class=\"fa-regular fa-square-check fa-xl\"></i>";
                cell.style.color = 'green';
            }
        });
    </script>

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
        document.getElementById("registerReserve").addEventListener("click", function(event) {
            // Prevent the default action of the anchor tag
            event.preventDefault();
            // Display the confirmation modal
            var modal = document.getElementById("myModal");
            modal.style.display = "block";
        });

        document.getElementById("cancelRegisterReserve").addEventListener("click", function() {
            var modal = document.getElementById("myModal");
            modal.style.display = "none";
        });

        document.getElementById('confirmRegisterReserve').addEventListener('click', function() {
            // Retrieve input values
            var patronId = document.getElementById('patron-input').value;
            var bookid = document.getElementById('call-num-input').value;

            // Create FormData object and append input values
            var formData = new FormData();
            formData.append('user-id', patronId);
            formData.append('book-id', bookid);

            // Send POST request to book_reserve.php
            fetch('book_reserve.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === 'Book reserved successfully.') {
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