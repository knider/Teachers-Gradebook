<?php
	include(dirname(__FILE__).'/loader.php');
	check_session();
?>
<script src="scripts/functions.js"></script>
<?php
	$className = array_key_exists("className", $_POST) ? $_POST["className"] : '';

	if($className) {
		//first, get the student ids and names
		$params = array('className' => $className, 'user' => $user);
		$stmt = $pdo->prepare("SELECT id, last_name, first_name FROM students WHERE class=:className AND user=:user ORDER BY last_name, first_name");
		if (!$stmt->execute($params)){ echo "Execute failed"; exit(); } 
		
		$stmt->bindColumn('id', $student_id);
		$stmt->bindColumn('first_name', $student_first);
		$stmt->bindColumn('last_name', $student_last);
		$student_array = array();
		$i = 1;
		$string = "<script>$(document).ready(function(){ updateTotal(); });</script>";
		$string .= "<table id='list' style='border: solid 1px black; border-collapse: collapse;'>";
		$string .= "<tr><td><strong>ID</strong></td><td><strong>Last Name</strong></td><td><strong>First Name</strong></td>";
		while($stmt->fetch()){
			$student_array[$i] = array("id"=>$student_id, "first"=>$student_first, "last"=>$student_last);
			$i++;
		}
			
		//now get assignments names
		$params = array('user' => $user);
		$stmt = $pdo->prepare("SELECT name FROM assignments a WHERE a.user=:user ORDER BY id");
		if (!$stmt->execute($params)){ echo "Execute failed"; exit(); } 
		
		while($ass_name = $stmt->fetchColumn()){
			$string .= "<td colspan=2><strong>".$ass_name."</strong></td>";
		}
		$string .= "<td><strong>Total Grade</strong></td>";
		$string .= "</tr>";
		
		//now add the students grades for each assignment
		foreach($student_array as $student){
			$string .= "<tr class='grade_line'>";
			$string .= "<td>".$student["id"]."</td>";
			$string .= "<td>".$student["first"]."</td>";
			$string .= "<td>".$student["last"]."</td>";
			
			//$student_id = $student["id"];
			$params = array(':className' => $className, ':student_id' => $student["id"], ':user' => $user);
			$stmt = $pdo->prepare("SELECT sa.id AS sa_id, sa.grade AS grade, a.max_grade AS max_grade FROM assignments a
							INNER JOIN students_assignments sa ON sa.assignment_id = a.id 
							INNER JOIN students s ON s.id = sa.student_id 
							WHERE s.class=:className AND s.id=:student_id AND a.user=:user ORDER BY a.id");
			if (!$stmt->execute($params)){ echo "Execute failed"; exit(); }
			$stmt->bindColumn('sa_id', $sa_id);
			$stmt->bindColumn('grade', $ass_grade);
			$stmt->bindColumn('max_grade', $ass_max_grade);
		
			while($stmt->fetch()){
				$string .= "<td class='grade_num'><input class='grades_num' type='number' name='". $sa_id ."' value='". $ass_grade ."' /></td><td class='max_grade_num'>" . $ass_max_grade ."</td>\n";
			}
			$string .= "<td><input class='grades_total' type='number' id='total'></td>";
			$string .= "</tr>";
		}
		
		$string .= "</table>\n";
		echo $string;
	}
?>
