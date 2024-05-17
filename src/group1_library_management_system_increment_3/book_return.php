<?php

include 'connect-edit.php';
session_start();

$user_id = $_POST['user-id'];
$book_id = $_POST['book-id'];
$book_issued_id = $_POST['book-issued-id'];
$reserve_exist = $_POST['reserve-exist'];
$currentDate = date('Y-m-d H:i:s');

// Update book_issued table to mark the return date
$sql = "UPDATE `book_issued` SET `DateReturn` = '$currentDate' WHERE BookIssuedID = '$book_issued_id' AND UserID = '$user_id'";

if ($conn->query($sql) === TRUE) {
    if ($reserve_exist) {
        // Select the user with the highest priority for reservation
        $sql1 = "SELECT UserID FROM `book_reserve` WHERE BookID='$book_id' AND DateReserve <> '0000-00-00 00:00:00' AND AllowBorrow='0' AND Borrowed='0' ORDER BY DateReserve ASC LIMIT 1";
        $result = $conn->query($sql1);

        if ($result) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $user_with_highest_priority = $row['UserID'];

                // Update book_reserve to allow borrow for the user with highest priority
                $sql2 = "UPDATE book_reserve SET allowborrow='1' WHERE bookid='$book_id' AND userid='$user_with_highest_priority' AND DateReserve<>'0000-00-00 00:00:00' AND AllowBorrow='0' AND Borrowed='0'";

                if ($conn->query($sql2) === TRUE) {
                    // Update book status to 'Reserved'
                    $sql3 = "UPDATE book SET status='Reserved' WHERE bookid='$book_id'";
                    
                    if ($conn->query($sql3) === TRUE) {
                        echo "Book returned successfully.";
                        $logMessage = "[" . date('Y-m-d H:i:s') . "] User ID: " . $_SESSION['ID'] . " have returned a book: $book_id.";
                        file_put_contents('login_log.txt', $logMessage . PHP_EOL, FILE_APPEND);
                    } else {
                        echo "Error: " . $sql3 . "<br>" . $conn->error;
                    }
                } else {
                    echo "Error: " . $sql2 . "<br>" . $conn->error;
                }
            } else {
                echo "No user found with reservation for book.";
            }
        } else {
            echo "Error: " . $sql1 . "<br>" . $conn->error;
        }
    } else {
        // Update book status to 'Available' since there's no reservation
        $sql2 = "UPDATE book SET status='Available' WHERE bookid='$book_id'";
        
        if ($conn->query($sql2) === TRUE) {
            echo "Book returned successfully.";
        } else {
            echo "Error: " . $sql2 . "<br>" . $conn->error;
        }
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
