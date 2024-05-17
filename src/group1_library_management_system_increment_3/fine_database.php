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
    <title>Fine</title>
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
	
        .data-table th:nth-child(5),
        .data-table td:nth-child(5),
        .data-table th:nth-child(3),
        .data-table td:nth-child(3),
        .data-table th:nth-child(3),
        .data-table td:nth-child(3) {
            min-width: 100px; 
        }

        .data-table th:nth-child(2),
        .data-table td:nth-child(2){
            min-width: 150px; 
        }

        .data-table th:nth-child(1),
        .data-table td:nth-child(1),
        .data-table th:nth-child(4),
        .data-table td:nth-child(4),
        .data-table th:nth-child(6),
        .data-table td:nth-child(6),
        .data-table th:nth-child(7),
        .data-table td:nth-child(7),
        .data-table th:nth-child(9) {
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
                    <a href="reserve.php" class="nav-item nav-link"><i class="fa-solid fa-calendar-check me-2"></i>Reserve</a>
                    <a href="fine_database.php" class="nav-item nav-link active"><i class="fa-solid fa-money-check-dollar"></i> Fines</a>
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
                        <div class="modal-header2"><h2>Issue Fine</h2></div>
                        <div class="modal-body">
                            						
                            <div class="mb-3">
                                <label class="label-text">Book Issued ID</label>

                                <select class="select-box" id="book-issued-input" name="book-issued-input" onchange="getBookDetails()" required>
                                    <option value="" disabled selected>Select an issued situation</option>
                                    <?php
                                    $sql = "SELECT bookissuedid FROM book_issued WHERE DateReturn <> '0000-00-00 00:00:00' ORDER BY bookissuedid ASC;";
                                    $result = $conn->query($sql);
                                    if ($result->rowCount() > 0) {
                                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                            echo '<option value="' . $row["bookissuedid"] . '">'. $row["bookissuedid"] . '</option>';
                                        }
                                    } else {
                                        echo "No existing users.";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Another input field to autofill -->
							<div class="mb-3">
								<label class="label-text">User ID</label>
								<input type="text" class="input-box" id="autofill-userid" name="autofill-userid" readonly>
							</div>

                            <!-- Another input field to autofill -->
							<div class="mb-3">
								<label class="label-text">Book ID</label>
								<input type="text" class="input-box" id="autofill-bookid" name="autofill-bookid" readonly>
							</div>

                            <!-- Another input field to autofill -->
							<div class="mb-3">
								<label class="label-text">Fine Amount</label>
								<input type="text" class="input-box" id="autofill-fine" name="autofill-fine" readonly>
							</div>
							
							<!-- Fine Type dropdown -->
							<div class="mb-3">
								<label class="label-text">Fine Type</label>
								<select class="select-box" id="fine-type-select" name="fine-type-select">
                                    <option value="" disabled selected>Choose the fine type</option>
									<option value="Damaged">Damaged Book</option>
									<option value="Lost">Lost Book</option>
								</select>
							</div>
							
                        </div>
                        
                        <div class="container2">
                            <div class="group-48">
                            <button id="cancelIssueFine" class="btn btn-secondary">Cancel</button>
                            </div>
                            <div class="group-49">
                            <button id="confirmIssueFine" class="btn btn-primary2">Yes</button>
                            </div>
                        </div>
                        
                    </div>
                </div>
				
				<div id = "fineModal" class="modal">
                    <div class="modal-content" id = "box">
                        <div class="modal-header2"><h2>Pay Fine</h2></div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="label-text">Patron's ID</label>
                                <?php
                                    $sql = "SELECT userid, id FROM user WHERE TotalFine > 0";
                                    $result = $conn->query($sql);

                                    if ($result->rowCount() > 0) {
                                        echo '<select class="select-box" id="patron-input-fine" name="patron-input-fine" required>';
                                        echo '<option value="" disabled selected>Select a student</option>';
                                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                            echo '<option value="' . $row["userid"] . '">'. $row["id"] . '</option>';
                                        }
                                        echo '</select>';
                                    } else {
                                        echo "No existing users.";
                                    }
                                ?>
                            </div>
							
                        </div>
                        
                        <div class="container2">
                            <div class="group-48">
                            <button id="cancelPayFine" class="btn btn-secondary">Cancel</button>
                            </div>
                            <div class="group-49">
                            <button id="confirmPayFine" class="btn btn-primary2">Pay</button>
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
                            <i class="fa-solid fa-file-invoice fa-3x icon-primary"></i>
                            <a id="issueFine" href="#" class="register-button">Issue Fine</a>
                        </div>
                    </div>

                    <div class="col"> 
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa-solid fa-money-check-dollar fa-3x icon-primary"></i>
                            <div class="ms-3 icon-text">
                                <p class="mb-2">Fine(s) Pending</p>
                                <?php
                                    $select_fine_amount = $conn->prepare("SELECT COUNT(*) AS Count FROM `fine` WHERE `DateCompleteFine` = '0000-00-00 00:00:00' OR `DateCompleteFine` IS NULL;");
                                    $select_fine_amount->execute();
                                    if($select_fine_amount->rowCount() > 0){
                                        while($fetch_fine_amount = $select_fine_amount->fetch(PDO::FETCH_ASSOC)){
                                ?>
                                        <h6 class="mb-0"><?= $fetch_fine_amount['Count']; ?></h6>
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
                            <i class="fa-solid fa-cash-register fa-3x icon-primary"></i>
                            <a id="payFine" href="#" class="register-button">Pay Fine</a>
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
                                        <a id="allFineBtn" class="dropdown-item">All Fine</a>
                                        <a id="paidBtn" class="dropdown-item">Paid</a>
                                        <a id="unpaidBtn" class="dropdown-item">Unpaid</a>
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
                                <?= $filtervalues?> Fine History
                            <?php
                            }else{
                            ?>
                                All Fine History
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
                                    <th>FineID</th>
                                    <th>Patron's Name</th>
                                    <th>Patron's ID</th>
                                    <th>Issued ID</th>
                                    <th>Fine Type</th>
                                    <th>Amount (RM)</th>
                                    <th>Paid</th>
                                    <th>Date Fined</th>
                                    <th>Date Complete Fine</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                if(isset($_GET['search'])){
                                $searchvalues = $_GET['search'];
                                $select_fine = $conn->prepare("SELECT `fine`.*, `user`.*, CONCAT(`user`.`LastName`, ' ', `user`.`FirstName`) AS `Name` FROM `user` JOIN `fine` ON `user`.`UserID` = `fine`.`UserID` WHERE CONCAT(`fine`.`FineID`, CONCAT(`user`.`LastName`, ' ', `user`.`FirstName`), `user`.`ID`, `fine`.`BookIssuedID`, `fine`.`FineType`, `fine`.`FineAmount`, `fine`.`IsPaid`, `fine`.`DateFined`, `fine`.`DateCompleteFine`) LIKE '%$searchvalues%'; ");
                                $select_fine->execute();
                                if($select_fine->rowCount() > 0){
                                    while($fetch_fine = $select_fine->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                        <tr>
                                            <td><?= $fetch_fine['FineID']; ?></td>
                                            <td><?= $fetch_fine['Name']; ?></td>
                                            <td><?= $fetch_fine['ID']; ?></td>
                                            <td><?= $fetch_fine['BookIssuedID']; ?></td>
                                            <td><?= $fetch_fine['FineType']; ?></td>
                                            <td><?= $fetch_fine['FineAmount']; ?></td>
                                            <td class="cellFine"><?= $fetch_fine['IsPaid']; ?></td>
                                            <td><?= $fetch_fine['DateFined']; ?></td>
                                            <td class="cellCompleteFine"><?= $fetch_fine['DateCompleteFine']; ?></td>
                                        </tr>
                                    <?php
                                    }
                                    }
                                    }elseif (isset($_GET['filter'])){
                                        $filtervalues = $_GET['filter'];
                                        if ($filtervalues=="Paid"){
                                            $select_fine = $conn->prepare("SELECT `fine`.*, `user`.*, CONCAT(`LastName`,`FirstName`) AS `Name` FROM `user` JOIN `fine`ON `user`.`UserID`=`fine`.`UserID` WHERE `fine`.`IsPaid` = '1'; ");
                                        }else if ($filtervalues=="Unpaid"){
                                            $select_fine = $conn->prepare("SELECT `fine`.*, `user`.*, CONCAT(`LastName`,`FirstName`) AS `Name` FROM `user` JOIN `fine`ON `user`.`UserID`=`fine`.`UserID` WHERE `fine`.`IsPaid` = '0';");
                                        }
                                        $select_fine->execute();
                                        if($select_fine->rowCount() > 0){
                                            while($fetch_fine = $select_fine->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                        <tr>
                                            <td><?= $fetch_fine['FineID']; ?></td>
                                            <td><?= $fetch_fine['Name']; ?></td>
                                            <td><?= $fetch_fine['ID']; ?></td>
                                            <td><?= $fetch_fine['BookIssuedID']; ?></td>
                                            <td><?= $fetch_fine['FineType']; ?></td>
                                            <td><?= $fetch_fine['FineAmount']; ?></td>
                                            <td class="cellFine"><?= $fetch_fine['IsPaid']; ?></td>
                                            <td><?= $fetch_fine['DateFined']; ?></td>
                                            <td class="cellCompleteFine"><?= $fetch_fine['DateCompleteFine']; ?></td>
                                        </tr>
                                    <?php
                                    }
                                    }
                                    }else{
                                        $select_fine = $conn->prepare("SELECT `fine`.*, `user`.*, CONCAT(`LastName`,`FirstName`) AS `Name` FROM `user` JOIN `fine`ON `user`.`UserID`=`fine`.`UserID`;  ");
                                        $select_fine->execute();
                                        if($select_fine->rowCount() > 0){
                                            while($fetch_fine = $select_fine->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                        <tr>
                                            <td><?= $fetch_fine['FineID']; ?></td>
                                            <td><?= $fetch_fine['Name']; ?></td>
                                            <td><?= $fetch_fine['ID']; ?></td>
                                            <td><?= $fetch_fine['BookIssuedID']; ?></td>
                                            <td><?= $fetch_fine['FineType']; ?></td>
                                            <td><?= $fetch_fine['FineAmount']; ?></td>
                                            <td class="cellFine"><?= $fetch_fine['IsPaid']; ?></td>
                                            <td><?= $fetch_fine['DateFined']; ?></td>
                                            <td class="cellCompleteFine"><?= $fetch_fine['DateCompleteFine']; ?></td>
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
                { wch: 5 }, // Column width for IssuedID
                { wch: 15 }, // Column width for FineType
                { wch: 10 }, // Column width for Amount
                { wch: 5 }, // Column width for Paid
                { wch: 20 }, // Column width for Date Fined
                { wch: 20 }, // Column width for Date Complete Fine
            ];

            /* Create workbook */
            var wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

            /* Save to file */
            var filename = "fine.xlsx";
            XLSX.writeFile(wb, filename);
        }
    </script>

    <script>
        $(document).ready(function() {
            $('.dropdown-item').click(function() {
                var filter = $(this).text(); // Get the text content of the clicked item
                if (filter != "All Fine") {
                    var href = 'fine_database.php?filter=' + encodeURIComponent(filter); 
                    window.location.href = href; // Redirect to the new link
                } else{
                    var href = 'fine_database.php'
                    window.location.href = href; // Redirect to the new link
                }
            });
        });
    </script>

    <script>
        var cells_date = document.querySelectorAll('.cellCompleteFine');
        cells_date.forEach(function(cell) {
            if (cell.innerText.trim() === "") {
                cell.innerHTML = "<i class=\"fa-solid fa-hourglass-start\"></i> Pending...";
                cell.style.color = '#bfbcbb';
            }
        });

        var cells_fine = document.querySelectorAll('.cellFine');
        cells_fine.forEach(function(cell) {
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
	// Define an object to store fine amounts based on call numbers
	
	/*issuing fine button*/
	document.getElementById("issueFine").addEventListener("click", function(event) {
		// Prevent the default action of the anchor tag
		event.preventDefault();
		// Display the confirmation modal
		var modal = document.getElementById("myModal");
		modal.style.display = "block";
	});
	
	let actualValue = ''; //value of patron selected to pay fine
		
	/*cancel issue fine*/
		document.getElementById("cancelIssueFine").addEventListener("click", function() {
            var modal = document.getElementById("myModal");
            modal.style.display = "none";
        });

        function getBookDetails() {
            var bookIssuedId = document.getElementById("book-issued-input").value;
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "get_fine.php?bookIssuedId=" + bookIssuedId, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    document.getElementById("autofill-userid").value = response.UserID;
                    document.getElementById("autofill-bookid").value = response.BookID;
                    document.getElementById("autofill-fine").value = response.Price;
                }
            };
            xhr.send();
        }

	//submit fine
	document.getElementById('confirmIssueFine').addEventListener('click', function() {
		// Retrieve input values
		
		var bookissuedid = document.getElementById('book-issued-input').value;
		var bookissueduserid = document.getElementById('autofill-userid').value;
		var bookissuedbookid = document.getElementById('autofill-bookid').value;
		var price = document.getElementById('autofill-fine').value;
		var fineType = document.getElementById('fine-type-select').value;

        // Check if any of the input fields are empty
		if (fineType.trim() === '' || bookissuedid.trim() === '') {
			alert("Please fill in all fields before submitting.");
			return; // Prevent form submission
		}
		
		//get user id?
		
		// Create FormData object and append input values
		var formData = new FormData();
		formData.append('book-issued-id', bookissuedid);
		formData.append('book-issued-userid', bookissueduserid);
		formData.append('book-issued-bookid', bookissuedbookid);
		formData.append('fine-price', price);
		formData.append('fine-type', fineType);

		// Send POST request to issue_fine.php
		fetch('issue_fine.php', {
			method: 'POST',
			body: formData
		})
		.then(response => response.text())
		.then(data => {
			if (data.trim() === 'Fine issued and total fine updated successfully.') {
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
	
	/*pay fine button*/
	document.getElementById("payFine").addEventListener("click", function(event) {
		// Prevent the default action of the anchor tag
		event.preventDefault();
		// Display the confirmation modal
		var modal = document.getElementById("fineModal");
		modal.style.display = "block";
		var patronId = document.getElementById('patron-input-fine').value;
		
	});
	
	
	/*cancel pay fine*/
		document.getElementById("cancelPayFine").addEventListener("click", function() {
            var modal = document.getElementById("fineModal");
            modal.style.display = "none";
	});
	
	// Confirm pay fine button event listener
	document.getElementById('confirmPayFine').addEventListener('click', function() {
		// Retrieve input values
		var patronId = document.getElementById('patron-input-fine').value;
		// Check if any of the input fields are empty
		if (patronId.trim() === '') {
			alert("Please select a patron before submitting.");
			return; // Prevent form submission
		}
		
		// Log the actualValue before redirecting
		console.log("Redirecting with patron ID:", patronId);

		// Redirect to the new page with actualValue as URL parameter
		window.location.href = 'pay-homepage-librarian.php?patronID=' + encodeURIComponent(patronId);
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
		var modal = document.getElementById("fineModal");
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
