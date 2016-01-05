<!--
Date Submitted April 1 2013
-->
<?php
session_start();
if (!$_SESSION['user']) {
	header("location:login.php");
	die();
} else {
	session_destroy();
	header("location:login.php");
}
?>
