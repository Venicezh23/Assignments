<?php
    include 'connect.php';

    session_start();

    if ($_SESSION['userType'] == "Librarian")  {
        header('location: dashboard.php');
        exit();
    }
    if ($_SESSION['userType'] != "Admin")  {
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
    <title>Admin - Book Database</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

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
    <link href="admin_database.css" rel="stylesheet">
    <style>
        .data-table th:nth-child(4),
        .data-table td:nth-child(4) {
            min-width: 250px; 
        }

        .data-table th:nth-child(7),
        .data-table td:nth-child(7) {
            min-width: 150px; 
        }

        .data-table th:nth-child(9),
        .data-table td:nth-child(9) {
            min-width: 150px; 
        }

        .data-table th:nth-child(13),
        .data-table td:nth-child(13) {
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
            background: linear-gradient(90deg, #171717 0%, #808080 100%);
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
    </style>
</head>

<body style="background-color:white">
    <div class="container-xxl position-relative bg-white d-flex p-0">

        <!-- Sidebar Start -->
        <div class="sidebar">
            <nav class="navbar">
                <a href="admin_user_database.php" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary" style="margin-left:-15px; margin-bottom:0.5rem;">
                        <img src="https://i.ibb.co/NNSKPfQ/Group-85.png" style="width: 2.5rem;">
                        <img src="https://pbs.twimg.com/media/GLMn-BvakAA70Dg?format=png&name=4096x4096" style="width: 9rem;"/>
                    </h3>
                </a>

                <div class="admin-text" style="margin-left:-15px; margin-bottom:5rem;">Admin</div>

                <div class="navbar-nav w-100">    
                    <a href="admin_user_database.php" class="nav-item nav-link"><i class="fa-solid fa-users me-2"></i>Users</a>
                    <a href="admin_book_database.php" class="nav-item nav-link active"><i class="fa-solid fa-book me-2"></i>Books</a>
                    <a href="admin_links.php" class="nav-item nav-link"><i class="fa-solid fa-link me-2"></i>Library Link</a>
                    <a href="admin_log_history.php" class="nav-item nav-link"><i class="fa-solid fa-timeline me-2"></i>Logs</a>
                    <a href="admin_setting.php" class="nav-item nav-link"><i class="fa-solid fa-gear"></i>Setting</a>
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand navbar-light sticky-top px-4 py-0" style="background-color: white;">
                <a href="admin_user_database.php" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0">
                        <img src="https://i.ibb.co/3dS9qVw/University-of-Southampton-Logo-3.png" style="width: 3rem;">
                    </h2>
                </a>
                <a class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars fa-2xl"></i>
                </a>

                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <div class="rounded-circle me-lg-2" data-bs-toggle="dropdown" style="width: 45px; height: 45px; cursor:pointer;">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path opacity="0.4" d="M12 22.01C17.5228 22.01 22 17.5329 22 12.01C22 6.48716 17.5228 2.01001 12 2.01001C6.47715 2.01001 2 6.48716 2 12.01C2 17.5329 6.47715 22.01 12 22.01Z" fill="#292D32"></path>
                                <path d="M12 6.93994C9.93 6.93994 8.25 8.61994 8.25 10.6899C8.25 12.7199 9.84 14.3699 11.95 14.4299C11.98 14.4299 12.02 14.4299 12.04 14.4299C12.06 14.4299 12.09 14.4299 12.11 14.4299C12.12 14.4299 12.13 14.4299 12.13 14.4299C14.15 14.3599 15.74 12.7199 15.75 10.6899C15.75 8.61994 14.07 6.93994 12 6.93994Z" fill="#292D32"></path>
                                <path d="M18.7807 19.36C17.0007 21 14.6207 22.01 12.0007 22.01C9.3807 22.01 7.0007 21 5.2207 19.36C5.4607 18.45 6.1107 17.62 7.0607 16.98C9.7907 15.16 14.2307 15.16 16.9407 16.98C17.9007 17.62 18.5407 18.45 18.7807 19.36Z" fill="#292D32"></path>
                            </g>
                            </svg>
                        </div>
                        <div class="dropdown-menu dropdown-menu-end border-0">
                            <a href="profile_admin.php" class="dropdown-item">Profile</a>
                            <a href="send_otp_code.php" class="dropdown-item">Reset Password</a>
                            <a href="login.php" class="dropdown-item" id="logout" >Log Out</a>
                        </div>
                    </div>
                </div>

                <div id="myModalDel" class="modal">
                    <div class="modal-content" id = "box">
                        <div id = "archiveHeader" class="modal-header"><h2>Delete</h2></div>
                        <div class="modal-body"><p>
                        Are you sure you want to delete this book?</p>
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
            </nav>
            <!-- Navbar End -->

            <div class="container-fluid pt-4 px-4">
                <div class="row g-3">
                    <div class="col"> 
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <div style="width: 54px;height: 54px;">
                                <svg fill="#000000" viewBox="0 0 256 256" id="Flat" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M233.61523,195.5752,192.2041,41.02637a16.01548,16.01548,0,0,0-19.5957-11.31348l-30.91016,8.28223c-.33935.09094-.66357.20923-.99219.32043A15.96591,15.96591,0,0,0,128,32H96a15.8799,15.8799,0,0,0-8,2.16492A15.8799,15.8799,0,0,0,80,32H48A16.01833,16.01833,0,0,0,32,48V208a16.01833,16.01833,0,0,0,16,16H80a15.8799,15.8799,0,0,0,8-2.16492A15.8799,15.8799,0,0,0,96,224h32a16.01833,16.01833,0,0,0,16-16V108.40283l27.7959,103.73584a15.992,15.992,0,0,0,19.5957,11.31445l30.91016-8.28222A16.01822,16.01822,0,0,0,233.61523,195.5752ZM176.749,45.167l6.21338,23.18238-30.91113,8.28247L145.83984,53.4502ZM128,48l.00732,120H96V48ZM80,48V72H48V48Zm48,160H96V184h32.0083l.00147,24Zm90.16016-8.28418-30.90967,8.28223-6.21143-23.18189,30.918-8.28491,6.21289,23.18164Z"></path> </g></svg>
                            </div>
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
                            </div>
                        </form>
                        <form action="admin_add_new_book.php">
                            <input type="submit" id="registerUserBtn" value="Add Book" class="register-button">
                        </form>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0 header-graph-text">
                            <?php
                            if (isset($_GET['filter'])){
                                $filtervalues = $_GET['filter'];
                            ?>
                                <?= $filtervalues?> Books
                            <?php
                            }else{
                            ?>
                                All Books
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
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Edition</th>
                                    <th>Call Number</th>
                                    <th>Author(s)</th>
                                    <th>ISBN</th>
                                    <th>Publisher</th>
                                    <th>Year</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Location</th>
                                    <th>Remark</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                    if(isset($_GET['search'])){
                                        $searchvalues = $_GET['search'];
                                        $select_book = $conn->prepare("SELECT `book`.*, GROUP_CONCAT(`author`.Name) AS Authors FROM `book` JOIN `author` ON `book`.`BookID` = `author`.`BookID` WHERE CONCAT(`book`.`BookID`, `book`.`CallNumber`, `book`.`Price`, `book`.`Location`, `book`.`Remark`, `book`.`Status`, `author`.Name, `book`.`Image`, `book`.`ISBN`, `book`.`Title`, `book`.`Edition`, `book`.`PublisherName`, `book`.`PublishedYear`, `book`.`Category`) LIKE '%$searchvalues%' AND Status <> 'Archived' GROUP BY `book`.`BookID`; ");
                                        $select_book->execute();
                                        if($select_book->rowCount() > 0){
                                            while($fetch_book = $select_book->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                        <tr>
                                            <td style="display:flex;padding-top:60px;">
                                                <?php if ($fetch_book['Status'] == "Unavailable" || $fetch_book['Status'] == "Reserved") { ?>
                                                <?php } else { ?>
                                                    <form action="admin_update_book.php" method="post">
                                                        <input type="hidden" name="bookid" value="<?= $fetch_book['BookID']; ?>">
                                                        <input type="hidden" name="image" value="<?= $fetch_book['Image']; ?>">
                                                        <input type="hidden" name="title" value="<?= $fetch_book['Title']; ?>">
                                                        <input type="hidden" name="author" value="<?= $fetch_book['Authors']; ?>">
                                                        <input type="hidden" name="isbn" value="<?= $fetch_book['ISBN']; ?>">
                                                        <input type="hidden" name="publisher" value="<?= $fetch_book['PublisherName']; ?>">
                                                        <input type="hidden" name="year" value="<?= $fetch_book['PublishedYear']; ?>">
                                                        <input type="hidden" name="category" value="<?= $fetch_book['Category']; ?>">
                                                        <input type="hidden" name="edition" value="<?= $fetch_book['Edition']; ?>">
                                                        <input type="hidden" name="location" value="<?= $fetch_book['Location']; ?>">
                                                        <input type="hidden" name="price" value="<?= $fetch_book['Price']; ?>">
                                                        <input type="hidden" name="callnumber" value="<?= $fetch_book['CallNumber']; ?>">
                                                        <input type="hidden" name="remark" value="<?= $fetch_book['Remark']; ?>">
                                                        <button type="submit" class="table-button" style="margin-right: 10px;"><i class="fa-solid fa-pen" width="20px" height="20px"></i></button>
                                                    </form>
                                                    <form class="archive-form" action="admin_archive_book.php" method="post">
                                                        <input type="hidden" name="bookid" value="<?= $fetch_book['BookID']; ?>">
                                                        <button type="button" class="archive-button table-button" data-bookid="<?= $fetch_book['BookID']; ?>"><i class="fa-regular fa-folder-open" width="20px" height="20px"></i></button>
                                                    </form>
                                                    <form class="delete-form" action="admin_delete_book.php" method="post">
                                                        <input type="hidden" name="bookid" value="">
                                                        <button type="button" class="delete-button table-button" data-bookid="<?= $fetch_book['BookID']; ?>"><i class="fa-solid fa-trash-can" width="20px" height="20px"></i></button>
                                                    </form>
                                                <?php } ?>
                                            </td>
                                            <td><?= $fetch_book['BookID']; ?></td>
                                            <td><img src="<?= $fetch_book['Image']; ?>" alt="Book" class="image-book"></td>
                                            <td><?= $fetch_book['Title']; ?></td>
                                            <td><?= $fetch_book['Edition']; ?></td>
                                            <td><?= $fetch_book['CallNumber']; ?></td>
                                            <td><?= $fetch_book['Authors']; ?></td>
                                            <td><?= $fetch_book['ISBN']; ?></td>
                                            <td><?= $fetch_book['PublisherName']; ?></td>
                                            <td><?= $fetch_book['PublishedYear']; ?></td>
                                            <td><?= $fetch_book['Category']; ?></td>
                                            <td><?= $fetch_book['Price']; ?></td>
                                            <td><?= $fetch_book['Location']; ?></td>
                                            <td><?= $fetch_book['Remark']; ?></td>
                                            <td><?= $fetch_book['Status']; ?></td>
                                        </tr>
                                    <?php
                                    }
                                    }
                                    }elseif (isset($_GET['filter'])){
                                        $filtervalues = $_GET['filter'];
                                        if ($filtervalues=="Available"){
                                            $select_book = $conn->prepare("SELECT `book`.*, GROUP_CONCAT(`author`.Name) AS Authors FROM `book` JOIN `author` ON `book`.`BookID` = `author`.`BookID` WHERE Status = 'Available' GROUP BY `book`.`BookID`; ");
                                        }else if ($filtervalues=="Unavailable"){
                                            $select_book = $conn->prepare("SELECT `book`.*, GROUP_CONCAT(`author`.Name) AS Authors FROM `book` JOIN `author` ON `book`.`BookID` = `author`.`BookID` WHERE Status = 'Unavailable' GROUP BY `book`.`BookID`; ");
                                        }else if ($filtervalues=="Reserved"){
                                            $select_book = $conn->prepare("SELECT `book`.*, GROUP_CONCAT(`author`.Name) AS Authors FROM `book` JOIN `author` ON `book`.`BookID` = `author`.`BookID` WHERE Status = 'Reserved' GROUP BY `book`.`BookID`; ");
                                        }else if ($filtervalues=="Archived"){
                                            $select_book = $conn->prepare("SELECT `book`.*, GROUP_CONCAT(`author`.Name) AS Authors FROM `book` JOIN `author` ON `book`.`BookID` = `author`.`BookID` WHERE Status = 'Archived' GROUP BY `book`.`BookID`; ");
                                        }
                                        $select_book->execute();
                                        if($select_book->rowCount() > 0){
                                            while($fetch_book = $select_book->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                        <tr>
                                            <td style="display:flex;padding-top:60px;">
                                                <?php if ($filtervalues == "Unavailable" || $filtervalues == "Reserved") { ?>
                                                <?php } else if ($filtervalues !== "Archived") { ?>
                                                    <form action="admin_update_book.php" method="post">
                                                        <input type="hidden" name="bookid" value="<?= $fetch_book['BookID']; ?>">
                                                        <input type="hidden" name="image" value="<?= $fetch_book['Image']; ?>">
                                                        <input type="hidden" name="title" value="<?= $fetch_book['Title']; ?>">
                                                        <input type="hidden" name="author" value="<?= $fetch_book['Authors']; ?>">
                                                        <input type="hidden" name="isbn" value="<?= $fetch_book['ISBN']; ?>">
                                                        <input type="hidden" name="publisher" value="<?= $fetch_book['PublisherName']; ?>">
                                                        <input type="hidden" name="year" value="<?= $fetch_book['PublishedYear']; ?>">
                                                        <input type="hidden" name="category" value="<?= $fetch_book['Category']; ?>">
                                                        <input type="hidden" name="edition" value="<?= $fetch_book['Edition']; ?>">
                                                        <input type="hidden" name="location" value="<?= $fetch_book['Location']; ?>">
                                                        <input type="hidden" name="price" value="<?= $fetch_book['Price']; ?>">
                                                        <input type="hidden" name="callnumber" value="<?= $fetch_book['CallNumber']; ?>">
                                                        <input type="hidden" name="remark" value="<?= $fetch_book['Remark']; ?>">
                                                        <button type="submit" class="table-button" style="margin-right: 10px;"><i class="fa-solid fa-pen" width="20px" height="20px"></i></button>
                                                    </form>
                                                    <form class="archive-form" action="admin_archive_book.php" method="post">
                                                        <input type="hidden" name="bookid" value="<?= $fetch_book['BookID']; ?>">
                                                        <button style="margin-right: 10px;" type="button" class="archive-button table-button" data-bookid="<?= $fetch_book['BookID']; ?>"><i class="fa-regular fa-folder-open" width="20px" height="20px"></i></button>
                                                    </form>
                                                    <form class="delete-form" action="admin_delete_book.php" method="post">
                                                        <input type="hidden" name="bookid" value="">
                                                        <button type="button" class="delete-button table-button" data-bookid="<?= $fetch_book['BookID']; ?>"><i class="fa-solid fa-trash-can" width="20px" height="20px"></i></button>
                                                    </form>
                                                <?php } else { ?>
                                                    <form action="admin_update_book.php" method="post">
                                                        <input type="hidden" name="bookid" value="<?= $fetch_book['BookID']; ?>">
                                                        <input type="hidden" name="image" value="<?= $fetch_book['Image']; ?>">
                                                        <input type="hidden" name="title" value="<?= $fetch_book['Title']; ?>">
                                                        <input type="hidden" name="author" value="<?= $fetch_book['Authors']; ?>">
                                                        <input type="hidden" name="isbn" value="<?= $fetch_book['ISBN']; ?>">
                                                        <input type="hidden" name="publisher" value="<?= $fetch_book['PublisherName']; ?>">
                                                        <input type="hidden" name="year" value="<?= $fetch_book['PublishedYear']; ?>">
                                                        <input type="hidden" name="category" value="<?= $fetch_book['Category']; ?>">
                                                        <input type="hidden" name="edition" value="<?= $fetch_book['Edition']; ?>">
                                                        <input type="hidden" name="location" value="<?= $fetch_book['Location']; ?>">
                                                        <input type="hidden" name="price" value="<?= $fetch_book['Price']; ?>">
                                                        <input type="hidden" name="callnumber" value="<?= $fetch_book['CallNumber']; ?>">
                                                        <input type="hidden" name="remark" value="<?= $fetch_book['Remark']; ?>">
                                                        <button type="submit" class="table-button" style="margin-right: 10px;"><i class="fa-solid fa-pen" width="20px" height="20px"></i></button>
                                                    </form>
                                                    <form class="unarchive-form" action="admin_unarchive_book.php" method="post">
                                                        <input type="hidden" name="bookid" value="<?= $fetch_book['BookID']; ?>">
                                                        <button style="margin-right: 10px;" type="button" class="unarchive-button table-button" data-bookid="<?= $fetch_book['BookID']; ?>"><i class="fa-solid fa-folder"></i></button>
                                                    </form>
                                                    <form class="delete-form" action="admin_delete_book.php" method="post">
                                                        <input type="hidden" name="bookid" value="">
                                                        <button type="button" class="delete-button table-button" data-bookid="<?= $fetch_book['BookID']; ?>"><i class="fa-solid fa-trash-can" width="20px" height="20px"></i></button>
                                                    </form>
                                                <?php } ?>
                                            </td>
                                            <td><?= $fetch_book['BookID']; ?></td>
                                            <td><img src="<?= $fetch_book['Image']; ?>" alt="Book" class="image-book"></td>
                                            <td><?= $fetch_book['Title']; ?></td>
                                            <td><?= $fetch_book['Edition']; ?></td>
                                            <td><?= $fetch_book['CallNumber']; ?></td>
                                            <td><?= $fetch_book['Authors']; ?></td>
                                            <td><?= $fetch_book['ISBN']; ?></td>
                                            <td><?= $fetch_book['PublisherName']; ?></td>
                                            <td><?= $fetch_book['PublishedYear']; ?></td>
                                            <td><?= $fetch_book['Category']; ?></td>
                                            <td><?= $fetch_book['Price']; ?></td>
                                            <td><?= $fetch_book['Location']; ?></td>
                                            <td><?= $fetch_book['Remark'] ?? ''; ?></td>
                                            <td><?= $fetch_book['Status']; ?></td>
                                        </tr>
                                    <?php
                                    }
                                    }
                                    }else{
                                        $select_book = $conn->prepare("SELECT `book`.*, GROUP_CONCAT(`author`.Name) AS Authors FROM `book` JOIN `author` ON `book`.`BookID` = `author`.`BookID` WHERE Status <> 'Archived' GROUP BY `book`.`BookID`; ");
                                        $select_book->execute();
                                        if($select_book->rowCount() > 0){
                                            while($fetch_book = $select_book->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                        <tr>
                                            <td style="display:flex;padding-top:60px;">
                                                <?php if ($fetch_book['Status'] == "Unavailable" || $fetch_book['Status'] == "Reserved") { ?>
                                                <?php } else { ?>
                                                    <form action="admin_update_book.php" method="post">
                                                        <input type="hidden" name="bookid" value="<?= $fetch_book['BookID']; ?>">
                                                        <input type="hidden" name="image" value="<?= $fetch_book['Image']; ?>">
                                                        <input type="hidden" name="title" value="<?= $fetch_book['Title']; ?>">
                                                        <input type="hidden" name="author" value="<?= $fetch_book['Authors']; ?>">
                                                        <input type="hidden" name="isbn" value="<?= $fetch_book['ISBN']; ?>">
                                                        <input type="hidden" name="publisher" value="<?= $fetch_book['PublisherName']; ?>">
                                                        <input type="hidden" name="year" value="<?= $fetch_book['PublishedYear']; ?>">
                                                        <input type="hidden" name="category" value="<?= $fetch_book['Category']; ?>">
                                                        <input type="hidden" name="edition" value="<?= $fetch_book['Edition']; ?>">
                                                        <input type="hidden" name="location" value="<?= $fetch_book['Location']; ?>">
                                                        <input type="hidden" name="price" value="<?= $fetch_book['Price']; ?>">
                                                        <input type="hidden" name="callnumber" value="<?= $fetch_book['CallNumber']; ?>">
                                                        <input type="hidden" name="remark" value="<?= $fetch_book['Remark']; ?>">
                                                        <button type="submit" class="table-button" style="margin-right: 10px;"><i class="fa-solid fa-pen" width="20px" height="20px"></i></button>
                                                    </form>
                                                    <form class="archive-form" action="admin_archive_book.php" method="post">
                                                        <input type="hidden" name="bookid" value="<?= $fetch_book['BookID']; ?>">
                                                        <button style="margin-right: 10px;" type="button" class="archive-button table-button" data-bookid="<?= $fetch_book['BookID']; ?>"><i class="fa-regular fa-folder-open" width="20px" height="20px"></i></button>
                                                    </form>
                                                    <form class="delete-form" action="admin_delete_book.php" method="post">
                                                        <input type="hidden" name="bookid" value="">
                                                        <button type="button" class="delete-button table-button" data-bookid="<?= $fetch_book['BookID']; ?>"><i class="fa-solid fa-trash-can" width="20px" height="20px"></i></button>
                                                    </form>
                                                <?php } ?>
                                            </td>
                                            <td><?= $fetch_book['BookID']; ?></td>
                                            <td><img src="<?= $fetch_book['Image']; ?>" alt="Book" class="image-book"></td>
                                            <td><?= $fetch_book['Title']; ?></td>
                                            <td><?= $fetch_book['Edition']; ?></td>
                                            <td><?= $fetch_book['CallNumber']; ?></td>
                                            <td><?= $fetch_book['Authors']; ?></td>
                                            <td><?= $fetch_book['ISBN']; ?></td>
                                            <td><?= $fetch_book['PublisherName']; ?></td>
                                            <td><?= $fetch_book['PublishedYear']; ?></td>
                                            <td><?= $fetch_book['Category']; ?></td>
                                            <td><?= $fetch_book['Price']; ?></td>
                                            <td><?= $fetch_book['Location']; ?></td>
                                            <td><?= $fetch_book['Remark'] ?? ''; ?></td>
                                            <td><?= $fetch_book['Status']; ?></td>
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
            var columnIndices = [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]; // Adjust as needed

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
                { wch: 40 }, // Column width for Title
                { wch: 10 }, // Column width for Edition
                { wch: 25 }, // Column width for Call Number
                { wch: 25 }, // Column width for Author
                { wch: 20 }, // Column width for ISBN
                { wch: 20 }, // Column width for Publisher
                { wch: 10 }, // Column width for Year
                { wch: 15 }, // Column width for Category
                { wch: 10 }, // Column width for Price
                { wch: 10 }, // Column width for Location
                { wch: 15 }, // Column width for Remark
                { wch: 15 }, // Column width for Status
            ];

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
                    var href = 'admin_book_database.php?filter=' + encodeURIComponent(filter); 
                    window.location.href = href; // Redirect to the new link
                } else{
                    var href = 'admin_book_database.php'
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

            $('.delete-button').click(function(e) {
                e.preventDefault(); // Prevent the default form submission
                var bookid = $(this).data('bookid'); // Get the book ID from data attribute
                $('#myModalDel').show(); // Show the modal
                $('#confirmDelete').data('bookid', bookid); // Store the book ID in a data attribute of the confirm button
            });

            $('#cancelDelete').click(function() {
                $('#myModalDel').hide(); // Hide the modal
            });

            $('#confirmDelete').click(function() {
                var bookid = $(this).data('bookid'); // Get the book ID stored in the data attribute
                console.log('Book ID to delete: ' + bookid);
                $('form.delete-form input[name="bookid"]').val(bookid); // Set the book ID in the hidden input field
                $('form.delete-form').submit(); // Submit the form
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
</body>

</html>w