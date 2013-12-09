<?php
	include(dirname(__FILE__).'/loader.php');
	check_session();

	$className = array_key_exists("className", $_POST) ? $_POST["className"] : '';
	
	//check if className var exists
	if(!$className) echo "No class name provided"; 
	else {
		//check for class existence 
		//src: http://stackoverflow.com/questions/14439009/php-check-existance-of-record-in-the-database-before-insertion
		$params = array(':className' => $className, ':user' => $user);
		$query = $pdo->prepare("SELECT COUNT(*) FROM classes WHERE name=:className AND user=:user");
		if (!($query->execute($params))) { echo "Query failed"; exit(); }
		if ($query->fetchColumn() != 0) { echo "Class already exists"; exit(); }
		
		$stmt = $pdo->prepare("INSERT INTO classes(name,user) values(:className, :user)");
		if (!($stmt->execute($params))) { echo "Execture failed"; exit(); }
		echo "Class ". $className ." added";
	}
?>
