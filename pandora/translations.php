<?php session_start();
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
							Backend<br> für Sicherheits- & Produktdatenblätter
						</div>
						<div class="header_buttons">
							<a href="#" class="add_new_lang_button"><i class="pe-7s-plus"></i> <span>Neue Sprache hinzufügen</span></a>
							<a href="#" class="remove_lang_button"><i class="pe-7s-less"></i> <span>Remove language</span></a>
						</div>
					</div>
				</div>
				<div class="body_content">
					<?php include("includes/common/left_column.php"); ?>
					<div class="center_column">
						<?php include("includes/pages/translations/main.php"); ?>
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