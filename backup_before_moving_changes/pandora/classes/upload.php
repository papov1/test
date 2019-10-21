<?php
if(!empty($_FILES)){
	// Include the database configuration file
	//require 'dbConfig.php';
	
	// File path configuration
	$targetDir = "../../documents/";
	$fileName = $_FILES['file']['name'];
	$targetFilePath = $targetDir.$fileName;
	
	// Upload file to server
	if(move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)){
		// Insert file information in the database
		//$insert = $db->query("INSERT INTO files (file_name, uploaded_on) VALUES ('".$fileName."', NOW())");
	}
}

if(isset($_POST["file_remove"])){
	if(unlink('../../documents/'.$_POST["file_remove"])){
	    echo "File deleted: ".$_POST["file_remove"];
	}
	else{
		echo "File NOT deleted: ".$_POST["file_remove"];
	}
}
?>