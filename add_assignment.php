<?php
	include(dirname(__FILE__).'/loader.php');
	check_session();
	
	$assignmentName = array_key_exists("assignmentName", $_POST) ? $_POST["assignmentName"] : '';
	$max_grade = array_key_exists("max_grade", $_POST) ? $_POST["max_grade"] : '';
	
	if(!$assignmentName) echo "No assignment created";
	else {
		//check for class existence 
		//src: http://stackoverflow.com/questions/14439009/php-check-existance-of-record-in-the-database-before-insertion
		$query = $mysqli->query("SELECT id FROM assignments WHERE name='".$assignmentName."' AND user='".$user."'");
		
		if($query->num_rows != 0) echo "Assignment already exists";
		else {
			if (!($stmt = $mysqli->prepare("INSERT INTO assignments(name, max_grade, user) values(?,?,?)"))) {
				echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
			}
			
			if (!($stmt->bind_param("sis", $assignmentName, $max_grade, $user))) {
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
			}

			if (!$stmt->execute()){ echo "Execute failed: "  . $stmt->errno . " " . $stmt->error; } 
			
			else { //success
				echo "Assignment ". $assignmentName ." added";
				$assignment_id = $mysqli->insert_id;
				
				//now add the student_assignment keys
				//first get the student ids
				$student_id_array = array();
				if (!($stmt = $mysqli->prepare("SELECT id FROM students WHERE user=?"))) {
					echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
				}
				
				if (!($stmt->bind_param("s", $user))) {
					echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
				}

				if (!$stmt->execute()){ echo "Execute failed: "  . $stmt->errno . " " . $stmt->error; } 
				
				if ( !$stmt->bind_result($student_id) ) {
						echo "Binding result failed: (" . $mysqli->errno . ")" . $mysqli->error;
				}
				else { //success
					$stmt->store_result();
					while($stmt->fetch()){
						$student_id_array[] = $student_id;
					}
					$stmt->free_result();
				}
				
				//now add them to the student_assignment table
				foreach($student_id_array as $student_id) 
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
