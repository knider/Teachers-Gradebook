<?php
	session_start();
	include(dirname(__FILE__).'/loader.php');

	/*
	Performing db lookup on email and password.
	If finds a valid row, then session is set.
	*/
	$username = array_key_exists("username", $_POST) ? $_POST["username"] : '';
	$pass = array_key_exists("password", $_POST) ? $_POST["password"] : '';

	if($username && $pass)
	{
		$params = array(':user' => $username, ':pass' => $pass);
		$stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE username=:user AND password=:pass");
		if (!($stmt->execute($params))) { echo "Execute failed"; exit(); }
		if ($stmt->fetchColumn() == 1)  //if user and pass are correct, set the session data
		{
			$_SESSION['username'] = $username;
			$_SESSION['string'] = sessionString($username);
			session_write_close(); 
			echo "0";
			//header('location:index.php');	
			exit();					
		} else { //if password is incorrect
			echo "Invalid Username and password combination, please try again or <a href=\"register.php\">register</a>. ";
		}
	} else { //no username
		echo "Please enter a username and password.";
	}
?>