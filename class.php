<?php
	$title = "Add Class";
	include(dirname(__FILE__).'/loader.php');
	check_session();
	
	get_header();
?>


<body>
<div data-role="page" id="page3" data-theme="b">
	<?php get_page_header(); ?>
	
	<div data-role="content" data-theme="b">
		
		<?php get_class_menu(); ?>
		
		<div data-theme="a" id="error">  </div>
		<div data-theme="a">
			<form id="addForm" method="post" action="add_class.php" data-ajax="false">
				<h3>Please Enter the Class Name and Submit</h3><br>
				<div><label for="className">Name:</label><input type="text" id="className" name="className" required /></div>
				<div class="ui-block-a"><input type="submit" value="Add Class"/></div>
						
			</form>
			<script>$("#addForm").validate();</script>
			
		</div>                  

	</div> <!-- /content -->
</div> <!-- /page -->
</body>
</html>