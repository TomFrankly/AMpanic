<?php

require_once("../includes/connect.php");


$set_alarms = mysql_query("UPDATE wakeup SET setnow='Yes'");

?>