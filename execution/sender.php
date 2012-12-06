<?php

require_once("../includes/connect.php");

$quer = "SELECT * FROM wakeup ORDER BY time";

$query = mysql_query($quer) or dies (mysql_error());

while ($row = mysql_fetch_array($query))
{
	
	date_default_timezone_set($row['timezone']);

	$currentTime = date('H:i');

	if ($currentTime == $row['time']) {

		if ($row['active'] == "Yes" && $row['set'] == "Yes") {	

			// send the email
			$to = $row['address'];
			$subject = "Alert: " . $row['user'] . " has failed to wake up on time!";
			$headers = "From: admin@thomasjfrank.com";
			$server = "174.120.31.190";

			$body = $row['message'] . "\n\n 

			Note: This message was sent from AMpanic, a web app that enables people to force themselves to wake up on time by scheduling an email to a friend that they have to wake up and deactivate. Since you received this, that means your friend didn't wake up on time today. Maybe you should let them know how you feel about that?\n\n

			<a href=\"http://thomasjfrank.com/AMpanic2/about.php\">Click here</a> to learn more about AMpanic. If you would like to stop receiving emails from AMpanic, you can <a href=\"http://thomasjfrank.com/AMpanic2/unsubscribe.php\">disallow your email address</a>.

			";

			mail($to, $subject, $body, $headers);

		}


	}

}

?>