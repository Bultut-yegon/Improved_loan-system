<?php 
session_start();
if (isset($_SESSION['user_id']))
{
	if (isset($_GET['id']))
	{
		$mysqli = require __DIR__ . "/database.php";
		$id = $_GET['id'];

		$sql = "DELETE FROM loan WHERE id = {$id}";
		$mysqli->query($sql);

		header("Location: ./loans.php");
		exit;
	}
}

else{
	header("Location: dashboard.php");
	exit;
}