<?php
require 'bookscanner/src/Bookscanner.php'; // Adjust the path as needed

use imonroe\bookscanner\Bookscanner;

if (isset($_GET['isbn'])) {
    $isbn_string = $_GET['isbn'];

    // Get book data based on the ISBN
    $isbn_data = Bookscanner::get_isbn_data($isbn_string);

    // Return book data as JSON
    echo json_encode($isbn_data);
}
?>
