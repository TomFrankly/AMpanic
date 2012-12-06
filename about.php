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
	
		<a href="index.php" class="nav">Wake Up</a>
		<a href="edit.php" class="nav">Create/Edit Alarm</a>
		<a href="stats.php" class="nav">Your Stats</a>
		<a href="settings.php" class="nav">Settings</a>
		<a href="execution/logout.php" class="nav">Logout</a>
			
			<?php

}

// if the user isn't logged in, tell them so
else {	
		?>
		
		<a href="register.php" class="nav">Register</a>
		
		<?php

	}

?>
		
		<h2>What Is This?</h2>
		<p>AMpanic is a web app that helps people get up on time.</p>
		<p>To do this, the app uses the threat of social shame to motivate users to wake up.</p>
		<p>Each user sets up a "sleep-in" message that will go to the email address of their choosing (a friend, parent, boss, etc).</p>
		<p>AMpanic will send this message to the selected person at the time the user chooses. To prevent this from happening (and thus notifyinging said person of their laziness), the user must wake up before that time and tell AMpanic he/she is awake.</p>
		<p>Doing so will prevent the message from going out that day. The next day, the user will have to do the same thing - and so on, as long as their alarm is active.</p>
		<p>If you'd like to start using AMpanic to motivate yourself to wake up on time, start by clicking the Register button and signing up for an account!</p>
		
		<div class="bottomlinks">
			<a href="about.php">About</a>
			<a href="faq.php">FAQ</a>
			<a href="contact.php">Contact</a>
		</div>

	</div>
	
</body>

</html>