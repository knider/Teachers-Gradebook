<?php
	include(dirname(__FILE__).'/loader.php');
	check_session();
?>
<script src="scripts/functions.js"></script>
<?php
/* 1) run this query - done
SELECT s.id, s.last_name, s.first_name FROM students s
WHERE s.user='knider' AND s.class='6' ORDER BY s.last_name, s.first_name
2) put IDs, names in assoc array
3) for each ID in the array, run this query
SELECT a.name, sa.grade, a.max_grade FROM assignments a
INNER JOIN students_assignments sa ON sa.assignment_id = a.id 
INNER JOIN students s ON s.id = sa.student_id 
WHERE a.user='knider' AND s.class='6' AND s.id='6' ORDER BY a.id
*/
	$className = array_key_exists("className", $_POST) ? $_POST["className"] : '';

	if($className) {
		if (!($stmt = $mysqli->prepare("SELECT id, last_name, first_name FROM students WHERE class=? AND user=? ORDER BY last_name, first_name"))) {
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		
		if (!($stmt->bind_param("ss", $className, $user))) {
			echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
		}

		if (!$stmt->execute()){ echo "Execute failed: "  . $stmt->errno . " " . $stmt->error; } 
		
		if ( !$stmt->bind_result($student_id, $student_first, $student_last) ) {
				echo "Binding result failed: (" . $mysqli->errno . ")" . $mysqli->error;
		}
		else { //success
			$stmt->store_result();
			
			$student_array = array();
			$student_id_array = array();
			$i = 1;
			$string = "<script>$(document).ready(function(){ updateTotal(); });</script>";
			$string .= "<table id='list' style='border: solid 1px black; border-collapse: collapse;'>";
			$string .= "<tr><td><strong>ID</strong></td><td><strong>Last Name</strong></td><td><strong>First Name</strong></td>";
			while($stmt->fetch()){
				$student_array[$i] = array("id"=>$student_id, "first"=>$student_first, "last"=>$student_last);
				$student_id_array[$i] = $student_id;
				$i++;
			}
			
			
			
			if (!($stmt = $mysqli->prepare("SELECT name FROM assignments a WHERE a.user=? ORDER BY id"))) {
				echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
			}
			
			if (!($stmt->bind_param("s", $user))) {
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
			}

			if (!$stmt->execute()){ echo "Execute failed: "  . $stmt->errno . " " . $stmt->error; } 
			
			if ( !$stmt->bind_result($ass_name) ) {
					echo "Binding result failed: (" . $mysqli->errno . ")" . $mysqli->error;
			}
			else { //success
				$stmt->store_result();
				while($stmt->fetch()){
					$string .= "<td colspan=2><strong>".$ass_name."</strong></td>";
				}
				$string .= "<td><strong>Total Grade</strong></td>";
				$string .= "</tr>";
			}
			
			foreach($student_array as $student){
				$string .= "<tr class='grade_line'>";
				$string .= "<td>".$student["id"]."</td>";
				$string .= "<td>".$student["first"]."</td>";
				$string .= "<td>".$student["last"]."</td>";
				//echo $student["id"]; 
				if (!($stmt = $mysqli->prepare("SELECT sa.id, sa.grade, a.max_grade FROM assignments a
												INNER JOIN students_assignments sa ON sa.assignment_id = a.id 
												INNER JOIN students s ON s.id = sa.student_id 
												WHERE s.class=? AND s.id=? AND a.user=? ORDER BY a.id"))) {
					echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
				}
				
				if (!($stmt->bind_param("sis", $className, $student["id"], $user))) {
					echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
				}

				if (!$stmt->execute()){ echo "Execute failed: "  . $stmt->errno . " " . $stmt->error; } 
				
				if ( !$stmt->bind_result($sa_id, $ass_grade, $ass_max_grade) ) {
						echo "Binding result failed: (" . $mysqli->errno . ")" . $mysqli->error;
				}
				else { //success
					$stmt->store_result();
					while($stmt->fetch()){
						$string .= "<td class='grade_num'><input class='grades_num' type='number' name='". $sa_id ."' value='". $ass_grade ."' /></td><td class='max_grade_num'>" . $ass_max_grade ."</td>\n";
					}
					$string .= "<td><input class='grades_total' type='number' id='total'></td>";
					$string .= "</tr>";
				}
			}
			
			$string .= "</table>\n";
			echo $string;
		}
	}
?>
