<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="keywords" content="email scheduling" />
<meta name="description" content="Schedule an email." />
<title>PopFly - schedule an email</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="shortcut icon" href="img/favicon.png" type="image/x-icon" />
</head>

<body>

	<div id="container" class="jobs">
		<a href="index.php" class="nav">Scheduler</a>
		<p>Current Time: 
			<?php 
				date_default_timezone_set('America/Chicago');

				$currentTime = date('H:i');

				echo $currentTime;
			?>
		</p>
		<p>Right now there are 
			<?php

				require_once("includes/connect.php");

				$result = mysql_query("SELECT * FROM emails") or die("Couldn't select the table");

				$num_rows = mysql_num_rows($result);

				echo $num_rows;
			?>
		 jobs in the database.</p>
		<table border="1">
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
			
			$quer = "SELECT * FROM emails ORDER BY record";
			
			$query = mysql_query($quer) or dies (mysql_error());
			
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