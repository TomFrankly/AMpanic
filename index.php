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
	
		<span class="nav">Wake Up</span>
		<a href="edit.php" class="nav">Create/Edit Alarm</a>
		<a href="stats.php" class="nav">Your Stats</a>
		<a href="settings.php" class="nav">Settings</a>
		<a href="execution/logout.php" class="nav">Logout</a>
	
		<?php

		// Starts the script when the user submits an email
		$submit = $_POST['submit'];
		$user = $_SESSION['username'];

		// connect to database
		require_once "includes/connect.php";

		if ($submit) {

			// Tell the database that the user woke up
			$query = mysql_query("UPDATE wakeup SET setnow='No' WHERE user='$user'") or die("Couldn't insert data.");

			// Congratulate user for waking up on time
			?>
				<p class="notify">You're officially awake on time - congrats! You can check your history of wake-up times at the Stats page. <a href="index.php">Dismiss</a></p>
			<?php

		}

		// Let's see if the current time is before the user's wake up time
		$time_query = mysql_query("SELECT * FROM wakeup WHERE user='$user'") or die ("Couldn't get data.");
		$row = mysql_fetch_array($time_query);
		date_default_timezone_set($row['timezone']);
		$user_time = explode(":", $row['time']);
		$user_hour = $user_time[0];
		$user_minute = $user_time[1];
		$current_hour = date('H');
		$current_minute = date('i');

		if (($current_hour < $user_hour) || ($current_hour == $user_hour && $current_minute < $user_minute)) {
			
			if ($row['setnow'] == "Yes") {	
				?>
					<h2>Greetings, <?php echo $row['user'] ?>!</h2>
					<p>Hit the "I'm Awake" button to tell AMpanic you're up so your wake-up message won't be sent!</p>
					<form action="index.php" method="POST">
						<input type="submit" name="submit" id="AwakeButton" value="I'm Awake" />
					</form>
				<?php
			}
			else {
				?>
					<h2>Greetings, <?php echo $row['user'] ?>!</h2>
					<p>Current Status: Awake and ready to go!</p>
				<?php
			}

		}
		else {
			if ($row['senttoday'] == "Yes") {
				?>
					<h2>Greetings, Lazy!</h2>
					<p>Looks like you FAILED to wake up on time today. I went ahead and sent your message to your friend, so you might have some 'splaining to do later! Better get up on time tomorrow!</p>
				<?php
			}
			else {
				?>
					<h2>Greetings, <?php echo $row['user'] ?>!</h2>
					<p>My records show you woke up on time today! Make sure you come back tomorrow and do the same!</p>
				<?php
			}
		}

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
		<div class="bottomlinks">
			<a href="about.php">About</a>
			<a href="faq.php">FAQ</a>
			<a href="contact.php">Contact</a>
		</div>
	</div>
	
</body>

</html>