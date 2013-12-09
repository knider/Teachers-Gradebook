<?php
	$title = "Add Assignment";
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
			<form id="addForm" method="post" action="add_assignment.php" data-ajax="false">
				<h3>Please Enter the Assignment Information</h3><br>
				<div><label for="assignmentName">Assignment Name:</label><input type="text" id="assignmentName" name="assignmentName" required /></div>
				<div><label for="max_grade">Max Grade:</label><input type="number" id="max_grade" name="max_grade" required /></div>
				
				<div class="ui-block-a"><input type="submit" value="Add Assignment"/></div>
						
			</form>
			<script>$("#addForm").validate();</script>
			
		</div>                  

	</div> <!-- /content -->
</div> <!-- /page -->
</body>
</html>