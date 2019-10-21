<?php session_start();

    $root = ($_SERVER['SERVER_NAME'] == 'ttal.loc') ? '/' : '';

    include_once $_SERVER["DOCUMENT_ROOT"].$root.'classes/general.php';
	$translation = getTranslation($_SESSION['lang']);

	if(isset($_POST["load_all"])){
		?>
		<div class="overview_table_sdb_material" data-simplebar>
			<div class="overview_table_results">
				<?php
					//$repositories_material = getRepositoriesByType($_SESSION['lang'],'material');
					$repositories_material = getRepositoriesByArt($_SESSION['lang'],'safety','material');

					if(count($repositories_material)>0){
						foreach ($repositories_material as $r) {
							echo '<div class="overview_table_line" data-overview_repository_id="'.$r['id'].'">';
								echo '<i class="pe-7s-file"></i> <span class="overview_table_item_name">'.$r["name"].'</span>';
							echo '</div>';
						}
					}
				?>
			</div>
		</div>

		<div class="overview_table_sdb_brandgas" data-simplebar>
			<div class="overview_table_results">
				<?php
					//$repositories_material = getRepositoriesByType($_SESSION['lang'],'material');
					$repositories_material = getRepositoriesByArt($_SESSION['lang'],'safety','brandgas');

					if(count($repositories_material)>0){
						foreach ($repositories_material as $r) {
							echo '<div class="overview_table_line" data-overview_repository_id="'.$r['id'].'">';
								echo '<i class="pe-7s-file"></i> <span class="overview_table_item_name">'.$r["name"].'</span>';
							echo '</div>';
						}
					}
				?>
			</div>
		</div>

		<div class="overview_table_pdb_material" data-simplebar>
			<div class="overview_table_results">
				<?php
					//$repositories_brandgas = getRepositoriesByType($_SESSION['lang'],'brandgas');
					$repositories_brandgas = getRepositoriesByArt($_SESSION['lang'],'product','material');

					if(count($repositories_brandgas)>0){
						foreach ($repositories_brandgas as $r) {
							echo '<div class="overview_table_line" data-overview_repository_id="'.$r['id'].'">';
								echo '<i class="pe-7s-file"></i> <span class="overview_table_item_name">'.$r["name"].'</span>';
							echo '</div>';
						}
					}
				?>
			</div>
		</div>

		<div class="overview_table_pdb_brandgas" data-simplebar>
			<div class="overview_table_results">
				<?php
					//$repositories_brandgas = getRepositoriesByType($_SESSION['lang'],'brandgas');
					$repositories_brandgas = getRepositoriesByArt($_SESSION['lang'],'product','brandgas');

					if(count($repositories_brandgas)>0){
						foreach ($repositories_brandgas as $r) {
							echo '<div class="overview_table_line" data-overview_repository_id="'.$r['id'].'">';
								echo '<i class="pe-7s-file"></i> <span class="overview_table_item_name">'.$r["name"].'</span>';
							echo '</div>';
						}
					}
				?>
			</div>
		</div>
		<?php
	}


	if(isset($_POST["load_repository_details"])){
		$repository_id = $_POST["repository_id"];

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

		echo '
		<div class="overview_details_close">
			<i class="pe-7s-close"></i>
			<div>
				<span>'.$translation[60].'</span>
				'.$translation[61].'
			</div>
		</div>
		';

		$prepare_name = $art.'_'.$repository_details["name"];
		$pdf_name = makeURL($prepare_name);

		echo '<div class="overview_details">';
			echo '<i class="pe-7s-file"></i>';
			echo '<div class="overview_details_repository_name">'.$repository_details["name"].'</div>';
			echo '<div class="overview_details_repository_details">';
				echo '<span class="repository_art">'.$art.'</span>';
				echo '<span class="repository_date">'.$translation[11].'<br>'.$repository_date.'</span>';
				
				if($repository_details["pdf_link"] != ''){
					echo '<span class="repository_button"><span class="open_in_pdf_viewer" data-pdf-name="'.$pdf_name.'" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-expand1"></i>'.$translation[12].'</span></span>';
					echo '<span class="repository_button"><span class="direct_download" data-pdf-name="'.$pdf_name.'" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-cloud-download"></i>'.$translation[13].'</span></span>';
					echo '<span class="repository_button"><span class="add_to_download_manager" data-search_repository_id="'.$repository_details["id"].'"><i class="pe-7s-plus"></i>'.$translation[14].'<br>'.$translation[59].'</span></span>';
				}
		
			echo '</div>';
		echo '</div>';
	}


?>