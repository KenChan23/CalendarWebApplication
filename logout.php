<!DOCTYPE html>

<HTML lang="en">

<HEAD>

	<META charset="utf-8"/>
	<META name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1"/>
	<TITLE>Logout Page</TITLE>
	<LINK rel="stylesheet" href="styles/reset.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/logout.css" type="text/css"/>
	<LINK rel="shortcut icon" href="images/events-calendar-icon.png"/>

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<SCRIPT type="text/javascript" src="scripts/countdown.js"></SCRIPT>

</HEAD>

<BODY>

	<?php

		session_start();
		session_destroy();
		
		echo '<DIV class="logout">';
		echo '<META http-equiv="refresh" content="3; url=index.php"/>';
		echo 'You are logged out. You will be redirected in <SPAN id="countdown">3</SPAN> seconds.';
		echo '</DIV>';  

	?>

</BODY>

</HTML>