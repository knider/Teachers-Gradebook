<?php
	include(dirname(__FILE__).'/loader.php');
	check_session();
	
	$name = array_key_exists("assignmentName", $_POST) ? $_POST["assignmentName"] : '';
	$max_grade = array_key_exists("max_grade", $_POST) ? $_POST["max_grade"] : '';
	
	if(!$name && $max_grade) echo "Need to enter assignment name and max grade";
	else {
		//check for class existence 
		//src: http://stackoverflow.com/questions/14439009/php-check-existance-of-record-in-the-database-before-insertion
		$params = array(':name' => $name, ':user' => $user);
		$query = $pdo->prepare("SELECT COUNT(*) FROM assignments WHERE name=:name AND user=:user");
		if (!($query->execute($params))) { echo "Query failed"; exit(); }
		if ($query->fetchColumn() != 0) { echo "Assignment already exists"; exit(); }
		
		$params = array(':name' => $name, ':max_grade' => $max_grade, ':user' => $user);
		$stmt = $pdo->prepare("INSERT INTO assignments(name, max_grade, user) values(:name,:max_grade,:user)");
		if (!$stmt->execute($params)){ echo "Execute failed"; exit(); } 
		
		echo "Assignment ". $name ." added";
		$assignment_id = $pdo->lastInsertId();
			
		//now add the student_assignment keys
		//first get the student ids
		$student_id_array = array();
		$params = array(':user' => $user);
		$stmt = $pdo->prepare("SELECT id FROM students WHERE user=:user");
		if (!$stmt->execute($params)){ echo "Execute failed"; exit(); }
		while($student_id = $stmt->fetchColumn()){
			$student_id_array[] = $student_id;
		}
			
		//now add them to the student_assignment table for each
		foreach($student_id_array as $student_id) 
		{
			$params = array(':student_id' => $student_id, ':assignment_id' => $assignment_id);
			$stmt = $pdo->prepare("INSERT INTO students_assignments(student_id, assignment_id) VALUES(:student_id,:assignment_id)");
			if (!$stmt->execute($params)){ echo "Execute failed"; exit(); }
		
		}
	}
?>
