<?php

session_start(); // Start the session

include 'connect-edit.php';

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

function calculateReturnDate($holidays, $dueDate) {
    // $currentDate = strtotime(date('Y-m-d H:i:s')); // Get the current timestamp
    $currentDate = strtotime($dueDate);
    $daysToAdd = 0;

    while ($daysToAdd < $_SESSION['ExtendDay']) {
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

$book_id = $_POST['book-issued-id'];
$sql = "SELECT DueDate FROM book_issued WHERE BookIssuedID='$book_id'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $dueDateFromDatabase = $row['DueDate'];

    // Use the due date to calculate the new return date
    $newReturnDate = calculateReturnDate($holidays, $dueDateFromDatabase);
    
    $sql2 = "UPDATE `book_issued` SET `DueDate` = '$newReturnDate' WHERE BookIssuedID = '$book_id'";

    if ($conn->query($sql2) === TRUE) {
        echo "Book extended successfully.";
        $logMessage = "[" . date('Y-m-d H:i:s') . "] User ID: " . $_SESSION['ID'] . " have extend their duedate on BookIssuedID: $book_id.";
        file_put_contents('login_log.txt', $logMessage . PHP_EOL, FILE_APPEND);
    } else {
        echo "Error: " . $sql2 . "<br>" . $conn->error;
    }
} else {
    echo "Error: No due date found for BookIssuedID $bookIssuedID";
}

$conn->close();

?>
