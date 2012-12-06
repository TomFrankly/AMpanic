<?php

// start the session
session_start();


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="keywords" content="Wake-Up Tool" />
<meta name="description" content="Wake up on time - or else!" />
<title>AMpanic - Home</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link href='http://fonts.googleapis.com/css?family=Racing+Sans+One' rel='stylesheet' type='text/css'>
<link rel="shortcut icon" href="img/favicon.png" type="image/x-icon" />
</head>

<body>

	<div id="container" class="index">
	
		<h1 class="title">AMpanic</h1>
		<a href="index.php"><span class="sub">Wake Up On Time - Or Else!</span></a>
		
		<?php

				// check if the user is logged in
		if (isset($_SESSION['username']))
		{
			
			?>
	
		<a href="jobs.php" class="nav">Scheduled Jobs</a>
		<a href="execution/logout.php" class="nav">Logout</a>
	
		<?php

// Starts the script when the user submits an email
$submit = $_POST['submit'];

if ($submit) {

	// connect to database
	require_once "includes/connect.php";

	// declare variables and match them to form data
	$address = mysql_real_escape_string(trim(strip_tags($_POST['address'])));
	$message = nl2br(substr(mysql_escape_string($_POST['message']),0,65535));

	if ($_POST['ampm'] == "am") {

		if ($_POST['hour'] == "12") {
			$hour = "00";
		}
		else {
			$hour = mysql_real_escape_string(trim(strip_tags($_POST['hour'])));
		}

	}
	else {
		if ( $_POST['hour'] != 12) {

			$hour = mysql_real_escape_string(trim(strip_tags($_POST['hour'])))+12;
		}
		else {
			$hour = 12;
		}
	}

	$time = $hour . ":" . mysql_real_escape_string(trim(strip_tags($_POST['minute'])));

	$timezone = mysql_real_escape_string(trim(strip_tags($_POST['timezone'])));

	$user = $_SESSION['username'];

	// A tiny bit of security to make sure the database doesn't get overloaded
	$result = mysql_query("SELECT * FROM wakeup") or die("Couldn't select the table");
	$num_rows = mysql_num_rows($result);

	if ($num_rows < 1000) {

		// make sure the email they chose isn't on the disallowed list
		$offlimits_result = mysql_query("SELECT address FROM offlimits WHERE address='$address'") or die("Couldn't select the table");
		$offlimits_count = mysql_num_rows($offlimits_result);

		if ($offlimits_count == 0) {

			// now figure out if the user already has an alarm set
			$user_result = mysql_query("SELECT * FROM wakeup") or die("Couldn't select the table");
			$user_num_rows = mysql_num_rows($user_result);

			// if they do, just alter the alarm with the new values
			if ($user_num_rows > 0) {

				$query = mysql_query("UPDATE wakeup SET address='$address', message='$message', time='$time', timezone='$timezone' WHERE user='$user'") or die("Couldn't insert data.");

				// Notify the user that the alarm has been EDITED
				?>
					<p class="notify">Your alarm has been changed to the new time and is now active. <a href="index.php">Dismiss</a></p>
				<?php

			}

			// otherwise create the alarm
			else {

				$query = mysql_query("INSERT INTO wakeup VALUES ('', '$address', '$message', '$time', '$timezone', '$user', 'Yes', 'Yes') ") or die("Couldn't insert data.");

				// Notify the user that the alarm has been CREATED
				?>
					<p class="notify">Your alarm has been set and is now active. <a href="index.php">Dismiss</a></p>
				<?php

			}

		}

		else {

			// Notify the user that they can't send an email to that particular address
			?>
				<p class="notify">The owner of that email address has opted to not receive emails from AMpanic. Please select another address. <a href="index.php">Dismiss</a></p>
			<?php

		}

	}

	else {

		// Notify the user that the database is overloaded
		?>
			<p class="notify">Error: AMpanic is currently undergoing maintenance, so your alarm can't be set right now. Sorry about that. <a href="index.php">Dismiss</a></p>
		<?php

		// Notify me that the database is full
		$to = "thomasfrank09@gmail.com";
		$subject = "Urgent message from AMpanic";
		$headers = "From: admin@thomasjfrank.com";
		$server = "174.120.31.190";

		$body = "Hey the wakeup table in the database has 1000 entries in it and it is full!";

		mail($to, $subject, $body, $headers);

	}

}

?>
		<form action="index.php" method="POST">
			<table>
		        <tr>
		            <td>
		            Address to send to:
		            </td>
		            <td>
		            <input type="text" name="address"/>
		            </td>
		        </tr>
		        <tr>
		            <td>
		            Your message:
		            </td>
		            <td>
		            <textarea name="message" rows="4" cols="50"></textarea>
		            </td>
		        </tr>
		        <tr>
		            <td>
		            When should it be sent?
		            </td>
		            <td>
		            	<table>
		            		<tr>
		            			<?php
		            			$file = "includes/time-options.txt";

		            			if (file_exists($file)) {
		            				readfile($file);
		            			}
		            			else {
		            				?>
		            					<option value="We lost something...">We lost something here...</option>
		            				<?php
		            			}
		            			?>
		            		</tr>
		            	</table>
		            </td>
		        </tr>
		        <tr>
		            <td>
		            	What is your timezone?
		            </td>
		            <td>
		            	<select name="timezone" id="timezone">
		            		<?php
		            			$file = "includes/timezones.txt";

		            			if (file_exists($file)) {
		            				readfile($file);
		            			}
		            			else {
		            				?>
		            					<option value="We lost something...">We lost something here...</option>
		            				<?php
		            			}
		            		?>
						</select>
		            </td>
		        </tr>
		    </table>
		    <p></p>
            <input type="submit" name="submit" value="Schedule!" />
		</form>
	
		      <?php

}

// if the user isn't logged in, tell them so
else {	
		?>
		
		<a href="register.php" class="nav">Register</a>
		
		<h3 class="login">Please log in to your account.</h3>
		<form action="execution/login.php" method="POST">
			<table>
		        <tr>
		            <td>
		            Username:
		            </td>
		            <td>
		            <input type="text" name="username"/>
		            </td>
		        </tr>
		        <tr>
		            <td>
		            Password:
		            </td>
		            <td>
		            <input type="password" name="password"/>
		            </td>
		        </tr>
		    </table>
		    <p></p>
            <input type="submit" name="login" value="Log In" />
		</form>
		
		<?php
}

?> 

	</div>
	
</body>

</html>