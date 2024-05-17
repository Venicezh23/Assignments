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
        $image = $_POST['image'];
        $title = $_POST['title'];
        $author = $_POST['author'];
        $isbn = $_POST['isbn'];
        $publisher = $_POST['publisher'];
        $year = $_POST['year'];
        $category = $_POST['category'];
        $edition = $_POST['edition'];
        $location = $_POST['location'];
        $price = $_POST['price'];
        $callnumber = $_POST['callnumber'];
        $remark = $_POST['remark'];
        $id = $_POST['bookid'];
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Admin - Update Book</title>
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
                        <div class="header-text">Update Book</div>
                    </div>

                    <div class="align-items-left justify-content-left">
                        <div class="bg-light rounded h-100 p-4">
                            <form id="book-form" action="query_update_book.php" method="post" class="row g-2">
                                <input type="hidden" id="book-id" name="book-id" value="<?php echo $id; ?>">
                                <div class="col-8" style="margin-right: 10px;">
                                    <div class="mb-3">
                                        <label class="label-text">Title</label>
                                        <input type="text" placeholder="Enter the title" class="input-box" id="title-input" name="title-input" value="<?php echo $title; ?>" required></input>
                                    </div>
                                    <div class="mb-3">
                                        <label class="label-text">ISBN</label>
                                        <input type="text" placeholder="Enter the ISBN" class="input-box" id="isbn-input" name="isbn-input" value="<?php echo $isbn; ?>" required></input>
                                    </div>
                                    <div class="mb-3 row g-2">
                                        <div class="col">
                                            <label class="label-text">Authors</label>
                                            <input type="text" placeholder="Enter the authors" class="input-box" id="author-input" name="author-input" value="<?php echo $author; ?>" required></input>
                                        </div>
                                        <div class="col">
                                            <label class="label-text">Edition</label>
                                            <input type="text" placeholder="Enter the edition" class="input-box" id="edition-input" name="edition-input" value="<?php echo $edition; ?>" required></input>
                                        </div>
                                    </div>
                                    <div class="mb-3 row g-2">
                                        <div class="col">
                                            <label class="label-text">Publisher</label>
                                            <input type="text" placeholder="Enter the publisher" class="input-box" id="publisher-input" name="publisher-input" value="<?php echo $publisher; ?>" required></input>
                                        </div>
                                        <div class="col">
                                            <label class="label-text">Year</label>
                                            <input type="text" placeholder="Enter the year" class="input-box" id="year-input" name="year-input" value="<?php echo $year; ?>" required></input>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="label-text">Call Number</label>
                                        <input type="text" placeholder="Enter the call number" class="input-box" id="callnum-input" name="callnum-input" value="<?php echo $callnumber; ?>" required></input>
                                    </div>
                                    <div class="mb-3 row g-2">
                                        <div class="col">
                                            <label class="label-text">Price (RM)</label>
                                            <input type="text" placeholder="Enter the price" class="input-box" id="price-input" name="price-input" value="<?php echo $price; ?>" required></input>
                                        </div>
                                        <div class="col">
                                            <label class="label-text">Location</label>
                                            <select id="location-input" name="location-input" class="select-box" required>
                                            <option value="" disabled>Select Location</option>
                                                <option value="A" <?php if ($location == 'A') echo 'selected'; ?>>A</option>
                                                <option value="B" <?php if ($location == 'B') echo 'selected'; ?>>B</option>
                                                <option value="C" <?php if ($location == 'C') echo 'selected'; ?>>C</option>
                                                <option value="D" <?php if ($location == 'D') echo 'selected'; ?>>D</option>
                                                <option value="E" <?php if ($location == 'E') echo 'selected'; ?>>E</option>
                                                <option value="F" <?php if ($location == 'F') echo 'selected'; ?>>F</option>
                                                <option value="G" <?php if ($location == 'G') echo 'selected'; ?>>G</option>
                                                <option value="H" <?php if ($location == 'H') echo 'selected'; ?>>H</option>
                                                <option value="I" <?php if ($location == 'I') echo 'selected'; ?>>I</option>
                                                <option value="J" <?php if ($location == 'J') echo 'selected'; ?>>J</option>
                                                <option value="K" <?php if ($location == 'K') echo 'selected'; ?>>K</option>
                                                <option value="L" <?php if ($location == 'L') echo 'selected'; ?>>L</option>
                                                <option value="M" <?php if ($location == 'M') echo 'selected'; ?>>M</option>
                                                <option value="N" <?php if ($location == 'N') echo 'selected'; ?>>N</option>
                                                <option value="O" <?php if ($location == 'O') echo 'selected'; ?>>O</option>
                                                <option value="P" <?php if ($location == 'P') echo 'selected'; ?>>P</option>
                                                <option value="Q" <?php if ($location == 'Q') echo 'selected'; ?>>Q</option>
                                                <option value="R" <?php if ($location == 'R') echo 'selected'; ?>>R</option>
                                                <option value="S" <?php if ($location == 'S') echo 'selected'; ?>>S</option>
                                                <option value="T" <?php if ($location == 'T') echo 'selected'; ?>>T</option>
                                                <option value="U" <?php if ($location == 'U') echo 'selected'; ?>>U</option>
                                                <option value="V" <?php if ($location == 'V') echo 'selected'; ?>>V</option>
                                                <option value="W" <?php if ($location == 'W') echo 'selected'; ?>>W</option>
                                                <option value="X" <?php if ($location == 'X') echo 'selected'; ?>>X</option>
                                                <option value="Y" <?php if ($location == 'Y') echo 'selected'; ?>>Y</option>
                                                <option value="Z" <?php if ($location == 'Z') echo 'selected'; ?>>Z</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 row g-2">
                                        <label class="label-text">Category</label>
                                        <select id="category-input" name="category-input" class="select-box" required>
                                            <option value="" disabled>Select Category</option>
                                            <option value="Computer Science" <?php if ($category == 'Computer Science') echo 'selected'; ?>>Computer Science</option>
                                            <option value="Business and Analytics" <?php if ($category == 'Business and Analytics') echo 'selected'; ?>>Business and Analytics</option>
                                            <option value="Finance and Financial Technology" <?php if ($category == 'Finance and Financial Technology') echo 'selected'; ?>>Finance and Financial Technology</option>
                                            <option value="Aeronautics and Astronautics" <?php if ($category == 'Aeronautics and Astronautics') echo 'selected'; ?>>Aeronautics and Astronautics</option>
                                            <option value="Electrical and Electronic Engineering" <?php if ($category == 'Electrical and Electronic Engineering') echo 'selected'; ?>>Electrical and Electronic Engineering</option>
                                            <option value="Mechanical Engineering" <?php if ($category == 'Mechanical Engineering') echo 'selected'; ?>>Mechanical Engineering</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="mb-3">
                                        <label class="label-text">Picture</label>
                                        <button class="picture-box" onclick="document.getElementById('image-input').click(); previewImage()">
                                            <img id="preview" style="height: 80px;" src="<?php echo $image; ?>"/>
                                        </button>
                                        <input type="file" id="image-input" style="display: none;" accept="image/*" onchange="previewImage();validateImage()"  name="image-input" <?php if(!empty($image)) echo 'value="'.$image.'"'; ?>></input>
                                        <input type="hidden" id="image-input-hidden" name="image-input-hidden" value="<?php echo $image; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="label-text">Remarks</label>
                                        <textarea placeholder="Enter the remarks" class="text-area-box" id="remark-input" name="remark-input"><?php echo $remark; ?></textarea>
                                    </div>
                                </div>
                                <div id="message" class="message-prompt"></div>
                                <input type="submit" value="CONFIRM" class="comfirmBtn">
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
            
            fetch('query_update_book.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                var messageDiv = document.getElementById('message');
                
                if (data.trim() === 'Book updated successfully.') {
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