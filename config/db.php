<?php
	$dbhost = "127.0.0.1";
	$dbusername = "homestead";
	$dbpassword = "secret";
	$db_name = "d02d95da";
	$con = mysqli_connect("$dbhost", "$dbusername", "$dbpassword");
	mysqli_select_db($con,"$db_name");
	mysqli_set_charset($con, "utf8");
?>