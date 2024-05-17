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
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>User Database</title>
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
        .data-table th:nth-child(4),
        .data-table td:nth-child(4) {
            min-width: 200px; 
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
                    <a href="user_database.php" class="nav-item nav-link active"><i class="fa-solid fa-users me-2"></i>Patrons</a>
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

                <!-- <div id="myModal" class="modal">
                    <div class="modal-content" id = "box">
                        <div id = "deleteHeader" class="modal-header"><h2>Delete</h2></div>
                        <div class="modal-body"><p>
                        Are you sure you want to delete this user?</p>
                        </div>
                        
                        <div class="container">
                            <div class="group-48">
                            <button id="cancelDelete" class="btn btn-secondary-modal">Cancel</button>
                            </div>
                            <div class="group-49">
                            <button id="confirmDelete" class="btn btn-primary-modal">Yes</button>
                            </div>
                        </div>
                        
                    </div>	
                </div> -->
                
                <div id="myModal" class="modal">
                    <div class="modal-content" id = "box">
                        <div id = "deleteHeader" class="modal-header"><h2>Change Status</h2></div>
                        <div class="modal-body"><p>
                        What is the current status of this user?</p>
                        <div class="mb-3">
                                <select class="select-box" id="status-input" name="status-input" required>
                                    <option value="" disabled selected>Select a status</option>
                                    <option value="4">Alumni</option>
                                    <option value="5">Withdrew</option>
                                    <option value="7">Transferred</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="container">
                            <div class="group-48">
                            <button id="cancelDelete" class="btn btn-secondary-modal">Cancel</button>
                            </div>
                            <div class="group-49">
                            <button id="confirmDelete" class="btn btn-primary-modal">Yes</button>
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

            <div class="container-fluid pt-4 px-4">
                <div class="row g-3">
                    <div class="col"> 
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                        <svg fill="#00c2cb" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 24 24" xml:space="preserve" width="54px" height="54px"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <style type="text/css"> .st0{fill:none;} </style> <path d="M7.5,5C5.6,5,4,6.6,4,8.5S5.6,12,7.5,12S11,10.4,11,8.5S9.4,5,7.5,5z M16.5,5C14.6,5,13,6.6,13,8.5s1.6,3.5,3.5,3.5 S20,10.4,20,8.5S18.4,5,16.5,5z M7.5,14C2.6,14,1,18,1,18v2h13v-2C14,18,12.4,14,7.5,14z M16.5,14c-1.5,0-2.7,0.4-3.6,0.9 c1.4,1.2,2,2.6,2.1,2.7l0.1,0.2V20h8v-2C23,18,21.4,14,16.5,14z"></path> <rect class="st0" width="24" height="24"></rect> </g></svg>
                            <div class="ms-3 icon-text">
                                <p class="mb-2">Total patron(s)</p>
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

            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-center rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                        <form action="" method="GET" class="search-form">
                            <div class="d-flex align-items-center">
                                <div class="search-container">
                                    <input type="text" name="search" value="<?php if(isset($_GET['search'])){echo $_GET['search'];}?>" placeholder="Enter any details of a patron" class="searchinput" id="searchInput">
                                    <button type="submit" class="searchbutton" id="searchBtn">
                                        <img class="search-img" src="https://pbs.twimg.com/media/GH4zgZ_XYAAVjAL?format=png&name=240x240" alt="Search">
                                    </button>
                                </div>
                                <div class="dropdown-sort"> 
                                    <img disabled class="sort-button" data-bs-toggle="dropdown" src="https://cdn-icons-png.freepik.com/256/14224/14224547.png?ga=GA1.1.1201796896.1698075133&" alt="" style="width: 40px; height: 40px; cursor:pointer;">
                                    <div class="dropdown-menu dropdown-menu-end border-0">
                                        <a id="allUserBtn" class="dropdown-item">All Patron</a>
                                        <a id="nofineUserBtn" class="dropdown-item">No Fine</a>
                                        <a id="fineUserBtn" class="dropdown-item">Fined</a>
                                        <a id="verifiedBtn" class="dropdown-item">Verified</a>
                                        <a id="unverifiedBtn" class="dropdown-item">Unverified</a>
                                        <a id="notactiveBtn" class="dropdown-item">Not Active</a>
                                    </div>
                                </div>
                            </div>
                        </form> 
                        <form action="register_user.php">
                            <input type="submit" id="registerUserBtn" value="Register Patron" class="register-button">
                        </form>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0 header-graph-text">
                            <?php
                            if (isset($_GET['filter'])){
                                $filtervalues = $_GET['filter'];
                            ?>
                                <?= $filtervalues?> Patrons
                            <?php
                            }else{
                            ?>
                                All Patrons
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
                            <th></th>
                            <th>No.</th>
                            <th>Type</th>
                            <th>Course</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>University ID</th>
                            <th>Email</th>
                            <th>Phone No.</th>
                            <th>Total Fine</th>
                            <th>Date Registered</th>
                            <th>Is Verified</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(isset($_GET['search'])){
                                $searchvalues = $_GET['search'];
                                $select_user = $conn->prepare("SELECT `user`.*, `user_type`.* FROM `user` JOIN `user_type`ON `user`.`UserTypeID`=`user_type`.`UserTypeID` WHERE CONCAT(ID,Email,LastName,FirstName,PhoneNumber,TotalFine,RegisteredDate,Type,COALESCE(Course, ''),IsVerified) LIKE'%$searchvalues%' AND `user_type`.`Type` != 'Admin';");
                                $select_user->execute();
                                if($select_user->rowCount() > 0){
                                    while($fetch_user = $select_user->fetch(PDO::FETCH_ASSOC)){
                            ?>
                                <tr>
                                    <td style="display:flex;padding-top:30px;">
                                        <form action="update_user.php" method="post">
                                            <input type="hidden" name="userid" value="<?= $fetch_user['UserID']; ?>">
                                            <input type="hidden" name="firstName" value="<?= $fetch_user['FirstName']; ?>">
                                            <input type="hidden" name="lastName" value="<?= $fetch_user['LastName']; ?>">
                                            <input type="hidden" name="phoneNum" value="<?= $fetch_user['PhoneNumber']; ?>">
                                            <input type="hidden" name="password" value="<?= $fetch_user['Password']; ?>">
                                            <button type="submit" class="table-button" style="margin-right: 10px;"><i class="fa-solid fa-pen" width="20px" height="20px"></i></button>
                                        </form>
                                        <form class="delete-form" action="delete_user.php" method="post">
                                            <input type="hidden" name="userid" value="">
                                            <input type="hidden" name="status" value="">
                                            <button type="button" class="delete-button table-button" data-userid="<?= $fetch_user['UserID']; ?>"><i class="fa-solid fa-trash-can" width="20px" height="20px"></i></button>
                                        </form>
                                    </td>
                                    <td><?= $fetch_user['UserID']; ?></td>
                                    <td><?= $fetch_user['Type']; ?></td>
                                    <td><?= $fetch_user['Course']; ?></td>
                                    <td><?= $fetch_user['FirstName']; ?></td>
                                    <td><?= $fetch_user['LastName']; ?></td>
                                    <td><?= $fetch_user['ID']; ?></td>
                                    <td><?= $fetch_user['Email']; ?></td>
                                    <td><?= $fetch_user['PhoneNumber']; ?></td>
                                    <td><?= $fetch_user['TotalFine']; ?></td>
                                    <td><?= $fetch_user['RegisteredDate']; ?></td>
                                    <td class="verifyCell"><?= $fetch_user['IsVerified']; ?></td>
                                </tr>
                            <?php
                            }
                            }
                            }elseif (isset($_GET['filter'])){
                                $filtervalues = $_GET['filter'];
                                if ($filtervalues=="No Fine"){
                                    $select_user = $conn->prepare("SELECT `user`.*, `user_type`.* FROM `user` JOIN `user_type`ON `user`.`UserTypeID`=`user_type`.`UserTypeID` WHERE TotalFine=0 AND `user_type`.`Type` != 'Admin' AND `user_type`.`Type` != 'Alumni' AND `user_type`.`Type` != 'Transferred' AND `user_type`.`Type` != 'Withdrew';");
                                }else if ($filtervalues=="Fined"){
                                    $select_user = $conn->prepare("SELECT `user`.*, `user_type`.* FROM `user` JOIN `user_type`ON `user`.`UserTypeID`=`user_type`.`UserTypeID` WHERE TotalFine>0 AND `user_type`.`Type` != 'Admin' AND `user_type`.`Type` != 'Alumni' AND `user_type`.`Type` != 'Transferred' AND `user_type`.`Type` != 'Withdrew';");
                                }else if ($filtervalues=="Verified"){
                                    $select_user = $conn->prepare("SELECT `user`.*, `user_type`.* FROM `user` JOIN `user_type`ON `user`.`UserTypeID`=`user_type`.`UserTypeID` WHERE IsVerified='1' AND `user_type`.`Type` != 'Admin' AND `user_type`.`Type` != 'Alumni' AND `user_type`.`Type` != 'Transferred' AND `user_type`.`Type` != 'Withdrew';");
                                }else if ($filtervalues=="Unverified"){
                                    $select_user = $conn->prepare("SELECT `user`.*, `user_type`.* FROM `user` JOIN `user_type`ON `user`.`UserTypeID`=`user_type`.`UserTypeID` WHERE IsVerified='0' AND `user_type`.`Type` != 'Admin' AND `user_type`.`Type` != 'Alumni' AND `user_type`.`Type` != 'Transferred' AND `user_type`.`Type` != 'Withdrew';");
                                }else if ($filtervalues=="Not Active"){
                                    $select_user = $conn->prepare("SELECT `user`.*, `user_type`.* FROM `user` JOIN `user_type`ON `user`.`UserTypeID`=`user_type`.`UserTypeID` WHERE `user_type`.`Type` = 'Alumni' OR `user_type`.`Type` = 'Transferred' OR `user_type`.`Type` = 'Withdrew';");
                                }
                                $select_user->execute();
                                if($select_user->rowCount() > 0){
                                    while($fetch_user = $select_user->fetch(PDO::FETCH_ASSOC)){
                                ?>
                                    <tr>
                                        <td style="display:flex;padding-top:30px;">
                                            <?php if ($filtervalues !== "Not Active") { ?>
                                            <form action="update_user.php" method="post">
                                                <input type="hidden" name="userid" value="<?= $fetch_user['UserID']; ?>">
                                                <input type="hidden" name="firstName" value="<?= $fetch_user['FirstName']; ?>">
                                                <input type="hidden" name="lastName" value="<?= $fetch_user['LastName']; ?>">
                                                <input type="hidden" name="phoneNum" value="<?= $fetch_user['PhoneNumber']; ?>">
                                                <input type="hidden" name="password" value="<?= $fetch_user['Password']; ?>">
                                                <button type="submit" class="table-button" style="margin-right: 10px;"><i class="fa-solid fa-pen" width="20px" height="20px"></i></button>
                                            </form>
                                            <form class="delete-form" action="delete_user.php" method="post">
                                                <input type="hidden" name="userid" value="">
                                                <input type="hidden" name="status" value="">
                                                <button type="button" class="delete-button table-button" data-userid="<?= $fetch_user['UserID']; ?>"><i class="fa-solid fa-trash-can" width="20px" height="20px"></i></button>
                                            </form>
                                            <?php } else { ?>
                                            <form action="update_user.php" method="post">
                                                <input type="hidden" name="userid" value="<?= $fetch_user['UserID']; ?>">
                                                <input type="hidden" name="firstName" value="<?= $fetch_user['FirstName']; ?>">
                                                <input type="hidden" name="lastName" value="<?= $fetch_user['LastName']; ?>">
                                                <input type="hidden" name="phoneNum" value="<?= $fetch_user['PhoneNumber']; ?>">
                                                <input type="hidden" name="password" value="<?= $fetch_user['Password']; ?>">
                                                <button type="submit" class="table-button" style="margin-right: 10px;"><i class="fa-solid fa-pen" width="20px" height="20px"></i></button>
                                            </form>
                                            <?php } ?>
                                        </td>
                                        <td><?= $fetch_user['UserID']; ?></td>
                                        <td><?= $fetch_user['Type']; ?></td>
                                        <td><?= $fetch_user['Course'] ?? ''; ?></td>
                                        <td><?= $fetch_user['FirstName']; ?></td>
                                        <td><?= $fetch_user['LastName']; ?></td>
                                        <td><?= $fetch_user['ID']; ?></td>
                                        <td><?= $fetch_user['Email']; ?></td>
                                        <td><?= $fetch_user['PhoneNumber']; ?></td>
                                        <td><?= $fetch_user['TotalFine']; ?></td>
                                        <td><?= $fetch_user['RegisteredDate']; ?></td>
                                        <td class="verifyCell"><?= $fetch_user['IsVerified']; ?></td>
                                    </tr>
                                <?php
                                }
                                }
                            }else{
                                $select_user = $conn->prepare("SELECT `user`.*, `user_type`.* FROM `user` JOIN `user_type`ON `user`.`UserTypeID`=`user_type`.`UserTypeID` WHERE `user_type`.`Type` != 'Admin' AND `user_type`.`Type` != 'Alumni' AND `user_type`.`Type` != 'Transferred' AND `user_type`.`Type` != 'Withdrew';");
                                $select_user->execute();
                                if($select_user->rowCount() > 0){
                                    while($fetch_user = $select_user->fetch(PDO::FETCH_ASSOC)){
                                ?>
                                    <tr>
                                        <td style="display:flex;padding-top:30px;">
                                            <form action="update_user.php" method="post">
                                                <input type="hidden" name="userid" value="<?= $fetch_user['UserID']; ?>">
                                                <input type="hidden" name="firstName" value="<?= $fetch_user['FirstName']; ?>">
                                                <input type="hidden" name="lastName" value="<?= $fetch_user['LastName']; ?>">
                                                <input type="hidden" name="phoneNum" value="<?= $fetch_user['PhoneNumber']; ?>">
                                                <input type="hidden" name="password" value="<?= $fetch_user['Password']; ?>">
                                                <button type="submit" class="table-button" style="margin-right: 10px;"><i class="fa-solid fa-pen" width="20px" height="20px"></i></button>
                                            </form>
                                            <form class="delete-form" action="delete_user.php" method="post">
                                                <input type="hidden" name="userid" value="">
                                                <input type="hidden" name="status" value="">
                                                <button type="button" class="delete-button table-button" data-userid="<?= $fetch_user['UserID']; ?>"><i class="fa-solid fa-trash-can" width="20px" height="20px"></i></button>
                                            </form>
                                        </td>
                                        <td><?= $fetch_user['UserID']; ?></td>
                                        <td><?= $fetch_user['Type']; ?></td>
                                        <td><?= $fetch_user['Course'] ?? ''; ?></td>
                                        <td><?= $fetch_user['FirstName']; ?></td>
                                        <td><?= $fetch_user['LastName']; ?></td>
                                        <td><?= $fetch_user['ID']; ?></td>
                                        <td><?= $fetch_user['Email']; ?></td>
                                        <td><?= $fetch_user['PhoneNumber']; ?></td>
                                        <td><?= $fetch_user['TotalFine']; ?></td>
                                        <td><?= $fetch_user['RegisteredDate']; ?></td>
                                        <td class="verifyCell"><?= $fetch_user['IsVerified']; ?></td>
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
            var columnIndices = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]; // Adjust as needed

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
                { wch: 15 }, // Column width for Type
                { wch: 25 }, // Column width for Course
                { wch: 20 }, // Column width for First Name
                { wch: 20 }, // Column width for Last Name
                { wch: 15 }, // Column width for University ID
                { wch: 20 }, // Column width for Email
                { wch: 20 }, // Column width for Phone No.
                { wch: 10 }, // Column width for Total Fine
                { wch: 20 }, // Column width for Date Registered
                { wch: 10 }, // Column width for Is Verified
            ];

            /* Create workbook */
            var wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

            /* Save to file */
            var filename = "patrons.xlsx";
            XLSX.writeFile(wb, filename);
        }

    </script>

    <script>
        $(document).ready(function() {
            $('.dropdown-item').click(function() {
                var filter = $(this).text(); // Get the text content of the clicked item
                if (filter != "All Patron") {
                    var href = 'user_database.php?filter=' + encodeURIComponent(filter); 
                    window.location.href = href; // Redirect to the new link
                } else{
                    var href = 'user_database.php'
                    window.location.href = href; // Redirect to the new link
                }
            });
        });

        $(document).ready(function() {
            $('.delete-button').click(function(e) {
                e.preventDefault(); // Prevent the default form submission
                var userid = $(this).data('userid'); // Get the book ID from data attribute
                $('#myModal').show(); // Show the modal
                $('#confirmDelete').data('userid', userid); // Store the book ID in a data attribute of the confirm button
            });

            $('#cancelDelete').click(function() {
                $('#myModal').hide(); // Hide the modal
            });

            $('#confirmDelete').click(function() {
                var userid = $(this).data('userid'); // Get the user ID stored in the data attribute
                var status = $('#status-input').val(); // Get the selected status from the select element
                console.log('User ID to delete: ' + userid + ', Status: ' + status);
                
                // Set the user ID and status in the form
                $('form.delete-form input[name="userid"]').val(userid);
                $('form.delete-form input[name="status"]').val(status);
                
                // Submit the form
                $('form.delete-form').submit();
            });
        });

        var cells_verify = document.querySelectorAll('.verifyCell');
        cells_verify.forEach(function(cell) {
            if (cell.innerText.trim() === "0") {
                cell.innerHTML = "<i class=\"fa-regular fa-rectangle-xmark fa-xl\"></i>";
                cell.style.color = '#ed4937';
            }else{
                cell.innerHTML = "<i class=\"fa-regular fa-square-check fa-xl\"></i>";
                cell.style.color = 'green';
            }
        });
    </script>

    <script>
        var registerButton = document.getElementById('registerUserBtn');

        function updateButtonValue() {
            if (window.innerWidth <= 650) {
                registerButton.value = '+';
            } else {
                registerButton.value = 'Register Patron';
            }
        }

        updateButtonValue();

        window.addEventListener('resize', updateButtonValue);

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