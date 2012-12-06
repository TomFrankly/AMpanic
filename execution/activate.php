<?php

	// connect to the database
	require_once "../includes/connect.php";

	// Get the ID and random number from the URL that was generated when the user registered
	$id = $_GET['id'];
	$code = $_GET['code'];
	
	if ($id && $code)
	{
		
		// Get the number of rows in the database whose 'id' and 'random' fields match what was in the URL
		$check = mysql_query("SELECT * FROM users WHERE id='$id' AND random='$code'");
		$checkNum = mysql_num_rows($check);
		
		if ($checkNum == 1) 
		{
			
			// run a query to activate the account by setting the 'activated' cell from 0 to 1
			$acti = mysql_query("UPDATE users SET activated ='1' WHERE id='$id'");
			die("<div style=\"width: 400px; margin: 150px auto; padding: 8px; background-color: #FF8DAE; border: 1px solid red; padding: 7px; color: #333; text-align: center;\">Your account is now active. You may now login. <a href=\"../index.php\">Return to home</a>.</div>");
			
		}
		else
			die("<div style=\"width: 400px; margin: 150px auto; padding: 8px; background-color: #FF8DAE; border: 1px solid red; padding: 7px; color: #333; text-align: center;\">Invalid ID or activation code.</div>");
	}
	else
		die("Data missing!");

?>