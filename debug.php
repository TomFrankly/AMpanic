<table>

<?php

require_once("includes/connect.php");

$update = mysql_query("UPDATE emails SET sent='Yes' WHERE record='35'");

$quer = "SELECT * FROM emails ORDER BY record";

$query = mysql_query($quer) or dies (mysql_error());

while ($row = mysql_fetch_array($query))
{
	
	?>
		<tr>
			<td><?php echo $row['record'] ?></td>
			<td><?php echo $row['sent'] ?></td>
		</tr>
	<?php

}

?>

</table>