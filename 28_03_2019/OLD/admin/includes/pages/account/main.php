<?php session_start();
	if (isset($_SESSION['admin_logged_in'])) {
		//logged

		include_once 'classes/general.php';

		?>
		<div class="main_table_container">
			<div class="main_table_columns_titles">
				<div class="main_table_column_title account_col_1"><span><input type="checkbox" class="users_approve_checkbox" name="users_approve_all_checkboxes"></span></div>
				<div class="main_table_column_title account_col_2"><span>Name</span></div>
				<div class="main_table_column_title account_col_3"><span>Surname</span></div>
				<div class="main_table_column_title account_col_5"><span>E-Mail adress</span></div>
				<div class="main_table_column_title account_col_6"><span>Status</span></div>
			</div>
		
			<?php
				$id_lang = 1;
				$users = getUsers();
				if(count($users) > 0){
					foreach($users as $user){

						$user_status = '';
						if($user["status"] == 'approved'){
							$user_status = '<span class="user_approval user_approved_color" data-id="'.$user["id"].'"><i class="pe-7s-check"></i>approved</span>';
						}
						if($user["status"] == 'blocked'){
							$user_status = '<span class="user_approval user_blocked_color" data-id="'.$user["id"].'"><i class="pe-7s-close-circle"></i>blocked</span>';
						}
						if($user["status"] == 'open_request'){
							$user_status = '<span class="user_approval user_request_color" data-id="'.$user["id"].'"><i class="pe-7s-attention"></i>open request</span>';
						}

						echo '<div class="main_table_row repository_row">';
						echo '<div class="main_table_column account_col_1" data-id="'.$user["id"].'"><span><input type="checkbox" class="users_approve_checkbox" name="users_approve_checkbox" value="'.$user["id"].'"></span></div>';
						echo '<div class="main_table_column account_edit_user account_col_2" data-id="'.$user["id"].'"><span>'.$user["first_name"].'</span></div>';
						echo '<div class="main_table_column account_edit_user account_col_3" data-id="'.$user["id"].'"><span>'.$user["last_name"].'</span></div>';
						echo '<div class="main_table_column account_edit_user account_col_5" data-id="'.$user["id"].'"><span>'.$user["email"].'</span></div>';
						echo '<div class="main_table_column account_col_6" data-id="'.$user["id"].'">
										'.$user_status.'
										<div class="user_approval_selection" id="user_approval_'.$user["id"].'">
											<div class="user_approval_approved" data-id="'.$user["id"].'"><i class="pe-7s-check"></i>approved</div>
											<div class="user_approval_blocked" data-id="'.$user["id"].'"><i class="pe-7s-close-circle"></i>blocked</div>
										</div>
									</div>';
						echo '</div>';
					}
				}
			?>
		</div>

		<?php
	}
?>