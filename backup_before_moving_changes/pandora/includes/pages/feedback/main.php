<?php session_start();
	if (isset($_SESSION['admin_logged_in'])) {
		//logged

		include_once 'classes/general.php';

		?>
		<div class="main_table_container">
			<div class="main_table_columns_titles">
				<div class="main_table_column_title feedback_col_1"><span>Date</span></div>
				<div class="main_table_column_title feedback_col_2"><span>Rating (5 - best, 1 - poor)</span></div>
				<div class="main_table_column_title feedback_col_3"><span>Customer comment</span></div>
			</div>
		
			<?php
				$id_lang = 1;
				$ratings = getRatings();
				if(count($ratings) > 0){
					foreach($ratings as $rating){
						echo '<div class="main_table_row repository_row">';
						echo '<div class="main_table_column feedback_col_1" data-id="'.$rating["id"].'"><span>'.$rating["created"].'</span></div>';
						echo '<div class="main_table_column feedback_col_2" data-id="'.$rating["id"].'"><span>'.$rating["rating"].'</span></div>';
						echo '<div class="main_table_column feedback_col_3" data-id="'.$rating["id"].'"><span>'.$rating["comment"].'</span></div>';
						echo '</div>';
					}
				}
			?>
		</div>

		<?php
	}
?>