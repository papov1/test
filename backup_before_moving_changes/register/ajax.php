<?php session_start();

	if(isset($_POST["user_email"])){
		$user_name = $_POST["user_name"];
		$user_lastname = $_POST["user_lastname"];
		$user_email = $_POST["user_email"];

		include($_SERVER["DOCUMENT_ROOT"].'/config/db.php');

		$sql = "
			SELECT
				*
			FROM
				admin_requests
			WHERE
				email = '".mysqli_real_escape_string($con,$user_email)."'
		";
		$result = mysqli_query($con,$sql);
		
		if(mysqli_num_rows($result) > 0){
			echo 'user_already_in_the_queue';
		}
		else{

			$sql_2 = "
				SELECT
					*
				FROM
					admin_allowed
				WHERE
					email = '".mysqli_real_escape_string($con,$user_email)."'
			";
			$result_2 = mysqli_query($con,$sql_2);
			if(mysqli_num_rows($result_2) > 0){
				echo 'user_already_registered';
			}
			else{
				$sql_add = "
					INSERT INTO 
						admin_requests (
							email,
							first_name,
							last_name,
							status
						) 
					VALUES (
						'".mysqli_real_escape_string($con,$user_email)."',
						'".mysqli_real_escape_string($con,$user_name)."',
						'".mysqli_real_escape_string($con,$user_lastname)."',
						'open_request'
					)";
				$result_add = mysqli_query($con,$sql_add);
				echo 'user_added';
			}

		}
	}