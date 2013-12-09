<?php
	$title = "Register";
	include(dirname(__FILE__).'/loader.php');

	get_header();

?>
<body>
<div data-role="page" data-theme="b">
<script>
$(document).ready(function(){
	//bind an event handler to the submit event for your login form
	$('#regForm').submit(function (e) {

		//cache the form element for use in this function
		var $this = $(this);

		//prevent the default submission of the form
		e.preventDefault();

		//run an AJAX post request to your server-side script, $this.serialize() is the data from your form being added to the request
		$.post($this.attr('action'), $this.serialize(), function (responseData) {
			//in here you can analyze the output from your server-side script (responseData) and validate the user's login without leaving the page
			var message = responseData;              //get message
			   
			$('#error').html(message); //Set output element html
			
		});
	});
});
</script>
	<?php get_page_header(); ?>
	
	<div data-role="content" data-theme="b">

		<div data-theme="a" id="error">  </div>
		
		<div data-theme="a">
			<form id="regForm" data-ajax="false" method="post" action="validate_reg.php">
				<h3>Enter Username and Password</h3><br>
				<div><label for="username">Username:</label><input type="text" id="username" name="username" minlength="4" required /></div>
				<div><label for="password">Password:</label><input type="password" id="password" name="password" minlength="8" required /></div>
				<input type="hidden" name="form" value="form" />
				<div><input type="submit" value="Register" /></div>
			</form>
			<script>$("#regForm").validate();</script>
		</div>                

		

	</div> <!-- /content -->
</div> <!-- /page -->
</body>
</html>
