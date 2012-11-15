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
<meta name="keywords" content="email scheduling" />
<meta name="description" content="Schedule an email." />
<title>AMpanic - schedule an email</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="shortcut icon" href="img/favicon.png" type="image/x-icon" />
</head>

<body>

	<div id="container" class="jobs">
		<a href="index.php" class="nav">Scheduler</a>
		<a href="execution/logout.php" class="nav">Logout</a>
		<p>Current Time: 
			<?php 
				date_default_timezone_set('America/Chicago');

				$currentTime = date('H:i');

				echo $currentTime;
			?>
		</p>
		<p>Right now there 
			<?php

				require_once("includes/connect.php");

				$user = $_SESSION['username'];

				$result = mysql_query("SELECT * FROM emails WHERE user='$user'") or die("Couldn't select the table");

				$num_rows = mysql_num_rows($result);

				if ( $num_rows == 0 ) {
					echo "are no jobs";
				}
				else if ( $num_rows == 1 ) {
					echo "is one job";
				}
				else {
					echo "are " . $num_rows . " jobs";
				}
			?>
		  in the database.</p>
		<table border="1" <?php if ( $num_rows == 0 ) { ?> style="display: none;" <?php } ?>>
				<tr>
					<td>Record</td>
					<td>Email</td>
					<td>Message</td>
					<td>Time to send</td>
					<td>Timezone</td>
					<td>Time there now</td>
					<td>Sent?</td>
				</tr>
			
			<?php
			
			require_once("includes/connect.php");

			$user = $_SESSION['username'];
			
			$quer = "SELECT * FROM emails WHERE user='$user' ORDER BY record";
			
			$query = mysql_query($quer) or die (mysql_error());
			
			while ($row = mysql_fetch_array($query))
			{
				
				?>
					<tr>
						<td width="30px"><?php echo $row['record'] ?></td>
						<td width="200px"><?php echo $row['address'] ?></td>
						<td width="400px"><?php echo $row['message'] ?></td>
						<td width="130px"><?php echo $row['time'] ?></td>
						<td width="200px"><?php echo $row['timezone'] ?></td>
						<td width="130px">
							<?php 
								date_default_timezone_set($row['timezone']);

								$currentTime = date('H:i');

								echo $currentTime;
							?>
						</td>
						<td width="30px" 
						<?php
							if ($row['sent'] == "No") {
								echo "class=\"sent_no\"";
							}
							else {
								echo "class=\"sent_yes\"";
							}
						 ?>>
							<?php echo $row['sent'] ?>
						</td>
					</tr>
				<?php
			}
			
			?>
							
		</table>
		
	</div>
</body>

</html>
	
	<?php
}

else {
	echo 'You must be logged in to view this page. <a href="index.php">Return home</a>';
}
