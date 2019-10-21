<?php session_start();

    $root = ($_SERVER['SERVER_NAME'] == 'ttal.loc') ? '/' : '';
    include_once $_SERVER["DOCUMENT_ROOT"].$root.'pandora/classes/general.php';

	if (isset($_SESSION['admin_logged_in'])) {
	
		if(isset($_POST["repository_item_name"])){

			$repository_item_name = $_POST["repository_item_name"];
			$repository_art = $_POST["repository_art"];
			$repository_typ = $_POST["repository_typ"];
			$repository_relations = $_POST["repository_relations"];
			$repository_date = $_POST["repository_date"];
			$repository_material_number = $_POST["repository_material_number"];
			$repository_tags = $_POST["repository_tags"];
			$repository_file_url = $_POST["repository_file_url"];
			$repository_old_file_url = $_POST["repository_old_file_url"];

			print_r($repository_file_url);

			$new_repository_id = addRepositoryItem($repository_item_name,$repository_art,$repository_typ,$repository_date,$repository_file_url,$repository_old_file_url,$_SESSION['lang']);
			addRepositoryDownloads($new_repository_id);
			addRepositoryTags($new_repository_id,$repository_tags);
			addRepositoryRelations($new_repository_id,$repository_relations,$_SESSION['lang']);
			updateRepositoryMaterialNumber($new_repository_id,$repository_material_number);

			if($repository_file_url != ''){
				updatePDFwords($new_repository_id,$repository_file_url);
			}
			

			echo 'Saved';
		}


		if(isset($_POST["get_relations"])){
			if($_POST["get_relations"] == 'add'){
				if(isset($_POST["current_id"])){
					$repositories = getRepositories($_SESSION['lang'],'add',$_POST["current_id"]);
				}
				else{
					$repositories = getRepositories($_SESSION['lang'],'add');
				}
				echo implode("||",$repositories);
			}
		}


		if(isset($_POST["repositories_to_remove"])){
			if(count($_POST["repositories_to_remove"]) > 0){
				$repositories_to_remove = $_POST["repositories_to_remove"];

				foreach($repositories_to_remove as $repository_to_remove){
					removeRepository($repository_to_remove);
				}
			}
		}


		if(isset($_POST["edit_repository_item_name"])){
			updateRepositoryName($_POST["edit_repository_item_id"],$_POST["edit_repository_item_name"]);
		}


		if(isset($_POST["step_2_selection"])){
			updateRepositoryArt($_POST["edit_repository_item_id"],$_POST["step_2_selection"]);
		}


		if(isset($_POST["step_3_selection"])){
			updateRepositoryTyp($_POST["edit_repository_item_id"],$_POST["step_3_selection"]);
		}


		if(isset($_POST["step_4_selection"])){
			updateRepositoryRelations($_POST["edit_repository_item_id"],$_POST["step_4_selection"],$_SESSION['lang']);
		}


		if(isset($_POST["step_5_selection"])){
			updateRepositoryDate($_POST["edit_repository_item_id"],$_POST["step_5_selection"]);
		}


		if(isset($_POST["step_6_selection"])){
			updateRepositoryMaterialNumber($_POST["edit_repository_item_id"],$_POST["step_6_selection"]);
		}


		if(isset($_POST["step_7_selection"])){
			updateRepositoryTags($_POST["edit_repository_item_id"],$_POST["step_7_selection"]);
		}


		if(isset($_POST["file_uploaded"])){
			updateRepositoryFile($_POST["edit_repository_item_id"],$_POST["file_uploaded"]);
		}


		if(isset($_POST["remove_old_file"])){
			updateRemoveOldFile($_POST["remove_old_file"]);
		}


		if(isset($_POST["get_repository_file"])){
			$repository_file = getRepositoryFile($_POST["get_repository_file"]);
			echo $repository_file;
		}


		if(isset($_POST["remove_old_file_and_db_entry"])){
			updateRemoveOldFileAndDb($_POST["remove_old_file_and_db_entry"]);
		}
	
		
	}
?>