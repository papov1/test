<?php session_start();

	include_once $_SERVER["DOCUMENT_ROOT"].'admin/classes/general.php';

	if (isset($_SESSION['admin_logged_in'])) {
		
		if(isset($_POST["lang_val_id"])){
			$lang_val_id = $_POST["lang_val_id"];
			$lang_val = $_POST["lang_val"];

			echo updateTranslationValue($lang_val_id,$lang_val);
		}

		if(isset($_POST["lang_val_id_lo"])){
			$lang_val_id = $_POST["lang_val_id_lo"];
			$lang_val = $_POST["lang_val"];

			updateLangTranslationValue($lang_val_id,$lang_val);

			echo 'Saved';
		}

		if(isset($_POST["new_lang_name"])){
			$new_lang_name = $_POST["new_lang_name"];
			$new_lang_iso = $_POST["new_lang_iso"];

			addNewLanguage($new_lang_name,$new_lang_iso);
		}

		if(isset($_POST["get_langs_to_remove"])){

			$langs = getLangsNames();
			foreach($langs as $lang){

				if(($lang["language_id"] == 1)||($lang["language_id"] == 2)){
					$button_class = 'lang_to_remove_button_inactive';
				}
				else{
					$button_class = 'lang_to_remove_button';
				}

				echo '
					<div class="lang_to_remove_section">
						<div class="lang_to_remove_name">'.$lang["lang_name"].'</div>
						<div id="remove_lang_'.$lang["language_id"].'" class="'.$button_class.'">Remove</div>

						<div class="lang_remove_validation" id="lang_remove_validation_'.$lang["language_id"].'">
							<div class="lang_remove_validation_content">
								<div class="lang_remove_validation_text">
									Type <strong>remove</strong> to confirm removal.
								</div>
								<input type="text" id="lang_remove_validation_field_'.$lang["language_id"].'" name="lang_remove_validation_field">
								<div class="lang_remove_validation_error" id="lang_remove_validation_error_'.$lang["language_id"].'">Typed verification phrase is incorrect</div>
								<div class="lang_remove_validation_button" id="lang_remove_validation_button_'.$lang["language_id"].'">Validate</div>
							</div>
						</div>

					</div>
				';

			}
		}


		if(isset($_POST["language_to_remove"])){
			$lang_id_to_remove = $_POST["language_to_remove"];

			removeLanguage($lang_id_to_remove);
		}


		if(isset($_POST["change_language"])){
			$_SESSION['lang'] = $_POST["lang_id"];
		}

	}
?>