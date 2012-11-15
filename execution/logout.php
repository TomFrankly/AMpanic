<?php

// start and then destroy the session
session_start();

if (isset($_COOKIE[session_name()])) {
	setcookie(session_name(), "", time()-3600, "/");
}

$_SESSION = array();
session_destroy();

?>

<style type="text/css">

h2 {
	margin: 0 auto;
	font: Century Gothic;
	font-size: 3.5em;
	color: #8d8d8d;
	position: relative;
	left: -2px;
	margin-top: 150px;
	text-align: center;
}

#body {
	margin: 0 auto;
	border: 3px dashed #BA0202;
	padding: 15px;
	width: 350px;
}

</style>

<h2>Logged Out.</h2>
<div id="body">
<?php echo "You've been logged out. <a href=\"../index.php\">Click here</a> to return."; ?>


</div>



