<?php

	function getSearchResult($id_lang,$keyword){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');
		
		//by name
		$sql = "
			SELECT 
				name
			FROM
				repository
			WHERE
				id_lang = $id_lang
			AND
				name LIKE '%".mysqli_real_escape_string($con,$keyword)."%'
		";
		$result = mysqli_query($con,$sql);
		
		while($content = mysqli_fetch_array($result)){
			$repositories[] = $content["name"];
		}

		//by material number
		$sql = "
			SELECT 
				name
			FROM
				repository
			WHERE
				id_lang = $id_lang
			AND
				material_number LIKE '%".mysqli_real_escape_string($con,$keyword)."%'
		";
		$result = mysqli_query($con,$sql);
		
		while($content = mysqli_fetch_array($result)){
			$repositories[] = $content["name"];
		}

		//by tags
		$sql = "
			SELECT 
				repository.name AS name
			FROM
				repository
			JOIN
				repository_tags
			ON
				repository.id = repository_tags.repository_id
			WHERE
				repository.id_lang = $id_lang
			AND
				repository_tags.name LIKE '%".mysqli_real_escape_string($con,$keyword)."%'
		";
		$result = mysqli_query($con,$sql);
		
		while($content = mysqli_fetch_array($result)){
			$repositories[] = $content["name"];
		}

		if(count($repositories) > 1){
			$repositories_no_duplicated = array_unique($repositories);
			natsort($repositories_no_duplicated);
		}
		else{
			$repositories_no_duplicated = $repositories;
		}

		return $repositories_no_duplicated;
			
	}


	function getSearchResultFromPDFContent($id_lang,$keyword){
		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');
		
		//by name
		$sql = "
			SELECT 
				repository_pdf_words.repository_id AS repository_id,
				repository_pdf_words.count AS word_count
			FROM
				repository_pdf_words
			JOIN
				repository
			ON
				repository_pdf_words.repository_id = repository.id
			WHERE
				repository.id_lang = $id_lang
			AND
				word LIKE '%".mysqli_real_escape_string($con,$keyword)."%'
			ORDER BY
				repository_pdf_words.count DESC
		";
		$result = mysqli_query($con,$sql);
		
		while($content = mysqli_fetch_array($result)){
			$repositories[] = $content["repository_id"];
		}

		if(count($repositories) > 1){
			$repositories_no_duplicated = array_unique($repositories);
			natsort($repositories_no_duplicated);
		}
		else{
			$repositories_no_duplicated = $repositories;
		}
		

		return $repositories_no_duplicated;
			
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
				name = '".mysqli_real_escape_string($con,$repository_name)."'
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
		$repository_set_link = 'http://'.$_SERVER['SERVER_NAME'].'/safety-sheets/set_'.date("d_m_Y").'_'.time();
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
				id
			FROM
				repository
			WHERE
				id_lang = $id_lang
			AND
				typ = '$typ'
		";
		$result = mysqli_query($con,$sql);
		
		while($content = mysqli_fetch_array($result)){
			$repositories[] = $content["id"];
		}

		return $repositories;	
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

?>