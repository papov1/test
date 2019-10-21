<?php
class User {

	private $dbHost          = "localhost";
	private $dbUsername      = "d02c5f13";
	private $dbPassword      = "MSz3knAftkKTcdQw";
	private $dbName          = "d02c5f13";
	private $adminTbl        = 'admin';
	private $adminAllowedTbl = 'admin_allowed';
	
	function __construct(){
		if(!isset($this->db)){
			$conn = new mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);
			if($conn->connect_error){
				die("Failed to connect with MySQL: " . $conn->connect_error);
			}
			else{
				$this->db = $conn;
				$this->db->set_charset("utf8");
			}
		}
	}



	function checkIfUserAllowed($userEmail){
		$checkQuery = "SELECT id FROM ".$this->adminAllowedTbl." WHERE email = '".$userEmail."' AND google_login_allowed = 'allowed'";
		$checkResult = $this->db->query($checkQuery);
		if($checkResult->num_rows > 0){
			return 'allowed';
		}
		else{
			return 'not allowed';
		}
	}
	
	function checkUser($userData = array()){
		if(!empty($userData)){
			//Check whether user data already exists in database
			$prevQuery = "SELECT * FROM ".$this->adminTbl." WHERE oauth_provider = '".$userData['oauth_provider']."' AND oauth_uid = '".$userData['oauth_uid']."'";
			$prevResult = $this->db->query($prevQuery);
			if($prevResult->num_rows > 0){
				//Update user data if already exists
				$query = "UPDATE ".$this->adminTbl." SET gender = '".$userData['gender']."', locale = '".$userData['locale']."', picture = '".$userData['picture']."', link = '".$userData['link']."', modified = '".date("Y-m-d H:i:s")."' WHERE oauth_provider = '".$userData['oauth_provider']."' AND oauth_uid = '".$userData['oauth_uid']."'";
				$update = $this->db->query($query);
			}
			else{
				//Insert user data
				
				$query = "
					INSERT INTO 
						admin (
							oauth_provider,
							oauth_uid,
							first_name,
							last_name,
							email,
							created,
							modified,
							status
						) 
					VALUES (
						'".$userData['oauth_provider']."',
						'".$userData['oauth_uid']."',
						'".$userData['first_name']."',
						'".$userData['last_name']."',
						'".$userData['email']."',
						'".date("Y-m-d H:i:s")."',
						'".date("Y-m-d H:i:s")."',
						'approved'
					)
				";

				$insert = $this->db->query($query);
				
			}
						
			//Get user data from the database
			$result = $this->db->query($prevQuery);
			$userData = $result->fetch_assoc();
		}
				
		//Return user data
		return $userData;
	}


	function addAdmins(){
		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (2, 'juergen.abber@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (3, 'michael.albrecht@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (4, 'dietmar.altendorfer@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (5, 'wilfried.banko@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (6, 'michael.schuh@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (7, 'karl.chalupka@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (8, 'daniel.christ-aut@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (9, 'romana.ede@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (10, 'thomas.fartek@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (11, 'daniel.fehn@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (12, 'christa.fimberger@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (13, 'alexanderrene.fountedakis@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (14, 'helga.gmeindl@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (15, 'claudia.hafner@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (16, 'joerg.lamprecht@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (17, 'herbert.humitsch@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (18, 'karlheinz.joksch@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (19, 'robert.kickinger@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (20, 'franjo.pesa@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (21, 'silvia.moder@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (22, 'christian.peterseil@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (23, 'emanuel.petz@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (24, 'wolfgang.plattner@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (25, 'thomas.plhak@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (26, 'richard.prantl@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (27, 'gabriele.pribyl@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (28, 'werner.reuberger@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (29, 'joerg.rohrauer@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (30, 'walter.santer@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (31, 'martin.schaffer@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (32, 'oliver.schlegel@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (33, 'peter.schleifer@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (34, 'michaela.schwarzbauer@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (35, 'heimo.spoerk@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (36, 'armin.stangl@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (37, 'dorothea.steindl@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (38, 'karin.steinkellner@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (39, 'eveline.stolz@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (40, 'wolfgang.stuchly@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (41, 'doris.tumpold@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (42, 'robert.wagner@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (43, 'albert.weber@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (44, 'manuel.wejwoda@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (45, 'andreas.wippel@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (46, 'johannes.zwanzger@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (47, 'erwin.crispel@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (48, 'markus.schloegl@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (49, 'christian.schwabach@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (50, 'julia.rapp@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (51, 'christoph.hubmann@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (52, 'ziva.bricman@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (53, 'markus.stelzer@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (54, 'christoph.hackl@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (55, 'milos.djordjevic@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (56, 'christian.groetzl@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (57, 'kai-michael.sigl@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (58, 'mario.bartl@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (59, 'markus.fuchs@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (60, 'richard.platzer@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (61, 'matthias.gerstl@airliquide.com')";
		$insert = $this->db->query($query);

		$query = "INSERT INTO `admin_allowed` (`id`, `email`) VALUES (62, 'support@polcoder.com')";
		$insert = $this->db->query($query);
	}
}
?>
