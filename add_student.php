<?php
	include(dirname(__FILE__).'/loader.php');
	check_session();

	$firstName = array_key_exists("firstName", $_POST) ? $_POST["firstName"] : '';
	$lastName = array_key_exists("lastName", $_POST) ? $_POST["lastName"] : '';
	$className = array_key_exists("className", $_POST) ? $_POST["className"] : '';
	
	if(!($className || $lastName || $firstName)) { //if all the fields are empty
		echo "No student created";
	} else { //carry on
		//check for existence 
		//src: http://stackoverflow.com/questions/14439009/php-check-existance-of-record-in-the-database-before-insertion
		$query = $mysqli->query("SELECT id FROM students WHERE first_name='".$firstName."' AND last_name='".$lastName."' AND class='".$className."' AND user='".$user."'");
		
		if($query->num_rows != 0) echo "Student already exists";
		
		else { //student doesn't exists, do insert
		
			if (!($stmt = $mysqli->prepare("INSERT INTO students(first_name, last_name, class, user) values(?,?,?,?)"))) {
				echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
			}
			
			if (!($stmt->bind_param("ssis", $firstName, $lastName, $className, $user))) {
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
			}

			if (!$stmt->execute()){ echo "Execute failed: "  . $stmt->errno . " " . $stmt->error; } 
			
			else { //success
				$student_id = $mysqli->insert_id;
				echo "Student ". $firstName ." ". $lastName ." added";
				
				//now add the student_assignment keys
				//first get the assignment ids
				$assignment_id_array = array();
				if (!($stmt = $mysqli->prepare("SELECT id FROM assignments WHERE user=? ORDER BY id"))) {
					echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
				}
				
				if (!($stmt->bind_param("s", $user))) {
					echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
				}

				if (!$stmt->execute()){ echo "Execute failed: "  . $stmt->errno . " " . $stmt->error; } 
				
				if ( !$stmt->bind_result($ass_id) ) {
						echo "Binding result failed: (" . $mysqli->errno . ")" . $mysqli->error;
				}
				else { //success
					$stmt->store_result();
					while($stmt->fetch()){
						$assignment_id_array[] = $ass_id;
					}
					$stmt->free_result();
				}
				
				//now add them to the student_assignment table
				foreach($assignment_id_array as $assignment_id) 
				{
					if (!($stmt = $mysqli->prepare("INSERT INTO students_assignments(student_id, assignment_id) VALUES(?,?)"))) {
					echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
				
					if (!($stmt->bind_param("ii", $student_id, $assignment_id))) {
						echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
					}

					if (!$stmt->execute()){ echo "Execute failed: "  . $stmt->errno . " " . $stmt->error; } 
				
				}
			}
		}
	}
?>
