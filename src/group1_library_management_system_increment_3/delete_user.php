<?php
session_start();

include 'connect-edit.php';

$id = mysqli_real_escape_string($conn, $_POST['userid']);
$status = mysqli_real_escape_string($conn, $_POST['status']);

$sql = "UPDATE user SET UserTypeID='$status' WHERE UserID='$id'";

if (mysqli_query($conn, $sql)) {
    echo "Record deleted successfully.";
    header("Location: user_database.php");
    exit();
  } else {
    echo "Error deleting record: " . mysqli_error($conn);
  }
  
  mysqli_close($conn);
?>