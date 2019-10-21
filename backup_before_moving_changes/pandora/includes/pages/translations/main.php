<?php session_start();
	if (isset($_SESSION['admin_logged_in'])) {
		//logged

		include_once 'classes/general.php';


		?>
		<div class="main_table_header">
			Languages
		</div>

		<div class="main_table_container">
			<div class="main_table_columns_titles">
				<?php
				$main_table_columns_titles = getLangsNames();
				$items = count($main_table_columns_titles) + 1;
				$item_width = 100 / $items;
				echo '<div class="main_table_column_title" style="width: '.$item_width.'%"><span>Content Description</span></div>';
				foreach($main_table_columns_titles as $main_table_column_title){
					echo '<div class="main_table_column_title" style="width: '.$item_width.'%"><span>'.$main_table_column_title["lang_name"].'</span></div>';
				}
				?>
			</div>
		
			<?php
				$main_table_columns_titles = getLangsNames();
				foreach($main_table_columns_titles as $main_table_column_title){
					echo '<div class="main_table_row">';
					echo '<div class="main_table_column" style="width: '.$item_width.'%"><span>Language: '.$main_table_column_title["lang_name"].'</span></div>';
					$lang_values = getLangsTranslationsValues($main_table_column_title["language_id"]);
					foreach($lang_values as $lang_value){
						echo '
							<div class="main_table_column" style="width: '.$item_width.'%">
								<textarea rows="1" class="lang_value_input lang_field" name="lang_value" id="lang_value-'.$lang_value["id"].'">'.$lang_value["name"].'</textarea>
								<textarea rows="1" class="source_lang_value_input lang_field" name="lang_value" id="source_lang_value-'.$lang_value["id"].'">'.$lang_value["name"].'</textarea>
							</div>
							';
					}
					echo '</div>';
				}
			?>
		</div>



		<?php
		$sections = getTranslationsHeaders();
		if($headers != 'no_headers'){
			foreach($sections as $section){
				?>

				<div class="main_table_header">
					<?php echo $section; ?>
				</div>

				<div class="main_table_container">
					<div class="main_table_columns_titles">
						<?php
						$main_table_columns_titles = getLangsNames();
						$items = count($main_table_columns_titles) + 1;
						$item_width = 100 / $items;
						echo '<div class="main_table_column_title" style="width: '.$item_width.'%"><span>Content Description</span></div>';
						foreach($main_table_columns_titles as $main_table_column_title){
							echo '<div class="main_table_column_title" style="width: '.$item_width.'%"><span>'.$main_table_column_title["lang_name"].'</span></div>';
						}
						?>
					</div>
	
					<?php
					$descriptions = getTranslationsDescriptions($section);
					foreach($descriptions as $description){
						echo '<div class="main_table_row">';
							echo '<div class="main_table_column" style="width: '.$item_width.'%"><span>'.$description["description"].'</span></div>';
							$lang_values = getTranslationsValues($description["id"]);
							foreach($lang_values as $lang_value){
								echo '
									<div class="main_table_column" style="width: '.$item_width.'%">
										<textarea rows="1" class="lang_value_input" name="lang_value" id="lang_value-'.$lang_value["id"].'">'.$lang_value["name"].'</textarea>
										<textarea rows="1" class="source_lang_value_input" name="lang_value" id="source_lang_value-'.$lang_value["id"].'">'.$lang_value["name"].'</textarea>
									</div>
									';
							}
						echo '</div>';
					}
					?>
				</div>
			
				<?php
			}
		}
	}
?>