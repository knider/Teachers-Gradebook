<?php
	$title = "Add Student";
	include(dirname(__FILE__).'/loader.php');
	check_session();
	get_header();
?>


<body>
<div data-role="page" id="page3" data-theme="b">
	<?php get_page_header(); ?>
	
	<div data-role="content" data-theme="b">
		
		<?php get_menu(); ?>
		
		<div data-theme="a" id="error">  </div>
		<div data-theme="a">
			<form id="addForm" method="post" action="add_student.php" data-ajax="false">
				<h3>Please Enter the Student's Information</h3><br>
				<div><label for="firstName">First Name:</label><input type="text" id="firstName" name="firstName" required /></div>
				<div><label for="lastName">Last Name:</label><input type="text" id="lastName" name="lastName" required /></div>
				<?php get_classes(); ?>
				<div class="ui-block-a"><input type="submit" value="Add Student"/></div>
						
			</form>
			<script>$("#addForm").validate();</script>
			
		</div>                  

	</div> <!-- /content -->
</div> <!-- /page -->
</body>
</html>