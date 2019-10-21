<?php session_start();
	if (isset($_SESSION['admin_logged_in'])) {
		//logged

		include_once 'classes/general.php';

		$id_lang = $_SESSION['lang'];
		$coookie_banner = getCookieBannerTexts($id_lang);
		$cookie_banner_settings = getCookieBannerSettings();

	?>
		<div class="main_table_container">

			<div class="cookie_banner_admin">
				<div class="cookie_banner_container">	
					<div class="cookie_banner_title"><?php echo $coookie_banner["cookie_banner_title"]["name"]; ?></div>		
					<div class="cookie_banner_inner_container">
						<div class="cookie_banner_text">
							<?php echo $coookie_banner["cookie_banner_text"]["name"]; ?><br>
							<a href="<?php echo $coookie_banner["cookie_banner_text_link_1"]["url"]; ?>" target="_blank"><?php echo $coookie_banner["cookie_banner_text_link_1"]["name"]; ?></a><br>
							<a href="<?php echo $coookie_banner["cookie_banner_text_link_2"]["url"]; ?>" target="_blank"><?php echo $coookie_banner["cookie_banner_text_link_2"]["name"]; ?></a>
						</div>
						<div class="cookie_banner_selection_list_container">
							<ul>
								<li class="cookieOption1">
									<label for="CookieOptionAll">
										<input id="CookieOptionAll" type="radio" name="cookieOption" value="all"><?php echo $coookie_banner["cookie_banner_option_1"]["name"]; ?>
									</label>
								</li>
								<li class="cookieOption2">
									<label for="CookieOptionRejectAll">
										<input id="CookieOptionRejectAll" type="radio" name="cookieOption" value="reject-all"><?php echo $coookie_banner["cookie_banner_option_2"]["name"]; ?>
									</label>
								</li>
							</ul>
							<div class="cookie_banner_small_text"><?php echo $coookie_banner["cookie_banner_small_text"]["name"]; ?></div>
						</div>
						<div class="cookie_banner_declaration"><?php echo $coookie_banner["cookie_banner_declaration"]["name"]; ?></div>
					</div>
				</div>
				<div class="cookie_banner_edit_container">
					<div class="cookie_banner_edit_container_row">
						<input type="text" id="cookie_banner_title" class="cookie_banner_edit_container_input" value="<?php echo $coookie_banner["cookie_banner_title"]["name"]; ?>">
					</div>
					<div class="cookie_banner_edit_container_row">
						<input type="text" id="cookie_banner_text" class="cookie_banner_edit_container_input" value="<?php echo $coookie_banner["cookie_banner_text"]["name"]; ?>">
					</div>
					<div class="cookie_banner_edit_container_row">
						<input type="text" id="cookie_banner_text_link_1" class="cookie_banner_edit_container_input" value="<?php echo $coookie_banner["cookie_banner_text_link_1"]["name"]; ?>">
						<input type="text" id="cookie_banner_url_link_1" class="cookie_banner_edit_container_input" value="<?php echo $coookie_banner["cookie_banner_text_link_1"]["url"]; ?>">
					</div>
					<div class="cookie_banner_edit_container_row">
						<input type="text" id="cookie_banner_text_link_2" class="cookie_banner_edit_container_input" value="<?php echo $coookie_banner["cookie_banner_text_link_2"]["name"]; ?>">
						<input type="text" id="cookie_banner_url_link_2" class="cookie_banner_edit_container_input" value="<?php echo $coookie_banner["cookie_banner_text_link_2"]["url"]; ?>">
					</div>
					<div class="cookie_banner_edit_container_row">
						<input type="text" id="cookie_banner_option_1" class="cookie_banner_edit_container_input" value="<?php echo $coookie_banner["cookie_banner_option_1"]["name"]; ?>">
					</div>
					<div class="cookie_banner_edit_container_row">
						<input type="text" id="cookie_banner_option_2" class="cookie_banner_edit_container_input" value="<?php echo $coookie_banner["cookie_banner_option_2"]["name"]; ?>">
					</div>
					<div class="cookie_banner_edit_container_row">
						<input type="text" id="cookie_banner_small_text" class="cookie_banner_edit_container_input" value="<?php echo $coookie_banner["cookie_banner_small_text"]["name"]; ?>">
					</div>
					<div class="cookie_banner_edit_container_row">
						<input type="text" id="cookie_banner_declaration" class="cookie_banner_edit_container_input" value="<?php echo $coookie_banner["cookie_banner_declaration"]["name"]; ?>">
					</div>
				</div>
			</div>

			<div class="cookie_banner_admin">
				<div class="cookie_banner_container">	
					<div class="cookie_banner_title"><?php echo $coookie_banner["cookie_banner_title"]["name"]; ?></div>
					<div class="cookie_banner_inner_container_second">
						<div class="cookie_banner_title_second"><?php echo $coookie_banner["cookie_banner_title_second"]["name"]; ?></div>
						<div class="cookie_banner_text_second">
							<?php echo $coookie_banner["cookie_banner_text_second"]["name"]; ?>
						</div>
						<div class="cookie_banner_back"><?php echo $coookie_banner["cookie_banner_back"]["name"]; ?></div>
					</div>
				</div>
				<div class="cookie_banner_edit_container">
					<div class="cookie_banner_edit_container_row">
						<input type="text" id="cookie_banner_title_second" class="cookie_banner_edit_container_input" value="<?php echo $coookie_banner["cookie_banner_title_second"]["name"]; ?>">
					</div>
					<div class="cookie_banner_edit_container_row">
						<textarea id="cookie_banner_text_second" class="cookie_banner_edit_container_input"><?php echo $coookie_banner["cookie_banner_text_second"]["name"]; ?></textarea>
					</div>
					<div class="cookie_banner_edit_container_row">
						<input type="text" id="cookie_banner_back" class="cookie_banner_edit_container_input" value="<?php echo $coookie_banner["cookie_banner_back"]["name"]; ?>">
					</div>
				</div>
			</div>

			<div class="cookie_banner_admin">
				<div class="code_snippets_container">
					<div class="cookie_banner_edit_container_row">
						<div class="code_snippets_title">
							Google Analytics Code Snippet
						</div>
						<div class="form-line">
							<input type="text" name="g_analytics_input" id="g_analytics_input" autocomplete="off" value="<?php echo $cookie_banner_settings["google_analytics_code"]["value"]; ?>">
							<label for="g_analytics_input">Paste UA code here, example: UA-123456-78</label>
						</div>
					</div>

					<div class="cookie_banner_edit_container_row">
						<div class="code_snippets_title">
							Hotjar Analytics Code Snippet
						</div>
						<div class="form-line">
							<input type="text" name="hotjar_input" id="hotjar_input" autocomplete="off" value="<?php echo $cookie_banner_settings["hotjar_code"]["value"]; ?>">
							<label for="hotjar_input">Paste code here, this part only: {hjid:XXXXXX,hjsv:X}</label>
						</div>
					</div>
				</div>
			</div>

		</div>

		<?php
	}
?>