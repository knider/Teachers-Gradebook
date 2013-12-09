<?php
	$title = "Home";
	
	include(dirname(__FILE__).'/loader.php');
	session_set_cookie_params(60);
	
	check_session();
	
	get_header();
?>

<body>
<div data-role="page" id="page1" data-theme="b">
	
	<?php get_page_header(); ?>
	
	<div data-role="content" data-theme="b">
		<?php get_home_menu(); ?>

	</div> <!-- /content -->
</div> <!-- /page -->
</body>
</html>
