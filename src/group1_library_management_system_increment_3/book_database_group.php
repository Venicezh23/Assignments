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
    <title>Book Database</title>
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
                    <a href="user_database.php" class="nav-item nav-link"><i class="fa-solid fa-users me-2"></i>Patrons</a>
                    <a href="book_database.php" class="nav-item nav-link active"><i class="fa-solid fa-book me-2"></i>Books</a>
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

                <div id="myModal" class="modal">
                    <div class="modal-content" id = "box">
                        <div id = "archiveHeader" class="modal-header"><h2>Archive</h2></div>
                        <div class="modal-body"><p>
                        Are you sure you want to archive this book?</p>
                        </div>
                        
                        <div class="container">
                            <div class="group-48">
                            <button id="cancelArchive" class="btn btn-secondary-modal">Cancel</button>
                            </div>
                            <div class="group-49">
                            <button id="confirmArchive" class="btn btn-primary-modal">Yes</button>
                            </div>
                        </div>
                        
                    </div>	
                </div>

                <div id="myModalUn" class="modal">
                    <div class="modal-content" id = "box">
                        <div id = "archiveHeader" class="modal-header"><h2>Unarchive</h2></div>
                        <div class="modal-body"><p>
                        Are you sure you want to unarchive this book?</p>
                        </div>
                        
                        <div class="container">
                            <div class="group-48">
                            <button id="cancelUnarchive" class="btn btn-secondary-modal">Cancel</button>
                            </div>
                            <div class="group-49">
                            <button id="confirmUnarchive" class="btn btn-primary-modal">Yes</button>
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
                        <svg height="54px" width="54px" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <style type="text/css"> .st0{fill:#00c2cb;} </style> <g> <path class="st0" d="M94.972,55.756H30.479C13.646,55.756,0,69.407,0,86.243v342.279c0,16.837,13.646,30.47,30.479,30.47h64.493 c16.833,0,30.479-13.634,30.479-30.47V86.243C125.452,69.407,111.805,55.756,94.972,55.756z M98.569,234.237H26.882v-17.922h71.687 V234.237z M98.569,180.471H26.882v-35.843h71.687V180.471z"></path> <path class="st0" d="M238.346,55.756h-64.493c-16.833,0-30.479,13.651-30.479,30.487v342.279c0,16.837,13.646,30.47,30.479,30.47 h64.493c16.833,0,30.479-13.634,30.479-30.47V86.243C268.825,69.407,255.178,55.756,238.346,55.756z M241.942,234.237h-71.687 v-17.922h71.687V234.237z M241.942,180.471h-71.687v-35.843h71.687V180.471z"></path> <path class="st0" d="M510.409,398.305L401.562,73.799c-5.352-15.961-22.63-24.554-38.587-19.208l-61.146,20.512 c-15.961,5.356-24.559,22.63-19.204,38.592L391.472,438.2c5.356,15.962,22.63,24.555,38.587,19.208l61.146-20.512 C507.166,431.541,515.763,414.267,510.409,398.305z M326.677,160.493l67.967-22.796l11.398,33.988l-67.968,22.796L326.677,160.493z M355.173,245.455l-5.701-16.994l67.968-22.796l5.696,16.994L355.173,245.455z"></path> </g> </g></svg>
                            <div class="ms-3 icon-text">
                                <p class="mb-2">Total copies of book</p>
                                <?php
                                    $select_copies = $conn->prepare("SELECT COUNT(*) AS Count FROM `book` WHERE `Status` != 'Archived';");
                                    $select_copies->execute();
                                    if($select_copies->rowCount() > 0){
                                        while($fetch_copies = $select_copies->fetch(PDO::FETCH_ASSOC)){
                                ?>
                                        <h6 class="mb-0"><?= $fetch_copies['Count']; ?></h6>
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
                            <i class="fa-solid fa-book fa-3x icon-primary"></i>
                            
                            <div class="ms-3 icon-text">
                                <p class="mb-2">Total book(s)</p>
                                <?php
                                    $select_copies = $conn->prepare("SELECT COUNT(DISTINCT ISBN) AS Count FROM `book` WHERE `Status` != 'Archived'; ");
                                    $select_copies->execute();
                                    if($select_copies->rowCount() > 0){
                                        while($fetch_copies = $select_copies->fetch(PDO::FETCH_ASSOC)){
                                ?>
                                        <h6 class="mb-0"><?= $fetch_copies['Count']; ?></h6>
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
                                    <input type="text" name="search" value="<?php if(isset($_GET['search'])){echo $_GET['search'];}?>" placeholder="Enter any details of a book" class="searchinput" id="searchInput">
                                    <button type="submit" class="searchbutton" id="searchBtn">
                                        <img class="search-img" src="https://pbs.twimg.com/media/GH4zgZ_XYAAVjAL?format=png&name=240x240" alt="Search">
                                    </button>
                                </div>
                                <div class="dropdown-sort">
                                    <img disabled class="sort-button" data-bs-toggle="dropdown" src="https://cdn-icons-png.freepik.com/256/14224/14224547.png?ga=GA1.1.1201796896.1698075133&" alt="" style="width: 40px; height: 40px; cursor:pointer;">
                                    <div class="dropdown-menu dropdown-menu-end border-0">
                                        <a id="allbookBtn" class="dropdown-item">All Books</a>
                                        <a id="availableBtn" class="dropdown-item">Available</a>
                                        <a id="unavailableBtn" class="dropdown-item">Unavailable</a>
                                        <a id="reservedBtn" class="dropdown-item">Reserved</a>
                                        <a id="archivedBtn" class="dropdown-item">Archived</a>
                                    </div>
                                </div>
                                <a href="book_database.php">
                                    <i class="fa-solid fa-list fa-xl" style="color:#002439; margin:20px;"></i>
                                </a>
                            </div>
                        </form>
                        <form action="add_new_book.php">
                            <input type="submit" id="registerUserBtn" value="Add Book" class="register-button">
                        </form>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0 header-graph-text">
                            <?php
                            if (isset($_GET['filter'])){
                                $filtervalues = $_GET['filter'];
                            ?>
                                <?= $filtervalues?> Books with Stocks
                            <?php
                            }else{
                            ?>
                                All Books with Stocks
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
                            <?php
                            if (isset($_GET['filter'])){
                            ?>
                            <style>
                                .data-table th:nth-child(4),
                                .data-table td:nth-child(4) {
                                    min-width: 250px; 
                                }

                                .data-table th:nth-child(8),
                                .data-table td:nth-child(8) {
                                    min-width: 150px; 
                                }

                                .data-table th:nth-child(10),
                                .data-table td:nth-child(10) {
                                    min-width: 150px; 
                                }

                                .data-table th:nth-child(1),
                                .data-table td:nth-child(1),
                                .data-table th:nth-child(2),
                                .data-table td:nth-child(2),
                                .data-table th:nth-child(13),
                                .data-table td:nth-child(13) {
                                    text-align: center;
                                }
                            </style>
                            <thead>
                                <tr>
                                    <th><?= $filtervalues?></th>
                                    <th>Stock(s)</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Edition</th>
                                    <th>Author(s)</th>
                                    <th>ISBN</th>
                                    <th>Publisher</th>
                                    <th>Year</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Location</th>
                                    <th>Remark</th>
                                </tr>
                            </thead>
                                <tbody>
                                <?php
                                    $filtervalues = $_GET['filter'];
                                    if ($filtervalues=="Available"){
                                        $select_book = $conn->prepare("SELECT *, COUNT(*) AS count FROM (SELECT `book`.*, GROUP_CONCAT(`author`.Name) AS Authors FROM `book` JOIN `author` ON `book`.`BookID` = `author`.`BookID` WHERE Status = 'Available' GROUP BY `book`.`BookID`) AS `subbook` GROUP BY `subbook`.`ISBN`;");
                                    }else if ($filtervalues=="Unavailable"){
                                        $select_book = $conn->prepare("SELECT *, COUNT(*) AS count FROM (SELECT `book`.*, GROUP_CONCAT(`author`.Name) AS Authors FROM `book` JOIN `author` ON `book`.`BookID` = `author`.`BookID` WHERE Status = 'Unavailable' GROUP BY `book`.`BookID`) AS `subbook` GROUP BY `subbook`.`ISBN`;");
                                    }else if ($filtervalues=="Reserved"){
                                        $select_book = $conn->prepare("SELECT *, COUNT(*) AS count FROM (SELECT `book`.*, GROUP_CONCAT(`author`.Name) AS Authors FROM `book` JOIN `author` ON `book`.`BookID` = `author`.`BookID` WHERE Status = 'Reserved' GROUP BY `book`.`BookID`) AS `subbook` GROUP BY `subbook`.`ISBN`;");
                                    }else if ($filtervalues=="Archived"){
                                        $select_book = $conn->prepare("SELECT *, COUNT(*) AS count FROM (SELECT `book`.*, GROUP_CONCAT(`author`.Name) AS Authors FROM `book` JOIN `author` ON `book`.`BookID` = `author`.`BookID` WHERE Status = 'Archived' GROUP BY `book`.`BookID`) AS `subbook` GROUP BY `subbook`.`ISBN`;");
                                    }
                                    $select_book->execute();
                                    $select_book_status = $conn->prepare("SELECT *, COUNT(*) AS count FROM (SELECT `book`.*, GROUP_CONCAT(`author`.Name) AS Authors FROM `book` JOIN `author` ON `book`.`BookID` = `author`.`BookID` GROUP BY `book`.`BookID`) AS `subbook` GROUP BY `subbook`.`ISBN`;");
                                    $select_book_status->execute();
                                    if($select_book->rowCount() > 0 && $select_book_status->rowCount() > 0){
                                        while($fetch_book = $select_book->fetch(PDO::FETCH_ASSOC)){
                                            $fetch_book_status = $select_book_status->fetch(PDO::FETCH_ASSOC);
                                ?>
                                    <tr>
                                        <td><?= $fetch_book['count']; ?></td>
                                        <td><?= $fetch_book_status['count']; ?></td>
                                        <td><img src="<?= $fetch_book['Image']; ?>" alt="Book" class="image-book"></td>
                                        <td><?= $fetch_book['Title']; ?></td>
                                        <td><?= $fetch_book['Edition']; ?></td>
                                        <td><?= $fetch_book['Authors']; ?></td>
                                        <td><?= $fetch_book['ISBN']; ?></td>
                                        <td><?= $fetch_book['PublisherName']; ?></td>
                                        <td><?= $fetch_book['PublishedYear']; ?></td>
                                        <td><?= $fetch_book['Category']; ?></td>
                                        <td><?= $fetch_book['Price']; ?></td>
                                        <td><?= $fetch_book['Location']; ?></td>
                                        <td><?= $fetch_book['Remark'] ?? ''; ?></td>
                                    </tr>
                                <?php
                                }
                                }
                                ?>
                                </tbody>
                            <?php
                            }else{
                                ?>
                                <style>
                                    .data-table th:nth-child(7),
                                    .data-table td:nth-child(7) {
                                        min-width: 250px; 
                                    }

                                    .data-table th:nth-child(9),
                                    .data-table td:nth-child(9) {
                                        min-width: 150px; 
                                    }

                                    .data-table th:nth-child(11),
                                    .data-table td:nth-child(11) {
                                        min-width: 150px; 
                                    }

                                    .data-table th:nth-child(1),
                                    .data-table td:nth-child(1),
                                    .data-table th:nth-child(2),
                                    .data-table td:nth-child(2),
                                    .data-table th:nth-child(3),
                                    .data-table td:nth-child(3),
                                    .data-table th:nth-child(4),
                                    .data-table td:nth-child(4),
                                    .data-table th:nth-child(5),
                                    .data-table td:nth-child(5),
                                    .data-table th:nth-child(15),
                                    .data-table td:nth-child(15) {
                                        text-align: center;
                                    }
                                </style>
                                <thead>
                                <tr>
                                    <th>Available</th>
                                    <th>Issued</th>
                                    <th>Reserved</th>
                                    <th>Archived</th>
                                    <th>Stock(s)</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Edition</th>
                                    <th>Author(s)</th>
                                    <th>ISBN</th>
                                    <th>Publisher</th>
                                    <th>Year</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Location</th>
                                    <th>Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                <?php
                                if(isset($_GET['search'])){
                                    $searchvalues = $_GET['search'];
                                    $select_book = $conn->prepare("SELECT *, COUNT(*) AS count FROM (SELECT `book`.*, GROUP_CONCAT(`author`.Name) AS Authors FROM `book` JOIN `author` ON `book`.`BookID` = `author`.`BookID` WHERE CONCAT(`book`.`BookID`, `book`.`CallNumber`, `book`.`Price`, `book`.`Location`, `book`.`Remark`, `book`.`Status`, `author`.Name, `book`.`Image`, `book`.`ISBN`, `book`.`Title`, `book`.`Edition`, `book`.`PublisherName`, `book`.`PublishedYear`, `book`.`Category`) LIKE '%$searchvalues%' GROUP BY `book`.`BookID`) AS `subbook` GROUP BY `subbook`.`ISBN`;");
                                    $select_book->execute();
                                    if($select_book->rowCount() > 0){
                                        while($fetch_book = $select_book->fetch(PDO::FETCH_ASSOC)){
                                            $select_book_available = $conn->prepare("SELECT *, COUNT(*) AS count FROM (SELECT `book`.*, GROUP_CONCAT(`author`.Name) AS Authors FROM `book` JOIN `author` ON `book`.`BookID` = `author`.`BookID` WHERE Status = 'Available' AND ISBN = :isbn GROUP BY `book`.`BookID`) AS `subbook` GROUP BY `subbook`.`ISBN`;");
                                            $select_book_available->bindParam(':isbn', $fetch_book['ISBN']);
                                            $select_book_available->execute();
                                            if($select_book_available->rowCount() > 0){
                                                while($fetch_book_available = $select_book_available->fetch(PDO::FETCH_ASSOC)){
                                                    ?>
                                                    <td><?= $fetch_book_available['count']; ?></td>
                                                    <?php
                                            }}else{
                                                ?>
                                                <td>0</td>
                                                <?php
                                            }
                                            $select_book_unavailable = $conn->prepare("SELECT *, COUNT(*) AS count FROM (SELECT `book`.*, GROUP_CONCAT(`author`.Name) AS Authors FROM `book` JOIN `author` ON `book`.`BookID` = `author`.`BookID` WHERE Status = 'Unavailable' AND ISBN = :isbn GROUP BY `book`.`BookID`) AS `subbook` GROUP BY `subbook`.`ISBN`;");
                                            $select_book_unavailable->bindParam(':isbn', $fetch_book['ISBN']);
                                            $select_book_unavailable->execute();
                                            if($select_book_unavailable->rowCount() > 0){
                                                while($fetch_book_unavailable = $select_book_unavailable->fetch(PDO::FETCH_ASSOC)){
                                                ?>
                                                    <td><?= $fetch_book_unavailable['count']; ?></td>
                                                <?php
                                            }}else{
                                                ?>
                                                <td>0</td>
                                                <?php
                                            }
                                            $select_book_reserved = $conn->prepare("SELECT *, COUNT(*) AS count FROM (SELECT `book`.*, GROUP_CONCAT(`author`.Name) AS Authors FROM `book` JOIN `author` ON `book`.`BookID` = `author`.`BookID` WHERE Status = 'Reserved' AND ISBN = :isbn GROUP BY `book`.`BookID`) AS `subbook` GROUP BY `subbook`.`ISBN`;");
                                            $select_book_reserved->bindParam(':isbn', $fetch_book['ISBN']);
                                            $select_book_reserved->execute();
                                            if($select_book_reserved->rowCount() > 0){
                                                while($fetch_book_reserved = $select_book_reserved->fetch(PDO::FETCH_ASSOC)){
                                                ?>
                                                    <td><?= $fetch_book_reserved['count']; ?></td>
                                                <?php
                                            }}else{
                                                ?>
                                                <td>0</td>
                                                <?php
                                            }
                                            $select_book_archived = $conn->prepare("SELECT *, COUNT(*) AS count FROM (SELECT `book`.*, GROUP_CONCAT(`author`.Name) AS Authors FROM `book` JOIN `author` ON `book`.`BookID` = `author`.`BookID` WHERE Status = 'Archived' AND ISBN = :isbn GROUP BY `book`.`BookID`) AS `subbook` GROUP BY `subbook`.`ISBN`;");
                                            $select_book_archived->bindParam(':isbn', $fetch_book['ISBN']);
                                            $select_book_archived->execute();
                                            if($select_book_archived->rowCount() > 0){
                                                while($fetch_book_archived = $select_book_archived->fetch(PDO::FETCH_ASSOC)){
                                                ?>
                                                    <td><?= $fetch_book_archived['count']; ?></td>
                                                <?php
                                            }}else{
                                                ?>
                                                <td>0</td>
                                                <?php
                                            }
                            ?>
                                    <td><?= $fetch_book['count']; ?></td>
                                    <td><img src="<?= $fetch_book['Image']; ?>" alt="Book" class="image-book"></td>
                                    <td><?= $fetch_book['Title']; ?></td>
                                    <td><?= $fetch_book['Edition']; ?></td>
                                    <td><?= $fetch_book['Authors']; ?></td>
                                    <td><?= $fetch_book['ISBN']; ?></td>
                                    <td><?= $fetch_book['PublisherName']; ?></td>
                                    <td><?= $fetch_book['PublishedYear']; ?></td>
                                    <td><?= $fetch_book['Category']; ?></td>
                                    <td><?= $fetch_book['Price']; ?></td>
                                    <td><?= $fetch_book['Location']; ?></td>
                                    <td><?= $fetch_book['Remark'] ?? ''; ?></td>
                                </tr>
                                <?php
                                }
                                }
                                }else{
                                $select_book = $conn->prepare("SELECT *, COUNT(*) AS count FROM (SELECT `book`.*, GROUP_CONCAT(`author`.Name) AS Authors FROM `book` JOIN `author` ON `book`.`BookID` = `author`.`BookID` GROUP BY `book`.`BookID`) AS `subbook` GROUP BY `subbook`.`ISBN`;");
                                $select_book->execute();
                                if($select_book->rowCount() > 0){
                                    while($fetch_book = $select_book->fetch(PDO::FETCH_ASSOC)){
                                        $select_book_available = $conn->prepare("SELECT *, COUNT(*) AS count FROM (SELECT `book`.*, GROUP_CONCAT(`author`.Name) AS Authors FROM `book` JOIN `author` ON `book`.`BookID` = `author`.`BookID` WHERE Status = 'Available' AND ISBN = :isbn GROUP BY `book`.`BookID`) AS `subbook` GROUP BY `subbook`.`ISBN`;");
                                        $select_book_available->bindParam(':isbn', $fetch_book['ISBN']);
                                        $select_book_available->execute();
                                        if($select_book_available->rowCount() > 0){
                                            while($fetch_book_available = $select_book_available->fetch(PDO::FETCH_ASSOC)){
                                                ?>
                                                <td><?= $fetch_book_available['count']; ?></td>
                                                <?php
                                        }}else{
                                            ?>
                                            <td>0</td>
                                            <?php
                                        }
                                        $select_book_unavailable = $conn->prepare("SELECT *, COUNT(*) AS count FROM (SELECT `book`.*, GROUP_CONCAT(`author`.Name) AS Authors FROM `book` JOIN `author` ON `book`.`BookID` = `author`.`BookID` WHERE Status = 'Unavailable' AND ISBN = :isbn GROUP BY `book`.`BookID`) AS `subbook` GROUP BY `subbook`.`ISBN`;");
                                        $select_book_unavailable->bindParam(':isbn', $fetch_book['ISBN']);
                                        $select_book_unavailable->execute();
                                        if($select_book_unavailable->rowCount() > 0){
                                            while($fetch_book_unavailable = $select_book_unavailable->fetch(PDO::FETCH_ASSOC)){
                                            ?>
                                                <td><?= $fetch_book_unavailable['count']; ?></td>
                                            <?php
                                        }}else{
                                            ?>
                                            <td>0</td>
                                            <?php
                                        }
                                        $select_book_reserved = $conn->prepare("SELECT *, COUNT(*) AS count FROM (SELECT `book`.*, GROUP_CONCAT(`author`.Name) AS Authors FROM `book` JOIN `author` ON `book`.`BookID` = `author`.`BookID` WHERE Status = 'Reserved' AND ISBN = :isbn GROUP BY `book`.`BookID`) AS `subbook` GROUP BY `subbook`.`ISBN`;");
                                        $select_book_reserved->bindParam(':isbn', $fetch_book['ISBN']);
                                        $select_book_reserved->execute();
                                        if($select_book_reserved->rowCount() > 0){
                                            while($fetch_book_reserved = $select_book_reserved->fetch(PDO::FETCH_ASSOC)){
                                            ?>
                                                <td><?= $fetch_book_reserved['count']; ?></td>
                                            <?php
                                        }}else{
                                            ?>
                                            <td>0</td>
                                            <?php
                                        }
                                        $select_book_archived = $conn->prepare("SELECT *, COUNT(*) AS count FROM (SELECT `book`.*, GROUP_CONCAT(`author`.Name) AS Authors FROM `book` JOIN `author` ON `book`.`BookID` = `author`.`BookID` WHERE Status = 'Archived' AND ISBN = :isbn GROUP BY `book`.`BookID`) AS `subbook` GROUP BY `subbook`.`ISBN`;");
                                        $select_book_archived->bindParam(':isbn', $fetch_book['ISBN']);
                                        $select_book_archived->execute();
                                        if($select_book_archived->rowCount() > 0){
                                            while($fetch_book_archived = $select_book_archived->fetch(PDO::FETCH_ASSOC)){
                                            ?>
                                                <td><?= $fetch_book_archived['count']; ?></td>
                                            <?php
                                        }}else{
                                            ?>
                                            <td>0</td>
                                            <?php
                                        }
                            ?>
                                    <td><?= $fetch_book['count']; ?></td>
                                    <td><img src="<?= $fetch_book['Image']; ?>" alt="Book" class="image-book"></td>
                                    <td><?= $fetch_book['Title']; ?></td>
                                    <td><?= $fetch_book['Edition']; ?></td>
                                    <td><?= $fetch_book['Authors']; ?></td>
                                    <td><?= $fetch_book['ISBN']; ?></td>
                                    <td><?= $fetch_book['PublisherName']; ?></td>
                                    <td><?= $fetch_book['PublishedYear']; ?></td>
                                    <td><?= $fetch_book['Category']; ?></td>
                                    <td><?= $fetch_book['Price']; ?></td>
                                    <td><?= $fetch_book['Location']; ?></td>
                                    <td><?= $fetch_book['Remark'] ?? ''; ?></td>
                                </tr>
                            <?php
                            }
                            }
                            }
                            ?>
                            </tbody>
                                <?php
                            }
                            ?>
                                    
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
            <?php
            if (isset($_GET['filter'])){
            ?>
                /* Specify the indices of the columns you want to export */
                var columnIndices = [0, 1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]; // Adjust as needed

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
                    { wch: 5 }, // Column width for Status Stock
                    { wch: 5 }, // Column width for Stock
                    { wch: 40 }, // Column width for Title
                    { wch: 10 }, // Column width for Edition
                    { wch: 25 }, // Column width for Author
                    { wch: 20 }, // Column width for ISBN
                    { wch: 20 }, // Column width for Publisher
                    { wch: 10 }, // Column width for Year
                    { wch: 15 }, // Column width for Category
                    { wch: 10 }, // Column width for Price
                    { wch: 10 }, // Column width for Location
                    { wch: 15 }, // Column width for Remark
                ];
            <?php
            }else{
            ?>
                /* Specify the indices of the columns you want to export */
                var columnIndices = [0, 1, 2, 3, 4, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15]; // Adjust as needed

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
                    { wch: 5 }, // Column width for Available Stock
                    { wch: 5 }, // Column width for Issued Stock
                    { wch: 5 }, // Column width for Reserved Stock
                    { wch: 5 }, // Column width for Archived Stock
                    { wch: 5 }, // Column width for Stock
                    { wch: 40 }, // Column width for Title
                    { wch: 10 }, // Column width for Edition
                    { wch: 25 }, // Column width for Author
                    { wch: 20 }, // Column width for ISBN
                    { wch: 20 }, // Column width for Publisher
                    { wch: 10 }, // Column width for Year
                    { wch: 15 }, // Column width for Category
                    { wch: 10 }, // Column width for Price
                    { wch: 10 }, // Column width for Location
                    { wch: 15 }, // Column width for Remark
                ];
            <?php
            }
            ?>
            
            /* Create workbook */
            var wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

            /* Save to file */
            var filename = "books.xlsx";
            XLSX.writeFile(wb, filename);
        }
    </script>

    <script>
        $(document).ready(function() {
            $('.dropdown-item').click(function() {
                var filter = $(this).text(); // Get the text content of the clicked item
                if (filter != "All Books") {
                    var href = 'book_database_group.php?filter=' + encodeURIComponent(filter); 
                    window.location.href = href; // Redirect to the new link
                } else{
                    var href = 'book_database_group.php'
                    window.location.href = href; // Redirect to the new link
                }
            });
        });

        $(document).ready(function() {
            $('.archive-button').click(function(e) {
                e.preventDefault(); // Prevent the default form submission
                var bookId = $(this).data('bookid'); // Get the book ID from data attribute
                $('#myModal').show(); // Show the modal
                $('#confirmArchive').data('bookid', bookId); // Store the book ID in a data attribute of the confirm button
            });

            $('#cancelArchive').click(function() {
                $('#myModal').hide(); // Hide the modal
            });

            $('#confirmArchive').click(function() {
                var bookId = $(this).data('bookid'); // Get the book ID stored in the data attribute
                console.log('Book ID to archive: ' + bookId);
                $('form.archive-form input[name="bookid"]').val(bookId); // Set the book ID in the hidden input field
                $('form.archive-form').submit(); // Submit the form
            });

            $('.unarchive-button').click(function(e) {
                e.preventDefault(); // Prevent the default form submission
                var bookId = $(this).data('bookid'); // Get the book ID from data attribute
                $('#myModalUn').show(); // Show the modal
                $('#confirmUnarchive').data('bookid', bookId); // Store the book ID in a data attribute of the confirm button
            });

            $('#cancelUnarchive').click(function() {
                $('#myModal').hide(); // Hide the modal
            });

            $('#confirmUnarchive').click(function() {
                var bookId = $(this).data('bookid'); // Get the book ID stored in the data attribute
                console.log('Book ID to archive: ' + bookId);
                $('form.unarchive-form input[name="bookid"]').val(bookId); // Set the book ID in the hidden input field
                $('form.unarchive-form').submit(); // Submit the form
            });
        });
    </script>

    <script>
        var registerButton = document.getElementById('registerUserBtn');

        function updateButtonValue() {
            if (window.innerWidth <= 650) {
                registerButton.value = '+';
            } else {
                registerButton.value = 'Add Book';
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
