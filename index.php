<?php

	include "connect_db.php";

?><!DOCTYPE html>

<HTML lang="en">

<HEAD>

	<META charset="utf-8"/>
	<META name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1"/>
	<LINK rel="stylesheet" href="styles/reset.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/index.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/notlogged.css" type="text/css"/>
	<LINK rel="shortcut icon" href="images/events-calendar-icon.png"/>

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

</HEAD>

<BODY><?php

	/* Check if the user is not logged in. */

	if(!isset($_SESSION["pid"]))
	{

		echo '<DIV id="notlogged">';
		echo 'Hello, you are not logged in...<BR/>';
		echo 'Please <A id="login" href="login.php">login</A> to your account.</BR>';
		echo '</DIV>';

	}
		
	else
	{

		/* Convert pre-defined characters to HTML entities. */

		$fname = htmlspecialchars($_SESSION["fname"]);
		$lname = htmlspecialchars($_SESSION["lname"]);

		echo "<TITLE>$fname $lname's Homepage</TITLE>";					
			
		echo "<H1>$fname $lname's Homepage <IMG src='images/user.png'/></BR><A id='logout' href='logout.php'>Logout <IMG src='images/logout.png'/></A></H1>";

		echo '<DIV class="container">';
		echo '<DIV class="row">';
		echo '<DIV class="fourcol">';
		echo '<A href="myscheduletoday.php"><H3>Today\'s</BR>Schedule</H3></A>';
		echo '<A href="myschedule.php"><H3>Overall</BR>Schedule</H3></A>';
		echo '<A href="friendsschedule.php"><H3>Friend\'s Schedule</H3></A>';
		echo '</DIV>';
		echo '<DIV class="fourcol">';
		echo '<A href="myorganized.php"><H3>Organized</BR>Event</H3></A>';
		echo '<A href="organizeevent.php"><H3>Create</BR>Event</H3></A>';
		echo '</DIV>';
		echo '<DIV class="fourcol last">';
		echo '<A href="pendinginvites.php"><H3>Pending</BR>Invitations</H3></A>';
		echo '<A href="issueinvites.php"><H3>Send</BR>Invitations</H3></A>';
		echo '</DIV>';
		echo '</DIV>';
		echo '</DIV>';

		/* Menu Alternative */

		/* 
		
		echo '<NAV>';
		echo '<UL>';
		echo '<LI><A href="myscheduletoday.php">Today\'s Schedule</A></LI>';
		echo '<LI><A href="myschedule.php">Schedule</A></LI>';
		echo '<LI><A href="myorganized.php">Organized Events</A></LI>';
		echo '<LI><A href="pendinginvites.php">Pending Invitations</A></LI>';
		echo '<LI><A href="organizeevent.php">Create Event</A></LI>';
		echo '<LI><A href="issueinvites.php">Send Invitations</A></LI>';
		echo '<LI><A href="friendsschedule.php">Friend\'s Schedule</A></LI>';
		echo '</UL>';
		echo '</NAV>';

		*/

	}

?></BODY>

</HTML>