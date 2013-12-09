<?php
header('Location: login.php');
ini_set('display_errors', 'On');
session_start();
	if(isset($_SESSION['user'])){
	unset($_SESSION);
	session_destroy();
	session_write_close();
	}
exit;
?>