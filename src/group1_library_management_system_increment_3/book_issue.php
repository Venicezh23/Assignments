<?php
include 'connect-edit.php';

session_start();
function isWeekend($date) {
    $dayOfWeek = date('w', strtotime($date));
    if ($dayOfWeek == 0 || $dayOfWeek == 6) {
        return true;
    } else {
        return false;
    }
}

// Function to check if a date is a holiday
function isHoliday($date, $holidays) {
    return in_array(date('Y-m-d', strtotime($date)), $holidays);
}

function calculateReturnDate($holidays) {
    $currentDate = strtotime(date('Y-m-d H:i:s')); // Get the current timestamp
    $daysToAdd = 0;

    while ($daysToAdd < 14) { // Loop until 14 days are added
        $date = date('Y-m-d', $currentDate);

        // Check if the date falls on a weekend or holiday
        if (isWeekend($date) || isHoliday($date, $holidays)) {
            // If it does, move to the next day
            $currentDate = strtotime('+1 day', $currentDate);
            continue;
        }

        // Increment the days added excluding weekends and holidays
        $daysToAdd++;
        // Move to the next day
        $currentDate = strtotime('+1 day', $currentDate);
    }

    // Calculate the new date by adding only the non-holiday and non-weekend days
    return date('Y-m-d H:i:s', $currentDate);
}                    

// Define your array of holidays
$holidays = array(
    '2024-01-01',
    '2024-04-26',
    '2024-05-01',
    '2024-05-22',
    '2024-05-23',
    '2024-05-30',
    '2024-05-31',
    '2024-06-03',
    '2024-06-04',
    '2024-06-16',
    '2024-06-17'
);

$user_id = $_POST['user-id'];
$book_id = $_POST['book-id'];
$reserve_exist = $_POST['reserve-exist'];
$currentDate = date('Y-m-d H:i:s');
$newDate2 = calculateReturnDate($holidays);

$sql = "INSERT INTO book_issued (userid, bookid, dateborrow, datereturn, duedate) 
        VALUES ('$user_id', '$book_id', '$currentDate', '0000-00-00 00:00:00', '$newDate2')";

if ($reserve_exist){
    $sql = "UPDATE book_reserve SET allowborrow = '0',borrowed = '1' WHERE bookid='$book_id' AND userid='$user_id' AND datereserve <> '0000-00-00 00:00:00'";
    if ($conn->query($sql) === TRUE) {
        // $sql2 = "UPDATE book_reserve SET allowborrow = '0',borrowed = '0' WHERE bookid='$book_id' AND userid<>'$user_id' AND datereserve <> '0000-00-00 00:00:00'";
        // if ($conn->query($sql2) === TRUE) {
        // }
        // else{
        //     echo "Error: ". $sql2 . "<br>" . $conn->error;
        // }
        $sql3 = "INSERT INTO book_issued (userid, bookid, dateborrow, datereturn, duedate) VALUES ('$user_id', '$book_id', '$currentDate', '0000-00-00 00:00:00', '$newDate2')";
        if ($conn->query($sql3) === TRUE) {
            $sql4 = "UPDATE book SET status='Unavailable' WHERE bookid='$book_id'";
            if ($conn->query($sql4) === TRUE) {
                echo "Book borrowed successfully.";
            }
            else{
                echo "Error: ". $sql4 . "<br>" . $conn->error;
            }
        }
        else{
            echo "Error: ". $sql3 . "<br>" . $conn->error;
        }
    } else {
        echo "Error: ". $sql . "<br>" . $conn->error;
    }
}
else{
    $sql3 = "INSERT INTO book_issued (userid, bookid, dateborrow, datereturn, duedate) VALUES ('$user_id', '$book_id', '$currentDate', '0000-00-00 00:00:00', '$newDate2')";
    if ($conn->query($sql3) === TRUE) {
        $sql4 = "UPDATE book SET status='Unavailable' WHERE bookid='$book_id'";
        if ($conn->query($sql4) === TRUE) {
            echo "Book borrowed successfully.";
            $logMessage = "[" . date('Y-m-d H:i:s') . "] User ID: " . $_SESSION['ID'] . " have borrow a book: $book_id.";
            file_put_contents('login_log.txt', $logMessage . PHP_EOL, FILE_APPEND);
        }
        else{
            echo "Error: ". $sql4 . "<br>" . $conn->error;
        }
    }
    else{
        echo "Error: ". $sql3 . "<br>" . $conn->error;
    }
}

$conn->close();
