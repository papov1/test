<?php
	$dbhost = "localhost";
	$dbusername = "d02d95da";
	$dbpassword = "mSswG4ehFHyk93Ao";
	$db_name = "d02d95da";
	$con = mysqli_connect("$dbhost", "$dbusername", "$dbpassword");
	mysqli_select_db($con,"$db_name");
	mysqli_set_charset($con, "utf8");
?>