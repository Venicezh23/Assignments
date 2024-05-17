<?php
session_start();

include 'connect-edit.php';

// Prepare the SQL statement for updating book details
$sql1 = "UPDATE book SET CallNumber=?, ISBN=?, Title=?, edition=?, Price=?, Category=?, PublishedYear=?, PublisherName=?, Location=?, Remark=?, Image=? WHERE BookID=?";

// Prepare the statement
$stmt1 = $conn->prepare($sql1);

// Bind parameters
$stmt1->bind_param("sssssssssssi", $callnum, $isbn, $title, $edition, $price, $category, $year, $publisher, $location, $remark, $image, $id);

// Set parameter values
$title = $_POST['title-input'];
$authorsInput = $_POST['author-input'];
$authorsInput = str_replace(",", ", ", $authorsInput);
$authors = explode(", ", $authorsInput);
$isbn = $_POST['isbn-input'];
$callnum = $_POST['callnum-input'];
$remark = $_POST['remark-input'];
$publisher = $_POST['publisher-input'];
$year = $_POST['year-input'];
$edition = $_POST['edition-input'];
$price = $_POST['price-input'];
$category = $_POST['category-input'];
$location = $_POST['location-input'];
$image = $_POST['image-input-hidden'];
$id = $_POST['book-id'];

$logMessage = "[" . date('Y-m-d H:i:s') . "] User ID: " . $_SESSION['ID'] . " have updated User $id's details.";
file_put_contents('login_log.txt', $logMessage . PHP_EOL, FILE_APPEND);

// Execute the statement for updating book details
if ($stmt1->execute()) {
    // Delete existing author records for the book
    $sql2 = "DELETE FROM author WHERE BookID=?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $id);
    $stmt2->execute();
    $stmt2->close();

    // Insert new author records for the book
    $sql3 = "INSERT INTO author (bookid, name) VALUES (?, ?)";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->bind_param("is", $id, $authorName);

    foreach ($authors as $author) {
        $authorName = $author;
        $stmt3->execute();
    }
    $stmt3->close();

    echo "Book updated successfully.";
} else {
    echo "Error updating record: " . $conn->error;
}

// Close the book statement and connection
$stmt1->close();
$conn->close();
?>