<?php

	include_once $_SERVER["DOCUMENT_ROOT"].'classes/general.php';

	if(isset($_POST["keyword"])){
		if($_POST["keyword"] != ''){
			$repositories = getSearchResult(1,$_POST["keyword"]);

			if(count($repositories)>0){
				echo '
				<div class="search_title">
					Suchergebnisse - nach	Titel:
				</div>
				';

				foreach ($repositories as $repository) {
					$repository_details = getRepositoryDetails($repository);
					if($repository_details["art"] == 'step_2_safety_datasheet'){
						$art = 'SDB';
					}
					else{
						$art = 'PDB';
					}
					$repository_date_exp = explode(" ",$repository_details["modification_date"]);
					$repository_date_exp_deep = explode("-",$repository_date_exp[0]);
					$repository_date = $repository_date_exp_deep[2].".".$repository_date_exp_deep[1].".".$repository_date_exp_deep[0];


					echo '<div class="autocomplete-suggestion">';
						echo '<div class="repository_name">'.$repository.'</div>';
						echo '<div class="repository_details">';
							echo '<span class="repository_art">'.$art.'</span>';
							echo '<span class="repository_date">zuletzt aktualisiert am<br>'.$repository_date.'</span>';
							
							if($repository_details["pdf_link"] != ''){
								echo '<span class="repository_button"><span class="open_in_pdf_viewer" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-expand1"></i>Ansicht öffnen</span></span>';
								echo '<span class="repository_button"><span class="direct_download" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-cloud-download"></i>Herunterladen</span></span>';
								echo '<span class="repository_button"><span class="add_to_download_manager" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-plus"></i>Zum Download<br>Manager hinzufügen</span></span>';
							}
					
						echo '</div>';
					echo '</div>';
				}
			}
			
			$repositories_pdf = getSearchResultFromPDFContent(1,$_POST["keyword"]);

			if(count($repositories_pdf)>0){
				echo '
				<div class="search_title">
					Suchergebnisse - nach	Inhalt:
				</div>
				';

				
				foreach ($repositories_pdf as $repository) {
					$repository_details = getRepositoryDetailsById($repository);
					if($repository_details["art"] == 'step_2_safety_datasheet'){
						$art = 'SDB';
					}
					else{
						$art = 'PDB';
					}
					$repository_date_exp = explode(" ",$repository_details["modification_date"]);
					$repository_date_exp_deep = explode("-",$repository_date_exp[0]);
					$repository_date = $repository_date_exp_deep[2].".".$repository_date_exp_deep[1].".".$repository_date_exp_deep[0];


					echo '<div class="autocomplete-suggestion">';
						echo '<div class="repository_name">'.$repository_details["name"].'</div>';
						echo '<div class="repository_details">';
							echo '<span class="repository_art">'.$art.'</span>';
							echo '<span class="repository_date">zuletzt aktualisiert am<br>'.$repository_date.'</span>';
							
							if($repository_details["pdf_link"] != ''){
								echo '<span class="repository_button"><span class="open_in_pdf_viewer" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-expand1"></i>Ansicht öffnen</span></span>';
								echo '<span class="repository_button"><span class="direct_download" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-cloud-download"></i>Herunterladen</span></span>';
								echo '<span class="repository_button"><span class="add_to_download_manager" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-plus"></i>Zum Download<br>Manager hinzufügen</span></span>';
							}
					
						echo '</div>';
					echo '</div>';
				}
			}
		}
	}


?>