<?php
	include(dirname(__FILE__).'/loader.php');
	check_session();

	$className = array_key_exists("className", $_POST) ? $_POST["className"] : '';
	
	//check if className var exists
	if(!$className) echo "No class name provided"; 
	else {
		//check for class existence 
		//src: http://stackoverflow.com/questions/14439009/php-check-existance-of-record-in-the-database-before-insertion
		$query = $mysqli->query("SELECT id FROM classes WHERE name='".$className."' AND user='".$user."'");
		
		if($query->num_rows != 0) echo "Class already exists";
		else {
			if (!($stmt = $mysqli->prepare("INSERT INTO classes(name,user) values(?,?)"))) {
				echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
			}
			
			if (!($stmt->bind_param("ss", $className, $user))) {
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
			}

			if (!$stmt->execute()){ echo "Execute failed: "  . $stmt->errno . " " . $stmt->error; } 
			
			//success
			else { 
				echo "Class ". $className ." added";
			}
		}
	}
?>
