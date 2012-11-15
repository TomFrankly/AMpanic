<?php

// start the session
session_start();


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="keywords" content="email scheduling" />
<meta name="description" content="Schedule an email." />
<title>PopFly - schedule an email</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link href='http://fonts.googleapis.com/css?family=Racing+Sans+One' rel='stylesheet' type='text/css'>
<link rel="shortcut icon" href="img/favicon.png" type="image/x-icon" />
</head>

<body>

	<div id="container" class="index">
	
		<h1 class="title">AMpanic</h1>
		<a href="index.php"><span class="sub">A Simple Email Scheduler</span></a>
		
		<?php

				// check if the user is logged in
		if (isset($_SESSION['username']))
		{
			
			?>
	
		<a href="jobs.php" class="nav">Scheduled Jobs</a>
	
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
			$hour = $_POST['hour'];
		}

	}
	else {
		if ( $_POST['hour'] != 12) {

			$hour = $_POST['hour']+12;
		}
		else {
			$hour = 12;
		}
	}

	$time = $hour . ":" . $_POST['minute'];

	$timezone = mysql_real_escape_string(trim(strip_tags($_POST['timezone'])));

	// A tiny bit of security to make sure the database doesn't get overloaded
	$result = mysql_query("SELECT * FROM emails") or die("Couldn't select the table");

	$num_rows = mysql_num_rows($result);

	// insert data into database
	if ($num_rows < 100) {
		$query = mysql_query("INSERT INTO emails VALUES ('', '$address', '$message', '$time', '$timezone', 'No') ") or die("Couldn't insert data.");
	}

	// Notify the user that the email has been scheduled
	?>
		<p class="notify">Your email has been scheduled!</p>
	<?php

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