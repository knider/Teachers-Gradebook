<?php
$title = "Login";
include(dirname(__FILE__).'/loader.php');

get_header();
?>
<body>

<script>
/*
Getting description of login attempt:
status 0 means user attempted to load index without logging in
status 1 means invalid password
status 2 means invalid email
status 3 means logout */

$(document).ready(function(){
	//bind an event handler to the submit event for your login form
	$('#loginForm').submit('submit', function (e) {

		//cache the form element for use in this function
		var $this = $(this);

		//prevent the default submission of the form
		e.preventDefault();

		//run an AJAX post request to your server-side script, $this.serialize() is the data from your form being added to the request
		$.post($this.attr('action'), $this.serialize(), function (responseData) {
			//in here you can analyze the output from your server-side script (responseData) and validate the user's login without leaving the page
			var message = responseData;              //get message
			if (message === "0") {
				window.location.replace("index.php");
			} else {
			$("#error").html(message);
			}
			
		});
	});
});
</script>
<div data-role="page" data-theme="b">
	
	<?php get_page_header(); ?>
	
	<div data-role="content" data-theme="b">

		<div data-theme="a" id="error">  </div>
		<div data-theme="a">
			<form id="loginForm" data-ajax="false" method="post" action="validate_login.php">
				<h3>Enter Username and Password</h3><br>
				<div><label for="username">Username:</label><input type="text" id="username" name="username" required /></div>
				<div><label for="password">Password:</label><input id="password" type="password" name="password" required /></div>
				<div><input type="submit" value="Login"/></div>
			</form>
			<script>$("#loginForm").validate();</script>
		</div>                

		

	</div> <!-- /content -->
</div> <!-- /page -->
</body>
</html>
