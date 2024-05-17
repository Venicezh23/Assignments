<?php
    include 'connect.php';
    session_start();

    require 'bookscanner/src/Bookscanner.php';
    use imonroe\bookscanner\Bookscanner;

    $isbn = $title = $author = $publisher = $publication_date = $number_of_pages = $physical_format =  '';
    $thumbnail_url ="https://cdn-icons-png.freepik.com/256/3405/3405403.png?ga=GA1.1.1201796896.1698075133&";

    $_SESSION['ID']=16;
    $_SESSION['userType'] = "Admin";

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
    <title>Add new book</title>
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
                <div class="bg-light rounded p-4">
                    <div class="d-flex text-center align-items-center justify-content-center mb-4">
                        <div class="header-text">Add New Book</div>
                    </div>

                    <div class="align-items-left justify-content-left">
                        <div class="bg-light rounded h-100 p-4">
                            <form id="book-form" action="book_added.php" method="post" class="row g-2">
                                <div class="col-8" style="margin-right: 10px;">
                                    <div class="mb-3">
                                    <label class="label-text">ISBN</label>
                                        <div class="row g-2">
                                            <input type="text" placeholder="Enter the ISBN" class="col input-box mx-2" id="isbn-input" name="isbn-input" required></input>
                                            <input class="col-auto comfirmBtn" id="isbn-submit" type="button" value="GET" style="height: 2.67rem;width: 5rem;font-size:15px">
                                        </div>
                                        <div id="emailHelp" class="form-text">The details of this ISBN will be automatically entered.</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="label-text">Title</label>
                                        <input type="text" placeholder="Enter the title" class="input-box" id="title-input" name="title-input" value="<?php echo $title; ?>" required></input>
                                    </div>
                                    <div class="mb-3 row g-2">
                                        <div class="col">
                                            <label class="label-text">Authors</label>
                                            <input type="text" placeholder="Enter the authors" class="input-box" id="author-input" name="author-input" value="<?php echo $author; ?>" required></input>
                                        </div>
                                        <div class="col">
                                            <label class="label-text">Edition</label>
                                            <input type="text" placeholder="Enter the edition" class="input-box" id="edition-input" name="edition-input" required></input>
                                        </div>
                                    </div>
                                    <div class="mb-3 row g-2">
                                        <div class="col">
                                            <label class="label-text">Publisher</label>
                                            <input type="text" placeholder="Enter the publisher" class="input-box" id="publisher-input" name="publisher-input" value="<?php echo $publisher; ?>" required></input>
                                        </div>
                                        <div class="col">
                                            <label class="label-text">Year</label>
                                            <input type="text" placeholder="Enter the year" class="input-box" id="year-input" name="year-input" value="<?php echo $publication_date; ?>" required></input>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="label-text">Call Number</label>
                                        <input type="text" placeholder="Enter the call number" class="input-box" id="callnum-input" name="callnum-input" required></input>
                                    </div>
                                    <div class="mb-3 row g-2">
                                        <div class="col">
                                            <label class="label-text">Price (RM)</label>
                                            <input type="text" placeholder="Enter the price" class="input-box" id="price-input" name="price-input" value="0.0" required></input>
                                        </div>
                                        <div class="col">
                                            <label class="label-text">Location</label>
                                            <select id="location-input" name="location-input" class="select-box" required>
                                                <option value="" disabled selected>Choose a location</option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                                <option value="D">D</option>
                                                <option value="E">E</option>
                                                <option value="F">F</option>
                                                <option value="G">G</option>
                                                <option value="H">H</option>
                                                <option value="I">I</option>
                                                <option value="J">J</option>
                                                <option value="K">K</option>
                                                <option value="L">L</option>
                                                <option value="M">M</option>
                                                <option value="N">N</option>
                                                <option value="O">O</option>
                                                <option value="P">P</option>
                                                <option value="Q">Q</option>
                                                <option value="R">R</option>
                                                <option value="S">S</option>
                                                <option value="T">T</option>
                                                <option value="U">U</option>
                                                <option value="V">V</option>
                                                <option value="W">W</option>
                                                <option value="X">X</option>
                                                <option value="Y">Y</option>
                                                <option value="Z">Z</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 row g-2">
                                        <label class="label-text">Category</label>
                                        <select id="category-input" name="category-input" class="select-box" required>
                                            <option value="" disabled selected>Choose a category</option>
                                            <option value="Computer Science">Computer Science</option>
                                            <option value="Business and Analytics">Business and Analytics</option>
                                            <option value="Finance and Financial Technology">Finance and Financial Technology</option>
                                            <option value="Aeronautics and Astronautics">Aeronautics and Astronautics</option>
                                            <option value="Electrical and Electronic Engineering">Electrical and Electronic Engineering</option>
                                            <option value="Mechanical Engineering">Mechanical Engineering</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="mb-3">
                                        <label class="label-text">Picture</label>
                                        <button class="picture-box" onclick="document.getElementById('image-input').click(); previewImage()">
                                            <img id="preview" style="height: 80px;" src="<?php echo $thumbnail_url; ?>"/>
                                        </button>
                                        <input type="file" id="image-input" style="display: none;" accept="image/*" onchange="previewImage();validateImage()"  name="image-input"></input>
                                        <input type="hidden" id="image-input-hidden" name="image-input-hidden">
                                        <input style="display: none;" type="text" class="input-box" id="image-url-input" name="image-url-input" value="<?php echo $thumbnail_url; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="label-text">Remarks</label>
                                        <textarea type="text" placeholder="Enter the remarks" class="text-area-box" id="remark-input" name="remark-input"><?php echo $physical_format; ?></textarea>
                                    </div>
                                </div>
                                <div id="message" class="message-prompt"></div>
                                <input type="submit" value="CONFIRM" class="comfirmBtn" id="comfirmBtn">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content End -->
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function previewImage() {
            var fileInput = document.getElementById('image-input');
            var file = fileInput.files[0];
            var imgElement = document.getElementById('preview');
            var hiddenImageInput = document.getElementById('image-input-hidden');

            var reader = new FileReader();

            reader.onload = function(e) {
                imgElement.src = e.target.result;
                hiddenImageInput.value = e.target.result; // Update the hidden input value
            }

            reader.readAsDataURL(file);
        }

        function validateImage() {
            var fileInput = document.getElementById('image-input');
            var file = fileInput.files[0];
            var imgElement = document.getElementById('preview');
            var messageDiv = document.getElementById('message');
            var comfirmBtn = document.getElementById('comfirmBtn');
            
            if (file.type !== 'image/png' && file.type !== 'image/jpeg' && file.type !== 'image/jpg') {
                imgElement.src = "https://cdn-icons-png.freepik.com/256/3405/3405403.png?ga=GA1.1.1201796896.1698075133&";
                messageDiv.style.color = "red";
                messageDiv.style.fontSize = "1rem";
                messageDiv.innerHTML = 'Error: Please select a PNG or JPEG image file.';
                comfirmBtn.disabled = true;
                comfirmBtn.style.opacity = '0.5';
            }else if (file.size > 1048576) { // 1MB limit (1048576 bytes)
                messageDiv.style.color = "red";
                messageDiv.style.fontSize = "1rem";
                messageDiv.innerHTML = 'Error: Image size exceeds the maximum allowed size of 1MB.';
                comfirmBtn.disabled = true;
                comfirmBtn.style.opacity = '0.5';
            }else{
                messageDiv.innerHTML = '';
                comfirmBtn.disabled = false;
                comfirmBtn.style.opacity = '1';
            }
        }

        document.getElementById('book-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            var formData = new FormData(this);
            
            fetch('book_added.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                var messageDiv = document.getElementById('message');
                
                if (data.trim() === 'New book inserted successfully.') {
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
    </script>

    <script>
    document.getElementById('isbn-submit').addEventListener('click', function() {
        var messageDiv = document.getElementById('message');
        var comfirmBtn = document.getElementById('comfirmBtn');
        var imgElement = document.getElementById('preview');

        messageDiv.innerHTML = '';
        comfirmBtn.disabled = false;
        comfirmBtn.style.opacity = '1';

        var isbn = document.getElementById('isbn-input').value;
        
        fetch('get_book_data.php?isbn=' + isbn)
        .then(response => response.json())
        .then(data => {
            // Populate form fields with retrieved data
            document.getElementById('isbn-input').value = data.isbn;
            document.getElementById('title-input').value = data.title;
            document.getElementById('author-input').value = data.author;
            document.getElementById('publisher-input').value = data.publisher;
            document.getElementById('year-input').value = data.publication_date;
            document.getElementById('remark-input').innerHTML = data.physical_format;
            if (data.thumbnail_url == "") {
                imgElement.src = "https://static.vecteezy.com/system/resources/previews/013/743/750/original/blank-book-cover-over-png.png";
                document.getElementById('image-url-input').value = "https://static.vecteezy.com/system/resources/previews/013/743/750/original/blank-book-cover-over-png.png";
                messageDiv.style.color = "red";
                messageDiv.style.fontSize = "1rem";
                messageDiv.innerHTML = 'No image available. Please input your own image or use a default book cover.';

            }else{
                document.getElementById('image-url-input').value = data.thumbnail_url;
                document.getElementById('preview').src = data.thumbnail_url;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            messageDiv.style.color = "red";
            messageDiv.style.fontSize = "1rem";
            messageDiv.innerHTML = 'Error: There was an error fetching book data. Please try again.';
        });
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