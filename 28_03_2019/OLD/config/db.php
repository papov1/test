<?php
	$dbhost = "localhost";
	$dbusername = "d02c5f13";
	$dbpassword = "MSz3knAftkKTcdQw";
	$db_name = "d02c5f13";
	$con = mysqli_connect("$dbhost", "$dbusername", "$dbpassword");
	mysqli_select_db($con,"$db_name");
	mysqli_set_charset($con, "utf8");
?>