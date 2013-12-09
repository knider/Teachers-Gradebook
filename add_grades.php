<?php
	include(dirname(__FILE__).'/loader.php');
	check_session();
	
	//couldn't think of a way to make it only do the changed entries instead of all of them.
	foreach($_POST as $key=>$value) {
		$id = $key;
		$grade = $value;
		
		if (!($stmt = $mysqli->prepare("UPDATE students_assignments SET grade=? WHERE id=?"))) {
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		
		if (!($stmt->bind_param("ii", $grade, $id))) {
			echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
		}

		if (!$stmt->execute()){ echo "Execute failed: "  . $stmt->errno . " " . $stmt->error; } 
		
		else { //success
			echo "Grades updated";
		}
	}
	
?>
