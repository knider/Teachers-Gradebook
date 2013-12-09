<?php 
	session_start();
	ini_set('display_errors', 'On');
	$loggedin = 0;
	if (isset($_SESSION['string'])) 
	{ //logged in
		$loggedin = 1;
		session_write_close();
	} else {
		$loggedin = 0;
	}
	
	if (!$loggedin) 
	{
?>
		<!DOCTYPE html>
		<html>
		<head>
			<title>Please Log in</title>
			<meta charset="utf-8">
			<script src="jquery-1.10.2.min.js"></script>
		</head>
		<body>
		<div>You need to <a href='login.php'>Log in</a></div>
<?php	
		echo '<pre>';
		print_r($GLOBALS);
		echo '</pre>';
	}	
	else 
	{ //is logged in
	
		if (array_key_exists('logout', $_REQUEST)) {
			session_start();
			$_SESSION = array();
			session_destroy();
			session_write_close();
			header('location:login.php');	
		}
		
		$user = $_SESSION['user_name'];
		$pass = $_SESSION['pass_word'];
		
		function run_select($user, $pass) 
		{
			//connect to DB
			$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "niderk-db", "8qV5RXYryvcPMSf8","niderk-db");
			if ($mysqli->connect_errno) {
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ")" . $mysqli->connect_error;
			}
			
			//Selecting SELECT username,password,todo1,todo2,todo3
			if ( !($stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ? AND password = ?") ) ) {
				echo "Prepare failed: (" . $mysqli->errno . ")" . $mysqli->error;
			}
			
			if ( !$stmt->bind_param("ss", $user, $pass) ) { echo "Bind paramaters failed: (" . $mysqli->errno . ")" . $mysqli->error; }
			
			//Run the statement
			if ( !$stmt->execute() ) { echo "Execute failed: (" . $mysqli->errno . ")" . $mysqli->error; }
			
			// One bound param for each thing selected in same order
			if ( !$stmt->bind_result($usernamer, $passwordr, $todo1r, $todo2r, $todo3r) ) {
				echo "Binding result failed: (" . $mysqli->errno . ")" . $mysqli->error;
			}
			
			else {
				
				//echo "running query";
				//Buffers data
				$stmt->store_result();
				if ($stmt->num_rows === 1) 
				{			
					//Gets rows from buffered data
					while($stmt->fetch()) {
						if ($todo1r || $todo2r || $todo3r) { //if not blank
							echo "<h2>To dos</h2><ul>\n";
							if ($todo1r) {	
								echo "<li>" . $todo1r . "<button class='remove_button' 
							onclick='javascript: remove_todo(\"todo1\")'>Remove</button></li>\n"; 
							}
							if ($todo2r) {	
								echo "<li>" . $todo2r . "<button class='remove_button' onclick='javascript: remove_todo(\"todo2\")'>Remove</button></li>\n"; 
							}
							if ($todo3r) {	
								echo "<li>" . $todo3r . "<button class='remove_button' onclick='javascript: remove_todo(\"todo3\")'>Remove</button></li>\n"; 
							}
							echo "</ul>\n"; 
						}
					}
				}
				else {
					echo "no result";
				}
					
				//Frees memory allocated for buffer
				$stmt->free_result(); 
				//Deallocates statement handle
				$stmt->close();
				
			}
		}
	
		function delete_todo($user, $pass, $todonum) 
		{
			
			//connect to DB
			$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "niderk-db", "8qV5RXYryvcPMSf8","niderk-db");
			if ($mysqli->connect_errno) {
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ")" . $mysqli->connect_error;
			}
			
			$prep = "UPDATE users SET " . $todonum . " = NULL WHERE ";
			if ( !($stmt = $mysqli->prepare($prep . "username = ? AND password = ?") ) ) {
				echo "Prepare failed: (" . $mysqli->errno . ")" . $mysqli->error;
			}
			if ( !$stmt->bind_param("ss", $user, $pass) ) { echo "Bind parameters failed: (" . $mysqli->errno . ")" . $mysqli->error; }
			

			//Run the statement
			if ( !$stmt->execute() ) { echo "Execute failed: (" . $mysqli->errno . ")" . $mysqli->error; } 
			
			$stmt->close();
			
			//Run select again
			run_select($user, $pass);
		}
		
		function update_todo($user, $pass, $todonum, $todotext) 
		{
			
			//connect to DB
			$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "niderk-db", "8qV5RXYryvcPMSf8","niderk-db");
			if ($mysqli->connect_errno) {
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ")" . $mysqli->connect_error;
			}
			
			$prep = "UPDATE users SET " . $todonum . " = \"". $todotext ."\" WHERE ";
			if ( !($stmt = $mysqli->prepare($prep . "username = ? AND password = ?") ) ) {
				echo "Prepare failed: (" . $mysqli->errno . ")" . $mysqli->error;
			}
			if ( !$stmt->bind_param("ss", $user, $pass) ) { echo "Bind parameters failed: (" . $mysqli->errno . ")" . $mysqli->error; }
			

			//Run the statement
			if ( !$stmt->execute() ) { echo "Execute failed: (" . $mysqli->errno . ")" . $mysqli->error; } 
			
			$stmt->close();
			
			//Run select again
			run_select($user, $pass);
		}
		
