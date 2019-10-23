<?php session_start();

    $root = ($_SERVER['SERVER_NAME'] == '3.16.24.200') ? '/' : '';
    include_once $_SERVER["DOCUMENT_ROOT"].$root.'pandora/classes/general.php';

	if (isset($_SESSION['admin_logged_in'])) {
	
		if (isset($_POST['update_translations'])) {
			$lang_id = $_POST["lang_id"];
			$cookie_banner_title = $_POST["cookie_banner_title"];
			$cookie_banner_text = $_POST["cookie_banner_text"];
			$cookie_banner_text_link_1 = $_POST["cookie_banner_text_link_1"];
			$cookie_banner_url_link_1 = $_POST["cookie_banner_url_link_1"];
			$cookie_banner_text_link_2 = $_POST["cookie_banner_text_link_2"];
			$cookie_banner_url_link_2 = $_POST["cookie_banner_url_link_2"];
			$cookie_banner_option_1 = $_POST["cookie_banner_option_1"];
			$cookie_banner_option_2 = $_POST["cookie_banner_option_2"];
			$cookie_banner_small_text = $_POST["cookie_banner_small_text"];
			$cookie_banner_declaration = $_POST["cookie_banner_declaration"];
			$cookie_banner_title_second = $_POST["cookie_banner_title_second"];
			$cookie_banner_text_second = $_POST["cookie_banner_text_second"];
			$cookie_banner_back = $_POST["cookie_banner_back"];

			udpateCookieBanner($lang_id,$cookie_banner_title,$cookie_banner_text,$cookie_banner_text_link_1,$cookie_banner_url_link_1,$cookie_banner_text_link_2,$cookie_banner_url_link_2,$cookie_banner_option_1,$cookie_banner_option_2,$cookie_banner_small_text,$cookie_banner_declaration,$cookie_banner_title_second,$cookie_banner_text_second,$cookie_banner_back);

			$g_analytics = $_POST["g_analytics"];
			$hotjar = $_POST["hotjar"];

			updateCookieBannerSettings($g_analytics,$hotjar);
		}


		if (isset($_POST['cookie_banner_off'])) {
			updateCookieBannerStatus('off');
		}

		if (isset($_POST['cookie_banner_on'])) {
			updateCookieBannerStatus('on');
		}

	}
?>