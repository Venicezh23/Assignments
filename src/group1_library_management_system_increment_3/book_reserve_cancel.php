<?php

session_start();

include 'connect-edit.php';

$user_id = $_POST['user-id'];
$book_id = $_POST['book-reserve-cancel-id'];

$sql = "UPDATE `book_reserve` SET `DateReserve` = '0000-00-00 00:00:00', `Priority` = '0' WHERE BookID = '$book_id' AND UserID = '$user_id' AND DateReserve <> '0000-00-00 00:00:00' AND AllowBorrow = '0' AND Borrowed = '0'";

if ($conn->query($sql) === TRUE) {
    echo "Book reservation cancelled successfully.";
    $logMessage = "[" . date('Y-m-d H:i:s') . "] User ID: " . $_SESSION['ID'] . " have cancel their reservation: $book_id.";
    file_put_contents('login_log.txt', $logMessage . PHP_EOL, FILE_APPEND);
}
else{
    echo "Error: ". $sql . "<br>" . $conn->error;
}

$conn->close();