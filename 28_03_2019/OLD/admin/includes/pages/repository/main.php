<?php session_start();

	if (isset($_SESSION['admin_logged_in'])) {
		//logged

		include_once 'classes/general.php';

		?>
		<div class="main_table_container">
			<div class="main_table_columns_titles">
				<div class="main_table_column_title repository_col_1"><span>Name</span></div>
				<div class="main_table_column_title repository_col_2"><span>Art</span></div>
				<div class="main_table_column_title repository_col_3"><span>Typ</span></div>
				<div class="main_table_column_title repository_col_4"><span>Verbund en mit</span></div>
				<div class="main_table_column_title repository_col_5"><span>Änderungs-datum</span></div>
				<div class="main_table_column_title repository_col_6"><span>Material nummer</span></div>
				<div class="main_table_column_title repository_col_7"><span>Tag- Beschreibung</span></div>
				<div class="main_table_column_title repository_col_8"><span>Anzahl der Downloads</span></div>
				<div class="main_table_column_title repository_col_9"><span>Link</span></div>
				<div class="main_table_column_title repository_col_10"><span>Upload</span></div>
				<div class="main_table_column_title repository_col_11"><span><i class="pe-7s-trash"></i></span></div>
			</div>
		
			<?php
				$id_lang = $_SESSION['lang'];
				$repositories = getRepositories($id_lang);
				if(count($repositories) > 0){
					foreach($repositories as $repository){
						echo '<div class="main_table_row repository_row">';
						echo '<div class="main_table_column repository_col_1" data-id="'.$repository["id"].'"><span>'.$repository["name"].'</span></div>';
						echo '<div class="main_table_column repository_col_2" data-id="'.$repository["id"].'"><span>'.$repository["art"].'</span></div>';
						echo '<div class="main_table_column repository_col_3" data-id="'.$repository["id"].'"><span>'.$repository["typ"].'</span></div>';
						echo '<div class="main_table_column repository_col_4" data-id="'.$repository["id"].'"><span>'.$repository["relations"].'</span></div>';
						echo '<div class="main_table_column repository_col_5" data-id="'.$repository["id"].'"><span>'.$repository["modification_date"].'</span></div>';
						echo '<div class="main_table_column repository_col_6" data-id="'.$repository["id"].'"><span>'.$repository["material_number"].'</span></div>';
						echo '<div class="main_table_column repository_col_7" data-id="'.$repository["id"].'"><span>'.$repository["tags"].'</span></div>';
						echo '<div class="main_table_column repository_col_8" data-id="'.$repository["id"].'"><span>'.$repository["downloads_counter"].'</span></div>';
						echo '<div class="main_table_column repository_col_9" data-id="'.$repository["id"].'"><span><a href="'.$repository["pdf_link"].'" target="_blank">'.$repository["pdf_link"].'</a></span><div class="old_url" style="display:none;">'.$repository["pdf_link_old"].'</div></div>';
						echo '<div class="main_table_column repository_col_10" data-id="'.$repository["id"].'"><span><i class="pe-7s-cloud-upload"></i> Hochladen</span></div>';
						echo '<div class="main_table_column repository_col_11" data-id="'.$repository["id"].'"><span><input type="checkbox" class="repository_remove_checkbox" name="repository_remove" value="'.$repository["id"].'"></span></div>';
						echo '</div>';
					}
				}
			?>
		</div>

		<?php
	}
?>