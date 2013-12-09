<?php
	session_start();
	include(dirname(__FILE__).'/loader.php');

	/*
	Performing db lookup on email and password.
	If finds a valid row, then session is set.
	*/
	$username = array_key_exists("username", $_POST) ? mysqli_real_escape_string($mysqli,$_POST["username"]) : '';
	$pass = array_key_exists("password", $_POST) ? mysqli_real_escape_string($mysqli,$_POST["password"]) : '';

	if($username)
	{
		if(!($stmt = $mysqli->prepare("SELECT username,password FROM user WHERE username=? AND password=?"))){
			echo "Prepare failed: (" . $mysqli->errno . ")" . $mysqli->error;
		}
		if(!($stmt->bind_param('ss', $username, $pass))){ echo "Bind failed: (" . $mysqli->errno . ")" . $mysqli->error; }
		
		if(!($stmt->execute())){ echo "Execute failed: (" . $mysqli->errno . ")" . $mysqli->error; }
		
		else { //no errors
			$stmt->store_result();
			if ($stmt->num_rows === 1) //if user and pass are correct, set the session data
			{
				$stmt->free_result();  //Frees memory allocated for buffer
				$stmt->close(); //Deallocates statement handle
				$_SESSION['username'] = $username;
				$_SESSION['string'] = sessionString($username);
				session_write_close(); 
				echo "0";
				//header('location:index.php');	
				exit();					
			} else { //if password is incorrect
				$stmt->free_result(); //Frees memory allocated for buffer
				$stmt->close(); //Deallocates statement handle
				echo "Invalid Username and password combination, please try again or <a href=\"register.php\">register</a>. ";
			}
		}
	} else { //no username
		echo "Please Log In";
	}

?>