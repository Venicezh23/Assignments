<?php
	$conn = mysqli_connect("localhost", "root", "", "library_system");
	
	if(!$conn){
		die("Error, cannot connect to library system database.");
	}
?>