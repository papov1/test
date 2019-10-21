<?php
include_once $_SERVER["DOCUMENT_ROOT"].'classes/general.php';

if(strpos($_SERVER['REQUEST_URI'], '/safety-sheets/') !== false) {
	$repository_set = getRepositorySet('http://testal.at'.$_SERVER['REQUEST_URI']);
	if($repository_set != 'not found'){
		$dm_repos_content = explode(",",$repository_set);
		setcookie('dm_repos', json_encode($dm_repos_content), time() + (86400 * 30), "/");
	}
}
?>

<html>
	<head>
		<base href="http://testal.at">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>SDB</title>
		<link rel="stylesheet" type="text/css" href="css/global.css">
		<link rel="stylesheet" type="text/css" href="css/pe-icon-7-stroke.css">
		<link rel="stylesheet" type="text/css" href="css/sweetalert2.css">
		<link rel="stylesheet" type="text/css" href="css/jquery.auto-complete.css">
		<link rel="stylesheet" type="text/css" href="css/fullpage.css">
		<!--<link rel="stylesheet" href="css/animate.css">-->
		<link rel="stylesheet" type="text/css" href="css/flipbook.style.css">
		<link rel="stylesheet" type="text/css" href="css/font-awesome.css">

		<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="js/custom.js"></script>
		<script type="text/javascript" src="js/sweetalert2.min.js"></script>
		<script type="text/javascript" src="js/jquery.auto-complete.js"></script>
		<script type="text/javascript" src="js/fullpage.extensions.min.js"></script>
		<script type="text/javascript" src="js/jquery.foggy.min.js"></script>
		<script type="text/javascript" src="js/typed.js"></script>
		<script type="text/javascript" src="js/flipbook.min.js"></script>
		<script type="text/javascript" src="js/js.cookie.js"></script>
	</head>

	<body>
		<div id="main_container">
			<div id="navbar">
				<div class="container">
					<div class="logo">
						<img src="../images/logo.png">
						<div class="logo_hover">
							<div class="dmc_arrow_up"></div>
							<div class="logo_hover_container">
								<a href="http://testal.at/#suche" class="search_activate"><i class="pe-7s-search"></i> Neue Suche starten</a>
								<a href="https://industrie.airliquide.at/" target="_blank"><i class="pe-7s-home"></i> Zurück zur Homepage</a>
							</div>
						</div>
					</div>
					<div class="navbar_right">
						<div class="navbar_buttons">
							<a data-menuanchor="search_section" href="#suche" class="top_link" id="top_search"><span>Suche</span></a>
							<a data-menuanchor="overview_section" href="#ubersicht" class="top_link" id="top_overview"><span>Übersicht</span></a>
							<a data-menuanchor="contact_section" href="#kontakt" class="top_link" id="top_contact"><span>Kontakt</span></a>

							<a href="#" class="download_manager_button">
								<i class="pe-7s-photo-gallery"></i> <span>Download Manager</span>
								<div id="download_manager_counter"></div>

								<div id="download_manager_content">
									<div class="dmc_arrow_up"></div>
									<div class="download_manager_content_in">
										<div class="dmc_title">Download Manager</div>
										<div class="dmc_subtitle">Datenblätter gesammelt herunterladen</div>
										<div class="dmc_repositories_container">
											<?php include("includes/download_manager/dm.php"); ?>
										</div>

										<div class="download_manager_main_buttons">
											<div class="download_manager_main_buttons_left">
												<div class="share_and_send">Teilen und Versenden</div>
												<span>Link generieren & per Mail versenden</span>
											</div>
											<div class="download_manager_main_buttons_right">
												<div class="download_zipped">Gesammelt herunterladen</div>
												<span><strong>WinZip</strong> zum Entpacken erforderlich</span>
											</div>
										</div>

									</div>
								</div>

							</a>

							<a href="#" class="select_lang_button"><i class="pe-7s-global"></i> <span>Deutsch</span></a>
						</div>
					</div>
				</div>
			</div>
			<div id="fullpage">
				

				<!-- **************** SEARCH **************** -->
				<div class="section search_section active">
					<div class="first_page_headline_container">
						<div class="first_page_headline first_page_titles">
							Sicherheitsdatenblätter
						</div>
						<div class="first_page_subheadline first_page_titles">
							schnell & einfach mit	unserer intelligenten Suche finden
						</div>
						<div class="second_page_headline second_page_titles">
							Ihre Suchergebnisse
						</div>
						<div class="second_page_subheadline second_page_titles">
							Wählen Sie das entsprechende Dokument durch	Anklicken aus
						</div>
						<div class="search_input">
							<div class="search_input_content">
								<input type="text" name="main_search" id="main_search">
								<i class="pe-7s-search"></i>
								<div class="main_search_label">
									<span id="typed"></span>
								</div>

								<div id="search_ajax_results">
									<div class="search_content">
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>


				<!-- **************** OVERVIEW **************** -->
				<div class="section overview_section">
					<div class="overview_title">
						Übersicht
					</div>
					<div class="overview_subtitle">
						alle Datenblätter übersichtlich	dargestellt
					</div>
					<div class="overview_table_container">
						<div class="overview_table_container_inner">
							<div class="overview_table_titles">
								<div class="overview_table_title">
									Stoffe
								</div>
								<div class="overview_table_title">
									Markengase
								</div>
							</div>
							<div class="overview_table_content">
								
								<div class="overview_table_material">
									<div class="overview_table_results">
										<?php
											$repositories_material = getRepositoriesByType(1,'material');
											$ctr = 1;
											foreach ($repositories_material as $repository_id) {
												$repository_details = getRepositoryDetailsById($repository_id);
												echo '<div class="overview_table_line" data-overview_repository_id="'.$repository_id.'">';
													echo '<i class="pe-7s-file"></i> <span class="overview_table_item_name">'.$repository_details["name"].'</span>';
												echo '</div>';
												if($ctr == 9){ break; }
												$ctr++;
											}
										?>
									</div>
								</div>

								<div class="overview_table_brandgas">
									<div class="overview_table_results">
										<?php
											$repositories_brandgas = getRepositoriesByType(1,'brandgas');
											$ctr = 1;
											foreach ($repositories_brandgas as $repository_id) {
												$repository_details = getRepositoryDetailsById($repository_id);
												echo '<div class="overview_table_line" data-overview_repository_id="'.$repository_id.'">';
													echo '<i class="pe-7s-file"></i> <span class="overview_table_item_name">'.$repository_details["name"].'</span>';
												echo '</div>';
												if($ctr == 9){ break; }
												$ctr++;
											}
										?>
									</div>
								</div>
								
							</div>
						</div>
						<?php
							if((count($repositories_material) > 10)||(count($repositories_brandgas) > 10)){
								echo '
									<div class="overview_show_all_button">
										<i class="pe-7s-angle-down"></i>
										<span>Vollständige Ansicht laden</span>
										<i class="pe-7s-angle-down"></i>
									</div>
								';
							}
						?>
					</div>
				</div>


				<!-- **************** CONTACT **************** -->
				<div class="section contact_section">
					<div id="contact_content">
						<div class="contact_title">
							Kontakt
						</div>
						<div class="contact_subtitle">
							Sie haben Fragen zu unseren	Sicherheits- oder Produktdatenblätter?
						</div>
						<div class="contact_form">
							<div class="contact_form_line">
								<div class="contact_form_half">
									<input type="text" id="contact_name" name="contact_name" placeholder="Ihr Vor- &amp; Nachname">
								</div>
								<div class="contact_form_half">
									<input type="text" id="contact_mail" name="contact_mail" placeholder="Ihre e-mail Adresse">
								</div>
							</div>
							<div class="contact_form_line">
								<div class="contact_form_half">
									<input type="text" id="contact_title" name="contact_title" placeholder="Betreff">
								</div>
								<div class="contact_form_half">
									<input type="text" id="contact_phone" name="contact_phone" placeholder="Telefon">
								</div>
							</div>
							<div class="contact_form_line">
								<div class="contact_form_full">
									<textarea id="contact_msg" name="contact_msg" cols="40" rows="" placeholder="Ihre Anfrage"></textarea>
								</div>
							</div>
						</div>

						<div class="acceptance_section">
							<div class="acceptance_section_inner">
								<span class="btr-accept-ui"></span>
								<span class="acceptance_text">
									Ich habe die <a href="https://industrie.airliquide.at/datenschutz" target="_blank">Datenschutzerklärung</a> gelesen und erkläre mich einverstanden, dass meine Daten für die Bearbeitung meiner Anfrage verarbeitet und gespeichert werden dürfen. Ich kann meine <a href="https://www.airliquide.com/de/group/contact-us-gdpr" target="_blank">Zustimmung</a> jederzeit hier widerrufen.
								</span>

								<div id="button-con">
									<div id="send"><span>Anfrage senden</span></div>
								</div>

							</div>
						</div>

					</div>

					<div id="kontakt_form_confirmation">
						<span class="vc_icon_element-icon pe-7s-check" style="font-size:170px"></span>
						<br>
						Vielen Dank.<br>
						Ihre Anfrage wird bearbeitet.
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
					<strong>Scrollen</strong>
					um zur Übersicht zu gelangen
				</div>
				<div class="mouse_scroll_overview">
					<strong>Scrollen</strong>
					um zum Kontaktformular zu gelangen
				</div>
			</div>

			<div id="rating_popup">
				<div id="rating_popup_content">
					<div class="rating_title">
						<i class="pe-7s-check"></i>
						<span>Download erfolgreich ausgeführt</span>
					</div>
					<div class="rating_subtitle">
						Wie finden Sie unsere Seite?
						<span>Ihre Bewertung erfolgt anonymisiert.</span>
					</div>

					<section class="rating-widget">
						<div class="rating-stars text-center">
							<ul id="stars">
								<li class="star" data-value="1">
									<i class="fa fa-star fa-fw"></i>
									<span>schlecht</span>
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
									<span>sehr gut</span>
								</li>
							</ul>
						</div>
					</section>

					<div class="form_line">
						<label for="rating_comment_text">Was können wir besser machen?</label>
						<textarea name="rating_comment_text" id="rating_comment_text"></textarea>
					</div>

					<div class="form_buttons">
						<div class="rating_button">Feedback geben</div>
					</div>

				</div>

				<div id="rating_popup_sent">
					<div class="rating_title">
						<i class="pe-7s-check"></i>
						<span>Vielen Dank für Ihr Feedback</span>
					</div>
				</div>

			</div>

		</div>
	</body>
</html>