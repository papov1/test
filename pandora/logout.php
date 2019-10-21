<?php
	session_start();
	unset($_SESSION['admin_logged_in']);
	unset($_SESSION['userData']);
	unset($_SESSION['userID']);
	unset($_SESSION['userFirstname']);
	unset($_SESSION['userLastname']);
	unset($_SESSION['token']);
	$login_status = 'not logged';
	header("Location: index.php");
?>