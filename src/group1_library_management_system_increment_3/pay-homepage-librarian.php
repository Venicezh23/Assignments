<?php
    include_once('connect-login.php');
								
	if ($conn->connect_error){
		die("Connection failed: " . $conn->connect_error);
	}
	
	date_default_timezone_set('Asia/Kuala_Lumpur');
	mysqli_query($conn, "SET time_zone = '+08:00'");

    session_start();

    // var_dump($_SESSION['patronID']);
	// $actualValue = $_GET['patron'] ?? 'No patron selected';
	// $_SESSION['patronID'] = $actualValue; //storing patron's id in session 
    if(isset($_GET['patronID'])) {
        $_SESSION['patronID'] = $_GET['patronID'];
    }
    
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
    <title>Payment Homepage</title>
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
		.total-rmx-xx {
			/* margin: 0 0.1rem 0.8rem 0; */
			display: block; /* Change display to block */
			overflow-wrap: break-word;
			font-family: 'Inter';
			font-weight: bolder;
			font-size: 2rem;
			color: #002439;
			padding-top: 2rem;
			padding-bottom: 1.0rem;
			text-align: center; /* Center align the text */
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
                </div>
            </nav>
            <!-- Navbar End -->

            <div class="container-fluid pt-4 px-4">
                <div class="bg-light rounded p-4">
                    <div class="d-flex text-center align-items-center justify-content-center mb-4">
                        <div class="header-text" style="margin-bottom:0.5rem;">Payment Details</div>
                    </div>
					<div class='total-rmx-xx' style="padding: 0; margin:0;font-size:15px">Patron ID: <?= $_SESSION['patronID']?></div>
                    <div class="align-items-center justify-content-center">
                        <table class="data-table">
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
										WHERE bi.UserID = ?
										AND IsPaid = '0'"; //join book, fine and book_issued
								// Prepare the statement
								$stmt = $conn->prepare($sql);
				
								// Bind the parameter
								$stmt->bind_param("s", $_SESSION['patronID']);
								// Execute the statement
								$stmt->execute();
				
								// Get the result
								$result = $stmt->get_result();
								
								if(!$result){
									die("Invalid query: " . $conn->error);
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
				
								$conn->close();
				
								// Display total amount due below the table
								echo "<div class='total-rmx-xx'>
										TOTAL: RM" . $totalAmountDue . "
									  </div>";
								?>
					</div>

					<button class="comfirmBtn" onclick="goToPaymentMethod('<?php echo htmlspecialchars($_SESSION['patronID']); ?>')">PROCEED TO PAYMENT</button>
	
                    </div>
                </div>
            </div>
        </div>
        <!-- Content End -->


    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script>
		function goToPaymentMethod() {
			window.location.href = 'payment_method_librarian.php';
		}
	
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
</body>

</html>