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

    $message="";
    $message_error="";

    function isValidTime($time) {
        // Regular expression pattern for 24-hour time format (HH:MM) or (HH:MM:SS)
        $pattern = '/^(?:2[0-3]|[01][0-9]):[0-5][0-9](?::[0-5][0-9])?$/';
        return preg_match($pattern, $time);
    }    
    
    function hasDomain($email) {
        $domain = substr($email, strpos($email, '@'));
        return $domain === '@soton.ac.uk';
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $return = $_POST['rday-input'];
        $extend = $_POST['eday-input'];
        $borrowLimit = $_POST['blimit-input'];
        $extendLimit = $_POST['elimit-input'];
        $fine = $_POST['fine-input'];
        $startTime = $_POST['start-input'];
        $endTime = $_POST['end-input'];
        $email = $_POST['email-input'];
        $phoneNo = $_POST['phoneNo-input'];

        // Check if all input values are float
        if (hasDomain($email) && is_numeric($return) && is_numeric($extend) && is_numeric($borrowLimit) && is_numeric($extendLimit) && (is_float($fine) || is_numeric($fine)) && isValidTime($startTime) && isValidTime($endTime)) {
            $sql = "UPDATE `setting` SET `ReturnDate` = :returnDay WHERE `ReturnDate` = :oldReturnDate;
            UPDATE `setting` SET `ExtendDate` = :extendDay WHERE `ExtendDate` = :oldExtendDate;
            UPDATE `setting` SET `BorrowLimit` = :borrowLimit WHERE `BorrowLimit` = :oldBorrowLimit;
            UPDATE `setting` SET `OverdueFine` = :fineAmount WHERE `OverdueFine` = :oldOverdueFineAmount;
            UPDATE `setting` SET `ExtendLimit` = :extendLimit WHERE `ExtendLimit` = :oldExtendLimit;
            UPDATE `setting` SET `StartTime` = :startTime WHERE `StartTime` = :oldStartTime;
            UPDATE `setting` SET `EndTime` = :endTime WHERE `EndTime` = :oldEndTime;
            UPDATE `setting` SET `Email` = :email WHERE `Email` = :oldEmail;
            UPDATE `setting` SET `PhoneNo` = :phoneNo WHERE `PhoneNo` = :oldPhoneNo;";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':returnDay', $return, PDO::PARAM_INT);
            $stmt->bindParam(':extendDay', $extend, PDO::PARAM_INT);
            $stmt->bindParam(':borrowLimit', $borrowLimit, PDO::PARAM_INT);
            $stmt->bindParam(':fineAmount', $fine, PDO::PARAM_STR);
            $stmt->bindParam(':extendLimit', $extendLimit, PDO::PARAM_STR);
            $stmt->bindParam(':startTime', $startTime, PDO::PARAM_STR);
            $stmt->bindParam(':endTime', $endTime, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':phoneNo', $phoneNo, PDO::PARAM_STR);
            $stmt->bindParam(':oldReturnDate', $_SESSION['ReturnDay'], PDO::PARAM_INT);
            $stmt->bindParam(':oldExtendDate', $_SESSION['ExtendDay'], PDO::PARAM_INT);
            $stmt->bindParam(':oldBorrowLimit', $_SESSION['BorrowLimit'], PDO::PARAM_INT);
            $stmt->bindParam(':oldOverdueFineAmount', $_SESSION['OverdueFineAmount'], PDO::PARAM_STR);
            $stmt->bindParam(':oldExtendLimit', $_SESSION['ExtendLimit'], PDO::PARAM_STR);
            $stmt->bindParam(':oldStartTime', $_SESSION['StartTime'], PDO::PARAM_STR);
            $stmt->bindParam(':oldEndTime', $_SESSION['EndTime'], PDO::PARAM_STR);
            $stmt->bindParam(':oldEmail', $_SESSION['LibraryEmail'], PDO::PARAM_STR);
            $stmt->bindParam(':oldPhoneNo', $_SESSION['LibraryPhoneNo'], PDO::PARAM_STR);

            // Execute the SQL statements
            $success = $stmt->execute();
    
            // Check if SQL execution was successful
            if ($success) {
                $message = "Settings updated successfully!";
                $_SESSION['ReturnDay'] = $return;
                $_SESSION['ExtendDay'] = $extend;
                $_SESSION['BorrowLimit'] = $borrowLimit;
                $_SESSION['OverdueFineAmount'] = $fine;
                $_SESSION['ExtendLimit'] = $extendLimit;
                $_SESSION['StartTime'] = $startTime;
                $_SESSION['EndTime'] = $endTime;
                $_SESSION['LibraryEmail'] = $email;
                $_SESSION['LibraryPhoneNo'] = $phoneNo;
            } else {
                $message_error = "Failed to update settings. Please try again.";
            }
        }else{
            $message_error = "Incorrect input!";
        }
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Setting</title>
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
                    <a href="fine_database.php" class="nav-item nav-link"><i class="fa-solid fa-money-check-dollar"></i> Fines</a>
                    <a href="setting.php" class="nav-item nav-link active"><i class="fa-solid fa-gear"></i>Setting</a>
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
                        <div class="header-text">Edit Library's Settings</div>
                    </div>

                    <div class="align-items-left justify-content-left">
                        <div class="bg-light rounded h-100 p-4">
                            <form id="edit-form" action="setting.php" method="post">
                                <div class="row g-2 mb-3">
                                    <label class="col label-text">Book returned after</label>
                                    <input value="<?= $_SESSION['ReturnDay']?>" type="text" id="rday-input" placeholder="Enter day (e.g. 14)" name="rday-input" class="col input-box" style="padding-left:10px;" required>
                                    <label class="col label-text">day(s).</label> 
                                </div>
                                <div class="row g-2 mb-3">
                                    <label class="col label-text">Extend due date for</label>
                                    <input value="<?= $_SESSION['ExtendDay']?>" type="text" id="eday-input" placeholder="Enter day (e.g. 14)" name="eday-input" class="col input-box" style="padding-left:10px;" required>
                                    <label class="col label-text">day(s).</label> 
                                </div>
                                <div class="row g-2 mb-3">
                                    <label class="col label-text">Borrow limit are</label>
                                    <input value="<?= $_SESSION['BorrowLimit']?>" type="text" id="blimit-input" placeholder="Enter book amount (e.g. 4)" name="blimit-input" class="col input-box" style="padding-left:10px;" required>
                                    <label class="col label-text">book(s).</label> 
                                </div>
                                <div class="row g-2 mb-3">
                                    <label class="col-md-auto label-text">Extend due date per book are</label>
                                    <input value="<?= $_SESSION['ExtendLimit']?>" type="text" id="elimit-input" placeholder="Enter extend time (e.g. 2)" name="elimit-input" class="col input-box" style="padding-left:10px;" required>
                                    <label class="col label-text">time(s).</label> 
                                </div>
                                <div class="row g-2 mb-3">
                                    <label class="col label-text">Overdue fine are RM</label>
                                    <input value="<?= $_SESSION['OverdueFineAmount']?>" type="text" id="fine-input" placeholder="Enter fine amount (e.g. 1.00)" name="fine-input" class="col input-box" style="padding-left:10px;" required>
                                    <label class="col label-text">per day.</label> 
                                </div>
                                <div class="row g-2 mb-3">
                                    <label class="col-md-auto label-text">Library opening hours</label>
                                    <input value="<?= $_SESSION['StartTime']?>" type="text" id="start-input" placeholder="Enter open time (09:00)" name="start-input" class="col-md-auto input-box" style="padding-left:10px;" required>
                                    <label class="col-md-auto label-text"> - </label> 
                                    <input value="<?= $_SESSION['EndTime']?>" type="text" id="end-input" placeholder="Enter close time (17:00)" name="end-input" class="col-md-auto input-box" style="padding-left:10px;" required>
                                    <div id="emailHelp" class="form-text">*Use 24-hour clock format (HH:MM:SS).</div>
                                </div>
                                <div class="row g-2 mb-3">
                                    <label class="col label-text">Library Email</label>
                                    <input value="<?= $_SESSION['LibraryEmail']?>" type="text" id="email-input" placeholder="Enter email (e.g. example@soton.ac.uk)" name="email-input" class="col input-box" style="padding-left:10px;" required>
                                    <label class="col label-text">.</label> 
                                </div>
                                <div class="row g-2 mb-3">
                                    <label class="col label-text">Library PhoneNo</label>
                                    <input value="<?= $_SESSION['LibraryPhoneNo']?>" type="text" id="phoneNo-input" placeholder="Enter phone no. (e.g. 012-3456789)" name="phoneNo-input" class="col input-box" style="padding-left:10px;" required>
                                    <label class="col label-text">.</label> 
                                </div>
                                <div id="message" class="message-prompt">
                                    <?php
                                    if (isset($message)) {
                                        echo $message;
                                    }
                                    ?>
                                </div>
                                <div id="message_error" class="message-prompt" style="color:red;">
                                    <?php
                                    if (isset($message_error)) {
                                        echo $message_error;
                                    }
                                    ?>
                                </div>
                                <input type="submit" value="SAVE SETTINGS" class="comfirmBtn">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content End -->


    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
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