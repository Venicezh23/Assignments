<?php
session_start();

include 'connect.php';

if (isset($_GET['bookIssuedId'])) {
    $bookIssuedId = $_GET['bookIssuedId'];
    $sql = "SELECT `book_issued`.`UserID`, `book_issued`.`BookID`, `book`.`Price` FROM book_issued JOIN `book` ON `book`.`BookID` = `book_issued`.`BookID` WHERE `book_issued`.`bookissuedid` = :bookIssuedId AND `book_issued`.`DateReturn` <> '0000-00-00 00:00:00'";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':bookIssuedId', $bookIssuedId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($result);
} else {
    echo json_encode(array()); // Return an empty JSON object if bookIssuedId is not set
}
?>
