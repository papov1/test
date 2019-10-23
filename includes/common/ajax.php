<?php session_start();

    $root = ($_SERVER['SERVER_NAME'] == '3.16.24.200') ? '/' : '';

    include_once $_SERVER["DOCUMENT_ROOT"].$root.'classes/general.php';

	//************************************ PDF VIEWER ***************************************//
	if(isset($_POST["open_in_pdf_viewer"])){
		$repository_id = $_POST["repository_id"];

		echo getRepositoryPdfLink($_POST["repository_id"]);
	}


	//************************************ DIRECT PDF DOWNLOAD ***************************************//
	if(isset($_POST["direct_pdf_download"])){
		$repository_id = $_POST["repository_id"];

		updateRepositoryPdfDownloadCounter($_POST["repository_id"]);
		echo getRepositoryPdfLink($_POST["repository_id"]);
	}


	//************************************ ADD TO DOWNLOAD MANAGER ***************************************//
	function addLineToDownloadManager($repository_id){
		$translation = getTranslation($_SESSION['lang']);
		$repository_details = getRepositoryDetailsById($repository_id);
		if($repository_details["art"] == 'step_2_safety_datasheet'){
			$art = 'SDB';
		}
		else{
			$art = 'PDB';
		}
		$repository_date_exp = explode(" ",$repository_details["modification_date"]);
		$repository_date_exp_deep = explode("-",$repository_date_exp[0]);
		$repository_date = $repository_date_exp_deep[2].".".$repository_date_exp_deep[1].".".$repository_date_exp_deep[0];

		echo '<div class="dmc_repositories_container_line" id="dmi_'.$repository_details["id"].'">';
			echo '<span class="dmc_repositories_container_line_name">'.$repository_details["name"].'</span>';
			echo '<span class="dmc_repositories_container_line_art">'.$art.'</span>';
			echo '<span class="dmc_repositories_container_line_date">'.$translation[11].'<br>'.$repository_date.'</span>';
			echo '<span class="dmc_repositories_container_line_repository_button"><span class="open_in_pdf_viewer" data-search_repository_id="'.$repository_details["id"].'">'.$translation[21].'</span></span>';
			echo '<span class="dmc_repositories_container_line_repository_button"><span class="remove_from_download_manager" data-search_repository_id="'.$repository_details["id"].'">'.$translation[22].'</span></span>';
		echo '</div>';
		
	}


	if(isset($_POST["add_to_download_manager"])){
		$repository_id = $_POST["repository_id"];

		if(!isset($_COOKIE['dm_repos'])) {
			$dm_repos_content[] = $_POST["repository_id"];
			setcookie('dm_repos', json_encode($dm_repos_content), time() + (86400 * 30), "/");
			addLineToDownloadManager($_POST["repository_id"]);

			//echo 'added to cookie';
		}
		else{
			$dm_repos_content = json_decode($_COOKIE['dm_repos'], true);
			if(!in_array($_POST["repository_id"], $dm_repos_content)) {
			  $dm_repos_content[] = $_POST["repository_id"];
			  setcookie('dm_repos', json_encode($dm_repos_content), time() + (86400 * 30), "/");
			  addLineToDownloadManager($_POST["repository_id"]);

			  //echo 'added to cookie';
			}
			else{
				//echo 'already in cookie';
			}
		}		
	}


	//************************************ REMOVE FROM DOWNLOAD MANAGER ***************************************//
	if(isset($_POST["remove_from_download_manager"])){
		$repository_id = $_POST["repository_id"];

		$dm_repos_content = json_decode($_COOKIE['dm_repos'], true);
		array_splice($dm_repos_content, array_search($_POST["repository_id"],$dm_repos_content),1);

		setcookie('dm_repos', json_encode($dm_repos_content), time() + (86400 * 30), "/");		
	}


	//************************************ DOWNLOAD ZIPPED FROM DOWNLOAD MANAGER ***************************************//
	function create_zip($files = array(),$destination = '',$overwrite = false) {
		if(file_exists($destination) && !$overwrite) { 
			return false; 
		}

		$valid_files = array();
		if(is_array($files)) {
			foreach($files as $file) {
				if(file_exists($file)) {
					$valid_files[] = $file;
				}
			}
		}

		if(count($valid_files)) {
			$zip = new ZipArchive();
			if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
				return false;
			}

			foreach($valid_files as $file) {
				$new_filename = substr($file,strrpos($file,'/') + 1);

				include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');
				$sql = "SELECT name, id FROM repository WHERE pdf_link LIKE '%".mysqli_real_escape_string($con,$new_filename)."%'";
				$result = mysqli_query($con,$sql);
				$content = mysqli_fetch_array($result);
				$new = $content["name"].'_'.$content["id"];
				$new_filename = makeURL($new).'.pdf';

				$zip->addFile($file,$new_filename);

			}

			$zip->close();

			return 'success';
		}
		else{
			return 'failed';
		}
	}

	if(isset($_POST["download_zipped"])){

		$dm_repos_content = json_decode($_COOKIE['dm_repos'], true);

		foreach ($dm_repos_content as $dm_repo_id) {
			$pdf_link = getRepositoryPdfLink($dm_repo_id);
			if($pdf_link != 'no pdf for this repository'){
				updateRepositoryPdfDownloadCounter($dm_repo_id);

				$file_path_exp = explode('http://3.16.24.200/',$pdf_link);
				$file_path = $_SERVER['DOCUMENT_ROOT'].$file_path_exp[1];

				$files_to_zip[] = $file_path;
			}
		}

		$now_date = date("d_m_Y").'_'.time();

		$result = create_zip($files_to_zip,$_SERVER['DOCUMENT_ROOT'].'downloads/sdb_export_'.$now_date.'.zip');
		if($result == 'success'){
			echo 'http://3.16.24.200/downloads/sdb_export_'.$now_date.'.zip';
		}

	}


	//************************************ SHARE & SEND ***************************************//
	if(isset($_POST["share_and_send"])){

		$translation = getTranslation($_SESSION['lang']);
		$repository_set_url = addRepositorySet($_COOKIE['dm_repos']);

		echo '
			<div class="custom_popup_front">
				<div class="popup_title">'.$translation[62].'</div>
				<div class="popup_description">'.$translation[63].'</div>
				<div class="url_section">
					<div class="form-line">
						<input type="text" name="repository_set_url" id="repository_set_url" readonly="readonly" value="'.$repository_set_url.'">
					</div>
					<div class="button_copy_url">
						<i class="pe-7s-link"></i>
						'.$translation[64].'
					</div>
					<div class="button_copy_url_confirmation"><i class="pe-7s-check"></i> '.$translation[65].'</div>
				</div>

				<div class="popup_title popup_title_second">'.$translation[66].'</div>
				<div class="popup_description">'.$translation[67].'</div>
				<div class="recipients_section">
					<div class="form-line">
						<input type="text" name="share_recipients" id="share_recipients">
						<label for="share_recipients">'.$translation[68].'</label>
						<div class="share_recipients_wrong_email">'.$translation[69].'</div>
					</div>
					<div class="form-line">
						<label for="share_mail_text">'.$translation[70].'</label>
						<textarea id="share_mail_text" name="share_mail_text"></textarea>
					</div>

					<div class="share_button_send">
						<i class="pe-7s-mail"></i>
						'.$translation[71].'
					</div>
				</div>
			</div>

			<div class="custom_popup_front_confirmation">
				<i class="pe-7s-check"></i>
				<span>'.$translation[72].'</span>
			</div>
		';
	}


	//************************************ SEND MAIL: SHARE & SEND ***************************************//
	if(isset($_POST["send_share_mail"])){
		
		require '../../mailer/Exception.php';
		require '../../mailer/PHPMailer.php';
		require '../../mailer/SMTP.php';
		require '../../classes/Html2Text.php';

		$url = '<a href="'.$_POST["repository_set_url"].'" target="_blank">'.$_POST["repository_set_url"].'</a>';
		$url_short = $_POST["repository_set_url"];
		$comment = Html2Text\Html2Text::convert($_POST["share_mail_text"]);

		//creating zip file
		$dm_repos_content = json_decode($_COOKIE['dm_repos'], true);
		foreach ($dm_repos_content as $dm_repo_id) {
			$pdf_link = getRepositoryPdfLink($dm_repo_id);
			if($pdf_link != 'no pdf for this repository'){
				updateRepositoryPdfDownloadCounter($dm_repo_id);

				$file_path_exp = explode('http://3.16.24.200/',$pdf_link);
				$file_path = $_SERVER['DOCUMENT_ROOT'].$file_path_exp[1];

				$files_to_zip[] = $file_path;
			}
		}

		$now_date = date("d_m_Y").'_'.time();

		$result = create_zip($files_to_zip,$_SERVER['DOCUMENT_ROOT'].'downloads/sdb_export_'.$now_date.'.zip');
		if($result == 'success'){
			$fileAttached = $_SERVER['DOCUMENT_ROOT'].'downloads/sdb_export_'.$now_date.'.zip';
		}
		else{
			$fileAttached = 'empty';
		}

		if($_SESSION['lang'] == 1){ //DE

		}

		if($_SESSION['lang'] == 2){ //EN
			$content = '
				<!doctype html>
				<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
					<head>
						<title>
							Ihre Daten werden gelöscht
						</title>
						<!--[if !mso]><!-- -->
						<meta http-equiv="X-UA-Compatible" content="IE=edge">
						<!--<![endif]-->
						<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
						<meta name="viewport" content="width=device-width, initial-scale=1">
						<style type="text/css">
							#outlook a { padding:0; }
							.ReadMsgBody { width:100%; }
							.ExternalClass { width:100%; }
							.ExternalClass * { line-height:100%; }
							body { margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%; }
							table, td { border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt; }
							img { border:0;height:auto;line-height:100%; outline:none;text-decoration:none;-ms-interpolation-mode:bicubic; }
							p { display:block;margin:13px 0; }
						</style>
						<!--[if !mso]><!-->
						<style type="text/css">
							@media only screen and (max-width:480px) {
								@-ms-viewport { width:320px; }
								@viewport { width:320px; }
							}
						</style>
						<!--<![endif]-->
						<!--[if mso]>
						<xml>
						<o:OfficeDocumentSettings>
							<o:AllowPNG/>
							<o:PixelsPerInch>96</o:PixelsPerInch>
						</o:OfficeDocumentSettings>
						</xml>
						<![endif]-->
						<!--[if lte mso 11]>
						<style type="text/css">
							.outlook-group-fix { width:100% !important; }
						</style>
						<![endif]-->
							
						<!--[if !mso]><!-->
							<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet" type="text/css">
							<style type="text/css">
								@import url(https://fonts.googleapis.com/css?family=Roboto:300,400,500,700);
							</style>
						<!--<![endif]-->
						
						<style type="text/css">
							@media only screen and (min-width:480px) {
								.mj-column-per-100 { width:100% !important; max-width: 100%; }
								.mj-column-per-50 { width:50% !important; max-width: 50%; }
							}
						</style>
						<style type="text/css">
							[owa] .mj-column-per-100 { width:100% !important; max-width: 100%; }
							[owa] .mj-column-per-50 { width:50% !important; max-width: 50%; }
						</style>
						<style type="text/css">
							@media only screen and (max-width:480px) {
								table.full-width-mobile { width: 100% !important; }
								td.full-width-mobile { width: auto !important; }
							}
						</style>			
					</head>
					<body style="background-color:#F4F4F4;">
						<div style="background-color:#F4F4F4;">
							<!--[if mso | IE]>
							<table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
								<tr>
									<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
							<![endif]-->
							<div  style="Margin:0px auto;max-width:600px;">	
								<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
									<tbody>
										<tr>
											<td style="direction:ltr;font-size:0px;padding:20px 0px 20px 0px;text-align:center;vertical-align:top;">
												<!--[if mso | IE]>
													<table role="presentation" border="0" cellpadding="0" cellspacing="0">
														<tr>
															<td class="" style="vertical-align:top;width:600px;">
												<![endif]-->
												<div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
													<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
														<tr>
															<td align="center" style="font-size:0px;padding:0px;padding-top:0px;padding-bottom:0px;word-break:break-word;">
																<div style="font-family:Roboto, Helvetica, Arial, sans-serif;font-size:11px;line-height:22px;text-align:center;color:#55575d;">
																	<style></style><p style="margin: 10px 0;">Your data sheets are available for download</p>
																</div>
															</td>
														</tr>
													</table>
												</div>
												<!--[if mso | IE]>
														</td>
													</tr>
												</table>
												<![endif]-->
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<!--[if mso | IE]>
									</td>
								</tr>
							</table>
							
							<table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
								<tr>
									<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
							<![endif]-->
							<div  style="background:#ffffff;background-color:#ffffff;Margin:0px auto;max-width:600px;">
								<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;background-color:#ffffff;width:100%;">
									<tbody>
										<tr>
											<td style="direction:ltr;font-size:0px;padding:20px 0;padding-bottom:20px;text-align:center;vertical-align:top;">
												<!--[if mso | IE]>
													<table role="presentation" border="0" cellpadding="0" cellspacing="0">
													<tr>
													<td class="" style="vertical-align:top;width:300px;">
												<![endif]-->
												<div class="mj-column-per-50 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
													<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
														<tr>
															<td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
																<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
																	<tbody>
																		<tr>
																			<td style="width:250px;">			
																				<img alt="Air Liquide - creative oxygen" height="auto" src="http://ng97.mjt.lu/tplimg/ylg4/b/hqp7/2qqj.png" style="border:none;display:block;outline:none;text-decoration:none;height:auto;width:100%;" title="" width="250" />
																			</td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr>
													</table>
												</div>
												<!--[if mso | IE]>
													</td>
													<td class="" style="vertical-align:top;width:300px;">
												<![endif]-->
												<div class="mj-column-per-50 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
													<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
														<tr>
															<td align="left" style="font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:0px;word-break:break-word;">		
																<div style="font-family:Roboto, Helvetica, Arial, sans-serif;font-size:13px;line-height:22px;text-align:left;color:#55575d;">
																	<style></style><p style="margin: 10px 0;"><span style="color:#ffffff">-</span></p>
																</div>
															</td>
														</tr>
													</table>
												</div>
												<!--[if mso | IE]>
												</td>
												</tr>
												</table>
												<![endif]-->
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<!--[if mso | IE]>
									</td>
								</tr>
							</table>
							<table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
								<tr>
									<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
							<![endif]-->
							<div  style="Margin:0px auto;max-width:600px;">	
								<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
									<tbody>
										<tr>
											<td style="direction:ltr;font-size:0px;padding:20px 0;padding-bottom:0px;padding-top:0px;text-align:center;vertical-align:top;">
												<!--[if mso | IE]>
													<table role="presentation" border="0" cellpadding="0" cellspacing="0">			
														<tr>
														<td class="" style="vertical-align:top;width:600px;">
												<![endif]-->	
												<div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
													<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
														<tr>
															<td align="center" style="font-size:0px;padding:10px 25px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;word-break:break-word;">		
																<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
																	<tbody>
																		<tr>
																			<td style="width:600px;">	
																				<img alt="Air Liquide - Jetzt Datenblatt herunterladen" height="auto" src="http://ng97.mjt.lu/tplimg/ylg4/b/74uq/uh2wl.jpeg" style="border:none;display:block;outline:none;text-decoration:none;height:auto;width:100%;" title="" width="600"/>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr>				
													</table>
												</div>
												<!--[if mso | IE]>
													</td>	
												</tr>
												</table>
												<![endif]-->
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<!--[if mso | IE]>
									</td>
								</tr>
							</table>
							<table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
								<tr>
									<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
							<![endif]-->
							<div  style="background:#ffffff;background-color:#ffffff;Margin:0px auto;max-width:600px;">
								<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;background-color:#ffffff;width:100%;">
									<tbody>
										<tr>
											<td style="direction:ltr;font-size:0px;padding:20px 0px 20px 0px;padding-bottom:0px;text-align:center;vertical-align:top;">
												<!--[if mso | IE]>
													<table role="presentation" border="0" cellpadding="0" cellspacing="0">		
													<tr>
													<td class="" style="vertical-align:top;width:600px;">
												<![endif]-->
												<div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">	
													<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
														<tr>
															<td align="left" style="font-size:0px;padding:0px 25px 0px 25px;padding-top:0px;padding-bottom:0px;word-break:break-word;">
																<div style="font-family:Roboto, Helvetica, Arial, sans-serif;font-size:13px;line-height:22px;text-align:left;color:#55575d;">
																	<style></style><p style="margin: 10px 0;">Dear customer,</p><p style="margin: 10px 0;">Your data sheets are now available for download, please find them in the attachment. Alternatively, you can download the data sheets directly from our website using the button below,&nbsp;you can also add or remove additional data sheets.</p><p style="margin: 10px 0;">Additional note:<br>'.$comment.'</p><p style="margin: 10px 0;">It may be necessary to unpack (.zip format) the data sheets using software such as <a target="_blank" href="https://www.winzip.com">Winzip</a>.</p>
																</div>
															</td>
														</tr>
													</table>
												</div>
												<!--[if mso | IE]>
													</td>
													</tr>
													</table>
												<![endif]-->
											</td>
										</tr>
									</tbody>
								</table>	
							</div>
							<!--[if mso | IE]>
								</td>
								</tr>
								</table>
								<table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
								<tr>
									<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
							<![endif]-->
							<div  style="Margin:0px auto;max-width:600px;">
								<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
									<tbody>
										<tr>
											<td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;vertical-align:top;">
												<!--[if mso | IE]>
													<table role="presentation" border="0" cellpadding="0" cellspacing="0">
													<tr>
													<td class="" style="vertical-align:top;width:600px;">
												<![endif]-->	
												<div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
													<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
														<tr>
															<td align="center" vertical-align="middle" style="font-size:0px;padding:10px 25px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;word-break:break-word;">			
																<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:separate;line-height:100%;">
																	<tr>
																		<td align="center" bgcolor="#375f9b" role="presentation" style="border:none;border-radius:3px;cursor:auto;padding:10px 25px;background:#375f9b;" valign="middle">
																			<a href="'.$url_short.'" style="background:#375f9b;color:#ffffff;font-family:Arial, sans-serif;font-size:13px;font-weight:normal;line-height:120%;Margin:0;text-decoration:none;text-transform:none;" target="_blank">
																				Download data sheets
																			</a>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>
													</table>
												</div>
												<!--[if mso | IE]>
													</td>
													</tr>
													</table>
												<![endif]-->
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<!--[if mso | IE]>
									</td>
								</tr>
								</table>	
								<table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
								<tr>
									<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
							<![endif]-->	
							<div style="Margin:0px auto;max-width:600px;">
								<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
									<tbody>
										<tr>
											<td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;vertical-align:top;">
												<!--[if mso | IE]>
													<table role="presentation" border="0" cellpadding="0" cellspacing="0">
													<tr>
													<td class="" style="vertical-align:top;width:600px;">
												<![endif]-->		
												<div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">	
													<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
														<tr>
															<td align="left" style="font-size:0px;padding:10px 25px;padding-top:0px;padding-right:11px;padding-bottom:0px;padding-left:11px;word-break:break-word;">		
																<div style="font-family:Roboto, Helvetica, Arial, sans-serif;font-size:13px;line-height:24px;text-align:left;color:#55575d;">
																	<style></style><p style="margin: 10px 0;"><b>Legal notice:</b></p><p style="margin: 10px 0;">Air Liquide Austria GmbH<br>A-2320 Schwechat, Sendnergasse 30<br>Phone.: 0810 242 427</p><p style="margin: 10px 0;"><a target="_blank" href="https://industrie.airliquide.at">https://industrie.airliquide.at</a><br><a href="mailto:webmaster.at@airliquide.com">webmaster.at@airliquide.com</a></p><p style="margin: 10px 0;">Air Liquide Austria GmbH<br>FB 86620 h LG Korneuburg<br>UID Nr.: ATU 14200201</p><p style="margin: 10px 0;">Exekutive: Sebastian Jureczek</p><p style="margin: 10px 0;">You receive this e-mail because either you, a colleague of yours, or an Air Liquide employee has requested data sheets for you to download.</p>
																</div>
															</td>
														</tr>
													</table>
												</div>
												<!--[if mso | IE]>
													</td>
													</tr>
													</table>
												<![endif]-->
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<!--[if mso | IE]>
								</td>
								</tr>
								</table>
								<table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
								<tr>
								<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
							<![endif]-->
							<div  style="Margin:0px auto;max-width:600px;">
								<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
									<tbody>
										<tr>
											<td style="direction:ltr;font-size:0px;padding:20px 0px 20px 0px;text-align:center;vertical-align:top;">
												<!--[if mso | IE]>
													<table role="presentation" border="0" cellpadding="0" cellspacing="0">	
													<tr>
													<td class="" style="vertical-align:top;width:600px;">
												<![endif]-->
												<div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
													<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
														<tbody>
															<tr>
																<td style="vertical-align:top;padding:0;">		
																	<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="" width="100%">
																	</table>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
												<!--[if mso | IE]>
													</td>
													</tr>
													</table>
												<![endif]-->
											</td>
										</tr>
									</tbody>
								</table>	
							</div>
							<!--[if mso | IE]>
								</td>
								</tr>
								</table>
								<table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
								<tr>
								<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
							<![endif]-->
							<div  style="Margin:0px auto;max-width:600px;">
								<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
									<tbody>
										<tr>
											<td style="direction:ltr;font-size:0px;padding:20px 0px 20px 0px;text-align:center;vertical-align:top;">
												<!--[if mso | IE]>
													<table role="presentation" border="0" cellpadding="0" cellspacing="0">
													<tr>
													<td class="" style="vertical-align:top;width:600px;">
												<![endif]-->	
												<div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
													<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
														<tbody>
															<tr>
																<td  style="vertical-align:top;padding:0;">		
																	<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="" width="100%">
																	</table>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
												<!--[if mso | IE]>
													</td>
													</tr>
													</table>
												<![endif]-->
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<!--[if mso | IE]>
								</td>
								</tr>
								</table>
							<![endif]-->
						</div>
					</body>
				</html>
			';
		}
		else{
			$content = '
			<!doctype html>
			<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
				<head>
					<title>
						Ihre Datenblätter stehen zum Download bereit
					</title>
					<!--[if !mso]><!-- -->
					<meta http-equiv="X-UA-Compatible" content="IE=edge">
					<!--<![endif]-->
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
					<meta name="viewport" content="width=device-width, initial-scale=1">
					<style type="text/css">
						#outlook a { padding:0; }
						.ReadMsgBody { width:100%; }
						.ExternalClass { width:100%; }
						.ExternalClass * { line-height:100%; }
						body { margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%; }
						table, td { border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt; }
						img { border:0;height:auto;line-height:100%; outline:none;text-decoration:none;-ms-interpolation-mode:bicubic; }
						p { display:block;margin:13px 0; }
					</style>
					<!--[if !mso]><!-->
					<style type="text/css">
						@media only screen and (max-width:480px) {
						@-ms-viewport { width:320px; }
						@viewport { width:320px; }
						}
					</style>
					<!--<![endif]-->
					<!--[if mso]>
					<xml>
						<o:OfficeDocumentSettings>
							<o:AllowPNG/>
							<o:PixelsPerInch>96</o:PixelsPerInch>
						</o:OfficeDocumentSettings>
					</xml>
					<![endif]-->
					<!--[if lte mso 11]>
					<style type="text/css">
						.outlook-group-fix { width:100% !important; }
					</style>
					<![endif]-->
					<!--[if !mso]><!-->
					<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet" type="text/css">
					<style type="text/css">
						@import url(https://fonts.googleapis.com/css?family=Roboto:300,400,500,700);
					</style>
					<!--<![endif]-->
					<style type="text/css">
						@media only screen and (min-width:480px) {
						.mj-column-per-100 { width:100% !important; max-width: 100%; }
						.mj-column-per-50 { width:50% !important; max-width: 50%; }
						}
					</style>
					<style type="text/css">
						[owa] .mj-column-per-100 { width:100% !important; max-width: 100%; }
						[owa] .mj-column-per-50 { width:50% !important; max-width: 50%; }
					</style>
					<style type="text/css">
						@media only screen and (max-width:480px) {
						table.full-width-mobile { width: 100% !important; }
						td.full-width-mobile { width: auto !important; }
						}
					</style>
				</head>
				<body style="background-color:#F4F4F4;">
					<div style="background-color:#F4F4F4;">
						<!--[if mso | IE]>
							<table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
							<tr>
							<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
						<![endif]-->
						<div  style="Margin:0px auto;max-width:600px;">
							<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
								<tbody>
									<tr>
										<td style="direction:ltr;font-size:0px;padding:20px 0px 20px 0px;text-align:center;vertical-align:top;">
											<!--[if mso | IE]>
												<table role="presentation" border="0" cellpadding="0" cellspacing="0">
												<tr>
												<td class="" style="vertical-align:top;width:600px;">
											<![endif]-->
											<div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
												<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
													<tr>
														<td align="center" style="font-size:0px;padding:0px;padding-top:0px;padding-bottom:0px;word-break:break-word;">
															<div style="font-family:Roboto, Helvetica, Arial, sans-serif;font-size:11px;line-height:22px;text-align:center;color:#55575d;">
																<style></style>
																<p style="margin: 10px 0;">Ihre Datenblätter stehen zum Download bereit</p>
															</div>
														</td>
													</tr>
												</table>
											</div>
											<!--[if mso | IE]>
												</td>
												</tr>
												</table>
											<![endif]-->
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!--[if mso | IE]>
							</td>
							</tr>
							</table>
							<table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
							<tr>
							<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
						<![endif]-->
						<div  style="background:#ffffff;background-color:#ffffff;Margin:0px auto;max-width:600px;">
							<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;background-color:#ffffff;width:100%;">
								<tbody>
									<tr>
										<td style="direction:ltr;font-size:0px;padding:20px 0;padding-bottom:20px;text-align:center;vertical-align:top;">
											<!--[if mso | IE]>
												<table role="presentation" border="0" cellpadding="0" cellspacing="0">
												<tr>
												<td class="" style="vertical-align:top;width:300px;">
											<![endif]-->
											<div class="mj-column-per-50 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
												<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
													<tr>
														<td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
															<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
																<tbody>
																	<tr>
																		<td style="width:250px;">
																			<img alt="Air Liquide - creative oxygen" height="auto" src="http://ng97.mjt.lu/tplimg/ylg4/b/hqp7/2qqj.png" style="border:none;display:block;outline:none;text-decoration:none;height:auto;width:100%;" title="" width="250" />
																		</td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</table>
											</div>
											<!--[if mso | IE]>
												</td>
												<td class="" style="vertical-align:top;width:300px;">
											<![endif]-->
											<div class="mj-column-per-50 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
												<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
													<tr>
														<td align="left" style="font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:0px;word-break:break-word;">
															<div style="font-family:Roboto, Helvetica, Arial, sans-serif;font-size:13px;line-height:22px;text-align:left;color:#55575d;">
																<style></style>
																<p style="margin: 10px 0;"><span style="color:#ffffff">-</span></p>
															</div>
														</td>
													</tr>
												</table>
											</div>
											<!--[if mso | IE]>
												</td>
												</tr>
												</table>
											<![endif]-->
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!--[if mso | IE]>
							</td>
							</tr>
							</table>
							<table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
							<tr>
							<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
						<![endif]-->
						<div style="Margin:0px auto;max-width:600px;">
							<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
								<tbody>
									<tr>
										<td style="direction:ltr;font-size:0px;padding:20px 0;padding-bottom:0px;padding-top:0px;text-align:center;vertical-align:top;">
											<!--[if mso | IE]>
												<table role="presentation" border="0" cellpadding="0" cellspacing="0">
												<tr>
												<td class="" style="vertical-align:top;width:600px;">
											<![endif]-->
											<div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
												<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
													<tr>
														<td align="center" style="font-size:0px;padding:10px 25px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;word-break:break-word;">
															<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
																<tbody>
																	<tr>
																		<td style="width:600px;">
																			<img alt="Air Liquide - Jetzt Datenblatt herunterladen" height="auto" src="http://ng97.mjt.lu/tplimg/ylg4/b/q0z3/uhm9g.jpeg" style="border:none;display:block;outline:none;text-decoration:none;height:auto;width:100%;" title="" width="600"/>
																		</td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</table>
											</div>
											<!--[if mso | IE]>
												</td>
												</tr>
												</table>
											<![endif]-->
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!--[if mso | IE]>
							</td>
							</tr>
							</table>
							<table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
							<tr>
							<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
						<![endif]-->
						<div style="background:#ffffff;background-color:#ffffff;Margin:0px auto;max-width:600px;">
							<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;background-color:#ffffff;width:100%;">
								<tbody>
									<tr>
										<td style="direction:ltr;font-size:0px;padding:20px 0px 20px 0px;padding-bottom:0px;text-align:center;vertical-align:top;">
											<!--[if mso | IE]>
												<table role="presentation" border="0" cellpadding="0" cellspacing="0">
												<tr>
												<td class="" style="vertical-align:top;width:600px;">
											<![endif]-->
											<div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
												<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
													<tr>
														<td align="left" style="font-size:0px;padding:0px 25px 0px 25px;padding-top:0px;padding-bottom:0px;word-break:break-word;">
															<div style="font-family:Roboto, Helvetica, Arial, sans-serif;font-size:13px;line-height:22px;text-align:left;color:#55575d;">
																<style></style>
																<p style="margin: 10px 0;">Sehr geehrter Kunde,</p>
																<p style="margin: 10px 0;">Ihre Datenblätter befinden sich im Anhang zum Download. Alternativ können Sie die Datenblätter direkt von unserer Webseite über den nachfolgenden Button herunterladen, ebenfalls können Sie weitere Datenblätter hinzufügen oder entfernen.<br><br>Zusätzliche Anmerkung:<br>
																'.$comment.'</p>
																<p style="margin: 10px 0;">Eventuell ist für das Entpacken (.zip Format) von den Datenblättern&nbsp;eine Software wie <a target="_blank" href="https://www.winzip.com">Winzip</a> erforderlich.</p>
															</div>
														</td>
													</tr>
												</table>
											</div>
											<!--[if mso | IE]>
												</td>
												</tr>
												</table>
											<![endif]-->
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!--[if mso | IE]>
							</td>
							</tr>
							</table>
							<table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
							<tr>
							<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
						<![endif]-->
						<div style="Margin:0px auto;max-width:600px;">
							<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
								<tbody>
									<tr>
										<td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;vertical-align:top;">
											<!--[if mso | IE]>
												<table role="presentation" border="0" cellpadding="0" cellspacing="0">
												<tr>
												<td class="" style="vertical-align:top;width:600px;">
											<![endif]-->
											<div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
												<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
													<tr>
														<td align="center" vertical-align="middle" style="font-size:0px;padding:10px 25px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;word-break:break-word;">
															<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:separate;line-height:100%;">
																<tr>
																	<td align="center" bgcolor="#375f9b" role="presentation" style="border:none;border-radius:3px;cursor:auto;padding:10px 25px;background:#375f9b;" valign="middle">
																		<a href="'.$url_short.'" style="background:#375f9b;color:#ffffff;font-family:Arial, sans-serif;font-size:13px;font-weight:normal;line-height:120%;Margin:0;text-decoration:none;text-transform:none;" target="_blank">
																			Datenblätter jetzt herunterladen
																		</a>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</div>
											<!--[if mso | IE]>
												</td>
												</tr>
												</table>
											<![endif]-->
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!--[if mso | IE]>
							</td>
							</tr>
							</table>
							<table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
							<tr>
							<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
						<![endif]-->
						<div style="Margin:0px auto;max-width:600px;">
							<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
								<tbody>
									<tr>
										<td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;vertical-align:top;">
											<!--[if mso | IE]>
												<table role="presentation" border="0" cellpadding="0" cellspacing="0">
												<tr>
												<td class="" style="vertical-align:top;width:600px;">
											<![endif]-->
											<div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
												<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
													<tr>
														<td align="left" style="font-size:0px;padding:10px 25px;padding-top:0px;padding-right:11px;padding-bottom:0px;padding-left:11px;word-break:break-word;">
															<div style="font-family:Roboto, Helvetica, Arial, sans-serif;font-size:13px;line-height:24px;text-align:left;color:#55575d;">
																<style></style>
																<p style="margin: 10px 0;"><b>Impressum:</b></p>
																<p style="margin: 10px 0;">Air Liquide Austria GmbH<br>A-2320 Schwechat, Sendnergasse 30<br>Tel.: 0810 242 427</p>
																<p style="margin: 10px 0;"><a target="_blank" href="https://industrie.airliquide.at">https://industrie.airliquide.at</a><br><a href="mailto:webmaster.at@airliquide.com">webmaster.at@airliquide.com</a></p>
																<p style="margin: 10px 0;">Air Liquide Austria GmbH<br>FB 86620 h LG Korneuburg<br>UID Nr.: ATU 14200201</p>
																<p style="margin: 10px 0;">Geschäftsführung: Sebastian Jureczek</p>
																<p style="margin: 10px 0;">Sie erhalten diese E-Mail, weil entweder Sie, ein Kollege von Ihnen oder ein Air Liquide Mitarbeiter Datenblätter für Sie zum Download angefordert hat.</p>
															</div>
														</td>
													</tr>
												</table>
											</div>
											<!--[if mso | IE]>
												</td>
												</tr>
												</table>
											<![endif]-->
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!--[if mso | IE]>
							</td>
							</tr>
							</table>
							<table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
							<tr>
							<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
						<![endif]-->
						<div style="Margin:0px auto;max-width:600px;">
							<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
								<tbody>
									<tr>
										<td style="direction:ltr;font-size:0px;padding:20px 0px 20px 0px;text-align:center;vertical-align:top;">
											<!--[if mso | IE]>
												<table role="presentation" border="0" cellpadding="0" cellspacing="0">
												<tr>
												<td class="" style="vertical-align:top;width:600px;">
											<![endif]-->
											<div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
												<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
													<tbody>
														<tr>
															<td style="vertical-align:top;padding:0;">
																<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="" width="100%">
																</table>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
											<!--[if mso | IE]>
												</td>
												</tr>
												</table>
											<![endif]-->
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!--[if mso | IE]>
							</td>
							</tr>
							</table>
						<![endif]-->
					</div>
				</body>
			</html>
			';
		}
		
		$emails = explode(",",str_replace(' ', '', $_POST["share_recipients"]));

		foreach ($emails as $email) {
			$mail = new PHPMailer\PHPMailer\PHPMailer();                              // Passing `true` enables exceptions
			try {
					//Server settings
					$mail->SMTPDebug = 2;                                 // Enable verbose debug output
					$mail->isSMTP();                                      // Set mailer to use SMTP
					$mail->Host = 'in-v3.mailjet.com';  // Specify main and backup SMTP servers
					$mail->SMTPAuth = true;                               // Enable SMTP authentication
					$mail->Username = '26689254bd824891ea9d39de97e2ffb8';                 // SMTP username
					$mail->Password = 'f25f14465bc84da8c667fb2a086ff215';                           // SMTP password
					$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
					$mail->Port = 587;                                    // TCP port to connect to

					//Recipients
					$mail->setFrom('webmaster.at@airliquide.com', 'Air Liquide Austria');
					$mail->addAddress($email);     // Add a recipient
					$mail->addReplyTo('webmaster.at@airliquide.com', 'airliquide.com');

					//Content
					$mail->isHTML(true);                                  // Set email format to HTML
					$mail->CharSet = 'UTF-8';
					$mail->Subject = 'SDB';
					$mail->Body    = $content;
					$mail->AltBody = '';

					//Attachement
					if($fileAttached != 'empty'){
						$mail->addAttachment($fileAttached);
					}

					$mail->send();

					echo 'mail sent';

			} catch (Exception $e) {
					echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
			}
		}

	}


	//************************************ SEND MAIL: CONTACT ***************************************//
	if(isset($_POST["send_contact_mail"])){
		
		require '../../mailer/Exception.php';
		require '../../mailer/PHPMailer.php';
		require '../../mailer/SMTP.php';
		require '../../classes/Html2Text.php';

		$comment = Html2Text\Html2Text::convert($_POST["contact_msg"]);

		$contact_name = $_POST["contact_name"];
		$contact_mail = $_POST["contact_mail"];
		$contact_title = $_POST["contact_title"];
		$contact_phone = $_POST["contact_phone"];
		$contact_name = $_POST["contact_name"];

		$content = '
		<!doctype html>
		<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
		<head>
		  <title>Ihre Daten werden gelöscht</title>
		  <!--[if !mso]><!-- -->
		  <meta http-equiv="X-UA-Compatible" content="IE=edge">
		  <!--<![endif]-->
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<style type="text/css">
		  #outlook a { padding: 0; }
		  .ReadMsgBody { width: 100%; }
		  .ExternalClass { width: 100%; }
		  .ExternalClass * { line-height:100%; }
		  body { margin: 0; padding: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
		  table, td { border-collapse:collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
		  img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
		  p { display: block; margin: 13px 0; }
		</style>
		<!--[if !mso]><!-->
		<style type="text/css">
		  @media only screen and (max-width:480px) {
		    @-ms-viewport { width:320px; }
		    @viewport { width:320px; }
		  }
		</style>
		<!--<![endif]-->
		<!--[if mso]>
		<xml>
		  <o:OfficeDocumentSettings>
		    <o:AllowPNG/>
		    <o:PixelsPerInch>96</o:PixelsPerInch>
		  </o:OfficeDocumentSettings>
		</xml>
		<![endif]-->
		<!--[if lte mso 11]>
		<style type="text/css">
		  .outlook-group-fix {
		    width:100% !important;
		  }
		</style>
		<![endif]-->

		<!--[if !mso]><!-->
		    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet" type="text/css">
		    <style type="text/css">

		        @import url(https://fonts.googleapis.com/css?family=Roboto:300,400,500,700);

		    </style>
		  <!--<![endif]--><style type="text/css">
		  @media only screen and (min-width:480px) {
		    .mj-column-per-100 { width:100%!important; }
		.mj-column-per-50 { width:50%!important; }
		  }
		</style>
		</head>
		<body style="background: #F4F4F4;">
		  
		  <div class="mj-container" style="background-color:#F4F4F4;"><!--[if mso | IE]>
		      <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">
		        <tr>
		          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
		      <![endif]--><div style="margin:0px auto;max-width:600px;"><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:20px 0px 20px 0px;"><!--[if mso | IE]>
		      <table role="presentation" border="0" cellpadding="0" cellspacing="0">
		        <tr>
		          <td style="vertical-align:top;width:600px;">
		      <![endif]--><div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:0px;padding-top:0px;padding-bottom:0px;" align="center"><div style="cursor:auto;color:#55575d;font-family:Roboto, Helvetica, Arial, sans-serif;font-size:11px;line-height:22px;text-align:center;"><style></style><p style="margin: 10px 0;"></p></div></td></tr></tbody></table></div><!--[if mso | IE]>
		      </td></tr></table>
		      <![endif]--></td></tr></tbody></table></div><!--[if mso | IE]>
		      </td></tr></table>
		      <![endif]-->
		      <!--[if mso | IE]>
		      <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">
		        <tr>
		          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
		      <![endif]--><div style="margin:0px auto;max-width:600px;background:#ffffff;"><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:#ffffff;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:20px 0px;padding-bottom:20px;"><!--[if mso | IE]>
		      <table role="presentation" border="0" cellpadding="0" cellspacing="0">
		        <tr>
		          <td style="vertical-align:top;width:300px;">
		      <![endif]--><div class="mj-column-per-50 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:10px 25px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;" align="center" border="0"><tbody><tr><td style="width:250px;"><img alt="Air Liquide - creative oxygen" title="" height="auto" src="http://ng97.mjt.lu/tplimg/ylg4/b/hqp7/2qqj.png" style="border:none;border-radius:0px;display:block;font-size:13px;outline:none;text-decoration:none;width:100%;height:auto;" width="250"></td></tr></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]>
		      </td><td style="vertical-align:top;width:300px;">
		      <![endif]--><div class="mj-column-per-50 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:0px;" align="left"><div style="cursor:auto;color:#55575d;font-family:Roboto, Helvetica, Arial, sans-serif;font-size:13px;line-height:22px;text-align:left;"><style></style><p style="margin: 10px 0;"><span style="color:#ffffff">-</span></p></div></td></tr></tbody></table></div><!--[if mso | IE]>
		      </td></tr></table>
		      <![endif]--></td></tr></tbody></table></div><!--[if mso | IE]>
		      </td></tr></table>
		      <![endif]-->
		      <!--[if mso | IE]>
		      <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">
		        <tr>
		          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
		      <![endif]--><div style="margin:0px auto;max-width:600px;"><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:20px 0px;padding-bottom:0px;padding-top:0px;"><!--[if mso | IE]>
		      <table role="presentation" border="0" cellpadding="0" cellspacing="0">
		        <tr>
		          <td style="vertical-align:top;width:600px;">
		      <![endif]--><div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:0px;padding-right:0px;padding-left:0px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;" align="center" border="0"><tbody><tr><td style="width:600px;"></td></tr></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]>
		      </td></tr></table>
		      <![endif]--></td></tr></tbody></table></div><!--[if mso | IE]>
		      </td></tr></table>
		      <![endif]-->
		      <!--[if mso | IE]>
		      <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">
		        <tr>
		          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
		      <![endif]--><div style="margin:0px auto;max-width:600px;background:#ffffff;"><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:#ffffff;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:20px 0px 20px 0px;padding-bottom:0px;"><!--[if mso | IE]>
		      <table role="presentation" border="0" cellpadding="0" cellspacing="0">
		        <tr>
		          <td style="vertical-align:top;width:600px;">
		      <![endif]--><div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:0px 25px 0px 25px;padding-top:0px;padding-bottom:0px;" align="left"><div style="cursor:auto;color:#55575d;font-family:Roboto, Helvetica, Arial, sans-serif;font-size:13px;line-height:22px;text-align:left;"><style></style><p style="margin: 10px 0;">Message from contact form</p><p style="margin: 10px 0;">Name: '.$contact_name.'<br>Mail: '.$contact_mail.'<br>Title: '.$contact_title.'<br>Phone: '.$contact_phone.'<br>Comment: <br>'.$comment.'</p></div></td></tr></tbody></table></div><!--[if mso | IE]>
		      </td></tr></table>
		      <![endif]--></td></tr></tbody></table></div><!--[if mso | IE]>
		      </td></tr></table>
		      <![endif]-->
		      <!--[if mso | IE]>
		      <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">
		        <tr>
		          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
		      <![endif]--><div style="margin:0px auto;max-width:600px;"><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:20px 0px;"><!--[if mso | IE]>
		      <table role="presentation" border="0" cellpadding="0" cellspacing="0">
		        <tr>
		          <td style="vertical-align:top;width:600px;">
		      <![endif]--><div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:0px;padding-right:11px;padding-left:11px;" align="left"><div style="cursor:auto;color:#55575d;font-family:Roboto, Helvetica, Arial, sans-serif;font-size:13px;line-height:24px;text-align:left;"><style></style><p style="margin: 10px 0;"><b>Impressum:</b></p><p style="margin: 10px 0;">Air Liquide Austria GmbH<br>A-2320 Schwechat, Sendnergasse 30<br>Tel.: 0810 242 427</p><p style="margin: 10px 0;"><a target="_blank" href="https://industrie.airliquide.at">https://industrie.airliquide.at</a><br><a href="mailto:webmaster.at@airliquide.com">webmaster.at@airliquide.com</a></p><p style="margin: 10px 0;">Air Liquide Austria GmbH<br>FB 86620 h LG Korneuburg<br>UID Nr.: ATU 14200201</p><p style="margin: 10px 0;">Geschäftsführung: Sebastian Jureczek</p><p style="margin: 10px 0;">Sie erhalten diese E-Mail, weil Sie in einer aktiven Geschäftsbeziehung mit der Air Liquide stehen.</p><p style="margin: 10px 0;">Diese E-Mail kann vertrauliche und/oder rechtlich geschützte Informationen enthalten. Wenn Sie nicht der richtige Adressat sind oder diese E-Mail irrtümlich erhalten haben, informieren Sie bitte sofort den Absender und vernichten Sie diese E-Mail. Das unerlaubte Kopieren sowie die unbefugte Weitergabe dieser E-Mail ist nicht gestattet.</p><p style="margin: 10px 0; text-align: center;">Benachrichtigung <a target="_blank" href="[[UNSUB_LINK_EN]]">abmelden</a></p></div></td></tr></tbody></table></div><!--[if mso | IE]>
		      </td></tr></table>
		      <![endif]--></td></tr></tbody></table></div><!--[if mso | IE]>
		      </td></tr></table>
		      <![endif]--></div>
		</body>
		</html>';

			$email = 'webmaster.at@airliquide.com';

			$mail = new PHPMailer\PHPMailer\PHPMailer();                              // Passing `true` enables exceptions
			try {
					//Server settings
					$mail->SMTPDebug = 2;                                 // Enable verbose debug output
					$mail->isSMTP();                                      // Set mailer to use SMTP
					$mail->Host = 'in-v3.mailjet.com';  // Specify main and backup SMTP servers
					$mail->SMTPAuth = true;                               // Enable SMTP authentication
					$mail->Username = '26689254bd824891ea9d39de97e2ffb8';                 // SMTP username
					$mail->Password = 'f25f14465bc84da8c667fb2a086ff215';                           // SMTP password
					$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
					$mail->Port = 587;                                    // TCP port to connect to

					//Recipients
					$mail->setFrom('webmaster.at@airliquide.com', 'Air Liquide Austria');
					$mail->addAddress($email);     // Add a recipient
					$mail->addReplyTo('webmaster.at@airliquide.com', 'airliquide.com');

					//Content
					$mail->isHTML(true);                                  // Set email format to HTML
					$mail->CharSet = 'UTF-8';
					$mail->Subject = 'Kontakt';
					$mail->Body    = $content;
					$mail->AltBody = '';

					$mail->send();

					echo 'mail sent';

			} catch (Exception $e) {
					echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
			}
		

	}


	//************************************ SEND RATING ***************************************//
	if(isset($_POST["send_rating"])){
		$rating = $_POST["rating"];
		$rating_comment = $_POST["rating_comment"];

		sendRating($rating,$rating_comment);

		$feedback_users = getNotificationUsers();
		if($feedback_users != 'empty'){
			require '../../mailer/Exception.php';
			require '../../mailer/PHPMailer.php';
			require '../../mailer/SMTP.php';
			require '../../classes/Html2Text.php';

			$comment = Html2Text\Html2Text::convert($rating_comment);

			$content = '
				<!doctype html>
				<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
					<head>
						<title>
							Ihre Daten werden gelöscht
						</title>
						<!--[if !mso]><!-- -->
						<meta http-equiv="X-UA-Compatible" content="IE=edge">
						<!--<![endif]-->
						<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
						<meta name="viewport" content="width=device-width, initial-scale=1">
						<style type="text/css">
							#outlook a { padding:0; }
							.ReadMsgBody { width:100%; }
							.ExternalClass { width:100%; }
							.ExternalClass * { line-height:100%; }
							body { margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%; }
							table, td { border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt; }
							img { border:0;height:auto;line-height:100%; outline:none;text-decoration:none;-ms-interpolation-mode:bicubic; }
							p { display:block;margin:13px 0; }
						</style>
						<!--[if !mso]><!-->
						<style type="text/css">
							@media only screen and (max-width:480px) {
								@-ms-viewport { width:320px; }
								@viewport { width:320px; }
							}
						</style>
						<!--<![endif]-->
						<!--[if mso]>
						<xml>
						<o:OfficeDocumentSettings>
							<o:AllowPNG/>
							<o:PixelsPerInch>96</o:PixelsPerInch>
						</o:OfficeDocumentSettings>
						</xml>
						<![endif]-->
						<!--[if lte mso 11]>
						<style type="text/css">
							.outlook-group-fix { width:100% !important; }
						</style>
						<![endif]-->
							
						<!--[if !mso]><!-->
							<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet" type="text/css">
							<style type="text/css">
								@import url(https://fonts.googleapis.com/css?family=Roboto:300,400,500,700);
							</style>
						<!--<![endif]-->
						
						<style type="text/css">
							@media only screen and (min-width:480px) {
								.mj-column-per-100 { width:100% !important; max-width: 100%; }
								.mj-column-per-50 { width:50% !important; max-width: 50%; }
							}
						</style>
						<style type="text/css">
							[owa] .mj-column-per-100 { width:100% !important; max-width: 100%; }
							[owa] .mj-column-per-50 { width:50% !important; max-width: 50%; }
						</style>
						<style type="text/css">
							@media only screen and (max-width:480px) {
								table.full-width-mobile { width: 100% !important; }
								td.full-width-mobile { width: auto !important; }
							}
						</style>			
					</head>
					<body style="background-color:#F4F4F4;">
						<div style="background-color:#F4F4F4;">
							<!--[if mso | IE]>
							<table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
								<tr>
									<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
							<![endif]-->
							<div  style="Margin:0px auto;max-width:600px;">	
								<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
									<tbody>
										<tr>
											<td style="direction:ltr;font-size:0px;padding:20px 0px 20px 0px;text-align:center;vertical-align:top;">
												<!--[if mso | IE]>
													<table role="presentation" border="0" cellpadding="0" cellspacing="0">
														<tr>
															<td class="" style="vertical-align:top;width:600px;">
												<![endif]-->
												<div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
													<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
														<tr>
															<td align="center" style="font-size:0px;padding:0px;padding-top:0px;padding-bottom:0px;word-break:break-word;">
																<div style="font-family:Roboto, Helvetica, Arial, sans-serif;font-size:11px;line-height:22px;text-align:center;color:#55575d;">
																	<style></style><p style="margin: 10px 0;">Your data sheets are available for download</p>
																</div>
															</td>
														</tr>
													</table>
												</div>
												<!--[if mso | IE]>
														</td>
													</tr>
												</table>
												<![endif]-->
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<!--[if mso | IE]>
									</td>
								</tr>
							</table>
							
							<table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
								<tr>
									<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
							<![endif]-->
							<div  style="background:#ffffff;background-color:#ffffff;Margin:0px auto;max-width:600px;">
								<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;background-color:#ffffff;width:100%;">
									<tbody>
										<tr>
											<td style="direction:ltr;font-size:0px;padding:20px 0;padding-bottom:20px;text-align:center;vertical-align:top;">
												<!--[if mso | IE]>
													<table role="presentation" border="0" cellpadding="0" cellspacing="0">
													<tr>
													<td class="" style="vertical-align:top;width:300px;">
												<![endif]-->
												<div class="mj-column-per-50 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
													<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
														<tr>
															<td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
																<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
																	<tbody>
																		<tr>
																			<td style="width:250px;">			
																				<img alt="Air Liquide - creative oxygen" height="auto" src="http://ng97.mjt.lu/tplimg/ylg4/b/hqp7/2qqj.png" style="border:none;display:block;outline:none;text-decoration:none;height:auto;width:100%;" title="" width="250" />
																			</td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr>
													</table>
												</div>
												<!--[if mso | IE]>
													</td>
													<td class="" style="vertical-align:top;width:300px;">
												<![endif]-->
												<div class="mj-column-per-50 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
													<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
														<tr>
															<td align="left" style="font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:0px;word-break:break-word;">		
																<div style="font-family:Roboto, Helvetica, Arial, sans-serif;font-size:13px;line-height:22px;text-align:left;color:#55575d;">
																	<style></style><p style="margin: 10px 0;"><span style="color:#ffffff">-</span></p>
																</div>
															</td>
														</tr>
													</table>
												</div>
												<!--[if mso | IE]>
												</td>
												</tr>
												</table>
												<![endif]-->
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<!--[if mso | IE]>
									</td>
								</tr>
							</table>
							<table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
								<tr>
									<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
							<![endif]-->
							<div  style="background:#ffffff;background-color:#ffffff;Margin:0px auto;max-width:600px;">
								<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;background-color:#ffffff;width:100%;">
									<tbody>
										<tr>
											<td style="direction:ltr;font-size:0px;padding:20px 0px 20px 0px;padding-bottom:0px;text-align:center;vertical-align:top;">
												<!--[if mso | IE]>
													<table role="presentation" border="0" cellpadding="0" cellspacing="0">		
													<tr>
													<td class="" style="vertical-align:top;width:600px;">
												<![endif]-->
												<div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">	
													<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
														<tr>
															<td align="left" style="font-size:0px;padding:0px 25px 0px 25px;padding-top:0px;padding-bottom:0px;word-break:break-word;">
															<div style="font-family:Roboto, Helvetica, Arial, sans-serif;font-size:13px;line-height:22px;text-align:left;color:#55575d;">
																<style></style><p style="margin: 10px 0;">New rating just appeared, below you will find more details.</p><p style="margin: 10px 0;"><strong>Star rating:</strong> '.$rating.' / 5<br><br><strong>Comment:</strong><br>'.$comment.'</p>
															</div>
															</td>
														</tr>
													</table>
												</div>
												<!--[if mso | IE]>
													</td>
													</tr>
													</table>
												<![endif]-->
											</td>
										</tr>
									</tbody>
								</table>	
							</div>
							<!--[if mso | IE]>
								</td>
								</tr>
								</table>

								<table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
								<tr>
									<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
							<![endif]-->	
							<div style="Margin:0px auto;max-width:600px;">
								<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
									<tbody>
										<tr>
											<td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;vertical-align:top;">
												<!--[if mso | IE]>
													<table role="presentation" border="0" cellpadding="0" cellspacing="0">
													<tr>
													<td class="" style="vertical-align:top;width:600px;">
												<![endif]-->		
												<div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">	
													<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
														<tr>
															<td align="left" style="font-size:0px;padding:10px 25px;padding-top:0px;padding-right:11px;padding-bottom:0px;padding-left:11px;word-break:break-word;">		
																<div style="font-family:Roboto, Helvetica, Arial, sans-serif;font-size:13px;line-height:24px;text-align:left;color:#55575d;">
																	<style></style><p style="margin: 10px 0;"><b>Legal notice:</b></p><p style="margin: 10px 0;">Air Liquide Austria GmbH<br>A-2320 Schwechat, Sendnergasse 30<br>Phone.: 0810 242 427</p><p style="margin: 10px 0;"><a target="_blank" href="https://industrie.airliquide.at">https://industrie.airliquide.at</a><br><a href="mailto:webmaster.at@airliquide.com">webmaster.at@airliquide.com</a></p><p style="margin: 10px 0;">Air Liquide Austria GmbH<br>FB 86620 h LG Korneuburg<br>UID Nr.: ATU 14200201</p><p style="margin: 10px 0;">Exekutive: Sebastian Jureczek</p><p style="margin: 10px 0;">You receive this e-mail because either you, a colleague of yours, or an Air Liquide employee has requested data sheets for you to download.</p>
																</div>
															</td>
														</tr>
													</table>
												</div>
												<!--[if mso | IE]>
													</td>
													</tr>
													</table>
												<![endif]-->
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<!--[if mso | IE]>
								</td>
								</tr>
								</table>
								<table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
								<tr>
								<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
							<![endif]-->
							<div  style="Margin:0px auto;max-width:600px;">
								<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
									<tbody>
										<tr>
											<td style="direction:ltr;font-size:0px;padding:20px 0px 20px 0px;text-align:center;vertical-align:top;">
												<!--[if mso | IE]>
													<table role="presentation" border="0" cellpadding="0" cellspacing="0">	
													<tr>
													<td class="" style="vertical-align:top;width:600px;">
												<![endif]-->
												<div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
													<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
														<tbody>
															<tr>
																<td style="vertical-align:top;padding:0;">		
																	<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="" width="100%">
																	</table>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
												<!--[if mso | IE]>
													</td>
													</tr>
													</table>
												<![endif]-->
											</td>
										</tr>
									</tbody>
								</table>	
							</div>
							<!--[if mso | IE]>
								</td>
								</tr>
								</table>
								<table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
								<tr>
								<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
							<![endif]-->
							<div  style="Margin:0px auto;max-width:600px;">
								<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
									<tbody>
										<tr>
											<td style="direction:ltr;font-size:0px;padding:20px 0px 20px 0px;text-align:center;vertical-align:top;">
												<!--[if mso | IE]>
													<table role="presentation" border="0" cellpadding="0" cellspacing="0">
													<tr>
													<td class="" style="vertical-align:top;width:600px;">
												<![endif]-->	
												<div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
													<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
														<tbody>
															<tr>
																<td  style="vertical-align:top;padding:0;">		
																	<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="" width="100%">
																	</table>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
												<!--[if mso | IE]>
													</td>
													</tr>
													</table>
												<![endif]-->
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<!--[if mso | IE]>
								</td>
								</tr>
								</table>
								<table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600">
								<tr>
								<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
							<![endif]-->
							<div  style="Margin:0px auto;max-width:600px;">
								<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
									<tbody>
										<tr>
											<td style="direction:ltr;font-size:0px;padding:20px 0px 20px 0px;text-align:center;vertical-align:top;">
												<!--[if mso | IE]>
													<table role="presentation" border="0" cellpadding="0" cellspacing="0">	
													<tr>
													<td class="" style="vertical-align:top;width:600px;">
												<![endif]-->
												<div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
													<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
														<tbody>
															<tr>
																<td  style="vertical-align:top;padding:0;">			
																	<table border="0" cellpadding="0" cellspacing="0" role="presentation" style="" width="100%">
																		<tr>
																			<td align="center" style="font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:0px;word-break:break-word;">			
																				<div style="font-family:Arial, sans-serif;font-size:11px;line-height:22px;text-align:center;color:#000000;">
																					<style></style><p style="margin: 10px 0;">   </p>
																				</div>
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
												<!--[if mso | IE]>
													</td>
													</tr>
													</table>
												<![endif]-->
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<!--[if mso | IE]>
								</td>
								</tr>
								</table>
							<![endif]-->
						</div>
					</body>
				</html>
			';

			foreach ($feedback_users as $email) {
				$mail = new PHPMailer\PHPMailer\PHPMailer();                              // Passing `true` enables exceptions
				try {
						//Server settings
						$mail->SMTPDebug = 2;                                 // Enable verbose debug output
						$mail->isSMTP();                                      // Set mailer to use SMTP
						$mail->Host = 'in-v3.mailjet.com';  // Specify main and backup SMTP servers
						$mail->SMTPAuth = true;                               // Enable SMTP authentication
						$mail->Username = '26689254bd824891ea9d39de97e2ffb8';                 // SMTP username
						$mail->Password = 'f25f14465bc84da8c667fb2a086ff215';                           // SMTP password
						$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
						$mail->Port = 587;                                    // TCP port to connect to

						//Recipients
						$mail->setFrom('webmaster.at@airliquide.com', 'Air Liquide Austria');
						$mail->addAddress($email["email"]);     // Add a recipient
						$mail->addReplyTo('webmaster.at@airliquide.com', 'airliquide.com');

						//Content
						$mail->isHTML(true);                                  // Set email format to HTML
						$mail->CharSet = 'UTF-8';
						$mail->Subject = 'SDB';
						$mail->Body    = $content;
						$mail->AltBody = '';

						$mail->send();

						echo 'mail sent';

				} catch (Exception $e) {
						echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
				}
			}
		}

		
	}


	//********************************** CHANGE LANGUAGE *************************************//
	if(isset($_POST["change_language"])){
		$_SESSION['lang'] = $_POST["lang_id"];

		$lang_iso = getLangIso($_POST["lang_id"]);
		if($lang_iso != 'de'){
			echo 'http://3.16.24.200/'.$lang_iso;
		}
		else{
			echo 'http://3.16.24.200/';
		}
	}






	//********************************** COOKIE BANNER *************************************//
	if (isset($_POST['cookie_banner_check'])) {
		$cookie_banner_settings = getCookieBannerSettings();

		echo $cookie_banner_settings["status"]["value"];
	}

?>