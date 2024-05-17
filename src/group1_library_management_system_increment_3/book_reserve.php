<?php
include 'connect-edit.php';

session_start();

$user_id = $_POST['user-id'];
$book_id = $_POST['book-id'];
$currentDate = date('Y-m-d H:i:s');

$sql1 = "SELECT * FROM book_reserve WHERE BookID='30' AND DateReserve <> '0000-00-00 00:00:00' AND AllowBorrow = '0' AND Borrowed = '0';";

$result = $conn->query($sql1);

if ($result) {
    if ($result->num_rows > 0) {
        $sql2 = "SELECT Priority FROM book_reserve WHERE BookID='$book_id' AND DateReserve <> '0000-00-00 00:00:00' AND AllowBorrow = '0' AND Borrowed = '0' ORDER BY DateReserve DESC LIMIT 1;";
        $result2 = $conn->query($sql2);

        if ($result2) {
            $row = $result2->fetch_assoc();
            
            $priority = $row['Priority']+1;
            
            $sql3 = "INSERT INTO book_reserve (userid, bookid, datereserve, allowborrow, borrowed, priority) VALUES ('$user_id', '$book_id', '$currentDate', '0', '0', '$priority')";
        
            if ($conn->query($sql3) === TRUE) {
                echo "Book reserved successfully.";
                $logMessage = "[" . date('Y-m-d H:i:s') . "] User ID: " . $_SESSION['ID'] . " have made a reservation: $book_id.";
                file_put_contents('login_log.txt', $logMessage . PHP_EOL, FILE_APPEND);
            } else {
                echo "Error: ". $sql3 . "<br>" . $conn->error;
            }
        } else {
            echo "Error: ". $sql2 . "<br>" . $conn->error;
        }
    } else {
        
        $sql = "INSERT INTO book_reserve (userid, bookid, datereserve, allowborrow, borrowed, priority) VALUES ('$user_id', '$book_id', '$currentDate', '0', '0', '1')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Book reserved successfully.";
            $logMessage = "[" . date('Y-m-d H:i:s') . "] User ID: " . $_SESSION['ID'] . " have made a reservation: $book_id.";
            file_put_contents('login_log.txt', $logMessage . PHP_EOL, FILE_APPEND);
        } else {
            echo "Error: ". $sql . "<br>" . $conn->error;
        }
    }
} else {
    echo "Error: ". $sql1 . "<br>" . $conn->error;
}

$conn->close();
