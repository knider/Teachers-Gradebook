<?php
	$title = "Grades";
	include(dirname(__FILE__).'/loader.php');
	check_session();
	
	get_header();
	
	
?>


<body>
<script>
$(document).ready(function(){
	//$("#classList").on("change", getAjax(gradesForm, "get_grades.php", "list"));
	//not the best way of doing this, but couldn't figure out above line
	$('#classList').change(function (e) {
		var $this = $(this); //cache the form element for use in this function
		e.preventDefault(); //prevent the default submission of the form

		//run an AJAX post request to your server-side script, $this.serialize() is the data from your form being added to the request
		$.post($this.attr('action'), $this.serialize(), function (responseData) {
			//in here you can analyze the output from your server-side script (responseData)
			var message = responseData;              //get message
			$('#gradesForm').html(message); //Set output element html
			
		});
	});
	
	
});
</script>
<div data-role="page" id="page3" data-theme="b">
	<?php get_page_header(); ?>
	
	<div data-role="content" data-theme="b">
		
		<?php get_menu(); ?>
		
		<div data-theme="b" id="error">  </div>
		<!-- get class list -->
		<div data-theme="b"><div class="ui-block-a"><?php get_classes(); ?></div></div>
		
		<!-- get gradebook -->
		<div data-theme="b" id="gradesForm" style="clear:both">
			
		</div>                  

	</div> <!-- /content -->
</div> <!-- /page -->
</body>
</html>