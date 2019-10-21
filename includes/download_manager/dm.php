<?php

    $root = ($_SERVER['SERVER_NAME'] == 'ttal.loc') ? '/' : '';

    include_once $_SERVER["DOCUMENT_ROOT"].$root.'classes/general.php';
	$translation = getTranslation($_SESSION['lang']);

	$dm_repos_content = json_decode($_COOKIE['dm_repos'], true);

	if(strpos($_SERVER['REQUEST_URI'], '/safety-sheets/') !== false) {
	  $repository_set = getRepositorySet('http://ttal.loc'.$_SERVER['REQUEST_URI']);
	  if($repository_set != 'not found'){
	  	$dm_repos_content = explode(",",$repository_set);
	  }
	}

	for ($x = 0; $x < count($dm_repos_content); $x++) {
	  $dm_repo_id = $dm_repos_content[$x];

	  $repository_details = getRepositoryDetailsById($dm_repo_id);
	  if($repository_details["art"] == 'step_2_safety_datasheet'){
	  	$art = 'SDB';
	  }
	  else{
	  	$art = 'PDB';
	  }
	  $repository_date_exp = explode(" ",$repository_details["modification_date"]);
	  $repository_date_exp_deep = explode("-",$repository_date_exp[0]);
	  $repository_date = $repository_date_exp_deep[2].".".$repository_date_exp_deep[1].".".$repository_date_exp_deep[0];

	  $prepare_name = $art.'_'.$repository_details["name"];
	  $pdf_name = makeURL($prepare_name);

	  echo '<div class="dmc_repositories_container_line" id="dmi_'.$repository_details["id"].'">';
	  	echo '<span class="dmc_repositories_container_line_name">'.$repository_details["name"].'</span>';
	  	echo '<span class="dmc_repositories_container_line_art">'.$art.'</span>';
	  	echo '<span class="dmc_repositories_container_line_date">'.$translation[11].'<br>'.$repository_date.'</span>';
	  	echo '<span class="dmc_repositories_container_line_repository_button"><span class="open_in_pdf_viewer" data-pdf-name="'.$pdf_name.'" data-search_repository_id="'.$repository_details["id"].'">'.$translation[21].'</span></span>';
	  	echo '<span class="dmc_repositories_container_line_repository_button"><span class="remove_from_download_manager" data-search_repository_id="'.$repository_details["id"].'">'.$translation[22].'</span></span>';
	  echo '</div>';
	} 




?>