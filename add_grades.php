<?php
	include(dirname(__FILE__).'/loader.php');
	check_session();
	
	foreach($_POST as $key=>$value) {
		$params = array(':id' => $key, ':grade' => $value);
		$stmt = $pdo->prepare("UPDATE students_assignments SET grade=:grade WHERE id=:id");
		if (!$stmt->execute($params)){ echo "Execute failed: "  . $stmt->errno . " " . $stmt->error; } 
		
		else { //success
			echo "Grades updated";
		}
	}
	
?>
