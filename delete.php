<!--
Date Submitted April 1 2013
-->
<?php
require_once 'library.php';
session_start();
if (!$_SESSION['user']) {
	header("location:login.php");
	die();
}
$link = new Connect;
$myconn = $link->connect();
$sql_query = "SELECT * from inventory where id =" . $_GET['id'];
$result = mysqli_query($myconn, $sql_query) or die('query failed' . mysql_error());

$row = mysqli_fetch_assoc($result);
if ($row['deleted'] == 'n') {
	$sql_query = "UPDATE inventory SET deleted = 'y' where id =" . $_GET['id'];
} else {
	$sql_query = "UPDATE inventory SET deleted = 'n' where id =" . $_GET['id'];
}
$result = mysqli_query($myconn, $sql_query) or die('query failed' . mysql_error());

header('Location: view.php');
?>
