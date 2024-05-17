<?php
session_start();
include 'connect-edit.php';

$callnum = $_POST['callnum-input'];

$callnumCheckQuery = "SELECT * FROM book WHERE callnumber = '$callnum'";
$callnumResult = $conn->query($callnumCheckQuery);

if ($callnumResult->num_rows > 0) {
    echo "Call number already exists in the database.";
    exit;
}

// Prepare the SQL statement for inserting book details
$sql1 = "INSERT INTO book (callnumber, isbn, title, edition, price, category, publishedyear, publishername, location, remark, status, image) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Prepare the statement
$stmt1 = $conn->prepare($sql1);

// Bind parameters
$stmt1->bind_param("ssssssssssss", $callnum, $isbn, $title, $edition, $price, $category, $year, $publisher, $location, $remark, $status, $image);

// Set parameter values
$title = $_POST['title-input'];
$edition = $_POST['edition-input'];
$price = $_POST['price-input'];
$category = $_POST['category-input'];
$year = $_POST['year-input'];
$publisher = $_POST['publisher-input'];
$location = $_POST['location-input'];
$remark = $_POST['remark-input'];
$status = "Available"; // Assuming default status is "Available"
if (!empty($_FILES['image-input']['name'])) {
    // File upload
    $image = $_POST['image-input-hidden'];
} else {
    // Image URL
    $image = $_POST['image-url-input'];
}
$isbn = $_POST['isbn-input'];
$authorsInput = $_POST['author-input'];

$isbnCheckQuery = "SELECT `book`.`BookID`, `book`.`Title`, `sub_book`.`Authors`, `book`.`Image`, `book`.`ISBN`, `book`.`CallNumber`, 
`book`.`Category`, `book`.`Edition`, `book`.`PublishedYear`, `book`.`PublisherName` FROM `book` JOIN (SELECT `book`.`BookID`, 
`book`.`Title`, GROUP_CONCAT(`author`.Name ORDER BY `author`.AuthorID) AS `Authors`, `book`.`Image`, `book`.`ISBN`, `book`.`CallNumber`, 
`book`.`Category`, `book`.`Edition`, `book`.`PublishedYear`, `book`.`PublisherName` FROM `book` JOIN `author` ON `book`.`BookID` = `author`.`BookID` 
GROUP BY `book`.`BookID`) AS `sub_book` ON `book`.`BookID` = `sub_book`.`BookID` WHERE `book`.`ISBN` = '$isbn'";

$isbnResult = $conn->query($isbnCheckQuery);
if ($isbnResult->num_rows > 0) {
    while ($row = $isbnResult->fetch_assoc()) {
        // Get the database authors without spaces
        $databaseAuthorsWithoutSpaces = preg_replace('/\s+/', '', $row['Authors']);
        // Get the user input authors without spaces
        $userInputAuthorsWithoutSpaces = preg_replace('/\s+/', '', $authorsInput);

        // Check if all details match including authors without spaces
        if ($row['Title'] != $title || 
            $row['Edition'] != $edition || 
            $row['PublishedYear'] != $year || 
            $row['PublisherName'] != $publisher || 
            $databaseAuthorsWithoutSpaces != $userInputAuthorsWithoutSpaces) {
            echo "ISBN already exists in the database with the same details.";
            exit;
        }

    }
}

// Execute the statement for inserting book details
if ($stmt1->execute()) {
    
    $logMessage = "[" . date('Y-m-d H:i:s') . "] User ID: " . $_SESSION['ID'] . " have added a new book: $isbn.";
    file_put_contents('login_log.txt', $logMessage . PHP_EOL, FILE_APPEND);

    // Get the last inserted book ID
    $bookId = $conn->insert_id;

    // Prepare the SQL statement for inserting authors
    $sql2 = "INSERT INTO author (bookid, name) VALUES (?, ?)";
    
    // Prepare the statement
    $stmt2 = $conn->prepare($sql2);

    // Bind parameters
    $stmt2->bind_param("is", $bookId, $authorName);

    // Get authors from form input
    // $authorsInput = $_POST['author-input'];
    $authors = explode(", ", $authorsInput);

    // Insert authors for the book
    foreach ($authors as $author) {
        $authorName = $author;
        $stmt2->execute();
    }

    // Close the author statement
    $stmt2->close();
    echo "New book inserted successfully.";
} else {
    echo "Error: " . $sql1 . "<br>" . $conn->error;
}


// Close the book statement and connection
$stmt1->close();
$conn->close();