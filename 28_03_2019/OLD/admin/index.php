<?php
include_once 'classes/gpConfig.php';
include_once 'classes/User.php';
include_once 'classes/general.php';

$login_status = 'not logged';

$authUrl = $gClient->createAuthUrl();
$output = '<a href="'.filter_var($authUrl, FILTER_SANITIZE_URL).'"><img src="images/glogin.png" alt=""/></a>';
$login_status = 'not logged';

if(isset($_GET['code'])){
	$gClient->authenticate($_GET['code']);
	$_SESSION['token'] = $gClient->getAccessToken();
	header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['token'])) {
	$gClient->setAccessToken($_SESSION['token']);
}

if ($gClient->getAccessToken()) {
	//get user data from google
	$gpUserProfile = $google_oauthV2->userinfo->get();
	
	$user = new User();
	
	$gpUserData = array(
		'oauth_provider'=> 'google',
		'oauth_uid'     => $gpUserProfile['id'],
		'first_name'    => $gpUserProfile['given_name'],
		'last_name'     => $gpUserProfile['family_name'],
		'email'         => $gpUserProfile['email'],
		'gender'        => $gpUserProfile['gender'],
		'locale'        => $gpUserProfile['locale'],
		'picture'       => $gpUserProfile['picture'],
		'link'          => $gpUserProfile['link']
	);

		//check if user is allowed to login
		if($user->checkIfUserAllowed($gpUserProfile['email']) == 'allowed'){
			$userData = $user->checkUser($gpUserData);
			
			if(!empty($userData))
			{
				//Storing user data into session
				$_SESSION['userID'] = $userData['id'];

				$login_status = 'logged';
				$_SESSION['admin_logged_in'] = 'true';
			}
			else{
				//section not-logged - error
				$output = '<h3 style="color:red">Some problem occurred, please try again.</h3>';
				$login_status = 'login error';
			}
		}
		else{
			//user not found
			$login_status = 'not allowed';	
			$_SESSION['userID'] = 'not allowed';
			unset($_SESSION['admin_logged_in']);
			unset($_SESSION['userFirstname']);
			unset($_SESSION['userLastname']);
		}
	
} 
else {
	//section not-logged - before login
	$authUrl = $gClient->createAuthUrl();
	$output = '<a href="'.filter_var($authUrl, FILTER_SANITIZE_URL).'"><img src="images/glogin.png" alt=""/></a>';
	$login_status = 'not logged';
}

// on page init
$incorrect_password = false;
if(isset($_POST['form_submitted'])){
	$user_email = $_POST['user_email'];
	$user_password = $_POST['user_password'];

	$login_status = login_user($user_email,$user_password);
	if($login_status == 'logged'){
		//logged
		$user_id = $_SESSION['userID'];
		$user_superadmin = check_if_superadmin($user_id);
	}
	elseif($login_status == 'not logged'){
		$incorrect_password = true;
	}
	else{
		$login_status = 'regular login not allowed';
	}
}

// on page reload
if (isset($_SESSION['admin_logged_in'])) {
	$login_status = 'logged';
	$user_id = $_SESSION['userID'];
	$user_superadmin = check_if_superadmin($user_id);
}

//check if user blocked
if(isset($_SESSION['userID'])){
	if($_SESSION['userID'] != 'not allowed'){
		$user_details = getUserDetails($_SESSION['userID']);
		if($user_details["status"] == 'blocked'){
			$login_status = 'blocked';
			unset($_SESSION['admin_logged_in']);
			unset($_SESSION['userFirstname']);
			unset($_SESSION['userLastname']);
		}
	}
	else{
		$login_status = $_SESSION['userID'];
	}
	
}


//setting default DE lang
if(!isset($_SESSION['lang'])){
	$_SESSION['lang'] = 1;
}


