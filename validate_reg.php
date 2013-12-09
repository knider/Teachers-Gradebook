<?php
	session_start();
	include(dirname(__FILE__).'/loader.php');

	$username = array_key_exists("username", $_POST) ? $_POST["username"] : '';
	$pass = array_key_exists("password", $_POST) ? $_POST["password"] : '';
	
	if($username && $pass) {
		//check login, see if name already in use
		$params = array(':user' => $username);
		$query = $pdo->prepare("SELECT COUNT(*) FROM user WHERE username=:user");
		if (!($query->execute($params))) { echo "Query failed"; exit(); }
		if ($query->fetchColumn() != 0) { echo "Username already exists"; exit(); }
		
		$params = array(':user' => $username, ':pass' => $pass);
		$stmt = $pdo->prepare("INSERT INTO user (username,password) VALUES (:user,:pass)");
		if (!($stmt->execute($params))) { echo "Execute failed"; exit(); }
					
		echo "Username " . $username . " created. Please <a href=\"login.php\">login</a>.";
	
	} else { //username or password missing
		echo "You must enter a username and password";
	}
?>