<?php

	function getSearchResult($id_lang,$keyword){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');
		
		if(strpos($keyword, ',') !== false) {
		  $keywords_array = explode(",",$keyword);

		  foreach ($keywords_array as $keyword_to_find) {

		  	$keyword_to_find = trim($keyword_to_find);
		  	if($keyword_to_find != ''){
		  		$sql = "
		  			SELECT 
		  				`repository`.`name` 
		  			FROM 
		  				`repository` 
		  			LEFT JOIN 
		  				`repository_material_number` ON `repository`.`id` = `repository_material_number`.`repository_id` 
		  			LEFT JOIN 
		  				`repository_tags` ON `repository`.`id` = `repository_tags`.`repository_id` 
		  			WHERE 
		  				`repository`.`id_lang` = ".$id_lang." 
		  			AND 
		  				(`repository`.`name` LIKE '%".mysqli_real_escape_string($con,$keyword_to_find)."%' 
		  					OR `repository_material_number`.`material_number` LIKE '%".mysqli_real_escape_string($con,$keyword_to_find)."%' 
		  					OR `repository_tags`.`name` REGEXP '[[:<:]]".mysqli_real_escape_string($con,$keyword_to_find)."[[:>:]]') 
		  			ORDER BY 
		  				`repository`.`name` ASC
		  		";
		  		$result = mysqli_query($con,$sql);

		  		while($content = mysqli_fetch_array($result)){
		  			$repositories[] = $content["name"];
		  		}
		  	}
		  	
		  }
		}
		else{
			$sql = "
				SELECT 
					`repository`.`name` 
				FROM 
					`repository` 
				LEFT JOIN 
					`repository_material_number` ON `repository`.`id` = `repository_material_number`.`repository_id` 
				LEFT JOIN 
					`repository_tags` ON `repository`.`id` = `repository_tags`.`repository_id` 
				WHERE 
					`repository`.`id_lang` = ".$id_lang." 
				AND 
					(`repository`.`name` LIKE '%".mysqli_real_escape_string($con,$keyword)."%' 
						OR `repository_material_number`.`material_number` LIKE '%".mysqli_real_escape_string($con,$keyword)."%' 
						OR `repository_tags`.`name` REGEXP '[[:<:]]".mysqli_real_escape_string($con,$keyword)."[[:>:]]') 
				ORDER BY 
					`repository`.`name` ASC
			";
			$result = mysqli_query($con,$sql);

			while($content = mysqli_fetch_array($result)){
				$repositories[] = $content["name"];
			}
		}

		if(count($repositories) > 1){
			$repositories_no_duplicated = array_unique($repositories);
			//natsort($repositories_no_duplicated);
		}
		else{
			$repositories_no_duplicated = $repositories;
		}

		return $repositories_no_duplicated;
			
	}


	function getSearchResultByID($id_lang,$keyword){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');
		
		if(strpos($keyword, ',') !== false) {
		  $keywords_array = explode(",",$keyword);

		  foreach ($keywords_array as $keyword_to_find) {

		  	$keyword_to_find = trim($keyword_to_find);
		  	if($keyword_to_find != ''){
		  		/* added by matias - polcoder mod
		  		$sql = "
		  			SELECT 
		  				`repository`.`id` 
		  			FROM 
		  				`repository` 
		  			LEFT JOIN 
		  				`repository_material_number` ON `repository`.`id` = `repository_material_number`.`repository_id` 
		  			LEFT JOIN 
		  				`repository_tags` ON `repository`.`id` = `repository_tags`.`repository_id` 
		  			WHERE 
		  				`repository`.`id_lang` = ".$id_lang." 
		  			AND 
		  				(`repository`.`name` LIKE '%".mysqli_real_escape_string($con,$keyword_to_find)."%' 
		  					OR `repository_material_number`.`material_number` LIKE '%".mysqli_real_escape_string($con,$keyword_to_find)."%' 
		  					OR `repository_tags`.`name` REGEXP '[[:<:]]".mysqli_real_escape_string($con,$keyword_to_find)."[[:>:]]') 
		  			ORDER BY 
		  				`repository`.`name` ASC
		  		";
		  		*/

		  		$sql = "
		  			SELECT 
		  				`repository`.`id` 
		  			FROM 
		  				`repository` 
		  			LEFT JOIN 
		  				`repository_material_number` ON `repository`.`id` = `repository_material_number`.`repository_id` 
		  			LEFT JOIN 
		  				`repository_tags` ON `repository`.`id` = `repository_tags`.`repository_id` 
		  			WHERE 
		  				`repository`.`id_lang` = ".$id_lang." 
		  			AND 
		  				(`repository`.`name` LIKE '%".mysqli_real_escape_string($con,$keyword_to_find)."%' 
		  					OR `repository_material_number`.`material_number` LIKE '%".mysqli_real_escape_string($con,$keyword_to_find)."%') 
		  			ORDER BY 
		  				`repository`.`name` ASC
		  		";

		  		$result = mysqli_query($con,$sql);

		  		while($content = mysqli_fetch_array($result)){
		  			$repositories[] = $content["id"];
		  		}
		  	}
		  	
		  }
		}
		else{
			/* added by matias - polcoder mod
			$sql = "
				SELECT 
					`repository`.`id` 
				FROM 
					`repository` 
				LEFT JOIN 
					`repository_material_number` ON `repository`.`id` = `repository_material_number`.`repository_id` 
				LEFT JOIN 
					`repository_tags` ON `repository`.`id` = `repository_tags`.`repository_id` 
				WHERE 
					`repository`.`id_lang` = ".$id_lang." 
				AND 
					(`repository`.`name` LIKE '%".mysqli_real_escape_string($con,$keyword)."%' 
						OR `repository_material_number`.`material_number` LIKE '%".mysqli_real_escape_string($con,$keyword)."%' 
						OR `repository_tags`.`name` REGEXP '[[:<:]]".mysqli_real_escape_string($con,$keyword)."[[:>:]]') 
				ORDER BY 
					`repository`.`name` ASC
			";
			*/

			$sql = "
				SELECT 
					`repository`.`id` 
				FROM 
					`repository` 
				LEFT JOIN 
					`repository_material_number` ON `repository`.`id` = `repository_material_number`.`repository_id` 
				LEFT JOIN 
					`repository_tags` ON `repository`.`id` = `repository_tags`.`repository_id` 
				WHERE 
					`repository`.`id_lang` = ".$id_lang." 
				AND 
					(`repository`.`name` LIKE '%".mysqli_real_escape_string($con,$keyword)."%' 
						OR `repository_material_number`.`material_number` LIKE '%".mysqli_real_escape_string($con,$keyword)."%') 
				ORDER BY 
					`repository`.`name` ASC
			";
			$result = mysqli_query($con,$sql);

			while($content = mysqli_fetch_array($result)){
				$repositories[] = $content["id"];
			}
		}

		if(count($repositories) > 1){
			$repositories_no_duplicated = array_unique($repositories);
			//natsort($repositories_no_duplicated);
		}
		else{
			$repositories_no_duplicated = $repositories;
		}

		return $repositories_no_duplicated;
			
	}


