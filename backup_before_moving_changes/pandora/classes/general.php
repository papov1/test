<?php

	//**********************************************************//
	//************************* LOGIN **************************//
	//**********************************************************//

	function login_user($user_email,$user_password){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "
			SELECT
				*
			FROM
				admin
			WHERE
				email = '".mysqli_real_escape_string($con,$user_email)."'
		";
		$result = mysqli_query($con,$sql);
		
		if(mysqli_num_rows($result) > 0){
			$content = mysqli_fetch_array($result);

			if(($content["login_type"] == '')||($content["login_type"] == 'not allowed')){
				return 'not allowed';
			}
			else{
				$stored_password = $content["password"];

				if (password_verify($user_password, $stored_password)){
					$_SESSION['admin_logged_in'] = 'true';
					$_SESSION['userID'] = $content["id"];
					$_SESSION['userFirstname'] = $content["first_name"];
					$_SESSION['userLastname'] = $content["last_name"];
					return 'logged';
				}
				else{
					return 'not logged';
				}
			}
		}
		else{
			return 'not logged';
		}
	}


	function check_if_superadmin($user_id){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "
			SELECT
				superadmin
			FROM
				admin
			WHERE
				id = '".mysqli_real_escape_string($con,$user_id)."'
		";
		$result = mysqli_query($con,$sql);
		$content = mysqli_fetch_array($result);
		
		return $content["superadmin"];
	}



	//**********************************************************//
	//********************** LANGUAGES *************************//
	//**********************************************************//
	function getTranslationsHeaders(){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "SELECT DISTINCT section AS section_title FROM translations ORDER BY sort ASC";
		$result = mysqli_query($con,$sql);

		if(mysqli_num_rows($result) > 0){

			while($content = mysqli_fetch_array($result)){
				$headers[] = $content["section_title"];
			}
			return $headers;
		}
		else{
			return 'no_headers';
		}
	}


	function getLangsTranslationsValues($language_id){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "
			SELECT 
				id,
				name
			FROM
				lang_name
			WHERE
				language_id = $language_id
			ORDER BY
				id ASC
			";
		$result = mysqli_query($con,$sql);
		$cnt = 0;
		while($content = mysqli_fetch_array($result)){
			$langs[$cnt]["id"] = $content["id"];
			$langs[$cnt]["name"] = $content["name"];
			$cnt++;
		}
		return $langs;
	}


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
				lang_name.id_lang = 1
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

		$sql = "SELECT name	FROM lang_name WHERE language_id = $lang_id AND id_lang = 1";
		$result = mysqli_query($con,$sql);
		$content = mysqli_fetch_array($result);
		
		return $content["name"];
	}


	function getTranslationsDescriptions($section){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "
			SELECT 
				id,
				description
			FROM 
				translations
			WHERE
				section = '".mysqli_real_escape_string($con,$section)."'
			ORDER BY 
				sort ASC
			";

		$result = mysqli_query($con,$sql);
		$cnt = 0;
		while($content = mysqli_fetch_array($result)){
			$descriptions[$cnt]["id"] = $content["id"];
			$descriptions[$cnt]["description"] = $content["description"];
			$cnt++;
		}
		return $descriptions;
	}


	function getTranslationsValues($id_value){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "
			SELECT 
				id,
				name,
				id_lang
			FROM 
				translations_lang
			WHERE
				id_translation = $id_value
			ORDER BY
				id_lang
			";

		$result = mysqli_query($con,$sql);
		$cnt = 0;
		while($content = mysqli_fetch_array($result)){
			$lang_values[$cnt]["id"] = $content["id"];
			$lang_values[$cnt]["name"] = $content["name"];
			$lang_values[$cnt]["id_lang"] = $content["id_lang"];
			$cnt++;
		}
		return $lang_values;
	}


	function updateTranslationValue($id_value,$value){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$id_value_import = explode("lang_value-",$id_value);
		$id_value = $id_value_import[1];

		$sql = "SELECT name	FROM translations_lang WHERE id = $id_value";
		$result = mysqli_query($con,$sql);
		$content = mysqli_fetch_array($result);
		$old_phrase = $content["name"];

		if($old_phrase != $value){
			$sql = "UPDATE translations_lang SET name = '".mysqli_real_escape_string($con,$value)."' WHERE id = $id_value";
			$result = mysqli_query($con,$sql);

			return 'Saved';
		}
		else{
			return 'Same, not saved';
		}
	}


	function updateLangTranslationValue($id_value,$value){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$id_value_import = explode("lang_value-",$id_value);
		$id_value = $id_value_import[1];

		$sql = "UPDATE lang_name SET name = '".mysqli_real_escape_string($con,$value)."' WHERE id = $id_value";
		$result = mysqli_query($con,$sql);
	}


	function addNewLanguage($new_lang_name,$new_lang_iso){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "INSERT INTO lang (iso,active) VALUES ('".mysqli_real_escape_string($con,$new_lang_iso)."',1)";
		$result = mysqli_query($con,$sql);
		$last_id = mysqli_insert_id($con);

		$sql = "SELECT id	FROM lang WHERE id NOT LIKE '$last_id'";
		$result = mysqli_query($con,$sql);
		while($content = mysqli_fetch_array($result)){
			$lang_id = $content["id"];
			
			if($lang_id == $last_id){
				$sql_add = "INSERT INTO lang_name (id_lang,name,language_id) VALUES ('$last_id','".mysqli_real_escape_string($con,$new_lang_name)."','$lang_id')";
			}
			else{
				$sql_add = "INSERT INTO lang_name (id_lang,language_id) VALUES ('$last_id','$lang_id')";
			}
			$result_add = mysqli_query($con,$sql_add);
		}

		$sql = "SELECT id	FROM lang";
		$result = mysqli_query($con,$sql);
		while($content = mysqli_fetch_array($result)){
			$lang_id = $content["id"];
			
			$sql_add = "INSERT INTO lang_name (id_lang,name,language_id) VALUES ('$lang_id','".mysqli_real_escape_string($con,$new_lang_name)."','$last_id')";
			$result_add = mysqli_query($con,$sql_add);
		}

		$sql = "SELECT DISTINCT id FROM translations";
		$result = mysqli_query($con,$sql);
		while($content = mysqli_fetch_array($result)){
			$id_translation = $content["id"];
			
			$sql_add = "INSERT INTO translations_lang (id_translation,id_lang) VALUES ('$id_translation','$last_id')";
			$result_add = mysqli_query($con,$sql_add);
		}
	}


	function removeLanguage($language_id){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "DELETE FROM lang WHERE id = '".mysqli_real_escape_string($con,$language_id)."'";
		$result = mysqli_query($con,$sql);

		$sql = "DELETE FROM lang_name WHERE id_lang = '".mysqli_real_escape_string($con,$language_id)."'";
		$result = mysqli_query($con,$sql);

		$sql = "DELETE FROM lang_name WHERE language_id = '".mysqli_real_escape_string($con,$language_id)."'";
		$result = mysqli_query($con,$sql);

		$sql = "DELETE FROM translations_lang WHERE id_lang = '".mysqli_real_escape_string($con,$language_id)."'";
		$result = mysqli_query($con,$sql);
	}





	//**********************************************************//
	//********************* REPOSITORY *************************//
	//**********************************************************//
	function addRepositoryItem($repository_item_name,$repository_art,$repository_typ,$repository_date,$repository_file_url,$repository_old_file_url,$lang_id){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$datetime_now = date("Y-m-d H:i:s");
		$datetime_now_exp = explode(" ",$datetime_now);

		$repository_date_exp = explode(".",$repository_date);
		$repository_creation_date = $repository_date_exp[2].'-'.$repository_date_exp[1].'-'.$repository_date_exp[0].' '.$datetime_now_exp[1];
		$repository_modification_date = $repository_creation_date;

		if($repository_file_url == 'http://ttal.loc/documents/'){
			$repository_file_url = '';
		}

		$sql = "
			INSERT INTO repository (
				name,
				art,
				typ,
				creation_date,
				modification_date,
				pdf_link,
				pdf_link_old,
				id_lang
			) 
			VALUES (
				'".mysqli_real_escape_string($con,$repository_item_name)."',
				'".mysqli_real_escape_string($con,$repository_art)."',
				'".mysqli_real_escape_string($con,$repository_typ)."',
				'".mysqli_real_escape_string($con,$repository_creation_date)."',
				'".mysqli_real_escape_string($con,$repository_modification_date)."',
				'".mysqli_real_escape_string($con,$repository_file_url)."',
				'".mysqli_real_escape_string($con,$repository_old_file_url)."',
				'".mysqli_real_escape_string($con,$lang_id)."'
			)";
		$result = mysqli_query($con,$sql);
		$last_id = mysqli_insert_id($con);
		
		return $last_id;
		
	}


	function getRepositories($id_lang,$type = false,$repository_id = false){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		if($type == 'add'){
			if($repository_id){
				$sql = "
					SELECT 
						id,
						name
					FROM
						repository
					WHERE
						id_lang = $id_lang
					AND
						id NOT LIKE $repository_id
					";
				$result = mysqli_query($con,$sql);
			}
			else{
				$sql = "
					SELECT 
						id,
						name
					FROM
						repository
					WHERE
						id_lang = $id_lang
					";
				$result = mysqli_query($con,$sql);
			}

			while($content = mysqli_fetch_array($result)){
				$repositories[] = $content["name"];
			}

			return $repositories;
		}
		else{
			$sql = "
				SELECT 
					repository.id AS id,
					repository.name AS name,
					repository.art AS art,
					repository.typ AS typ,
					repository.modification_date AS modification_date,
					repository.pdf_link AS pdf_link,
					repository.pdf_link_old AS pdf_link_old,
					repository_downloads.counter AS downloads_counter
				FROM
					repository
				JOIN
					repository_downloads
				ON
					repository.id = repository_downloads.repository_id
				WHERE
					repository.id_lang = $id_lang
				ORDER BY
					repository.name ASC
				";
			$result = mysqli_query($con,$sql);

			$cnt = 0;
			while($content = mysqli_fetch_array($result)){
				$repositories[$cnt]["id"] = $content["id"];
				$repositories[$cnt]["name"] = $content["name"];
				
				if($content["art"] == 'step_2_safety_datasheet'){
					$repositories[$cnt]["art"] = 'SDB';
				}
				elseif($content["art"] == 'step_2_product_datasheet'){
					$repositories[$cnt]["art"] = 'PDB';
				}
				else{
					$repositories[$cnt]["art"] = 'GDB';
				}

				if($content["typ"] == 'step_3_material_datasheet'){
					$repositories[$cnt]["typ"] = 'Stoff';
				}
				else{
					$repositories[$cnt]["typ"] = 'Marken gas';
				}

				$date_exp = explode(" ",$content["modification_date"]);
				$date_exp2 = explode("-",$date_exp[0]);
				$modification_date = $date_exp2[2].'.'.$date_exp2[1].'.'.$date_exp2[0];

				$repositories[$cnt]["modification_date"] = $modification_date;
				$repositories[$cnt]["pdf_link"] = $content["pdf_link"];
				$repositories[$cnt]["pdf_link_old"] = $content["pdf_link_old"];
				$repositories[$cnt]["downloads_counter"] = $content["downloads_counter"];
				$repositories[$cnt]["tags"] = getRepositoryTags($content["id"]);
				$repositories[$cnt]["relations"] = getRepositoryRelations($content["id"]);
				$cnt++;
			}

			return $repositories;
		}
		
	}


	function addRepositoryDownloads($repository_id){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "
			INSERT INTO repository_downloads (
				repository_id,
				counter
			) 
			VALUES (
				'".mysqli_real_escape_string($con,$repository_id)."',
				'0'
			)";
		$result = mysqli_query($con,$sql);		
	}


	function addRepositoryTags($repository_id,$tags){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		if($tags != ''){
			$tags = substr($tags, 1, -1);
			$tags_exp = explode(",",$tags);

			foreach($tags_exp as $tag_exp){
				$tag_part = substr($tag_exp, 10);
				$tag_final = substr($tag_part, 0, -2);
				$tags_array[] = $tag_final;
			}
			
			foreach($tags_array as $tag){
				$sql = "
					INSERT INTO repository_tags (
						repository_id,
						name
					) 
					VALUES (
						'".mysqli_real_escape_string($con,$repository_id)."',
						'".mysqli_real_escape_string($con,$tag)."'
					)";
				$result = mysqli_query($con,$sql);		
			}
		}
	}


	function addRepositoryRelations($repository_id,$related_repositories,$lang_id){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');
		
		if($related_repositories != ''){

			$related_repositories = substr($related_repositories, 0, -1);
			$related_repositories_array = explode(",",$related_repositories);
			
			foreach($related_repositories_array as $related_repository_name){
				$related_repositories_id[] = getRepositoryIdFromName($related_repository_name,$lang_id);
			}

			foreach($related_repositories_id as $related_repository_id){
				$sql = "
					INSERT INTO repository_relations (
						repository_id,
						related_repository_id
					) 
					VALUES (
						'".mysqli_real_escape_string($con,$repository_id)."',
						'".mysqli_real_escape_string($con,$related_repository_id)."'
					)";
				$result = mysqli_query($con,$sql);	
			}
		}
	}


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


	function getRepositoryMaterialNumber($repository_id){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "SELECT material_number FROM repository_material_number WHERE repository_id = '".mysqli_real_escape_string($con,$repository_id)."'";
		$result = mysqli_query($con,$sql);
		if(mysqli_num_rows($result) > 0){
			while($content = mysqli_fetch_array($result)){
				$material_numbers[] = $content["material_number"];
			}

			return implode(", ",$material_numbers);
		}
		else{
			return '';
		}	
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


	function getRepositoryIdFromName($repository_name,$lang_id){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "
			SELECT 
				id
			FROM 
				repository
			WHERE 
				name = '".mysqli_real_escape_string($con,$repository_name)."'
			AND
				id_lang = '".mysqli_real_escape_string($con,$lang_id)."'
		";
		$result = mysqli_query($con,$sql);
		if(mysqli_num_rows($result) > 0){
			$content = mysqli_fetch_array($result);

			return $content["id"];
		}
		else{
			return 'not found';
		}
	}


	function removeRepository($repository_id){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "SELECT pdf_link	FROM repository WHERE id = '".mysqli_real_escape_string($con,$repository_id)."'";
		$result = mysqli_query($con,$sql);
		if(mysqli_num_rows($result) > 0){
			$content = mysqli_fetch_array($result);
			$pdf_link = $content["pdf_link"];
			$pdf_to_remove = explode("documents",$pdf_link);
            $root = ($_SERVER['SERVER_NAME'] == 'testal.devv') ? '/' : '';
            unlink($_SERVER['DOCUMENT_ROOT'].$root.'documents'.$pdf_to_remove[1]);
		}

		$sql = "DELETE FROM repository WHERE id = '".mysqli_real_escape_string($con,$repository_id)."'";
		$result = mysqli_query($con,$sql);

		$sql = "DELETE FROM repository_downloads WHERE repository_id = '".mysqli_real_escape_string($con,$repository_id)."'";
		$result = mysqli_query($con,$sql);

		$sql = "DELETE FROM repository_relations WHERE repository_id = '".mysqli_real_escape_string($con,$repository_id)."'";
		$result = mysqli_query($con,$sql);

		$sql = "DELETE FROM repository_relations WHERE related_repository_id = '".mysqli_real_escape_string($con,$repository_id)."'";
		$result = mysqli_query($con,$sql);

		$sql = "DELETE FROM repository_tags WHERE repository_id = '".mysqli_real_escape_string($con,$repository_id)."'";
		$result = mysqli_query($con,$sql);

		$sql = "DELETE FROM repository_material_number WHERE repository_id = '".mysqli_real_escape_string($con,$repository_id)."'";
		$result = mysqli_query($con,$sql);

		$sql = "DELETE FROM repository_pdf_words WHERE repository_id = '".mysqli_real_escape_string($con,$repository_id)."'";
		$result = mysqli_query($con,$sql);
		
	}


	function updateRepositoryName($repository_id,$repository_name){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "UPDATE repository	SET name = '".mysqli_real_escape_string($con,$repository_name)."' WHERE id = '".mysqli_real_escape_string($con,$repository_id)."'";
		$result = mysqli_query($con,$sql);
	}


	function updateRepositoryArt($repository_id,$repository_art){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "UPDATE repository	SET art = '".mysqli_real_escape_string($con,$repository_art)."' WHERE id = '".mysqli_real_escape_string($con,$repository_id)."'";
		$result = mysqli_query($con,$sql);
	}


	function updateRepositoryTyp($repository_id,$repository_typ){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "UPDATE repository	SET typ = '".mysqli_real_escape_string($con,$repository_typ)."' WHERE id = '".mysqli_real_escape_string($con,$repository_id)."'";
		$result = mysqli_query($con,$sql);
	}


	function updateRepositoryRelations($repository_id,$related_repositories,$lang_id){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "DELETE FROM repository_relations WHERE repository_id = '".mysqli_real_escape_string($con,$repository_id)."'";
		$result = mysqli_query($con,$sql);

		if($related_repositories != ''){

			$related_repositories = ltrim($related_repositories);

			$related_repositories = substr($related_repositories, 0, -1);
			$related_repositories_array = explode(",",$related_repositories);
			
			foreach($related_repositories_array as $related_repository_name){
				$related_repositories_id[] = getRepositoryIdFromName($related_repository_name,$lang_id);
			}

			print_r($related_repositories_id);

			foreach($related_repositories_id as $related_repository_id){
				$sql = "
					INSERT INTO repository_relations (
						repository_id,
						related_repository_id
					) 
					VALUES (
						'".mysqli_real_escape_string($con,$repository_id)."',
						'".mysqli_real_escape_string($con,$related_repository_id)."'
					)";
				$result = mysqli_query($con,$sql);	
			}
		}
	}


	function updateRepositoryDate($repository_id,$repository_date){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$datetime_now = date("Y-m-d H:i:s");
		$datetime_now_exp = explode(" ",$datetime_now);

		$repository_date_exp = explode(".",$repository_date);
		$repository_creation_date = $repository_date_exp[2].'-'.$repository_date_exp[1].'-'.$repository_date_exp[0].' '.$datetime_now_exp[1];
		$repository_modification_date = $repository_creation_date;

		$sql = "UPDATE repository	SET modification_date = '".mysqli_real_escape_string($con,$repository_modification_date)."' WHERE id = '".mysqli_real_escape_string($con,$repository_id)."'";
		$result = mysqli_query($con,$sql);
	}


	function updateRepositoryMaterialNumber($repository_id,$repository_material_number){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "DELETE FROM repository_material_number WHERE repository_id = '".mysqli_real_escape_string($con,$repository_id)."'";
		$result = mysqli_query($con,$sql);

		if($repository_material_number != ''){
			if(strpos($repository_material_number, ',') !== false) {
			  $numbers = explode(",",$repository_material_number);
			  foreach ($numbers as $number) {
			  	$add_number = str_replace(' ', '', $number);
			  	$sql = "
			  		INSERT INTO repository_material_number (
			  			repository_id,
			  			material_number
			  		) 
			  		VALUES (
			  			'".mysqli_real_escape_string($con,$repository_id)."',
			  			'".mysqli_real_escape_string($con,$add_number)."'
			  		)";
			  	$result = mysqli_query($con,$sql);
			  }
			}
			else{
				$sql = "
					INSERT INTO repository_material_number (
						repository_id,
						material_number
					) 
					VALUES (
						'".mysqli_real_escape_string($con,$repository_id)."',
						'".mysqli_real_escape_string($con,$repository_material_number)."'
					)";
				$result = mysqli_query($con,$sql);
			}
		}

	}


	function updateRepositoryTags($repository_id,$repository_tags){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "DELETE FROM repository_tags WHERE repository_id = '".mysqli_real_escape_string($con,$repository_id)."'";
		$result = mysqli_query($con,$sql);

		if($repository_tags != ''){
			$repository_tags = substr($repository_tags, 1, -1);
			$tags_exp = explode(",",$repository_tags);

			foreach($tags_exp as $tag_exp){
				$tag_part = substr($tag_exp, 10);
				$tag_final = substr($tag_part, 0, -2);
				$tags_array[] = $tag_final;
			}
			
			foreach($tags_array as $tag){
				$sql = "
					INSERT INTO repository_tags (
						repository_id,
						name
					) 
					VALUES (
						'".mysqli_real_escape_string($con,$repository_id)."',
						'".mysqli_real_escape_string($con,$tag)."'
					)";
				$result = mysqli_query($con,$sql);		
			}
		}
	}


	function updatePDFwords($repository_id,$repository_file){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');
		require_once($_SERVER["DOCUMENT_ROOT"].'/pandora/vendor/autoload.php');

		$parser = new \Smalot\PdfParser\Parser();
		$pdf    = $parser->parseFile($repository_file);

		$text = $pdf->getText();
		$text = str_replace("\n", "", $text);
		$text = str_replace("\r", "", $text);
		$text = preg_replace('!\s+!', ' ', $text);

		$words_array = explode(" ",strtolower($text));
		$vals = array_count_values($words_array);
		arsort($vals);

		foreach ($vals as $key => $value) {
			if(!is_numeric($key) && (strlen($key) > 1)){
				$sql = "
					INSERT INTO repository_pdf_words (
						repository_id,
						word,
						count
					) 
					VALUES (
						'".mysqli_real_escape_string($con,$repository_id)."',
						'".mysqli_real_escape_string($con,$key)."',
						'".mysqli_real_escape_string($con,$value)."'
					)";
				$result = mysqli_query($con,$sql);
			}
			//echo $key.' - '.$value.'<br>';
		}
	}


	function updateRepositoryOldUrl($repository_id,$repository_old_url){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "UPDATE repository	SET pdf_link_old = '".mysqli_real_escape_string($con,$repository_old_url)."' WHERE id = '".mysqli_real_escape_string($con,$repository_id)."'";
		$result = mysqli_query($con,$sql);
	}


	function updateRepositoryFile($repository_id,$repository_file){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "UPDATE repository	SET pdf_link = '".mysqli_real_escape_string($con,$repository_file)."' WHERE id = '".mysqli_real_escape_string($con,$repository_id)."'";
		$result = mysqli_query($con,$sql);

		updatePDFwords($repository_id,$repository_file);
	}


	function updateRemoveOldFile($repository_id){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "SELECT pdf_link	FROM repository WHERE id = '".mysqli_real_escape_string($con,$repository_id)."'";
		$result = mysqli_query($con,$sql);
		if(mysqli_num_rows($result) > 0){
			$content = mysqli_fetch_array($result);
			$pdf_link = $content["pdf_link"];
			$pdf_to_remove = explode("documents",$pdf_link);
			unlink($_SERVER[DOCUMENT_ROOT].'documents'.$pdf_to_remove[1]);

			$sql_rem = "DELETE FROM repository_pdf_words WHERE repository_id = '".mysqli_real_escape_string($con,$repository_id)."'";
			$result_rem = mysqli_query($con,$sql_rem);
		}
	}


	function updateRemoveOldFileAndDb($repository_id){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "SELECT pdf_link	FROM repository WHERE id = '".mysqli_real_escape_string($con,$repository_id)."'";
		$result = mysqli_query($con,$sql);
		if(mysqli_num_rows($result) > 0){
			$content = mysqli_fetch_array($result);
			$pdf_link = $content["pdf_link"];
			$pdf_to_remove = explode("documents",$pdf_link);
			unlink($_SERVER[DOCUMENT_ROOT].'documents'.$pdf_to_remove[1]);

			$sql = "UPDATE repository	SET pdf_link = '' WHERE id = '".mysqli_real_escape_string($con,$repository_id)."'";
			$result = mysqli_query($con,$sql);

			$sql_rem = "DELETE FROM repository_pdf_words WHERE repository_id = '".mysqli_real_escape_string($con,$repository_id)."'";
			$result_rem = mysqli_query($con,$sql_rem);
		}
	}


	function getRepositoryFile($repository_id){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "SELECT pdf_link	FROM repository WHERE id = '".mysqli_real_escape_string($con,$repository_id)."'";
		$result = mysqli_query($con,$sql);
		if(mysqli_num_rows($result) > 0){
			$content = mysqli_fetch_array($result);
			$pdf_link = $content["pdf_link"];
			
			return $pdf_link;
		}
		else{
			return 'empty';
		}
	}




	//**********************************************************//
	//************************ RATINGS *************************//
	//**********************************************************//
	function getRatings(){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "SELECT *	FROM ratings ORDER BY created DESC";
		$result = mysqli_query($con,$sql);

		$ctr = 0;
		while($content = mysqli_fetch_array($result)){

			$date_exp = explode(" ",$content["created"]);
			$date_exp2 = explode("-",$date_exp[0]);
			$created = $date_exp2[2].'.'.$date_exp2[1].'.'.$date_exp2[0];

			$ratings[$ctr]["id"] = $content["id"];
			$ratings[$ctr]["rating"] = $content["rating"];
			$ratings[$ctr]["comment"] = $content["comment"];
			$ratings[$ctr]["created"] = $created;

			$ctr++;
		}

		return $ratings;
	}


	function getNotificationUsers(){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "
			SELECT 
				feedback_users.id AS id,
				feedback_users.admin_id AS admin_id,
				feedback_users.email AS email,
				feedback_users.created AS created,
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

				$date_exp = explode(" ",$content["created"]);
				$date_exp2 = explode("-",$date_exp[0]);
				$created = $date_exp2[2].'.'.$date_exp2[1].'.'.$date_exp2[0];

				$feedback_users[$ctr]["id"] = $content["id"];
				$feedback_users[$ctr]["email"] = $content["email"];
				$feedback_users[$ctr]["first_name"] = $content["first_name"];
				$feedback_users[$ctr]["last_name"] = $content["last_name"];
				$feedback_users[$ctr]["created"] = $created;

				$ctr++;
			}

			return $feedback_users;
		}
		else{
			return 'empty';
		}
		
	}


	function addNotificationUser($email,$admin_id){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		//1st check if not exist
		$sql = "SELECT email FROM feedback_users WHERE email = '".mysqli_real_escape_string($con,$email)."'";
		$result = mysqli_query($con,$sql);
		if(mysqli_num_rows($result) == 0){
			$sql = "INSERT INTO feedback_users (admin_id,email,created) VALUES ('".mysqli_real_escape_string($con,$admin_id)."','".mysqli_real_escape_string($con,$email)."','".date("Y-m-d H:i:s")."')";
			$result = mysqli_query($con,$sql);
		}		
	}


	function removeNotificationUser($id){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "DELETE FROM feedback_users WHERE id = '".mysqli_real_escape_string($con,$id)."'";
		$result = mysqli_query($con,$sql);
	}




	//**********************************************************//
	//************************* USERS **************************//
	//**********************************************************//
	function getUsers(){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "SELECT id,first_name,last_name,email,status,'admin' as source FROM admin UNION SELECT id,first_name,last_name,email,status,'admin_requests' as source FROM admin_requests ORDER BY first_name ASC";
		$result = mysqli_query($con,$sql);

		$ctr = 0;
		while($content = mysqli_fetch_array($result)){

			$users[$ctr]["id"] = $content["id"];
			$users[$ctr]["first_name"] = $content["first_name"];
			$users[$ctr]["last_name"] = $content["last_name"];
			$users[$ctr]["email"] = $content["email"];
			$users[$ctr]["status"] = $content["status"];
			$users[$ctr]["source"] = $content["source"];

			$ctr++;
		}

		/*
		$sql = "SELECT * FROM admin_requests ORDER BY name ASC";
		$result = mysqli_query($con,$sql);

		while($content = mysqli_fetch_array($result)){

			$users[$ctr]["id"] = 'or_'.$content["id"];
			$users[$ctr]["first_name"] = $content["name"];
			$users[$ctr]["last_name"] = $content["lastname"];
			$users[$ctr]["email"] = $content["email"];
			$users[$ctr]["username"] = '';
			$users[$ctr]["status"] = 'open_request';

			$ctr++;
		}
		*/

		return $users;
	}


	function getUsersToApprove(){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$ctr = 0;
		$sql = "SELECT * FROM admin_requests ORDER BY first_name ASC";
		$result = mysqli_query($con,$sql);

		while($content = mysqli_fetch_array($result)){

			$users[$ctr]["id"] = 'or_'.$content["id"];
			$users[$ctr]["first_name"] = $content["first_name"];
			$users[$ctr]["last_name"] = $content["last_name"];
			$users[$ctr]["email"] = $content["email"];
			$users[$ctr]["status"] = $content["status"];

			$ctr++;
		}

		return $users;
	}


	function getUserDetails($user_id){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "SELECT * FROM admin WHERE id = '".mysqli_real_escape_string($con,$user_id)."'";
		$result = mysqli_query($con,$sql);
		if(mysqli_num_rows($result) > 0){
			$content = mysqli_fetch_array($result);

			return $content;
		}
		else{
			return 'user not found';
		}
	}


	function updateUserEmail($user_id,$user_email,$user_email_old,$google_login_status){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		if($google_login_status == '1'){
			$google_login_status = 'allowed';
		}
		else{
			$google_login_status = 'not allowed';
		}

		$sql = "UPDATE admin SET email = '".mysqli_real_escape_string($con,$user_email)."' WHERE id = '".mysqli_real_escape_string($con,$user_id)."'";
		$result = mysqli_query($con,$sql);

		$sql = "UPDATE admin_allowed SET email = '".mysqli_real_escape_string($con,$user_email)."', google_login_allowed = '".mysqli_real_escape_string($con,$google_login_status)."' WHERE email = '".mysqli_real_escape_string($con,$user_email_old)."'";
		$result = mysqli_query($con,$sql);
	}


	function updateUserDetails($user_id,$firstname,$lastname,$password,$regular_login_status){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		if($regular_login_status == '1'){
			$google_login_status = 'allowed';
		}
		else{
			$regular_login_status = 'not allowed';
		}

		$sql = "UPDATE admin SET first_name = '".mysqli_real_escape_string($con,$firstname)."', last_name = '".mysqli_real_escape_string($con,$lastname)."', login_type = '".mysqli_real_escape_string($con,$regular_login_status)."' WHERE id = '".mysqli_real_escape_string($con,$user_id)."'";
		$result = mysqli_query($con,$sql);

		if($password != ''){
			$hashed_password = password_hash($password,PASSWORD_DEFAULT, ['cost' => 15]);
			$sql = "
				UPDATE 
					admin 
				SET 
					password = '".mysqli_real_escape_string($con,$hashed_password)."'
				WHERE 
					id = '".mysqli_real_escape_string($con,$user_id)."'
			";
			$result = mysqli_query($con,$sql);
		}
	}


	function checkIfUserAllowedForGoogleLogin($email){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "SELECT google_login_allowed FROM admin_allowed WHERE email = '".mysqli_real_escape_string($con,$email)."'";
		$result = mysqli_query($con,$sql);
		$content = mysqli_fetch_array($result);

		return $content["google_login_allowed"];
	}


	function checkIfUserAllowedForRegularLogin($email){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "SELECT login_type FROM admin WHERE email = '".mysqli_real_escape_string($con,$email)."'";
		$result = mysqli_query($con,$sql);
		$content = mysqli_fetch_array($result);

		if(($content["login_type"] == '')||($content["login_type"] == 'not allowed')){
			$regular_login_status = 'not allowed';
		}
		else{
			$regular_login_status = 'allowed';
		}

		return $regular_login_status;
	}


	function approveUser($user_id){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		if(strpos($user_id, 'or_') !== false) {
		  $user_id_exp = explode("or_",$user_id);
		  $user_id_final = $user_id_exp[1];

		  $sql = "SELECT email,first_name,last_name FROM admin_requests WHERE id = '".mysqli_real_escape_string($con,$user_id_final)."'";
		  $result = mysqli_query($con,$sql);
		  $content = mysqli_fetch_array($result);

		  $email = $content["email"];
		  $first_name = $content["first_name"];
		  $last_name = $content["last_name"];

		  $sql = "INSERT INTO admin_allowed SET status = 'allowed', email = '".mysqli_real_escape_string($con,$email)."'";
		  $result = mysqli_query($con,$sql);

		  $sql = "INSERT INTO admin SET status = 'approved', email = '".mysqli_real_escape_string($con,$email)."', first_name = '".mysqli_real_escape_string($con,$first_name)."', last_name = '".mysqli_real_escape_string($con,$last_name)."', created = '".date("Y-m-d H:i:s")."', modified = '".date("Y-m-d H:i:s")."'";
		  $result = mysqli_query($con,$sql);

		  $sql = "DELETE FROM admin_requests WHERE id = '".mysqli_real_escape_string($con,$user_id_final)."'";
		  $result = mysqli_query($con,$sql);
		}
		else{
			$sql = "UPDATE admin SET status = 'approved' WHERE id = '".mysqli_real_escape_string($con,$user_id)."'";
			$result = mysqli_query($con,$sql);
		}

		
	}


	function blockUser($user_id){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		if(strpos($user_id, 'or_') !== false) {
		  $user_id_exp = explode("or_",$user_id);
		  $user_id_final = $user_id_exp[1];

		  $sql = "SELECT email,first_name,last_name FROM admin_requests WHERE id = '".mysqli_real_escape_string($con,$user_id_final)."'";
		  $result = mysqli_query($con,$sql);
		  $content = mysqli_fetch_array($result);

		  $email = $content["email"];
		  $first_name = $content["first_name"];
		  $last_name = $content["last_name"];

		  $sql = "INSERT INTO admin_allowed SET status = 'allowed', email = '".mysqli_real_escape_string($con,$email)."'";
		  $result = mysqli_query($con,$sql);

		  $sql = "INSERT INTO admin SET status = 'blocked', email = '".mysqli_real_escape_string($con,$email)."', first_name = '".mysqli_real_escape_string($con,$first_name)."', last_name = '".mysqli_real_escape_string($con,$last_name)."', created = '".date("Y-m-d H:i:s")."', modified = '".date("Y-m-d H:i:s")."'";
		  $result = mysqli_query($con,$sql);

		  $sql = "DELETE FROM admin_requests WHERE id = '".mysqli_real_escape_string($con,$user_id_final)."'";
		  $result = mysqli_query($con,$sql);
		}
		else{
			$sql = "UPDATE admin SET status = 'blocked' WHERE id = '".mysqli_real_escape_string($con,$user_id)."'";
			$result = mysqli_query($con,$sql);
		}
	}


	function approveUsers($users_id_input){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$users_id = explode(",",$users_id_input);

		foreach($users_id as $user_id){
			if(strpos($user_id, 'or_') !== false) {
			  $user_id_exp = explode("or_",$user_id);
			  $user_id_final = $user_id_exp[1];

			  $sql = "SELECT email,first_name,last_name FROM admin_requests WHERE id = '".mysqli_real_escape_string($con,$user_id_final)."'";
			  $result = mysqli_query($con,$sql);
			  $content = mysqli_fetch_array($result);

			  $email = $content["email"];
			  $first_name = $content["first_name"];
			  $last_name = $content["last_name"];

			  $sql = "INSERT INTO admin_allowed SET status = 'allowed', email = '".mysqli_real_escape_string($con,$email)."'";
			  $result = mysqli_query($con,$sql);

			  $sql = "INSERT INTO admin SET status = 'approved', email = '".mysqli_real_escape_string($con,$email)."', first_name = '".mysqli_real_escape_string($con,$first_name)."', last_name = '".mysqli_real_escape_string($con,$last_name)."', created = '".date("Y-m-d H:i:s")."', modified = '".date("Y-m-d H:i:s")."'";
			  $result = mysqli_query($con,$sql);

			  $sql = "DELETE FROM admin_requests WHERE id = '".mysqli_real_escape_string($con,$user_id_final)."'";
			  $result = mysqli_query($con,$sql);
			}
			else{
				$sql = "UPDATE admin SET status = 'approved' WHERE id = '".mysqli_real_escape_string($con,$user_id)."'";
				$result = mysqli_query($con,$sql);
			}
		}
	}




	//**********************************************************//
	//************************* COOKIES ************************//
	//**********************************************************//

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


	function udpateCookieBanner($lang_id,$cookie_banner_title,$cookie_banner_text,$cookie_banner_text_link_1,$cookie_banner_url_link_1,$cookie_banner_text_link_2,$cookie_banner_url_link_2,$cookie_banner_option_1,$cookie_banner_option_2,$cookie_banner_small_text,$cookie_banner_declaration,$cookie_banner_title_second,$cookie_banner_text_second,$cookie_banner_back){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "UPDATE cookie_banner_lang SET name = '".mysqli_real_escape_string($con,$cookie_banner_title)."' WHERE id_lang = '".mysqli_real_escape_string($con,$lang_id)."' AND cookie_id = 1";
		$result = mysqli_query($con,$sql);

		$sql = "UPDATE cookie_banner_lang SET name = '".mysqli_real_escape_string($con,$cookie_banner_text)."' WHERE id_lang = '".mysqli_real_escape_string($con,$lang_id)."' AND cookie_id = 2";
		$result = mysqli_query($con,$sql);

		$sql = "UPDATE cookie_banner_lang SET name = '".mysqli_real_escape_string($con,$cookie_banner_text_link_1)."' WHERE id_lang = '".mysqli_real_escape_string($con,$lang_id)."' AND cookie_id = 3";
		$result = mysqli_query($con,$sql);

		$sql = "UPDATE cookie_banner_lang SET url = '".mysqli_real_escape_string($con,$cookie_banner_url_link_1)."' WHERE id_lang = '".mysqli_real_escape_string($con,$lang_id)."' AND cookie_id = 3";
		$result = mysqli_query($con,$sql);

		$sql = "UPDATE cookie_banner_lang SET name = '".mysqli_real_escape_string($con,$cookie_banner_text_link_2)."' WHERE id_lang = '".mysqli_real_escape_string($con,$lang_id)."' AND cookie_id = 4";
		$result = mysqli_query($con,$sql);

		$sql = "UPDATE cookie_banner_lang SET url = '".mysqli_real_escape_string($con,$cookie_banner_url_link_2)."' WHERE id_lang = '".mysqli_real_escape_string($con,$lang_id)."' AND cookie_id = 4";
		$result = mysqli_query($con,$sql);

		$sql = "UPDATE cookie_banner_lang SET name = '".mysqli_real_escape_string($con,$cookie_banner_option_1)."' WHERE id_lang = '".mysqli_real_escape_string($con,$lang_id)."' AND cookie_id = 5";
		$result = mysqli_query($con,$sql);

		$sql = "UPDATE cookie_banner_lang SET name = '".mysqli_real_escape_string($con,$cookie_banner_option_2)."' WHERE id_lang = '".mysqli_real_escape_string($con,$lang_id)."' AND cookie_id = 6";
		$result = mysqli_query($con,$sql);

		$sql = "UPDATE cookie_banner_lang SET name = '".mysqli_real_escape_string($con,$cookie_banner_small_text)."' WHERE id_lang = '".mysqli_real_escape_string($con,$lang_id)."' AND cookie_id = 7";
		$result = mysqli_query($con,$sql);

		$sql = "UPDATE cookie_banner_lang SET name = '".mysqli_real_escape_string($con,$cookie_banner_declaration)."' WHERE id_lang = '".mysqli_real_escape_string($con,$lang_id)."' AND cookie_id = 8";
		$result = mysqli_query($con,$sql);

		$sql = "UPDATE cookie_banner_lang SET name = '".mysqli_real_escape_string($con,$cookie_banner_title_second)."' WHERE id_lang = '".mysqli_real_escape_string($con,$lang_id)."' AND cookie_id = 9";
		$result = mysqli_query($con,$sql);

		$sql = "UPDATE cookie_banner_lang SET name = '".mysqli_real_escape_string($con,$cookie_banner_text_second)."' WHERE id_lang = '".mysqli_real_escape_string($con,$lang_id)."' AND cookie_id = 10";
		$result = mysqli_query($con,$sql);

		$sql = "UPDATE cookie_banner_lang SET name = '".mysqli_real_escape_string($con,$cookie_banner_back)."' WHERE id_lang = '".mysqli_real_escape_string($con,$lang_id)."' AND cookie_id = 11";
		$result = mysqli_query($con,$sql);

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


	function updateCookieBannerStatus($status){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "UPDATE cookie_banner_settings SET value = '".mysqli_real_escape_string($con,$status)."' WHERE id = 1";
		$result = mysqli_query($con,$sql);
	}



	function updateCookieBannerSettings($analytics,$hotjar){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "UPDATE cookie_banner_settings SET value = '".mysqli_real_escape_string($con,$analytics)."' WHERE id = 2";
		$result = mysqli_query($con,$sql);

		$sql = "UPDATE cookie_banner_settings SET value = '".mysqli_real_escape_string($con,$hotjar)."' WHERE id = 3";
		$result = mysqli_query($con,$sql);
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



	function udpateFooter($lang_id,$footer_copyright,$footer_text_link_1,$footer_url_link_1,$footer_text_link_2,$footer_url_link_2,$footer_text_link_3,$footer_url_link_3,$footer_text_link_4,$footer_url_link_4){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "UPDATE footer_lang SET name = '".mysqli_real_escape_string($con,$footer_copyright)."' WHERE id_lang = '".mysqli_real_escape_string($con,$lang_id)."' AND footer_id = 1";
		$result = mysqli_query($con,$sql);

		$sql = "UPDATE footer_lang SET name = '".mysqli_real_escape_string($con,$footer_text_link_1)."' WHERE id_lang = '".mysqli_real_escape_string($con,$lang_id)."' AND footer_id = 2";
		$result = mysqli_query($con,$sql);

		$sql = "UPDATE footer_lang SET url = '".mysqli_real_escape_string($con,$footer_url_link_1)."' WHERE id_lang = '".mysqli_real_escape_string($con,$lang_id)."' AND footer_id = 2";
		$result = mysqli_query($con,$sql);

		$sql = "UPDATE footer_lang SET name = '".mysqli_real_escape_string($con,$footer_text_link_2)."' WHERE id_lang = '".mysqli_real_escape_string($con,$lang_id)."' AND footer_id = 3";
		$result = mysqli_query($con,$sql);

		$sql = "UPDATE footer_lang SET url = '".mysqli_real_escape_string($con,$footer_url_link_2)."' WHERE id_lang = '".mysqli_real_escape_string($con,$lang_id)."' AND footer_id = 3";
		$result = mysqli_query($con,$sql);

		$sql = "UPDATE footer_lang SET name = '".mysqli_real_escape_string($con,$footer_text_link_3)."' WHERE id_lang = '".mysqli_real_escape_string($con,$lang_id)."' AND footer_id = 4";
		$result = mysqli_query($con,$sql);

		$sql = "UPDATE footer_lang SET url = '".mysqli_real_escape_string($con,$footer_url_link_3)."' WHERE id_lang = '".mysqli_real_escape_string($con,$lang_id)."' AND footer_id = 4";
		$result = mysqli_query($con,$sql);

		$sql = "UPDATE footer_lang SET name = '".mysqli_real_escape_string($con,$footer_text_link_4)."' WHERE id_lang = '".mysqli_real_escape_string($con,$lang_id)."' AND footer_id = 5";
		$result = mysqli_query($con,$sql);

		$sql = "UPDATE footer_lang SET url = '".mysqli_real_escape_string($con,$footer_url_link_4)."' WHERE id_lang = '".mysqli_real_escape_string($con,$lang_id)."' AND footer_id = 5";
		$result = mysqli_query($con,$sql);

		

	}
?>