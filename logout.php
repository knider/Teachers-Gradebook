<?php
ini_set('display_errors', 'On');
session_start();
unset($_SESSION['username']);
header('Location: login.php');
?>