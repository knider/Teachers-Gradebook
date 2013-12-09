<?php
	include(dirname(__FILE__).'/loader.php');
	check_session();

	$firstName = array_key_exists("firstName", $_POST) ? $_POST["firstName"] : '';
	$lastName = array_key_exists("lastName", $_POST) ? $_POST["lastName"] : '';
	$className = array_key_exists("className", $_POST) ? $_POST["className"] : '';
	
	if(!($className || $lastName || $firstName)) { //if all the fields are empty
		echo "You must include a class, first name and last name.";
	} else { //carry on
		//check for existence 
		//src: http://stackoverflow.com/questions/14439009/php-check-existance-of-record-in-the-database-before-insertion
		
		$params = array(':first_name' => $firstName, ':lastName' => $lastName, ':className' => $className, ':user' => $user);
		$query = $pdo->prepare("SELECT COUNT(*) FROM students WHERE first_name=:first_name AND last_name=:lastName AND class=:className AND user=:user");
		if (!($query->execute($params))) { echo "Query failed"; exit(); }
		if ($query->fetchColumn() != 0) echo "Student already exists";
		else { //student doesn't exists, do insert
			
			$stmt = $pdo->prepare("INSERT INTO students(first_name, last_name, class, user) VALUES(:first_name,:lastName,:className,:user)");
			if (!($stmt->execute($params))) { echo "Execute statement 1 failed"; exit(); }
			$student_id = $pdo->lastInsertId();
			echo "Student ". $firstName ." ". $lastName ." added";
			
			//now add the student_assignment keys
			//first get the assignment ids
			$assignment_id_array = array();
			$params = array(':user' => $user);
			$stmt = $pdo->prepare("SELECT id FROM assignments WHERE user=:user ORDER BY id");
			if (!($stmt->execute($params))) { echo "Execute statement 2 failed"; exit(); }
			while($row = $stmt->fetchColumn()) {
				$assignment_id_array[] = $row;
			}
			//else $assignment_id_array[] = $row;
			
			//now add them to the student_assignment table
			foreach($assignment_id_array as $assignment_id) 
			{
				$params = array(':student_id' => $student_id, 'assignment_id' => $assignment_id);
				$stmt = $pdo->prepare("INSERT INTO students_assignments(student_id, assignment_id) VALUES(:student_id,:assignment_id)");
				if (!($stmt->execute($params))) { echo "Execute statement 3 failed"; exit();}
				
			}
		}
	}
?>
