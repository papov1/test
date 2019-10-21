<?php
	include_once 'classes/general.php';
	$current_page = basename($_SERVER['PHP_SELF']);

	$user_url = '';
	if(check_if_superadmin($_SESSION['userID']) == 0){
		$user_url = '?user='.$_SESSION['userID'];
	}
	echo '
		<div class="left_column">
			<div class="column_content">
				<a href="index.php" '.($current_page == 'index.php' ? 'class="active_page"' : '').' ><i class="pe-7s-network"></i> Verzeichnis</a>
				<a href="translations.php" '.($current_page == 'translations.php' ? 'class="active_page"' : '').' ><i class="pe-7s-flag"></i> Sprachen</a>
				<a href="account.php'.$user_url.'" '.(($current_page=='account.php' || $current_page=='account_edit.php') ? 'class="active_page"' : '').' ><i class="pe-7s-settings"></i> Account</a>
				<a href="feedback.php" '.($current_page == 'feedback.php' ? 'class="active_page"' : '').' ><i class="pe-7s-like2"></i> Feedback</a>
				<a href="logout.php"><i class="pe-7s-power"></i> Abmelden</a>
			</div>
		</div>
	';
?>
