<?php
	$conn_mysqli = mysqli_connect("localhost", "root", "", "library_system");
	
	if(!$conn_mysqli){
		die("Error, cannot connect to library system database.");
	}
?>
