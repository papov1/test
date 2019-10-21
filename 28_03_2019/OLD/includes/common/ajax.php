<?php

	include_once $_SERVER["DOCUMENT_ROOT"].'classes/general.php';

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
			echo '<span class="dmc_repositories_container_line_date">zuletzt aktualisiert am<br>'.$repository_date.'</span>';
			echo '<span class="dmc_repositories_container_line_repository_button"><span class="open_in_pdf_viewer" data-search_repository_id="'.$repository_details["id"].'">Ansicht öffnen</span></span>';
			echo '<span class="dmc_repositories_container_line_repository_button"><span class="remove_from_download_manager" data-search_repository_id="'.$repository_details["id"].'">Entfernen</span></span>';
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

				$file_path_exp = explode('http://testal.at/',$pdf_link);
				$file_path = $_SERVER['DOCUMENT_ROOT'].$file_path_exp[1];

				$files_to_zip[] = $file_path;
			}
		}

		$now_date = date("d_m_Y").'_'.time();

		$result = create_zip($files_to_zip,$_SERVER['DOCUMENT_ROOT'].'downloads/sdb_export_'.$now_date.'.zip');
		if($result == 'success'){
			echo 'http://testal.at/downloads/sdb_export_'.$now_date.'.zip';
		}

	}


	//************************************ SHARE & SEND ***************************************//
	if(isset($_POST["share_and_send"])){
		
		$repository_set_url = addRepositorySet($_COOKIE['dm_repos']);

		echo '
			<div class="custom_popup_front">
				<div class="popup_title">Link teilen</div>
				<div class="popup_description">Datenblätter im Download Manager unkompliziert über den spezifischen Link teilen</div>
				<div class="url_section">
					<div class="form-line">
						<input type="text" name="repository_set_url" id="repository_set_url" readonly="readonly" value="'.$repository_set_url.'">
					</div>
					<div class="button_copy_url">
						<i class="pe-7s-link"></i>
						Link kopieren
					</div>
					<div class="button_copy_url_confirmation"><i class="pe-7s-check"></i> Link in Zwischenablage kopiert</div>
				</div>

				<div class="popup_title popup_title_second">Per E-Mail versenden</div>
				<div class="popup_description">Datenblätter per Anhang als E-Mail versenden</div>
				<div class="recipients_section">
					<div class="form-line">
						<input type="text" name="share_recipients" id="share_recipients">
						<label for="share_recipients">E-Mail Adresse des Empfängers</label>
						<div class="share_recipients_wrong_email">There is something wrong with e-mail you entered</div>
					</div>
					<div class="form-line">
						<label for="share_mail_text">Ihre zusätzliche Anmerkung für den Empfänger (optional):</label>
						<textarea id="share_mail_text" name="share_mail_text"></textarea>
					</div>

					<div class="share_button_send">
						<i class="pe-7s-mail"></i>
						E-Mail absenden
					</div>
				</div>
			</div>

			<div class="custom_popup_front_confirmation">
				<i class="pe-7s-check"></i>
				<span>Ihre E-Mail wurde erfolgreich versendet</span>
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
		      <![endif]--><div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:0px;padding-top:0px;padding-bottom:0px;" align="center"><div style="cursor:auto;color:#55575d;font-family:Roboto, Helvetica, Arial, sans-serif;font-size:11px;line-height:22px;text-align:center;"><style></style><p style="margin: 10px 0;">Ihre Datenblätter stehen zum Download bereit</p></div></td></tr></tbody></table></div><!--[if mso | IE]>
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
		      <![endif]--><div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:0px;padding-right:0px;padding-left:0px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;" align="center" border="0"><tbody><tr><td style="width:600px;"><img alt="Air Liquide respektiert Datenschutz. Jetzt zustimmen auf: welcome-kit.at" title="" height="auto" src="http://ng97.mjt.lu/tplimg/ylg4/b/izlh/pxgv.jpeg" style="border:none;border-radius:0px;display:block;font-size:13px;outline:none;text-decoration:none;width:100%;height:auto;" width="600"></td></tr></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]>
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
		      <![endif]--><div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:0px 25px 0px 25px;padding-top:0px;padding-bottom:0px;" align="left"><div style="cursor:auto;color:#55575d;font-family:Roboto, Helvetica, Arial, sans-serif;font-size:13px;line-height:22px;text-align:left;"><style></style><p style="margin: 10px 0;">Sehr geehrter Kunde,</p><p style="margin: 10px 0;">Ihre Datenblätter stehen im Anhang zum Download bereit.</p><p style="margin: 10px 0;">IF text of download manager array "Ihre zusätzliche Anmerkung für den Empfänger" then place it HERE with: '.$url.'<br>Zusätzliche Anmerkung: <br>'.$comment.'</p><p style="margin: 10px 0;">Sie benötigen weitere Sicherheits- oder Produktdatenblätter, dann besuchen Sie die Seite über den nachfolgenden Button.</p></div></td></tr></tbody></table></div><!--[if mso | IE]>
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
		      <![endif]--><div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:0px;padding-right:0px;padding-left:0px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:separate;" align="center" border="0"><tbody><tr><td style="border:none;border-radius:3px;color:#ffffff;cursor:auto;padding:10px 25px;" align="center" valign="middle" bgcolor="#375f9b"><a href="'.$url_short.'" style="text-decoration:none;background:#375f9b;color:#ffffff;font-family:Arial, sans-serif;font-size:13px;font-weight:normal;line-height:120%;text-transform:none;margin:0px;" target="_blank">SDB Download Seite</a></td></tr></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]>
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

					$mail->send();

					echo 'mail sent';

			} catch (Exception $e) {
					echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
			}
		}

	}


	//************************************ SEND MAIL: SHARE & SEND ***************************************//
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
		      <![endif]--><div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:0px;padding-top:0px;padding-bottom:0px;" align="center"><div style="cursor:auto;color:#55575d;font-family:Roboto, Helvetica, Arial, sans-serif;font-size:11px;line-height:22px;text-align:center;"><style></style><p style="margin: 10px 0;">Ihre Datenblätter stehen zum Download bereit</p></div></td></tr></tbody></table></div><!--[if mso | IE]>
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
		      <![endif]--><div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:0px;padding-right:0px;padding-left:0px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;" align="center" border="0"><tbody><tr><td style="width:600px;"><img alt="Air Liquide respektiert Datenschutz. Jetzt zustimmen auf: welcome-kit.at" title="" height="auto" src="http://ng97.mjt.lu/tplimg/ylg4/b/izlh/pxgv.jpeg" style="border:none;border-radius:0px;display:block;font-size:13px;outline:none;text-decoration:none;width:100%;height:auto;" width="600"></td></tr></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]>
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
		      <![endif]--><div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:0px 25px 0px 25px;padding-top:0px;padding-bottom:0px;" align="left"><div style="cursor:auto;color:#55575d;font-family:Roboto, Helvetica, Arial, sans-serif;font-size:13px;line-height:22px;text-align:left;"><style></style><p style="margin: 10px 0;">Sehr geehrter Kunde,</p><p style="margin: 10px 0;">Ihre Datenblätter stehen im Anhang zum Download bereit.</p><p style="margin: 10px 0;">IF text of download manager array "Ihre zusätzliche Anmerkung für den Empfänger" then place it HERE with: '.$url.'<br>Zusätzliche Anmerkung: <br>'.$comment.'</p><p style="margin: 10px 0;">Sie benötigen weitere Sicherheits- oder Produktdatenblätter, dann besuchen Sie die Seite über den nachfolgenden Button.</p></div></td></tr></tbody></table></div><!--[if mso | IE]>
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
		      <![endif]--><div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:0px;padding-right:0px;padding-left:0px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:separate;" align="center" border="0"><tbody><tr><td style="border:none;border-radius:3px;color:#ffffff;cursor:auto;padding:10px 25px;" align="center" valign="middle" bgcolor="#375f9b"><a href="'.$url_short.'" style="text-decoration:none;background:#375f9b;color:#ffffff;font-family:Arial, sans-serif;font-size:13px;font-weight:normal;line-height:120%;text-transform:none;margin:0px;" target="_blank">SDB Download Seite</a></td></tr></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]>
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

					//$mail->send();

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
	}

?>