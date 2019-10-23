<?php session_start();

    $root = ($_SERVER['SERVER_NAME'] == '3.16.24.200') ? '/' : '';
    include_once $_SERVER["DOCUMENT_ROOT"].$root.'pandora/classes/general.php';

	if (isset($_SESSION['admin_logged_in'])) {

		if((!isset($_POST["notifications"])) && (!isset($_POST["feedback_new_user_email"])) && (!isset($_POST["feedback_user_id_to_remove"]))) {
	
			header('Content-Type: text/csv; charset=utf-8');
			header('Content-Disposition: attachment; filename=feedback.csv');

			$output = fopen('php://output', 'w');

			
			$feedback_arr[0] = array('Date', 'Rating (5 - best, 1 - poor)', 'Customer comment');
			$ctr = 1;

			$ratings = getRatings();
			foreach($ratings as $rating){
				$date = $rating["created"];
				$rating_value = $rating["rating"];
				$comment = $rating["comment"];

				$feedback_arr[$ctr] = array($date, $rating_value, $comment);
				$ctr++;
			}

			foreach ($feedback_arr as $line) {
			  fputcsv($output, $line, ';');
			}
			fclose($output);
		}




		if(isset($_POST["notifications"])){
			$feedback_users = getNotificationUsers();

			echo '
				<div class="main_table_container">
					<div class="main_table_columns_titles">
						<div class="main_table_column_title feedback_users_col_1"><span>Date added</span></div>
						<div class="main_table_column_title feedback_users_col_2"><span>E-mail</span></div>
						<div class="main_table_column_title feedback_users_col_3"><span>Added by</span></div>
						<div class="main_table_column_title feedback_users_col_4"><span>Delete</span></div>
					</div>
					
				';
			
			if($feedback_users != 'empty'){
				foreach($feedback_users as $feedback_user){
					echo '
					<div class="main_table_row repository_row">
						<div class="main_table_column feedback_users_col_1" data-id="'.$feedback_user["id"].'"><span>'.$feedback_user["created"].'</span></div>
						<div class="main_table_column feedback_users_col_2" data-id="'.$feedback_user["id"].'"><span>'.$feedback_user["email"].'</span></div>
						<div class="main_table_column feedback_users_col_3" data-id="'.$feedback_user["id"].'"><span>'.$feedback_user["first_name"].' '.$feedback_user["last_name"].'</span></div>
						<div class="main_table_column feedback_users_col_4 remove_feedback_user" data-id="'.$feedback_user["id"].'"><span><i class="pe-7s-trash"></i> <p>Delete</p></span></div>
					</div>
					';
				}
			}
			else{
				echo '
				<div class="main_table_row repository_row" style="padding:5px 15px; font-size: 15px; font-weight: 400;">
					No users at the moment
				</div>
				';
			}

			echo '
				</div>
				';

			echo '
				<div class="feedback_add_user_container">
					<input type="text" name="feedback_new_user_email" id="feedback_new_user_email">
					<button type="submit" class="form_button feedback_new_user_email_button"><i class="pe-7s-plus"></i> <span>E-Mail Adresse hinzufügen</span></button>
					<div class="feedback_new_user_email_button_confirmation"><i class="pe-7s-check"></i> e-mail address succesfully added</div>
				</div>
			';

		}



		if(isset($_POST["feedback_new_user_email"])){
			$new_user_email = $_POST["feedback_new_user_email"];
			$add_new_user_email = addNotificationUser($new_user_email,$_SESSION['userID']);

			$feedback_users = getNotificationUsers();
			echo '
					<div class="main_table_columns_titles">
						<div class="main_table_column_title feedback_users_col_1"><span>Date added</span></div>
						<div class="main_table_column_title feedback_users_col_2"><span>E-mail</span></div>
						<div class="main_table_column_title feedback_users_col_3"><span>Added by</span></div>
						<div class="main_table_column_title feedback_users_col_4"><span>Delete</span></div>
					</div>
				';
			
			if($feedback_users != 'empty'){
				foreach($feedback_users as $feedback_user){
					echo '
					<div class="main_table_row repository_row">
						<div class="main_table_column feedback_users_col_1" data-id="'.$feedback_user["id"].'"><span>'.$feedback_user["created"].'</span></div>
						<div class="main_table_column feedback_users_col_2" data-id="'.$feedback_user["id"].'"><span>'.$feedback_user["email"].'</span></div>
						<div class="main_table_column feedback_users_col_3" data-id="'.$feedback_user["id"].'"><span>'.$feedback_user["first_name"].' '.$feedback_user["last_name"].'</span></div>
						<div class="main_table_column feedback_users_col_4 remove_feedback_user" data-id="'.$feedback_user["id"].'"><span><i class="pe-7s-trash"></i> <p>Delete</p></span></div>
					</div>
					';
				}
			}
			else{
				echo '
				<div class="main_table_row repository_row" style="padding:5px 15px; font-size: 15px; font-weight: 400;">
					No users at the moment
				</div>
				';
			}
			
		}



		if(isset($_POST["feedback_user_id_to_remove"])){
			$feedback_user_id_to_remove = $_POST["feedback_user_id_to_remove"];
			$user_id_to_remove = removeNotificationUser($feedback_user_id_to_remove);

			$feedback_users = getNotificationUsers();
			echo '
					<div class="main_table_columns_titles">
						<div class="main_table_column_title feedback_users_col_1"><span>Date added</span></div>
						<div class="main_table_column_title feedback_users_col_2"><span>E-mail</span></div>
						<div class="main_table_column_title feedback_users_col_3"><span>Added by</span></div>
						<div class="main_table_column_title feedback_users_col_4"><span>Delete</span></div>
					</div>
				';
			
			if($feedback_users != 'empty'){
				foreach($feedback_users as $feedback_user){
					echo '
					<div class="main_table_row repository_row">
						<div class="main_table_column feedback_users_col_1" data-id="'.$feedback_user["id"].'"><span>'.$feedback_user["created"].'</span></div>
						<div class="main_table_column feedback_users_col_2" data-id="'.$feedback_user["id"].'"><span>'.$feedback_user["email"].'</span></div>
						<div class="main_table_column feedback_users_col_3" data-id="'.$feedback_user["id"].'"><span>'.$feedback_user["first_name"].' '.$feedback_user["last_name"].'</span></div>
						<div class="main_table_column feedback_users_col_4 remove_feedback_user" data-id="'.$feedback_user["id"].'"><span><i class="pe-7s-trash"></i> <p>Delete</p></span></div>
					</div>
					';
				}
			}
			else{
				echo '
				<div class="main_table_row repository_row" style="padding:5px 15px; font-size: 15px; font-weight: 400;">
					No users at the moment
				</div>
				';
			}
		}

	}
?>