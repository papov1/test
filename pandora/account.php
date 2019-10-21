<?php session_start();
	if (isset($_SESSION['admin_logged_in'])) {
		//logged
		include_once 'classes/general.php';
		
		if(check_if_superadmin($_SESSION['userID']) == 0){
			$proper_url = '/pandora/account.php?user='.$_SESSION['userID'];
			if($_SERVER['REQUEST_URI'] != $proper_url){
				?>
					<script type="text/javascript">
						window.location.href = "account.php?user=<?php echo $_SESSION['userID']; ?>";
					</script>
				<?php
			}
			else{
				?>
				<html>
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						<title>SDB Admin</title>
						<link rel="stylesheet" href="css/global.css">
						<link rel="stylesheet" href="css/pe-icon-7-stroke.css">
						<link rel="stylesheet" href="css/sweetalert2.css">
						<!--<link rel="stylesheet" href="css/animate.css">-->
						<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
						<script type="text/javascript" src="js/custom.js"></script>
						<script type="text/javascript"  src="js/sweetalert2.min.js"></script>
						<script type="text/javascript"  src="js/autosize.min.js"></script>
					</head>

					<body id="translations">
						<div id="navbar">
							<div class="logo">
								<img src="images/logo_login.png">
							</div>
							<div class="navbar_right">
								<div class="page_title">
									<?php 
										echo 'Wählen Sie Ihr gewünschtes<br>Anmeldeverfahren:';
									?>
								</div>
								<div class="header_buttons">
								</div>
							</div>
						</div>
						<div class="body_content">
							<?php include("includes/common/left_column.php"); ?>
							<div class="center_column">
								<?php 
									include("includes/pages/account/edit_user.php");
								?>
							</div>
						</div>
						<div id="lang_result"></div>
					</body>
				</html>
				<?php
			}
		}
		else
		{
			?>
			<html>
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					<title>SDB Admin</title>
					<link rel="stylesheet" href="css/global.css">
					<link rel="stylesheet" href="css/pe-icon-7-stroke.css">
					<link rel="stylesheet" href="css/sweetalert2.css">
					<!--<link rel="stylesheet" href="css/animate.css">-->
					<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
					<script type="text/javascript" src="js/custom.js"></script>
					<script type="text/javascript"  src="js/sweetalert2.min.js"></script>
					<script type="text/javascript"  src="js/autosize.min.js"></script>
				</head>

				<body id="translations">
					<div id="navbar">
						<div class="logo">
							<img src="images/logo_login.png">
						</div>
						<div class="navbar_right">
							<div class="page_title">
								<?php 
								if (isset($_GET['user'])) {
									echo 'Wählen Sie Ihr gewünschtes<br>Anmeldeverfahren:';
								}
								else{
									echo 'Admin-Panel<br>AL-User Verwaltung:';
								}
								?>
							</div>
							<div class="header_buttons">
								<?php 
								if (isset($_GET['user'])) {

								}
								else{
									$users_approve_counter = count(getUsersToApprove());
									if($users_approve_counter == 0){ 
										$approve_notification = 'style="display:none;"';
									}
									else{
										$approve_notification = 'style="display:block;"';
									}
									echo '
										<div class="upload-btn-wrapper-2">
											<form enctype="multipart/form-data" id="fupForm" >
												<input id="batch_upload" name="batch_upload" type="file" accept=".csv,text/csv" />
												<a href="#" class="batch_action_button"><i class="pe-7s-photo-gallery"></i> <span>Batch action</span></a>
											</form>
										</div>
										<a href="#" class="approve_accounts_button"><i class="pe-7s-users"></i> <span>Approve accounts</span> <div class="users_approve_counter" '.$approve_notification.'>'.$users_approve_counter.'</div> </a>
									';
								}
								?>
								
							</div>
						</div>
					</div>
					<div class="body_content">
						<?php include("includes/common/left_column.php"); ?>
						<div class="center_column">
							<?php 
							if (isset($_GET['user'])) {
								include("includes/pages/account/edit_user.php");
							}
							else{
								include("includes/pages/account/main.php");
							}
							?>
						</div>
					</div>
					<div id="lang_result"></div>
				</body>
			</html>
			<?php
		}
		
	}
	else{
		?>
			<script type="text/javascript">
				window.location.href = "index.php";
			</script>
		<?php
	}
?>