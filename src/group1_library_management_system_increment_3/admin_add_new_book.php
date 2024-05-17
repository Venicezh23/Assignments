<?php
    include 'connect.php';
    session_start();

    require 'bookscanner/src/Bookscanner.php';
    use imonroe\bookscanner\Bookscanner;

    $isbn = $title = $author = $publisher = $publication_date = $number_of_pages = $physical_format =  '';
    $thumbnail_url ="https://cdn-icons-png.freepik.com/256/3405/3405403.png?ga=GA1.1.1201796896.1698075133&";

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
    <title>Admin - Add Book</title>
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
                                        <button type="button" class="picture-box" onclick="document.getElementById('image-input').click(); previewImage()">
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
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
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
</body>

</html>w