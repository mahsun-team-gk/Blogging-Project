<?php
	
	require_once("database_settings.php");
	
	$driver = new mysqli_driver();
	$driver->report_mode = MYSQLI_REPORT_OFF;
	
	$connection = mysqli_connect($host_name, $user_name, $password, $database_name);
	
	if(mysqli_connect_errno())
	{
		echo "<p style='color: red'><b>Database Connection Problem !...</b></p>";
		echo "<p style='color: red'><b>Error Code: </b>".mysqli_connect_errno()."</p>";
		echo "<p style='color: red'><b>Error Message: </b>".mysqli_connect_error()."</p>";
	}
	
?>