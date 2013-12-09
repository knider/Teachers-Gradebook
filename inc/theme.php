<?php	
//connect to DB
try {
	global $pdo;
	$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname='. DB_NAME, DB_USER, DB_PASS);
} catch (PDOException $e) 
{
	echo 'Error connecting to database';
	exit();
}

function check_session() {
	session_start();
	global $user;
	if(isset($_SESSION['username'])){
		$user = $_SESSION['username'];
		if($_SESSION['string'] == sessionString($user)) {
			//success
		} else { //not a match
			header('Location: login.php');
		}
	} else { //no username session
		header('Location: login.php');
	}
}

//simple session protector, not very secure
function sessionString($email){
	$string = ($email * 5 + 30) ^ 4;
	return $string;
}

function get_header(){
	global $title;
	echo '<!DOCTYPE html>
	<html lang="en">
	<head>
		<title>';
	if (!$title) echo SiteName;
	else echo $title. ' | ' .SiteName;
		
	echo '</title>	
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="theme/style.css" />
		<link rel="stylesheet" href="theme/jquery.mobile-1.3.2.css" />
		<link rel="stylesheet" href="theme/jquery.mobile.structure-1.3.2.css" />
		<link rel="stylesheet" href="theme/jquery.mobile.theme-1.3.2.css" />
		

		<script src="scripts/jquery-1.10.2.min.js"></script>
		<script src="scripts/jquery.mobile-1.3.2.min.js"></script>
		<script src="scripts/jquery.validate.min.js"></script>
		<script src="scripts/functions.js"></script>
	</head>
	';
}



function get_page_header(){
	global $title;
	global $user;
	$page_header = (!$title) ? SiteName : $title.' | '.SiteName;
	echo ($user) ? '<div data-role="header"><a href="logout.php" data-ajax="false" data-role="button" data-mini="true" style="right: 5px; left: auto;">Logout</a><h1>'.$page_header.'</h1></div>' : '<div data-role="header"><h1>'.$page_header.'</h1></div>';

}
	
	/**
	 * Output nav buttons
	 */
	function get_menu(){
		echo '<div id="mainbuttons" class="ui-grid-a">
			<div class="ui-block-a">
				<a href="/" data-icon="back" data-ajax="false" data-role="button" data-rel="back" data-mini="true">Back</a>
			</div>
			<div class="ui-block-b"><a href="'.Home.'" data-ajax="false" data-role="button" data-mini="true">Home</a>
			</div>
			
		</div>';
	}
	
	function get_class_menu(){
		echo '<div id="mainbuttons" class="ui-grid-b">
			<div class="ui-block-a">
				<a href="/" data-icon="back" data-ajax="false" data-role="button" data-rel="back" data-mini="true">Back</a>
			</div>
			<div class="ui-block-b"><a href="'.Home.'" data-ajax="false" data-role="button" data-mini="true">Home</a>
			</div>
			<div class="ui-block-c"><a href="student.php" data-ajax="false" data-role="button" data-mini="true">+ Student</a>
			</div>
			
		</div>';
	}
	
	function get_home_menu(){
		echo '<div id="mainbuttons" class="ui-grid-a">
			<div class="ui-block-a">
					<a href="class.php" data-role="button" data-mini="true">+ Class</a>
			</div>
			<div class="ui-block-b">
					<a href="student.php" data-role="button" data-mini="true">+ Student</a>
			</div>
			
		</div>                
		<div id="secondary_buttons" class="ui-grid-a">
			<div class="ui-block-a">
					<a href="assignment.php" data-role="button" data-mini="true">+ Assignment</a>
					<p id="here1">
					</p>
			</div>
			<div class="ui-block-b">
					<a href="grades.php" data-role="button" data-ajax="false" data-mini="true">+ / Show Grades</a>
			</div>
			
		</div>';
	}
	//Get classes
	function get_classes() {
		global $user;
		global $pdo;
		global $title;
		$params = array(':username' => $user);
		$stmt = $pdo->prepare("SELECT id, name FROM classes WHERE user= :username");
		if ($stmt->execute($params)) {
			$stmt->bindColumn('id',$class_id);
			$stmt->bindColumn('name',$class_name);
			$class_list = ($title === "Grades") ? "<form id=\"classList\" method=\"post\" action=\"get_grades.php\" data-ajax=\"false\">" : "";
			$class_list .= "<div><label for=\"classes\">Select a Class:</label><select id=\"classes\" name=\"className\">\n";
			$class_list .= "<option value=\"\">Select a class</option>\n";
			while($row = $stmt->fetch()) {
				$class_list .= "<option value=\"" .$class_id. "\">" .$class_name. "</option>\n";
			}
			$class_list .= ($title === "Grades") ? "</select></div></form>\n" : "</select></div>\n";
			
			echo $class_list;
		}
	}
	
?>