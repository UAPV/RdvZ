<?php 
session_start();


	if( !isset($_SESSION['uid']))
	{
		header("Location:login.php");
		exit();
	}
	else
	{
		$uid = $_SESSION['uid'];
	}


?>
