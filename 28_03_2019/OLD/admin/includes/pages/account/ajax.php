<?php session_start();

	include_once $_SERVER["DOCUMENT_ROOT"].'admin/classes/general.php';

	if (isset($_SESSION['admin_logged_in'])) {
	

		if(isset($_POST["get_relations"])){
			if($_POST["get_relations"] == 'add'){
				if(isset($_POST["current_id"])){
					$repositories = getRepositories(1,'add',$_POST["current_id"]);
				}
				else{
					$repositories = getRepositories(1,'add');
				}
				echo implode("||",$repositories);
			}
		}


		if(isset($_POST["save_login_with_google_email_address"])){
			$email_address = $_POST["email_address"];
			$email_address_old = $_POST["email_address_old"];
			$user_id = $_POST["user_id"];
			$google_login_status = $_POST["google_login_status"];

			$update_user_email = updateUserEmail($user_id,$email_address,$email_address_old,$google_login_status);
		}


		if(isset($_POST["save_login_regular"])){
			$firstname = $_POST["firstname"];
			$lastname = $_POST["lastname"];
			$password = $_POST["password"];
			$user_id = $_POST["user_id"];
			$regular_login_type_status = $_POST["regular_login_type_status"];

			$update_user_email = updateUserDetails($user_id,$firstname,$lastname,$password,$regular_login_type_status);
		}


		if(isset($_POST["approve_user"])){
			$user_id = $_POST["user_id"];

			$approve_user = approveUser($user_id);
		}


		if(isset($_POST["block_user"])){
			$user_id = $_POST["user_id"];

			$block_user = blockUser($user_id);
		}
		
	}
?>