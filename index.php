<?php session_start();

$root = ($_SERVER['SERVER_NAME'] == 'ttal.loc') ? '/' : '';

include_once $_SERVER["DOCUMENT_ROOT"].$root.'classes/general.php';

if($_GET["pre"]){
	$prefilled = $_GET["pre"];
	if(strpos($prefilled, ',') !== false) {
		$prefilled = str_replace(",", ", ", $prefilled);
	}
}
else{
	$prefilled = '';
}

if(strpos($_SERVER['REQUEST_URI'], '/safety-sheets/') !== false) {
	$repository_set = getRepositorySet('http://ttal.loc'.$_SERVER['REQUEST_URI']);
	if($repository_set != 'not found'){
		$dm_repos_content = explode(",",$repository_set);
		setcookie('dm_repos', json_encode($dm_repos_content), time() + (86400 * 30), "/");
	}
}

//setting default DE lang
if(!isset($_SESSION['lang'])){
	$_SESSION['lang'] = 1;
}

$translation = getTranslation($_SESSION['lang']);
?>

<html>
	<head>
		<base href="http://ttal.loc">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
		<title>SDB</title>
		<link rel="stylesheet" type="text/css" href="css/global.css">
		<link rel="stylesheet" type="text/css" href="css/pe-icon-7-stroke.css">
		<link rel="stylesheet" type="text/css" href="css/sweetalert2.css">
		<link rel="stylesheet" type="text/css" href="css/jquery.auto-complete.css">
		<link rel="stylesheet" type="text/css" href="css/fullpage.css">
		<!--<link rel="stylesheet" href="css/animate.css">-->
		<link rel="stylesheet" type="text/css" href="css/flipbook.style.css">
		<link rel="stylesheet" type="text/css" href="css/font-awesome.css">
		<link href="https://fonts.googleapis.com/css?family=Shadows+Into+Light+Two&amp;subset=latin-ext" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="css/simplebar.css">

		<?php
			if($_SESSION['lang'] == 1){
				echo '<link rel="stylesheet" type="text/css" href="css/de.css">';
			}
			else{
				echo '<link rel="stylesheet" type="text/css" href="css/en.css">';
			}
		?>

		<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="js/custom.js"></script>
		<script type="text/javascript" src="js/sweetalert2.min.js"></script>
		<script type="text/javascript" src="js/jquery.auto-complete.js"></script>
		<script type="text/javascript" src="js/fullpage.extensions.min.js"></script>
		<script type="text/javascript" src="js/jquery.foggy.min.js"></script>
		<script type="text/javascript" src="js/typed.js"></script>
		<script type="text/javascript" src="js/flipbook.min.js"></script>
		<script type="text/javascript" src="js/js.cookie.js"></script>
		<script type="text/javascript" src="js/gaseumrechner.js"></script>
		<script type="text/javascript" src="js/simplebar.js"></script>
	</head>

	<body>
		<div id="bg_image"></div>

		<?php
			$cookie_banner_settings = getCookieBannerSettings();
			if($cookie_banner_settings["status"]["value"] == 'on'){

				$id_lang = $_SESSION['lang'];
				$coookie_banner = getCookieBannerTexts($id_lang);
				$footer = getFooterTexts($id_lang);

				?>

				<div id="cookie_banner_overlay">
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

						<div class="cookie_banner_inner_container_second">
							<div class="cookie_banner_title_second"><?php echo $coookie_banner["cookie_banner_title_second"]["name"]; ?></div>
							<div class="cookie_banner_text_second">
								<?php echo $coookie_banner["cookie_banner_text_second"]["name"]; ?>
							</div>
							<div class="cookie_banner_back"><?php echo $coookie_banner["cookie_banner_back"]["name"]; ?></div>
						</div>

					</div>
				</div>

			<?php
			}
		?>

		<div id="navbar">
			<div class="container">
				<div class="logo">
					<img src="../images/logo.png">
					<div class="logo_hover">
						<div class="dmc_arrow_up"></div>
						<div class="logo_hover_container">
							<a href="http://ttal.loc/#suche" class="search_activate"><i class="pe-7s-search"></i> <?php echo $translation[54]; ?></a>
							<a href="https://industrie.airliquide.at/" target="_blank"><i class="pe-7s-home"></i> <?php echo $translation[55]; ?></a>
						</div>
					</div>
				</div>
				<div class="flag">
					<img src="../images/flag.png">
				</div>
				<div class="navbar_right">
					<div class="navbar_buttons">
						
						<div class="navbar_mobile">
							<i class="pe-7s-menu"></i>
						</div>

						<a data-menuanchor="search_section" href="#suche" class="top_link" id="top_search"><span><?php echo $translation[1]; ?></span></a>
						<a data-menuanchor="overview_section" href="#ubersicht" class="top_link" id="top_overview"><span><?php echo $translation[2]; ?></span></a>
						<a data-menuanchor="calculator_section" href="#rechner" class="top_link" id="top_calculator"><span><?php echo $translation[80]; ?></span></a>
						<a data-menuanchor="contact_section" href="#kontakt" class="top_link" id="top_contact"><span><?php echo $translation[3]; ?></span></a>

						<a href="#" class="download_manager_button">
							<i class="pe-7s-photo-gallery"></i> <span><?php echo $translation[4]; ?></span>
							<div id="download_manager_counter"></div>

							<div id="download_manager_content">
								<div class="dmc_arrow_up"></div>
								<div class="download_manager_content_in">
									<div class="dmc_title"><?php echo $translation[19]; ?></div>
									<div class="dmc_subtitle"><?php echo $translation[20]; ?></div>
									<div class="dmc_repositories_container">
										<?php include("includes/download_manager/dm.php"); ?>
									</div>

									<div class="download_manager_main_buttons">
										<div class="download_manager_main_buttons_left">
											<div class="share_and_send"><?php echo $translation[56]; ?></div>
											<span><?php echo $translation[57]; ?></span>
										</div>
										<div class="download_manager_main_buttons_right">
											<div class="download_zipped"><?php echo $translation[23]; ?></div>
											<span><strong>WinZip</strong> <?php echo $translation[24]; ?></span>
										</div>
									</div>

								</div>
							</div>

							<?php
								if(strpos($_SERVER['REQUEST_URI'], '/safety-sheets/') !== false) {
									echo '
										<div class="dm_share_notification">
											<i class="pe-7s-back"></i>
											'.$translation[79].'
										</div>
									';
								}
							?>

						</a>

						<?php 
							if($_SESSION['lang'] == 1){
								$new_lang_id = 2;
							}
							else{
								$new_lang_id = 1;
							}
						?>
						<div class="select_lang_button" data-lang-id-nav="<?php echo $new_lang_id; ?>">
							<i class="pe-7s-global"></i> <span><?php echo getLangName($_SESSION['lang']); ?></span>
							<div class="langs_container wider" >
								<div class="dmc_arrow_up"></div>
								<div class="langs_content">
									<?php
										$langs = getLangsNames();
										foreach ($langs as $lang) {
											if($lang["language_id"] != $_SESSION['lang']){
												if($_SESSION['lang'] == 1){
													echo '<div class="lang_name" data-lang-id="'.$lang["language_id"].'">'.$translation[100].'</div>';
												}
												else{
													echo '<div class="lang_name" data-lang-id="'.$lang["language_id"].'">'.$translation[101].'</div>';
												}
												
											}
										}
									?>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>

		<div class="mobile_menu">
			<a data-menuanchor="search_section" href="#suche" class="top_link"><span><?php echo $translation[1]; ?></span></a>
			<a data-menuanchor="overview_section" href="#ubersicht" class="top_link"><span><?php echo $translation[2]; ?></span></a>
			<a data-menuanchor="calculator_section" href="#rechner" class="top_link"><span><?php echo $translation[80]; ?></span></a>
			<a data-menuanchor="contact_section" href="#kontakt" class="top_link"><span><?php echo $translation[3]; ?></span></a>
		</div>

		<div id="main_container">
			
			<div id="fullpage">
				

				<!-- **************** SEARCH **************** -->
				<div id="suche" class="section search_section active">
					<div class="first_page_headline_container">
						<div class="first_page_headline first_page_titles">
							<?php echo $translation[5]; ?>
						</div>
						<div class="first_page_subheadline first_page_titles">
							<?php echo $translation[6]; ?>
						</div>
						<div class="second_page_headline second_page_titles">
							<?php echo $translation[49]; ?>
						</div>
						<div class="second_page_subheadline second_page_titles">
							<?php echo $translation[50]; ?>
						</div>
						<div class="search_input">
							<div class="search_input_content">
								<input type="text" name="main_search" id="main_search" value="<?php echo $prefilled; ?>">
								<i class="pe-7s-search"></i>
								<div class="main_search_label">
									<span id="typed"></span>
								</div>
								<i id="cancel_search" class="pe-7s-close-circle"></i>

								<div id="search_ajax_results" data-simplebar>
									<div class="search_content">
									</div>
								</div>

							</div>
						</div>
						<div class="first_page_subheadline_bottom first_page_titles">
							<?php echo $translation[102]; ?>
						</div>
					</div>
				</div>


				<!-- **************** OVERVIEW **************** -->
				<div id="ubersicht" class="section overview_section">
					<div class="overview_title">
						<?php echo $translation[31]; ?>
					</div>
					<div class="overview_subtitle">
						<?php echo $translation[32]; ?>
					</div>
					<div class="overview_table_container">
						<div class="overview_table_container_inner">
							<div class="overview_table_titles">
								<div class="overview_table_title">
									<span class="mobile_section_active sdb_section"><?php echo $translation[15]; ?></span><span class="mobile_section_inactive pdb_section"><?php echo $translation[17]; ?></span>
									<div class="overview_table_subtitles">
										<div class="overview_table_subtitle">
											<?php echo $translation[33]; ?>
										</div>
										<div class="overview_table_subtitle">
											<?php echo $translation[34]; ?>
										</div>
									</div>
								</div>
								<div class="overview_table_title mobile_hidden">
									<?php echo $translation[17]; ?>
									<div class="overview_table_subtitles">
										<div class="overview_table_subtitle">
											<?php echo $translation[33]; ?>
										</div>
										<div class="overview_table_subtitle">
											<?php echo $translation[34]; ?>
										</div>
									</div>
								</div>
							</div>
							
							<div class="overview_table_content">
								
								<div class="overview_table_sdb_material">
									<div class="overview_table_results">
										<?php
											//$repositories_material = getRepositoriesByType($_SESSION['lang'],'material');
											$repositories_material = getRepositoriesByArt($_SESSION['lang'],'safety','material');
											$ctr = 1;

											if(count($repositories_material) > 0){
												foreach ($repositories_material as $r) {

													echo '<div class="overview_table_line" data-overview_repository_id="'.$r['id'].'">';
														echo '<i class="pe-7s-file"></i> <span class="overview_table_item_name">'.$r["name"].'</span>';
													echo '</div>';
													if($ctr == 7){ break; }
													$ctr++;
												}
											}
										?>
									</div>
								</div>

								<div class="overview_table_sdb_brandgas">
									<div class="overview_table_results">
										<?php
											//$repositories_material = getRepositoriesByType($_SESSION['lang'],'material');
											$repositories_material = getRepositoriesByArt($_SESSION['lang'],'safety','brandgas');
											$ctr = 1;

											if(count($repositories_material) > 0){
												foreach ($repositories_material as $r) {

													echo '<div class="overview_table_line" data-overview_repository_id="'.$r['id'].'">';
														echo '<i class="pe-7s-file"></i> <span class="overview_table_item_name">'.$r["name"].'</span>';
													echo '</div>';
													if($ctr == 7){ break; }
													$ctr++;
												}
											}
										?>
									</div>
								</div>

								<div class="overview_table_pdb_material">
									<div class="overview_table_results">
										<?php
											//$repositories_brandgas = getRepositoriesByType($_SESSION['lang'],'brandgas');
											$repositories_brandgas = getRepositoriesByArt($_SESSION['lang'],'product','material');
											$ctr = 1;
											if(count($repositories_brandgas)>0){
												foreach ($repositories_brandgas as $r) {
													echo '<div class="overview_table_line" data-overview_repository_id="'.$r['id'].'">';
														echo '<i class="pe-7s-file"></i> <span class="overview_table_item_name">'.$r["name"].'</span>';
													echo '</div>';
													if($ctr == 7){ break; }
													$ctr++;
												}
											}
										?>
									</div>
								</div>

								<div class="overview_table_pdb_brandgas">
									<div class="overview_table_results">
										<?php
											//$repositories_brandgas = getRepositoriesByType($_SESSION['lang'],'brandgas');
											$repositories_brandgas = getRepositoriesByArt($_SESSION['lang'],'product','brandgas');
											$ctr = 1;
											if(count($repositories_brandgas)>0){
												foreach ($repositories_brandgas as $r) {
													echo '<div class="overview_table_line" data-overview_repository_id="'.$r['id'].'">';
														echo '<i class="pe-7s-file"></i> <span class="overview_table_item_name">'.$r["name"].'</span>';
													echo '</div>';
													if($ctr == 7){ break; }
													$ctr++;
												}
											}
										?>
									</div>
								</div>
								
							</div>
						</div>
						<?php
							if((count($repositories_material) > 10)||(count($repositories_brandgas) > 10)){
								echo '
									<div class="overview_show_all_button_container">
										<div class="overview_show_all_button">
											<i class="pe-7s-angle-down"></i>
											<span>'.$translation[35].'</span>
											<i class="pe-7s-angle-down"></i>
										</div>
									</div>
								';
							}
						?>
					</div>
				</div>


				<!-- **************** CONTACT **************** -->
				<div id="kontakt" class="section contact_section">
					<div id="contact_content">
						<div class="contact_title">
							<?php echo $translation[39]; ?>
						</div>
						<div class="contact_subtitle">
							<?php echo $translation[40]; ?>
						</div>
						<div class="contact_form">
							<div class="contact_form_line">
								<div class="contact_form_half">
									<input type="text" id="contact_name" name="contact_name" placeholder="<?php echo $translation[41]; ?>">
								</div>
								<div class="contact_form_half">
									<input type="text" id="contact_mail" name="contact_mail" placeholder="<?php echo $translation[42]; ?>">
								</div>
							</div>
							<div class="contact_form_line">
								<div class="contact_form_half">
									<input type="text" id="contact_title" name="contact_title" placeholder="<?php echo $translation[43]; ?>">
								</div>
								<div class="contact_form_half">
									<input type="text" id="contact_phone" name="contact_phone" placeholder="<?php echo $translation[44]; ?>">
								</div>
							</div>
							<div class="contact_form_line">
								<div class="contact_form_full">
									<textarea id="contact_msg" name="contact_msg" cols="40" rows="" placeholder="<?php echo $translation[45]; ?>"></textarea>
								</div>
							</div>
						</div>

						<div class="acceptance_section">
							<div class="acceptance_section_inner">
								<span class="btr-accept-ui"></span>
								<span class="acceptance_text">
									<?php echo $translation[48]; ?>
								</span>

								<div id="button-con">
									<div id="send"><span><?php echo $translation[58]; ?></span></div>
								</div>

							</div>
						</div>

					</div>

					<div id="kontakt_form_confirmation">
						<span class="vc_icon_element-icon pe-7s-check" style="font-size:170px"></span>
						<br>
						<?php echo $translation[75]; ?><br>
						<?php echo $translation[76]; ?>
					</div>
				</div>

								<!-- **************** CALCULATOR **************** -->

								<div id="rechner" class="section calculator_section">
										<div class="calculator_content">
											<div class="calculator_title">
												<?php echo $translation[80]; ?>
											</div>
											<div class="calculator_subtitle">
												<?php echo $translation[81]; ?>
											</div>

												<div class="gaseumrechner">
														<script>var gase_data = {"gas":[{"@attributes":{"id":"stickstoff","name":"Stickstoff"},"einheit":[{"@attributes":{"id":"kg","faktor-kg":"1","faktor-l":"1.2369","faktor-m3":"0.85514"}},{"@attributes":{"id":"l","faktor-kg":"0.8085","faktor-l":"1","faktor-m3":"0.6914"}},{"@attributes":{"id":"m3","faktor-kg":"1.1694","faktor-l":"1.4463","faktor-m3":"1"}}]},{"@attributes":{"id":"sauerstoff","name":"Sauerstoff"},"einheit":[{"@attributes":{"id":"kg","faktor-kg":"1","faktor-l":"0.876","faktor-m3":"0.748"}},{"@attributes":{"id":"l","faktor-kg":"1.142","faktor-l":"1","faktor-m3":"0.854"}},{"@attributes":{"id":"m3","faktor-kg":"1.337","faktor-l":"1.171","faktor-m3":"1"}}]},{"@attributes":{"id":"argon","name":"Argon"},"einheit":[{"@attributes":{"id":"kg","faktor-kg":"1","faktor-l":"0.717","faktor-m3":"0.599"}},{"@attributes":{"id":"l","faktor-kg":"1.395","faktor-l":"1","faktor-m3":"0.835"}},{"@attributes":{"id":"m3","faktor-kg":"1.669","faktor-l":"1.198","faktor-m3":"1"}}]},{"@attributes":{"id":"kohlendioxid","name":"Kohlendioxid"},"einheit":[{"@attributes":{"id":"kg","faktor-kg":"1","faktor-l":"0.849","faktor-m3":"0.541"}},{"@attributes":{"id":"l","faktor-kg":"1.178","faktor-l":"1","faktor-m3":"0.637"}},{"@attributes":{"id":"m3","faktor-kg":"1.847","faktor-l":"1.568","faktor-m3":"1"}}]},{"@attributes":{"id":"kohlenmonoxid","name":"Kohlenmonoxid"},"einheit":[{"@attributes":{"id":"kg","faktor-kg":"1","faktor-l":"1.268","faktor-m3":"0.855"}},{"@attributes":{"id":"l","faktor-kg":"0.788","faktor-l":"1","faktor-m3":"0.674"}},{"@attributes":{"id":"m3","faktor-kg":"1.170","faktor-l":"1.484","faktor-m3":"1"}}]},{"@attributes":{"id":"helium","name":"Helium"},"einheit":[{"@attributes":{"id":"kg","faktor-kg":"1","faktor-l":"8","faktor-m3":"5.988"}},{"@attributes":{"id":"l","faktor-kg":"0.125","faktor-l":"1","faktor-m3":"0.749"}},{"@attributes":{"id":"m3","faktor-kg":"0.167","faktor-l":"1.336","faktor-m3":"1"}}]},{"@attributes":{"id":"wasserstoff","name":"Wasserstoff"},"einheit":[{"@attributes":{"id":"kg","faktor-kg":"1","faktor-l":"14.124","faktor-m3":"11.892"}},{"@attributes":{"id":"l","faktor-kg":"0.071","faktor-l":"1","faktor-m3":"0.842"}},{"@attributes":{"id":"m3","faktor-kg":"0.084","faktor-l":"1.188","faktor-m3":"1"}}]},{"@attributes":{"id":"methan","name":"Methan"},"einheit":[{"@attributes":{"id":"kg","faktor-kg":"1","faktor-l":"2.367","faktor-m3":"1.490"}},{"@attributes":{"id":"l","faktor-kg":"0.423","faktor-l":"1","faktor-m3":"0.630"}},{"@attributes":{"id":"m3","faktor-kg":"0.671","faktor-l":"1.588","faktor-m3":"1"}}]}]}</script>
														
														<div class="calculator_inner_section">
															<p class="calculator_subtitle2"><?=$translation[82]?></p>
															<p class="gaspicker">
																<label><?=$translation[83]?></label>
																<select name="gas">
																	<option value="stickstoff"><?=$translation[92]?></option>
																	<option value="sauerstoff"><?=$translation[93]?></option>
																	<option value="argon"><?=$translation[94]?></option>
																	<option value="kohlendioxid"><?=$translation[95]?></option>
																	<option value="kohlenmonoxid"><?=$translation[96]?></option>
																	<option value="helium"><?=$translation[97]?></option>
																	<option value="wasserstoff"><?=$translation[98]?></option>
																	<option value="methan"><?=$translation[99]?></option>
																</select>
															</p>
														</div>

														<div class="calculator_inner_section">
															<p class="calculator_subtitle2"><?=$translation[91]?></p>
																<div class="calculator-top">
																		<label for="kg"><?=$translation[84]?></label>
																		<input class="calculator" id="kg" name="kg" type="text">
																		<span data-limit-to="kohlendioxid" class="legend" style="display: none;"><?=$translation[88]?></span>
																</div>
																<div class="calculator-top">
																		<label for="l"><?=$translation[85]?></label>
																		<input class="calculator" id="l" name="l" type="text">
																		<span class="legend"><?=$translation[89]?></span>
																		<span data-limit-to="kohlendioxid" class="legend" style="display: none;"><?=$translation[88]?></span>
																</div>
																<div class="calculator-top">
																		<label for="m3"><?=$translation[86]?></label>
																		<input class="calculator" id="m3" name="m3" type="text">
																		<span class="legend"><?=$translation[90]?></span>
																		<span data-limit-to="kohlendioxid" class="legend" style="display: none;"><?=$translation[90]?></span>
																</div>
																<div class="calculator-bottom">
																		<a class="reset" href="javascript:void(0);"><?=$translation[87]?></a>
																</div>
														</div>
												</div>
												<div class="clear"></div>
										</div>
								</div>

				<!-- **************** FOOTER **************** -->
				<div class="section footer_section fp-auto-height">
					<div id="footer_content">
						<div class="footer_copyright"><?php echo $footer["footer_copyright"]["name"]; ?></div>
						<div class="footer_links">
							<?php
								foreach ($footer as $footer_link) {
									if(($footer_link["name"] != '')&&($footer_link["url"] != '')){
										echo '<a href="'.$footer_link["url"].'" target="_blank">'.$footer_link["name"].'</a>';
									}
								}
							?>
						</div>
					</div>
				</div>



				<div id="pdf_viewer_window"></div>
			</div>

			<div class="mouse_scroll">
				<div class="mouse_scroll_icon">
					<i class="pe-7s-angle-up"></i>
					<i class="pe-7s-mouse"></i>
					<i class="pe-7s-angle-down"></i>
				</div>
				<div class="mouse_scroll_search">
					<strong><?php echo $translation[8]; ?></strong>
					<?php echo $translation[9]; ?>
				</div>
				<div class="mouse_scroll_overview">
					<strong><?php echo $translation[36]; ?></strong>
					<?php echo $translation[37]; ?>
				</div>
			</div>

			<div id="rating_popup">
				<div id="rating_popup_content">
					<div class="rating_title">
						<i class="pe-7s-check"></i>
						<span><?php echo $translation[25]; ?></span>
					</div>
					<div class="rating_subtitle">
						<?php echo $translation[26]; ?>
						<span><?php echo $translation[27]; ?></span>
					</div>

					<section class="rating-widget">
						<div class="rating-stars text-center">
							<ul id="stars">
								<li class="star" data-value="1">
									<i class="fa fa-star fa-fw"></i>
									<span><?php echo $translation[28]; ?></span>
								</li>
								<li class="star" data-value="2">
									<i class="fa fa-star fa-fw"></i>
								</li>
								<li class="star" data-value="3">
									<i class="fa fa-star fa-fw"></i>
								</li>
								<li class="star" data-value="4">
									<i class="fa fa-star fa-fw"></i>
								</li>
								<li class="star" data-value="5">
									<i class="fa fa-star fa-fw"></i>
									<span><?php echo $translation[29]; ?></span>
								</li>
							</ul>
						</div>
					</section>

					<div class="form_line">
						<label for="rating_comment_text"><?php echo $translation[30]; ?></label>
						<textarea name="rating_comment_text" id="rating_comment_text"></textarea>
					</div>

					<div class="form_buttons">
						<div class="rating_button"><?php echo $translation[73]; ?></div>
					</div>

				</div>

				<div id="rating_popup_sent">
					<div class="rating_title">
						<i class="pe-7s-check"></i>
						<span><?php echo $translation[74]; ?></span>
					</div>
				</div>

			</div>

		</div>
	</body>
</html>