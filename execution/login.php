<?php

// initialize the session upon login
session_start();

// connect to the MySQL server
require_once "connect.php";

// take in the username and password from the form and store them in vars
$username = mysql_real_escape_string(trim(strip_tags($_POST['username'])));
$password = mysql_real_escape_string(trim(strip_tags($_POST['password'])));

// make sure both username and password were entered before doing anything
if ($username && $password)
{

	
	
	// select all entries from the *users* tables that have {$username} in their username field
	$query = mysql_query("SELECT * FROM users WHERE username='$username'");
	
	// count the number of rows in the table that meet the above query
	$numRows = mysql_num_rows($query);
	
	// check to make sure at least one does - if not, the user doesn't exist
	if ($numRows != 0)
	{
		// store all entries from the query in an array and assign to {$row}
		while ($row = mysql_fetch_assoc($query))
		{
			// create vars to store the existing username and password from the db
			$dbUsername = $row['username'];
			$dbPassword = $row['password'];	
			$dbRandom = $row['random'];
			$activated = $row['activated'];
			
			if ($activated=='0')
			{
				die("Your account is not yet active. Please check your email for an activation link. <a href=\"index.php\">Return to home.</a>");
				exit();
			}
				
		}
		
		// escape the strings passed for user and password
		$username = mysql_real_escape_string($username);
		$password = mysql_real_escape_string($password);
		
		// salt the password
		$salt = hash('sha512', $dbRandom);
		$password = $password.$salt;
		$password = hash('sha512', $password);
		
		// check to see if they match the user-supplied username and password
		if ($username==$dbUsername && $password==$dbPassword)
		{
			
			$_SESSION['username'] = $dbUsername;
			
			?>
			
				<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>Redirect</title>
				<style type="text/css">
				
				h2 {
					margin: 0 auto;
					font: Century Gothic;
					font-size: 3.5em;
					color: #8d8d8d;
					position: relative;
					left: -2px;
					margin-top: 150px;
					text-align: center;
				}
				
				#body {
					margin: 0 auto;
					border: 3px dashed #BA0202;
					padding: 5px;
					width: 350px;
				}
				
				</style>
				<script>	
				
				// Wait a bit.
				function pausecomp(millis) {
					var date = new Date();
					var curDate = null;
					
					do {curDate = new Date();}
					while(curDate-date < millis);
				}
				
				// Redirect the user back.
				function reDirect() {
					
					setTimeout('window.location = "index.php"', 1250);
				return;
				}
				</script>
				</head>
				
				<body onLoad="reDirect();">
					<h2>You're in.</h2>
					<div id="body">
					<p style="text-align: center;">Redirecting you to your control panel.</p>
					
				    </div>
				    <script>
					pausecomp(500);
					</script>
					
				</body>
				</html>
			
			<?php	
		}
		else
			echo "Incorrect password. <a href=\"index.php\">Return to home.</a>";
	}
	else
		die("User does not exist. <a href=\"index.php\">Return to home.</a>");
	
	
	
}
else
	die("Please enter a username and password <a href=\"index.php\">Return to home.</a>");

?>



