<?php
	session_start(); 
	ini_set('display_errors', 'On');

	//if they are already logged in, redirect to logged-in page
	if (isset($_SESSION['user_name'])) {
		session_write_close(); 
		//header('location:post-login.php');	
		if (array_key_exists('logout', $_REQUEST)) {
			session_start();
			$_SESSION = array();
			session_destroy();
			session_write_close();
			header('location:login.php');	
		}
		?>
		<!DOCTYPE html>
		<html>
		<head>
			<title>Post Login</title>
			<meta charset="utf-8">
			<link rel="stylesheet" type="text/css" media="screen" href="style.css" >
			<script src="jquery-1.10.2.min.js"></script>			
		</head>
		<body>
		<div class="welcome">Welcome <?php echo $_SESSION['user_name']; ?>
			<form name='logoutform' id='logoutform' action='login.php' method='POST' style="display:inline;">
				<input type='hidden' name='logout' value='1' />
				<input type="submit" value='Log Out' style="margin-top: 0;" />
			</form>
		</div>
		<div>
			Please go to the <a href="post-login.php">Post-login page</a>
		</div>
			</body>
			</html>
		<?php
	} else //if not logged in
	{
		//if form was submitted
		if (!empty($_POST["username"])) {
			//set variables
			$user = "";
			$pass = "";
			//connect to DB
			$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "niderk-db", "8qV5RXYryvcPMSf8","niderk-db");
			if ($mysqli->connect_errno) {
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ")" . $mysqli->connect_error;
			}
			
			//check username and password
			if ( array_key_exists("username", $_REQUEST) && array_key_exists("password", $_REQUEST) ) {
				$user = $_REQUEST["username"];
				$pass = $_REQUEST["password"];
			}
			
			//Selecting SELECT ,todo1,todo2,todo3 FROM users WHERE username = '" . $user . "' AND password = '" . $pass . "'"
			if ( !($stmt = $mysqli->prepare("SELECT username,password FROM users WHERE username = ? AND password = ?") ) ) {
				echo "Prepare failed: (" . $mysqli->errno . ")" . $mysqli->error;
			}
			
			//bind the username and password
			if ( !$stmt->bind_param("ss", $user, $pass) ) { echo "Bind paramaters failed: (" . $mysqli->errno . ")" . $mysqli->error; }
			
			//Run the statement
			if ( !$stmt->execute() ) {
				echo "Execute failed: (" . $mysqli->errno . ")" . $mysqli->error;
			}
			
			// One bound param for each thing selected in same order
			if ( !$stmt->bind_result($usernamer, $passwordr) ) {
				echo "Binding result failed: (" . $mysqli->errno . ")" . $mysqli->error;
			}
			
			//if no errors
			else 
			{
				//Buffers data
				$stmt->store_result();
				if ($stmt->num_rows === 1) //if user and pass are correct, set the session data
				{
					
					//Frees memory allocated for buffer
					$stmt->free_result(); 
					//Deallocates statement handle
					$stmt->close();
					$_SESSION['user_name'] = $user;
					$_SESSION['pass_word'] = $pass;
					session_write_close(); 
					header('location:post-login.php');					
				}
				else 
				{ //if password is incorrect
					
				//Frees memory allocated for buffer
					$stmt->free_result(); 
					//Deallocates statement handle
					$stmt->close();
					?>
					<!DOCTYPE html>
					<html>
					<head>
						<title>Login Page</title>
						<meta charset="utf-8">
						<script src="jquery-1.10.2.min.js"></script>
						<script src="jquery.validate.min.js"></script>
						<script>
							$(document).ready(function() {$("form").validate();});
						</script>
					</head>

					<body>
					<div style="color: red; margin: 10px;">Invalid username and password</div>
					<form action="login.php" method="POST">
						<div>Username:* <input type="text" name="username" class="required"></div>
						<div>Password:* <input type="password" name="password" class="required"></div>
						<div><input type="submit" value="Login"></div>
					</form>
					<?php /*
						echo '<pre>';
						print_r($GLOBALS);
						echo '</pre>';  */
					?>
					</body>
					</html>
					<?php
				}
				
			}
		}
		else 
		{ //if no form was submitted
?>
			<!DOCTYPE html>
			<html>
			<head>
				<title>Login Page</title>
				<meta charset="utf-8">
				<script src="jquery-1.10.2.min.js"></script>
				<script src="jquery.validate.min.js"></script>
				<script>
					$(document).ready(function() {$("form").validate();});
				</script>
			</head>

			<body>
			<form action="login.php" method="POST">
			<div>Username:* <input type="text" name="username" class="required"></div>
			<div>Password:* <input type="password" name="password" class="required"></div>
			<div><input type="submit" value="Login"></div>
			</form>
			<?php
				/*
				echo '<pre>';
				print_r($GLOBALS);
				echo '</pre>'; */
			?>
			</body>
			</html>

<?php
		}
	}
?>