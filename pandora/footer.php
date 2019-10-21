<?php session_start();

	include_once 'classes/general.php';
	
	if (isset($_SESSION['admin_logged_in'])) {
		//logged
		?>
		<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>SDB Admin</title>
				<link rel="stylesheet" href="css/global.css">
				<link rel="stylesheet" href="css/pe-icon-7-stroke.css">
				<link rel="stylesheet" href="css/sweetalert2.css">
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
							Footer<br>Content
						</div>
						<div class="header_buttons">
							<a class="footer_save_button" data-lang-id="<?php echo $_SESSION['lang']; ?>"><i class="pe-7s-diskette"></i> <span>Save customizations</span></a>
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
						<?php include("includes/pages/footer/main.php"); ?>
					</div>
				</div>
				<div id="lang_result"></div>
			</body>
			<script type="text/javascript">
				autosize($('textarea'));
			</script>
		</html>
		<?php
	}
	else{
		?>
			<script type="text/javascript">
				window.location.href = "index.php";
			</script>
		<?php
	}
?>