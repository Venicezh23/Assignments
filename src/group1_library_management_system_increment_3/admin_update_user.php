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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $firstname = $_POST['firstName'];
        $lastname = $_POST['lastName'];
        $phoneNum = $_POST['phoneNum'];
        $id = $_POST['userid'];
        $pwd = $_POST['password'];
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Admin - Update User</title>
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
                    <a href="admin_user_database.php" class="nav-item nav-link active"><i class="fa-solid fa-users me-2"></i>Users</a>
                    <a href="admin_book_database.php" class="nav-item nav-link"><i class="fa-solid fa-book me-2"></i>Books</a>
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
            </nav>
            <!-- Navbar End -->

            <div class="container-fluid pt-4 px-4">
                <div class="bg-light rounded p-4">
                    <div class="d-flex text-center align-items-center justify-content-center mb-4">
                        <div class="header-text">Update User</div>
                    </div>

                    <div class="align-items-left justify-content-left">
                        <div class="bg-light rounded h-100 p-4">
                            <form id="update-form" action="query_update_user.php" method="post" class="row g-2">
                                <input type="hidden" id="user-id" name="user-id" value="<?php echo $id; ?>">
                                <div class="mb-3">
                                    <label class="label-text">First Name</label>
                                    <input type="text" placeholder="Enter the first name" class="input-box" id="fname-input" name="fname-input" value="<?php echo $firstname; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="label-text">Last Name</label>
                                    <input type="text" placeholder="Enter the last name" class="input-box" id="lname-input" name="lname-input" value="<?php echo $lastname; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="label-text">Password</label>
                                    <div class="row g-2">
                                        <input type="password" id="password-input" name ="password-input" value="<?php echo $pwd; ?>" placeholder="Enter password here" class="col input-box mx-2" readonly>
                                        <input style="height: 2.67rem;" class="col comfirmBtn" onclick="randomPass()" type="button" value="GENERATE PASSWORD">
                                    </div>
                                    <div id="emailHelp" class="form-text">This password will be sent to the user.</div>
                                </div>
                                <div class="mb-3">
                                    <label class="label-text">Phone No.</label>
                                    <input type="text" id="phnum-input" placeholder="Enter phone number here" name="phnum-input" class="input-box" value="<?php echo $phoneNum; ?>" required>
                                </div>
                                <div id="message" class="message-prompt"></div>
                                <input type="submit" value="UPDATE" class="comfirmBtn">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>

    <script>
        function randomPass(){
            var length = 2;
            var charsetLower = "abcdefghijklmnopqrstuvwxyz";
			var charsetUpper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
			var charsetNum = "0123456789";
			var charsetSpecial = "!@#$£%^&*(-)+=_{}[]\|:;'<>,.?/¬`~";
            var password = "";
            for (var i = 0; i < length; i++) { //generate password - guarantee lowercase, uppercase, numbers and special char
                password += charsetLower.charAt(Math.floor(Math.random() * charsetLower.length));
            }
			for (var i = 0; i < length; i++) {
                password += charsetUpper.charAt(Math.floor(Math.random() * charsetUpper.length));
            }
			for (var i = 0; i < length+1; i++) {
                password += charsetNum.charAt(Math.floor(Math.random() * charsetNum.length));
            }
			for (var i = 0; i < length+1; i++) {
                password += charsetSpecial.charAt(Math.floor(Math.random() * charsetSpecial.length));
            }
			//shuffle the password
			password = password.split('').sort(() => Math.random() - 0.5).join('');
            document.getElementById("password-input").value = password;
			console.log("Generated password: " + password);
        }


        document.getElementById('update-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            var formData = new FormData(this);
            
            fetch('query_update_user.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                var messageDiv = document.getElementById('message');
                
                if (data.trim() === 'Record updated successfully.') {
                    messageDiv.innerHTML = data;
                    messageDiv.style.color = "#788800";
                    messageDiv.style.fontSize = "1.2rem";
                } else {
                    messageDiv.style.color = "red";
                    messageDiv.style.fontSize = "1rem";
                    messageDiv.innerHTML = 'Error: ' + data;
                }
            })
            .catch(error => console.error('Error:', error));
        });

        document.getElementById("dropdownMenu").addEventListener("click", function() {
            var dropdownContent = document.getElementById("dropdownContent");
            dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
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
</body>

</html>w