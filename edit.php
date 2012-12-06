<?php

// start the session
session_start();


// check if the user is logged in
if (isset($_SESSION['username']))
{
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
		
	
		<a href="index.php" class="nav">Wake Up</a>
		<span class="nav">Create/Edit Alarm</span>
		<a href="stats.php" class="nav">Your Stats</a>
		<a href="settings.php" class="nav">Settings</a>
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

	$minute = mysql_real_escape_string(trim(strip_tags($_POST['minute'])));

	if ($hour == "00" && $minute == "00") {
		$minute = "05";
	}

	$time = $hour . ":" . $minute;

	$timezone = mysql_real_escape_string(trim(strip_tags($_POST['timezone'])));

	$user = $_SESSION['username'];

	// A tiny bit of security to make sure the database doesn't get overloaded
	$result = mysql_query("SELECT * FROM wakeup") or die("Couldn't select the table");
	$num_rows = mysql_num_rows($result);

	if ($num_rows < 1000) {

		// make sure the email they chose isn't on the disallowed list and that it's valid
		$offlimits_result = mysql_query("SELECT address FROM offlimits WHERE address='$address'") or die("Couldn't select the table");
		$offlimits_count = mysql_num_rows($offlimits_result);

		if ($offlimits_count == 0 && filter_var($address, FILTER_VALIDATE_EMAIL)) {

			// now figure out if the user already has an alarm set
			$user_result = mysql_query("SELECT * FROM wakeup") or die("Couldn't select the table");
			$user_num_rows = mysql_num_rows($user_result);

			// if they do, just alter the alarm with the new values
			if ($user_num_rows > 0) {

				$query = mysql_query("UPDATE wakeup SET address='$address', message='$message', time='$time', timezone='$timezone', setnow='Yes' WHERE user='$user'") or die("Couldn't insert data.");

				// Notify the user that the alarm has been EDITED
				?>
					<p class="notify">Your alarm has been edited. Make sure its status is set to ACTIVE below if you want it to work. If you set it for a time later than the current time, remember to tell AMpanic you're awake TODAY. Otherwise, your message will be sent today. <a href="edit.php">Dismiss</a></p>
				<?php

			}

			// otherwise create the alarm
			else {

				$query = mysql_query("INSERT INTO wakeup VALUES ('', '$address', '$message', '$time', '$timezone', '$user', 'Yes', 'Yes', 'No') ") or die("Couldn't insert data.");

				// Notify the user that the alarm has been CREATED
				?>
					<p class="notify">Your alarm has been set and is now active. If you set it for a time later than the current time, remember to tell AMpanic you're awake TODAY. Otherwise, your message will be sent today. <a href="edit.php">Dismiss</a></p>
				<?php

			}

		}

		else if ($offlimits_count == 0) {

			// Notify the user that they can't send an email to that particular address
			?>
				<p class="notify">Please enter a valid email address. <a href="edit.php">Dismiss</a></p>
			<?php

		}

		else {

			// Notify the user that they can't send an email to that particular address
			?>
				<p class="notify">The owner of that email address has opted to not receive emails from AMpanic. Please select another address. <a href="edit.php">Dismiss</a></p>
			<?php

		}

	}

	else {

		// Notify the user that the database is overloaded
		?>
			<p class="notify">Error: AMpanic is currently undergoing maintenance, so your alarm can't be set right now. Sorry about that. <a href="edit.php">Dismiss</a></p>
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

// This part handles alarm activation/deactivation
if (isset($_POST['deactivate'])) {
	require_once("includes/connect.php");
	$user_to_edit = $_SESSION['username'];
	$deactivate = mysql_query("UPDATE wakeup SET active ='No' WHERE user='$user_to_edit'");

	// Tell the user their alarm has been deactivated
	?>
		<p class="notify">Your alarm is now deactivated. You may go back to sleeping in, you lazy bum. <a href="edit.php">Dismiss</a></p>
	<?php
}

if (isset($_POST['activate'])) {
	require_once("includes/connect.php");
	$user_to_edit = $_SESSION['username'];
	$deactivate = mysql_query("UPDATE wakeup SET active ='Yes' WHERE user='$user_to_edit'");

	// Tell the user their alarm has been activated
	?>
		<p class="notify">Your alarm is now ACTIVE. Make sure you wake up BEFORE your set time so you can tell AMpanic you're awake. <a href="edit.php">Dismiss</a></p>
	<?php
}

// Get current alarm information from database, if there is any
require_once("includes/connect.php");
$current_user = $_SESSION['username'];
$val1 = "SELECT * FROM wakeup WHERE user='$current_user'";
$value1 = mysql_query($val1) or die (mysql_error());
$row = mysql_fetch_array($value1);
$user_time = explode(":", $row['time']);
$user_hour = $user_time[0];
$user_minute = $user_time[1];

?>

		<p style="padding: 8px; border: 1px solid #ddd;"><?php if ($row['address'] == "") {
			?>Looks like you haven't set your alarm yet. Use the form below to do so!<?php
		}
		else {
			?>Your current alarm settings are shown below. Edit whatever you'd like the change and then click Submit.<?php
		}
		?><br /><br />Remember - you need to wake up BEFORE the time you schedule your alarm message to go out so you have time to get to your computer and tell AMpanic you're awake!</p>
		<form action="edit.php" method="POST">
			<table>
		        <tr>
		            <td>
		            Address to send to:
		            </td>
		            <td>
		            <input type="text" name="address" size="45" value="<?php echo $row['address'] ?>"/>
		            </td>
		        </tr>
		        <tr>
		            <td>
		            Your message:
		            </td>
		            <td>
		            <textarea name="message" rows="4" cols="50"><?php echo $row['message'] ?></textarea>
		            </td>
		        </tr>
		        <tr>
		            <td>
		            When should it be sent?
		            </td>
		            <td>
		            	<table>
		            		<tr>
		            			<td>
									<select name="hour">
										<option value="01" <?php if ($user_hour == "01" || $user_hour == "13"): ?>selected="selected"<?php endif; ?>>1</option>
										<option value="02" <?php if ($user_hour == "02" || $user_hour == "14"): ?>selected="selected"<?php endif; ?>>2</option>
										<option value="03" <?php if ($user_hour == "03" || $user_hour == "15"): ?>selected="selected"<?php endif; ?>>3</option>
										<option value="04" <?php if ($user_hour == "04" || $user_hour == "16"): ?>selected="selected"<?php endif; ?>>4</option>
										<option value="05" <?php if ($user_hour == "05" || $user_hour == "17"): ?>selected="selected"<?php endif; ?>>5</option>
										<option value="06" <?php if ($user_hour == "06" || $user_hour == "18"): ?>selected="selected"<?php endif; ?>>6</option>
										<option value="07" <?php if ($user_hour == "07" || $user_hour == "19"): ?>selected="selected"<?php endif; ?>>7</option>
										<option value="08" <?php if ($user_hour == "08" || $user_hour == "20"): ?>selected="selected"<?php endif; ?>>8</option>
										<option value="09" <?php if ($user_hour == "09" || $user_hour == "21"): ?>selected="selected"<?php endif; ?>>9</option>
										<option value="10" <?php if ($user_hour == "10" || $user_hour == "22"): ?>selected="selected"<?php endif; ?>>10</option>
										<option value="11" <?php if ($user_hour == "11" || $user_hour == "23"): ?>selected="selected"<?php endif; ?>>11</option>
										<option value="12" <?php if ($user_hour == "12" || $user_hour == "00"): ?>selected="selected"<?php endif; ?>>12</option>
									</select>
								</td>
								<td>
									<select name="minute">
										<option value="00" <?php if ($user_minute == "00"): ?>selected="selected"<?php endif; ?>>00</option>
										<option value="05" <?php if ($user_minute == "05"): ?>selected="selected"<?php endif; ?>>05</option>
										<option value="10" <?php if ($user_minute == "10"): ?>selected="selected"<?php endif; ?>>10</option>
										<option value="15" <?php if ($user_minute == "15"): ?>selected="selected"<?php endif; ?>>15</option>
										<option value="20" <?php if ($user_minute == "20"): ?>selected="selected"<?php endif; ?>>20</option>
										<option value="25" <?php if ($user_minute == "25"): ?>selected="selected"<?php endif; ?>>25</option>
										<option value="30" <?php if ($user_minute == "30"): ?>selected="selected"<?php endif; ?>>30</option>
										<option value="35" <?php if ($user_minute == "35"): ?>selected="selected"<?php endif; ?>>35</option>
										<option value="40" <?php if ($user_minute == "40"): ?>selected="selected"<?php endif; ?>>40</option>
										<option value="45" <?php if ($user_minute == "45"): ?>selected="selected"<?php endif; ?>>45</option>
										<option value="50" <?php if ($user_minute == "50"): ?>selected="selected"<?php endif; ?>>50</option>
										<option value="55" <?php if ($user_minute == "55"): ?>selected="selected"<?php endif; ?>>55</option>
									</select>
								</td>
								<td>
									<select name="ampm">
										<option value="am" <?php 
										$am_times = array("00","01","02","03","04","05","06","07","08","09","10","11"); 
										if (in_array($user_hour, $am_times)): ?>selected="selected"<?php endif; ?>>AM</option>
										<option value="pm" <?php 
										$pm_times = array("12","13","14","15","16","17","18","19","20","21","22","23"); 
										if (in_array($user_hour, $pm_times)): ?>selected="selected"<?php endif; ?>>PM</option>
									</select>
								</td>
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
		            		<option value="Kwajalein" <?php if ($row['timezone'] == "Kwajalein"): ?>selected="selected"<?php endif; ?>>(GMT -12:00) Eniwetok, Kwajalein</option>
							<option value="Pacific/Midway" <?php if ($row['timezone'] == "Pacific/Midway"): ?>selected="selected"<?php endif; ?>>(GMT -11:00) Midway Island, Samoa</option>
							<option value="America/Adak" <?php if ($row['timezone'] == "America/Adak"): ?>selected="selected"<?php endif; ?>>(GMT -10:00) Hawaii</option>
							<option value="America/Anchorage" <?php if ($row['timezone'] == "America/Anchorage"): ?>selected="selected"<?php endif; ?>>(GMT -9:00) Alaska</option>
							<option value="America/Los_Angeles" <?php if ($row['timezone'] == "America/Los_Angeles"): ?>selected="selected"<?php endif; ?>>(GMT -8:00) Pacific Time (US &amp; Canada)</option>
							<option value="America/Denver" <?php if ($row['timezone'] == "America/Denver"): ?>selected="selected"<?php endif; ?>>(GMT -7:00) Mountain Time (US &amp; Canada)</option>
							<option value="America/Chicago" <?php if ($row['timezone'] == "America/Chicago"): ?>selected="selected"<?php endif; ?>>(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
							<option value="America/New_York" <?php if ($row['timezone'] == "America/New_York"): ?>selected="selected"<?php endif; ?>>(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
							<option value="America/Caracas" <?php if ($row['timezone'] == "America/Caracas"): ?>selected="selected"<?php endif; ?>>(GMT -4:30) Caracas</option>
							<option value="America/Barbados" <?php if ($row['timezone'] == "America/Barbados"): ?>selected="selected"<?php endif; ?>>(GMT -4:00) Atlantic Time (Canada), La Paz, Santiago</option>
							<option value="Canada/Newfoundland" <?php if ($row['timezone'] == "Canada/Newfoundland"): ?>selected="selected"<?php endif; ?>>(GMT -3:30) Newfoundland</option>
							<option value="America/Buenos_Aires" <?php if ($row['timezone'] == "America/Buenos_Aires"): ?>selected="selected"<?php endif; ?>>(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
							<option value="America/Noronha" <?php if ($row['timezone'] == "America/Noronha"): ?>selected="selected"<?php endif; ?>>(GMT -2:00) Mid-Atlantic</option>
							<option value="Atlantic/Cape_Verde" <?php if ($row['timezone'] == "Atlantic/Cape_Verde"): ?>selected="selected"<?php endif; ?>>(GMT -1:00 hour) Azores, Cape Verde Islands</option>
							<option value="Africa/Casablanca" <?php if ($row['timezone'] == "Africa/Casablanca"): ?>selected="selected"<?php endif; ?>>(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
							<option value="Europe/Copenhagen" <?php if ($row['timezone'] == "Europe/Copenhagen"): ?>selected="selected"<?php endif; ?>>(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
							<option value="Africa/Cairo" <?php if ($row['timezone'] == "Africa/Cairo"): ?>selected="selected"<?php endif; ?>>(GMT +2:00) Kaliningrad, South Africa, Cairo</option>
							<option value="Asia/Baghdad" <?php if ($row['timezone'] == "Asia/Baghdad"): ?>selected="selected"<?php endif; ?>>(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
							<option value="Asia/Tehran" <?php if ($row['timezone'] == "Asia/Tehran"): ?>selected="selected"<?php endif; ?>>(GMT +3:30) Tehran</option>
							<option value="Asia/Baku" <?php if ($row['timezone'] == "Asia/Baku"): ?>selected="selected"<?php endif; ?>>(GMT +4:00) Abu Dhabi, Muscat, Yerevan, Baku, Tbilisi</option>
							<option value="Asia/Kabul" <?php if ($row['timezone'] == "Asia/Kabul"): ?>selected="selected"<?php endif; ?>>(GMT +4:30) Kabul</option>
							<option value="Asia/Yekaterinburg" <?php if ($row['timezone'] == "Asia/Yekaterinburg"): ?>selected="selected"<?php endif; ?>>(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
							<option value="Asia/Calcutta" <?php if ($row['timezone'] == "Asia/Calcutta"): ?>selected="selected"<?php endif; ?>>(GMT +5:30) Mumbai, Kolkata, Chennai, New Delhi</option>
							<option value="Asia/Katmandu" <?php if ($row['timezone'] == "Asia/Katmandu"): ?>selected="selected"<?php endif; ?>>(GMT +5:45) Kathmandu</option>
							<option value="Asia/Dhaka" <?php if ($row['timezone'] == "Asia/Dhaka"): ?>selected="selected"<?php endif; ?>>(GMT +6:00) Almaty, Dhaka, Colombo</option>
							<option value="Indian/Cocos" <?php if ($row['timezone'] == "Indian/Cocos"): ?>selected="selected"<?php endif; ?>>(GMT +6:30) Yangon, Cocos Islands</option>
							<option value="Asia/Bangkok" <?php if ($row['timezone'] == "Asia/Bangkok"): ?>selected="selected"<?php endif; ?>>(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
							<option value="Asia/Singapore" <?php if ($row['timezone'] == "Asia/Singapore"): ?>selected="selected"<?php endif; ?>>(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
							<option value="Asia/Tokyo" <?php if ($row['timezone'] == "Asia/Tokyo"): ?>selected="selected"<?php endif; ?>>(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
							<option value="Australia/Darwin" <?php if ($row['timezone'] == "Australia/Darwin"): ?>selected="selected"<?php endif; ?>>(GMT +9:30) Adelaide, Darwin</option>
							<option value="Australia/Melbourne" <?php if ($row['timezone'] == "Australia/Melbourne"): ?>selected="selected"<?php endif; ?>>(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
							<option value="Asia/Magadan" <?php if ($row['timezone'] == "Asia/Magadan"): ?>selected="selected"<?php endif; ?>>(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
							<option value="Asia/Kamchatka" <?php if ($row['timezone'] == "Asia/Kamchatka"): ?>selected="selected"<?php endif; ?>>(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
						</select>
		            </td>
		        </tr>
		    </table>
		    <p></p>
            <input type="submit" name="submit" value="Submit" />
		</form>
		<?php if ($row['message'] != "") {
			?><p>Current Alarm Status: 
				<?php 
				if ($row['active'] == "Yes") {
					?><span style="font-weight: bold; color: #7DF087">ACTIVE</span></p>
					<form action="edit.php" method="POST"><input type="submit" name="deactivate" value="Deactivate" /></form><?php
				}
				else {
					?><span style="font-weight: bold; color: #FF4642">INACTIVE</span></p>
					<form action="edit.php" method="POST"><input type="submit" name="activate" value="Activate" /></form><?php
				}

			?><p style="padding: 8px; border: 1px solid #ddd;">If your alarm is set to Active, AMpanic will set it every night at midnight and you'll be required to wake up the next morning and tell it you're awake to prevent the message from being sent.
			<br /><br />If the alarm is set to Inactive, AMpanic will not set it and the message will never go out.</p><?php
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
<?php

}

// if the user isn't logged in, tell them so
else {
	echo 'You must be logged in to view this page. <a href="index.php">Return home</a>';
}