?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>SDB Admin</title>
		<link rel="stylesheet" href="css/global.css">
		<link rel="stylesheet" href="css/pe-icon-7-stroke.css">
		<link rel="stylesheet" href="css/sweetalert2.css">
		<link rel="stylesheet" href="css/datepicker.css">
		<link rel="stylesheet" href="css/tagify.css">
		<link rel="stylesheet" href="css/dropzone.css">
		<link rel="stylesheet" href="css/jquery.auto-complete.css">
		<!--<link rel="stylesheet" href="css/animate.css">-->
		<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="js/custom.js"></script>
		<script type="text/javascript"  src="js/sweetalert2.min.js"></script>
		<script type="text/javascript" src="js/datepicker.js"></script>
		<script type="text/javascript" src="js/datepicker.de-DE.js"></script>
		<script type="text/javascript" src="js/jQuery.tagify.js"></script>
		<script type="text/javascript" src="js/dropzone.js"></script>
		<script type="text/javascript" src="js/jquery.auto-complete.js"></script>
		<script type="text/javascript">
			$(document).ready(function () {
				Dropzone.autoDiscover = false;
			});
		</script>
	</head>

	<body <?php if($login_status != 'logged'){ echo 'id="login"'; } ?> >
		<?php

		if(($login_status == 'not logged')||($login_status == 'not allowed')||($login_status == 'regular login not allowed')||($login_status == 'blocked')){
			?>
				<div class="main_container">

					<div class="login_container">
						<div class="logo_container">
							<img src="images/logo_login.png">
						</div>
						<div class="login_box">
							<span class="login_title">
								Backend Login
							</span>
							<div class="login_form_container">
								<form action="index.php" method="POST" id="classic_login_form">
									<?php
										if($incorrect_password == true){
											echo '
												<div class="login_failed"><span>Login failed: make sure your e-mail and password are correct.</span></div>
											';
										}
										if($login_status == 'not allowed'){
											echo '
												<div class="login_failed"><span>Login failed: this e-mail address is not allowed.</span></div>
											';
										}
										if($login_status == 'regular login not allowed'){
											echo '
												<div class="login_failed"><span>Login failed: this login method is not allowed for selected account.</span></div>
											';
										}
										if($login_status == 'blocked'){
											echo '
												<div class="login_failed"><span>Login failed: this account is blocked.</span></div>
											';
										}
									?>
									<div class="form-line">
										<input type="text" name="user_email" id="user_email">
										<label for="user_email">E-Mail adress</label>
										<div class="form_warning_msg">E-mail field can not be empty</div>
									</div>
									<div class="form-line">
										<input type="password" name="user_password" id="user_password">
										<label for="user_password">Password</label>
										<div class="form_warning_msg">Password field can not be empty</div>
									</div>
									<input type="hidden" name="form_submitted" value="1" />
									<button type="submit" id="classic_login_form_button" form="classic_login_form" value="Anmelden"><i class="pe-7s-door-lock"></i> Anmelden</button>
								</form>

								<div class="login_form_or">
									<span>or</span>
								</div>
								<a id="google_login_form_button" href="<?php echo filter_var($authUrl, FILTER_SANITIZE_URL) ?>"><i class="pe-7s-user"></i> <span>Mit google anmelden</span></a>
								<a id="forgot_password_button" href="#">Forgot password?</a>
							</div>
						</div>
					</div>
					
				</div>
			<?php
		}

		if($login_status == 'logged'){
			?>
				<div id="navbar">
					<div class="logo">
						<img src="images/logo_login.png">
					</div>
					<div class="navbar_right">
						<div class="page_title">
							Backend<br> für Sicherheits- & Produktdatenblätter
						</div>
						<div class="header_buttons">
							<a href="#" class="add_new_repository_item_button"><i class="pe-7s-plus"></i> <span>Neues Datenblatt hinzufügen</span></a>
							<div class="select_lang_button">
								<i class="pe-7s-global"></i> <span><?php echo getLangName($_SESSION['lang']); ?></span>
								<div class="langs_container">
									<div class="dmc_arrow_up"></div>
									<div class="langs_content">
										<?php
											$langs = getLangsNames();
											foreach ($langs as $lang) {
												if($lang["language_id"] != $_SESSION['lang']){
													echo '<div class="lang_name" data-lang-id="'.$lang["language_id"].'">'.$lang["lang_name"].'</div>';
												}
											}
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="body_content">
					<?php include("includes/common/left_column.php"); ?>
					<div class="center_column">
						<?php include("includes/pages/repository/main.php"); ?>
						<div id="repository_remove_batch"><i class="pe-7s-trash"></i><span>Remove selected</span></div>
					</div>
				</div>
			<?php
		}

		?>
	</body>
</html>