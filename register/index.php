<?php
include_once 'classes/general.php';
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>SDB Register</title>
		<link rel="stylesheet" href="css/global.css">
		<link rel="stylesheet" href="css/pe-icon-7-stroke.css">
		<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="js/custom.js"></script>
	</head>

	<body id="login">
		<div class="main_container">

			<div class="login_container">
				<div class="logo_container">
					<img src="images/logo_login.png">
				</div>
				<div class="login_box">
					<span class="login_title">
						Register for back end
					</span>
					<div class="login_form_container">
						<form action="index.php" method="POST" id="classic_login_form">

							<div class="login_failed_1"><span>You are already in the queue. Please wait for adminitrator to validate your request.</span></div>
							<div class="login_failed_2"><span>You are already registered. No need to send request anymore.</span></div>

							<div class="form-line">
								<input type="text" name="user_name" id="user_name">
								<label for="user_name">Name:</label>
								<div class="form_warning_msg">This field can not be empty</div>
							</div>
							<div class="form-line">
								<input type="text" name="user_lastname" id="user_lastname">
								<label for="user_lastname">Surname:</label>
								<div class="form_warning_msg">This field can not be empty</div>
							</div>
							<div class="form-line">
								<input type="email" name="user_email" id="user_email">
								<label for="user_email">E-Mail adress [gmail]:</label>
								<div class="form_warning_msg">E-mail field can not be empty</div>
							</div>
							<input type="hidden" name="form_submitted" value="1" />
							<button type="submit" id="google_login_form_button" form="classic_login_form" value="Anmelden"><i class="pe-7s-user"></i> Register for access</button>
						</form>
					</div>
				</div>
			</div>

			<div class="added_confirmation">
				<span class="vc_icon_element-icon pe-7s-check" style="font-size:170px"></span><br>
				Your request will be handled by an administrator.<br>
				You will receive an e-mail confirmation, once you have been approved for access.<br>
			</div>
					
		</div>
	</body>
</html>