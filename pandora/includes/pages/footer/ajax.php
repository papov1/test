<?php session_start();

    $root = ($_SERVER['SERVER_NAME'] == 'ttal.loc') ? '/' : '';
    include_once $_SERVER["DOCUMENT_ROOT"].$root.'pandora/classes/general.php';

	if (isset($_SESSION['admin_logged_in'])) {
	
		if(isset($_POST["update_translations"])){
			$lang_id = $_POST["lang_id"];
			$footer_copyright = $_POST["footer_copyright"];
			$footer_text_link_1 = $_POST["footer_text_link_1"];
			$footer_url_link_1 = $_POST["footer_url_link_1"];
			$footer_text_link_2 = $_POST["footer_text_link_2"];
			$footer_url_link_2 = $_POST["footer_url_link_2"];
			$footer_text_link_3 = $_POST["footer_text_link_3"];
			$footer_url_link_3 = $_POST["footer_url_link_3"];
			$footer_text_link_4 = $_POST["footer_text_link_4"];
			$footer_url_link_4 = $_POST["footer_url_link_4"];

			udpateFooter($lang_id,$footer_copyright,$footer_text_link_1,$footer_url_link_1,$footer_text_link_2,$footer_url_link_2,$footer_text_link_3,$footer_url_link_3,$footer_text_link_4,$footer_url_link_4);
		}

	}
?>