<?php

require_once("../includes/connect.php");

$quer = "SELECT * FROM emails ORDER BY time";

$query = mysql_query($quer) or dies (mysql_error());

while ($row = mysql_fetch_array($query))
{
	
	date_default_timezone_set($row['timezone']);

	$currentTime = date('H:i');

	if ($currentTime == $row['time']) {

		// send the email
		$to = $row['address'];
		$subject = "You've received an email from your good friend Tom";
		$headers = "From: admin@thomasjfrank.com";
		$server = "174.120.31.190";

		$body = $row['message'];

		mail($to, $subject, $body, $headers);

		// change the sent flag to yes

		$update = mysql_query("UPDATE emails SET sent = 'Yes' WHERE record= " . $row['record']);
	}

}

?>