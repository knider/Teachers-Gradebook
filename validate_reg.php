<?php
	session_start();
	include(dirname(__FILE__).'/loader.php');

	$username_in = array_key_exists("username", $_POST) ? mysqli_real_escape_string($mysqli,$_POST["username"]) : '';
	$pass = array_key_exists("password", $_POST) ? mysqli_real_escape_string($mysqli,$_POST["password"]) : '';
	
	//phpinfo();
	//check login, see if name already in use
	if(array_key_exists("form", $_POST)) { 
		if (!($stmt = $mysqli->prepare("SELECT username FROM user WHERE username=?"))) {
			echo "Check Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		if (!($stmt->bind_param('s', $username_in))) { echo "Bind failed: "  . $stmt->errno . " " . $stmt->error; }
		
		if (!$stmt->execute()){  echo "Execute1 failed: "  . $stmt->errno . " " . $stmt->error; }
		
		if (!$stmt->bind_result($username_out)) {
			echo "Binding result failed: (" . $mysqli->errno . ")" . $mysqli->error;
		}
		
		else { //no errors 
			$stmt->store_result();
			
			if (!$stmt->num_rows) //go ahead and create the login
			{ 				
				$stmt->close();
				if (!($stmt = $mysqli->prepare("INSERT INTO user (username,password) VALUES (?,?)" ))) {
					echo "Insert Prepare failed: " . $stmt->errno . " " . $stmt->error;
				}
				
				if (!($stmt->bind_param("ss", $username_in, $pass))) { echo "Bind failed: "  . $stmt->errno . " " . $stmt->error; } 

				if (!$stmt->execute()){ echo "Execute2 failed: "  . $stmt->errno . " " . $stmt->error; } 
				
				else { //no errors
					echo "Username " . $username_in . " created. Please <a href=\"index.php\">login</a>.";
					//$error_string .= "Username created.";
				}
			} else { //if there is a login already
				echo "Username " . $username_in .  " already exists, please try a different username.";
			
			}
		}
	}
?>