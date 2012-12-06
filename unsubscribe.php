<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="keywords" content="Wake-Up Tool" />
<meta name="description" content="Wake up on time - or else!" />
<title>AMpanic - Unsubscribe</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link href='http://fonts.googleapis.com/css?family=Racing+Sans+One' rel='stylesheet' type='text/css'>
<link rel="shortcut icon" href="img/favicon.png" type="image/x-icon" />
</head>

<body>

	<div id="container" class="index">
	
		<h1 class="title">AMpanic</h1>
		<a href="index.php"><span class="sub">Wake Up On Time - Or Else!</span></a>
	
		<a href="index.php" class="nav">Home</a>
	
		<?php

// Starts the script when the user submits an email
$submit = $_POST['submit'];

if ($submit) {

	// connect to database
	require_once "includes/connect.php";

	// declare variables and match them to form data
	$address = mysql_real_escape_string(trim(strip_tags($_POST['address'])));

	if(filter_var($address, FILTER_VALIDATE_EMAIL)) {

		// A tiny bit of security to make sure the database doesn't get overloaded
		$result = mysql_query("SELECT * FROM offlimits") or die("Couldn't select the table");

		$num_rows = mysql_num_rows($result);

		// insert data into database
		if ($num_rows < 1000) {

			$nameCheck2 = mysql_query("SELECT address FROM offlimits WHERE address='$address'") or die("Couldn't select the table");
			$count2 = mysql_num_rows($nameCheck2);

			if ($count2 == 0) {
				$query = mysql_query("INSERT INTO offlimits VALUES ('', '$address') ") or die("Couldn't insert data.");
			}
		}

		// Notify the user that the email has been scheduled
		?>
			<p class="notify">Your email has been added to the list of disallowed addresses. You will no longer receive notifications from AMpanic.</p>
		<?php
	}

	else {

		?>
			<p class="notify">Error: Please enter a valid email address.</p>
		<?php
	}

}

?>
		<p>If you would like to prevent others from sending you notifications from AMpanic, enter your email address below and it will be added to the list of disallowed emails. (Note: Your email will never be shared or used for any other purpose)</p>
		
		<form action="unsubscribe.php" method="POST">
			<table>
		        <tr>
		            <td>
		            Enter your email:
		            </td>
		            <td>
		            <input type="text" name="address"/>
		            </td>
		        </tr>
		    </table>
		    <p></p>
            <input type="submit" name="submit" value="Disallow My Email" />
		</form>
		
		<div class="bottomlinks">
			<a href="about.php">About</a>
			<a href="faq.php">FAQ</a>
			<a href="contact.php">Contact</a>
		</div>

	</div>
	
</body>

</html>