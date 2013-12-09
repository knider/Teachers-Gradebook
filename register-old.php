<?php
	$title = "Register";
	include(dirname(__FILE__).'/loader.php');

	get_header();

	$error_string = "  ";
	$username_in = array_key_exists("username", $_POST) ? mysqli_real_escape_string($mysqli,$_POST["username"]) : '';
	$pass = array_key_exists("password", $_POST) ? mysqli_real_escape_string($mysqli,$_POST["password"]) : '';
	
	//phpinfo();
	//check login, see if name already in use
	if(array_key_exists("form", $_POST)) { 
		if (!($stmt = $mysqli->prepare("SELECT username FROM user WHERE username=?"))) {
			echo "Check Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		if (!($stmt->bind_param('s', $username_in))) { echo "Bind failed: "  . $stmt->errno . " " . $stmt->error; }
		
		if (!$stmt->execute()){  echo "Execute1 failed: "  . $stmt->errno . " " . $stmt->error; }
		
		if (!$stmt->bind_result($username_out)) {
			echo "Binding result failed: (" . $mysqli->errno . ")" . $mysqli->error;
		}
		
		else { //no errors 
			$stmt->store_result();
			
			if (!$stmt->num_rows) //go ahead and create the login
			{ 				
				$stmt->close();
				if (!($stmt = $mysqli->prepare("INSERT INTO user (username,password) VALUES (?,?)" ))) {
					echo "Insert Prepare failed: " . $stmt->errno . " " . $stmt->error;
				}
				
				if (!($stmt->bind_param("ss", $username_in, $pass))) { echo "Bind failed: "  . $stmt->errno . " " . $stmt->error; } 

				if (!$stmt->execute()){ echo "Execute2 failed: "  . $stmt->errno . " " . $stmt->error; } 
				
				else { //no errors
					$error_string .= "Username " . $username_in . " created. Please <a href=\"index.php\">login</a>.";
					//$error_string .= "Username created.";
				}
			} else { //if there is a login already
				$error_string .= "Username " . $username_in .  " already exists, please try a different username ";
			
			}
		}
	}
?>
<body>
<div data-role="page" data-theme="b">
<script>
//bind an event handler to the submit event for your login form
$('#regForm').live('submit', function (e) {

    //cache the form element for use in this function
    var $this = $(this);

    //prevent the default submission of the form
    e.preventDefault();

    //run an AJAX post request to your server-side script, $this.serialize() is the data from your form being added to the request
    $.post($this.attr('action'), $this.serialize(), function (responseData) {

        //in here you can analyze the output from your server-side script (responseData) and validate the user's login without leaving the page
    });
});
</script>
	<?php get_page_header(); ?>
	
	<div data-role="content" data-theme="b">

		<div data-theme="a" id="error"> <?php echo $error_string; ?> </div>
		
		<div data-theme="a">
			<form id="regForm" data-ajax="false" method="post" action="register.php">
				<h3>Enter Username and Password</h3><br>
				<div><label for="username">Username:</label><input type="text" id="username" name="username" minlength="4" required /></div>
				<div><label for="password">Password:</label><input type="password" id="password" name="password" minlength="8" required /></div>
				<input type="hidden" name="form" value="form" />
				<div><input type="submit" value="Login"/></div>
			</form>
			<script>$("#regForm").validate();</script>
		</div>                

		

	</div> <!-- /content -->
</div> <!-- /page -->
</body>
</html>