?>
		<!DOCTYPE html>
		<html>
		<head>
			<title>Post Login</title>
			<meta charset="utf-8">
			<link rel="stylesheet" type="text/css" media="screen" href="style.css" >
			<script src="jquery-1.10.2.min.js"></script>
			<script type="text/javascript">
			function remove_todo(todo) {
				document.write("<form name='deleteform' id='deleteform' action='post-login.php' method='POST'>");
				document.write("<input type='hidden' name='todo' value='" + todo + "' />");
				document.write("<input type='hidden' name='delete' value='1' />");
				document.write("</form>");
				document.deleteform.submit();
			}
			</script>
			
		</head>
		<body>

		
		<div class="welcome">Welcome <?php echo $_SESSION['user_name']; ?>
			<form name='logoutform' id='logoutform' action='post-login.php' method='POST' style="display:inline;">
				<input type='hidden' name='logout' value='1' />
				<input type="submit" value='Log Out' style="margin-top: 0;" />
			</form>
		</div>
<?php		
		if (array_key_exists('delete', $_REQUEST)) 
		{
			$todonum = $_POST['todo'];
			delete_todo($_SESSION['user_name'], $_SESSION['pass_word'], $todonum);
		} 
		else if (array_key_exists('updatetodo', $_REQUEST)) 
		{
			$todonum = $_POST['updatetodo'];
			$todotext = $_POST['todo'];
			update_todo($_SESSION['user_name'], $_SESSION['pass_word'], $todonum, $todotext);
		}
		else 
		{
			run_select($_SESSION['user_name'], $_SESSION['pass_word']);
		}
?>
<h2>Add/Update To dos</h2>
<form action='post-login.php' name='updateform' id='updateform1' method='POST'>
	<div>To do: 
		<input type='text' name='todo'>
		<input type='hidden' name='updatetodo' value='todo1' />
		<input type='submit' value='Update'>
	</div>
</form>
<form action='post-login.php' name='updateform' id='updateform2' method='POST'>
	<div>To do: 
		<input type='text' name='todo'>
		<input type='hidden' name='updatetodo' value='todo2' />
		<input type='submit' value='Update'>
	</div>
</form>
<form action='post-login.php' name='updateform' id='updateform3' method='POST'>
	<div>To do: 
		<input type='text' name='todo'>
		<input type='hidden' name='updatetodo' value='todo3' />
		<input type='submit' value='Update'>
	</div>
</form>

<?php	
		
		/*echo '<pre>';
		print_r($GLOBALS);
		echo '</pre>';  */
	}
?>
</body>
</html>