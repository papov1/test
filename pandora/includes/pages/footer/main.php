<?php session_start();
	if (isset($_SESSION['admin_logged_in'])) {
		//logged

		include_once 'classes/general.php';

		$id_lang = $_SESSION['lang'];
		$footer = getFooterTexts($id_lang);

		?>
		<div class="cookie_banner_admin">
			<div class="cookie_banner_edit_container">
				<div class="cookie_banner_edit_container_row">
					<input type="text" id="footer_copyright" class="cookie_banner_edit_container_input" value="<?php echo $footer["footer_copyright"]["name"]; ?>">
				</div>
				<div class="cookie_banner_edit_container_row">
					<input type="text" id="footer_text_link_1" class="cookie_banner_edit_container_input" value="<?php echo $footer["footer_text_link_1"]["name"]; ?>">
					<input type="text" id="footer_url_link_1" class="cookie_banner_edit_container_input" value="<?php echo $footer["footer_text_link_1"]["url"]; ?>">
				</div>
				<div class="cookie_banner_edit_container_row">
					<input type="text" id="footer_text_link_2" class="cookie_banner_edit_container_input" value="<?php echo $footer["footer_text_link_2"]["name"]; ?>">
					<input type="text" id="footer_url_link_2" class="cookie_banner_edit_container_input" value="<?php echo $footer["footer_text_link_2"]["url"]; ?>">
				</div>
				<div class="cookie_banner_edit_container_row">
					<input type="text" id="footer_text_link_3" class="cookie_banner_edit_container_input" value="<?php echo $footer["footer_text_link_3"]["name"]; ?>">
					<input type="text" id="footer_url_link_3" class="cookie_banner_edit_container_input" value="<?php echo $footer["footer_text_link_3"]["url"]; ?>">
				</div>
				<div class="cookie_banner_edit_container_row">
					<input type="text" id="footer_text_link_4" class="cookie_banner_edit_container_input" value="<?php echo $footer["footer_text_link_4"]["name"]; ?>">
					<input type="text" id="footer_url_link_4" class="cookie_banner_edit_container_input" value="<?php echo $footer["footer_text_link_4"]["url"]; ?>">
				</div>
			</div>
		</div>
		<?php
	}
?>