//	function getSearchResultFromPDFContent($id_lang,$keyword){
//		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');
//
//		//by name
//		$sql = "
//			SELECT
//				repository_pdf_words.repository_id AS repository_id,
//				repository_pdf_words.count AS word_count
//			FROM
//				repository_pdf_words
//			JOIN
//				repository
//			ON
//				repository_pdf_words.repository_id = repository.id
//			WHERE
//				repository.id_lang = $id_lang
//			AND
//				word LIKE '%".mysqli_real_escape_string($con,$keyword)."%'
//			ORDER BY
//				repository_pdf_words.count DESC
//		";
//		$result = mysqli_query($con,$sql);
//
//		while($content = mysqli_fetch_array($result)){
//			$repositories[] = $content["repository_id"];
//		}
//
//		if(count($repositories) > 1){
//			$repositories_no_duplicated = array_unique($repositories);
//			natsort($repositories_no_duplicated);
//		}
//		else{
//			$repositories_no_duplicated = $repositories;
//		}
//
//
//		return $repositories_no_duplicated;
//
//	}


	function getRepositoryTags($repository_id){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "SELECT name FROM repository_tags WHERE repository_id = '".mysqli_real_escape_string($con,$repository_id)."'";
		$result = mysqli_query($con,$sql);
		if(mysqli_num_rows($result) > 0){
			while($content = mysqli_fetch_array($result)){
				$tags[] = $content["name"];
			}

			return implode(", ",$tags);
		}		
	}


	function getRepositoryPdfLink($repository_id){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "SELECT pdf_link FROM repository WHERE id = '".mysqli_real_escape_string($con,$repository_id)."'";
		$result = mysqli_query($con,$sql);
		if(mysqli_num_rows($result) > 0){
			$content = mysqli_fetch_array($result);
	
			return $content["pdf_link"];
		}
		else{
			return 'no pdf for this repository';
		}
	}


	function updateRepositoryPdfDownloadCounter($repository_id){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "UPDATE repository_downloads SET counter=counter+1 WHERE repository_id='".mysqli_real_escape_string($con,$repository_id)."'";
		$result = mysqli_query($con,$sql);
		
	}


	function getRepositoryRelations($repository_id){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "
			SELECT 
				repository_relations.related_repository_id AS related_repository_id,
				repository.name AS name
			FROM 
				repository_relations
			JOIN
				repository
			ON	
				 repository_relations.related_repository_id = repository.id
			WHERE 
				repository_relations.repository_id = '".mysqli_real_escape_string($con,$repository_id)."'
		";

		$result = mysqli_query($con,$sql);
		if(mysqli_num_rows($result) > 0){
			while($content = mysqli_fetch_array($result)){
				$related_repositories[] = $content["name"];
			}

			return implode(", ",$related_repositories);
		}
	}


	function getRepositoryDetails($repository_name){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "
			SELECT 
				id,
				art,
				typ,
				pdf_link,
				modification_date 
			FROM 
				repository 
			WHERE 
				name LIKE binary '".mysqli_real_escape_string($con,$repository_name)."'
		";
		$result = mysqli_query($con,$sql);
		$content = mysqli_fetch_array($result);

		$repository_details["id"] = $content["id"];
		$repository_details["art"] = $content["art"];
		$repository_details["typ"] = $content["typ"];
		$repository_details["pdf_link"] = $content["pdf_link"];
		$repository_details["modification_date"] = $content["modification_date"];

			return $repository_details;	
	}


	function getRepositoryDetailsById($repository_id){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "
			SELECT 
				id,
				name,
				art,
				typ,
				pdf_link,
				modification_date 
			FROM 
				repository 
			WHERE 
				id = '".mysqli_real_escape_string($con,$repository_id)."' 
		";
		$result = mysqli_query($con,$sql);
		$content = mysqli_fetch_array($result);

		$repository_details["id"] = $content["id"];
		$repository_details["name"] = $content["name"];
		$repository_details["art"] = $content["art"];
		$repository_details["typ"] = $content["typ"];
		$repository_details["pdf_link"] = $content["pdf_link"];
		$repository_details["modification_date"] = $content["modification_date"];

		return $repository_details;	
	}


	function getRepositoryNameFromFile($filename){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "SELECT name,id FROM repository WHERE pdf_link LIKE '%".mysqli_real_escape_string($con,$new_filename)."%'";
		$result = mysqli_query($con,$sql);
		$content = mysqli_fetch_array($result);
		$new = $content["name"].'_'.$content["id"];
		$new_filename = makeURL($new);

		return $new_filename;
	}


	function getRepositorySet($url){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "
			SELECT 
				repository_ids 
			FROM 
				repository_sets 
			WHERE 
				link = '".mysqli_real_escape_string($con,$url)."'
		";
		$result = mysqli_query($con,$sql);		

		if(mysqli_num_rows($result) > 0){
			$content = mysqli_fetch_array($result);
			
			return $content["repository_ids"];
		}
		else{
			return 'not found';
		}
	}


	function addRepositorySet($repository_set_ids){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$datetime_now = date("Y-m-d H:i:s");
		$repository_set_link = 'https://'.$_SERVER['SERVER_NAME'].'/safety-sheets/set_'.date("d_m_Y").'_'.time();
		$dm_repos_content = json_decode($repository_set_ids, true);

		$sql = "
			INSERT INTO 
				repository_sets
				(
					link,
					repository_ids,
					creation_date
				)
			VALUES
				(
					'".mysqli_real_escape_string($con,$repository_set_link)."',
					'".mysqli_real_escape_string($con,implode(",",$dm_repos_content))."',
					'".$datetime_now."'
				) 
		";
		$result = mysqli_query($con,$sql);

		return $repository_set_link;	
	}


	function getRepositoriesByType($id_lang,$type){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');
		
		if($type == 'material'){
			$typ = 'step_3_material_datasheet';
		}
		else{
			$typ = 'step_3_brandgas_datasheet';
		}

		$sql = "
			SELECT 
				id,
				name,
				art,
				typ,
				pdf_link,
				modification_date 
			FROM
				repository
			WHERE
				id_lang = $id_lang
			AND
				typ = '$typ' 
						ORDER BY name ASC 
		";
		$result = mysqli_query($con,$sql);
		return mysqli_fetch_all($result, MYSQLI_ASSOC);
	}


	function getRepositoriesByArt($id_lang,$art_typ,$type){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');
			
		if($art_typ == 'safety'){
			$art = 'step_2_safety_datasheet';
		}
		else{
			$art = 'step_2_product_datasheet';
		}

		if($type == 'material'){
			$typ = 'step_3_material_datasheet';
		}
		else{
			$typ = 'step_3_brandgas_datasheet';
		}

		$sql = "
			SELECT 
				id,
				name,
				art,
				typ,
				pdf_link,
				modification_date 
			FROM
				repository
			WHERE
				id_lang = $id_lang
			AND
				art = '$art' 
			AND
				typ = '$typ' 
			ORDER BY name ASC 
		";
		$result = mysqli_query($con,$sql);
		return mysqli_fetch_all($result, MYSQLI_ASSOC);
	}


	function sendRating($rating,$rating_comment){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$datetime_now = date("Y-m-d H:i:s");

		$sql = "
			INSERT INTO 
				ratings
				(
					rating,
					comment,
					created
				)
			VALUES
				(
					'".mysqli_real_escape_string($con,$rating)."',
					'".mysqli_real_escape_string($con,$rating_comment)."',
					'".$datetime_now."'
				) 
		";
		$result = mysqli_query($con,$sql);
	}


	function makeURL($string,$separator = '_' ){
		$accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
		$special_cases = array( '&' => 'and', "'" => '');
		$string = mb_strtolower( trim( $string ), 'UTF-8' );
		$string = str_replace( array_keys($special_cases), array_values( $special_cases), $string );
		$string = preg_replace( $accents_regex, '$1', htmlentities( $string, ENT_QUOTES, 'UTF-8' ) );
		$string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
		$string = preg_replace("/[$separator]+/u", "$separator", $string);
		return $string;
	}


	//**********************************************************//
	//********************** LANGUAGES *************************//
	//**********************************************************//

	function getLangsNames(){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "
			SELECT 
				lang.id AS lang_id,
				lang_name.name AS lang_name,
				lang_name.language_id AS language_id
			FROM 
				lang
			JOIN
				lang_name
			ON
				lang.id = lang_name.id_lang
			WHERE
				lang.active = 1
			AND
				lang_name.id_lang = lang_name.language_id
			";
		$result = mysqli_query($con,$sql);
		$cnt = 0;
		while($content = mysqli_fetch_array($result)){
			$langs[$cnt]["lang_id"] = $content["lang_id"];
			$langs[$cnt]["language_id"] = $content["language_id"];
			$langs[$cnt]["lang_name"] = $content["lang_name"];
			$cnt++;
		}
		return $langs;
	}


	function getLangName($lang_id){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "SELECT name	FROM lang_name WHERE language_id = $lang_id AND id_lang = $lang_id";
		$result = mysqli_query($con,$sql);
		$content = mysqli_fetch_array($result);
		
		return $content["name"];
	}


	function getLangIso($lang_id){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "SELECT iso FROM lang WHERE id = $lang_id";
		$result = mysqli_query($con,$sql);
		$content = mysqli_fetch_array($result);
		
		return $content["iso"];
	}


	function getTranslation($lang_id){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "SELECT id_translation,name FROM translations_lang WHERE id_lang = $lang_id";
		$result = mysqli_query($con,$sql);

		while($content = mysqli_fetch_array($result)){
			$translation[$content["id_translation"]] = $content["name"];
		}
		return $translation;
	}



	function getNotificationUsers(){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "
			SELECT 
				feedback_users.email AS email,
				admin.first_name AS first_name,
				admin.last_name AS last_name
			FROM 
				feedback_users 
			JOIN
				admin
			ON
				feedback_users.admin_id = admin.id
			ORDER BY 
				admin.first_name 
			ASC";
		$result = mysqli_query($con,$sql);

		if(mysqli_num_rows($result) > 0){
			$ctr = 0;
			while($content = mysqli_fetch_array($result)){

				$feedback_users[$ctr]["email"] = $content["email"];
				$feedback_users[$ctr]["first_name"] = $content["first_name"];
				$feedback_users[$ctr]["last_name"] = $content["last_name"];

				$ctr++;
			}

			return $feedback_users;
		}
		else{
			return 'empty';
		}
		
	}



	function getCookieBannerTexts($id_lang){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "
			SELECT
				cookie_banner.section AS section,
				cookie_banner_lang.name AS name,
				cookie_banner_lang.url AS url
			FROM
				cookie_banner
			JOIN
				cookie_banner_lang
			ON
				cookie_banner.id = cookie_banner_lang.cookie_id
			WHERE
				cookie_banner_lang.id_lang = '".mysqli_real_escape_string($con,$id_lang)."'
		";
		$result = mysqli_query($con,$sql);

		while($content = mysqli_fetch_array($result)){
			$section = $content["section"];

			$cookie_banner[$section]["name"] = $content["name"];
			$cookie_banner[$section]["url"] = $content["url"];
		}

		return $cookie_banner;
	}



	function getCookieBannerSettings(){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "SELECT * FROM cookie_banner_settings";
		$result = mysqli_query($con,$sql);

		while($content = mysqli_fetch_array($result)){
			$name = $content["name"];

			$cookie_banner_settings[$name]["value"] = $content["value"];
		}

		return $cookie_banner_settings;
	}



	//**********************************************************//
	//************************* FOOTER *************************//
	//**********************************************************//

	function getFooterTexts($id_lang){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "
			SELECT
				footer.section AS section,
				footer_lang.name AS name,
				footer_lang.url AS url
			FROM
				footer
			JOIN
				footer_lang
			ON
				footer.id = footer_lang.footer_id
			WHERE
				footer_lang.id_lang = '".mysqli_real_escape_string($con,$id_lang)."'
		";
		$result = mysqli_query($con,$sql);

		while($content = mysqli_fetch_array($result)){
			$section = $content["section"];

			$footer[$section]["name"] = $content["name"];
			$footer[$section]["url"] = $content["url"];
		}

		return $footer;
	}
?>