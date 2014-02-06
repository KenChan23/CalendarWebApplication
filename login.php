<?php

	include "connect_db.php";
		
?><!DOCTYPE html>

<HTML lang="en">

<HEAD>

	<META charset="utf-8"/>
	<META name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1"/>
	<TITLE>Login Page</TITLE>
	<LINK rel="stylesheet" href="styles/reset.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/login.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/success.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/failure.css" type="text/css"/>
 	<LINK rel="shortcut icon" href="images/events-calendar-icon.png"/>
	
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<SCRIPT type="text/javascript" src="scripts/countdown.js"></SCRIPT>

</HEAD>

<BODY><?php

	/* Check if the user is already logged in. */

	if(isset($_SESSION["pid"]))
	{

		echo '<META http-equiv="refresh" content="3; url=index.php"/>';
		echo '<DIV class="logged_in">';
		echo 'You are already logged in.';
		echo '<BR/>You will be redirected in 3 seconds or click <a href="index.php">here</a>.<BR/>';
		echo '</DIV>';
		
	}

	else
	{

		/* Check if the user inputted data into the two input fields. */

		if(isset($_POST["pid"]) && isset($_POST["passwd"]))
		{

			if($stmt = $mysqli->prepare("select * from person where pid = ? and passwd = md5(?)"))
			{

				$stmt->bind_param("ss", $_POST["pid"], $_POST["passwd"]);
				$stmt->execute();
				$stmt->bind_result($pid, $passwd, $fname, $lname, $d_privacy);

				/* Login is a success. */

				if($stmt->fetch())
				{

					$_SESSION["pid"] = $pid;
					$_SESSION["passwd"] = $passwd;
					$_SESSION["fname"] = $fname;
					$_SESSION["lname"] = $lname;
					$_SESSION["d_privacy"] = $d_privacy;
					$_SESSION["REMOTE_ADDR"] = $_SERVER["REMOTE_ADDR"];

					/* Ensure session global variables contain proper values. */
					/* printf('%s%s%s%s%u', $_SESSION["pid"], $_SESSION["passwd"], $_SESSION["fname"], $_SESSION["lname"], $_SESSION["d_privacy"]); */

					/* Javascript employed to dynamically display the countdown until refresh. */

					echo '<META http-equiv="refresh" content="3; url=index.php"/>';
					echo '<DIV class="success">';
					echo '<H1><IMG src="images/check-mark.png"/></H1>';
					echo '<P>Login successful.</P>';
					echo '<BR/><P>You will be redirected in <SPAN id="countdown">3</SPAN> seconds or click <A href="index.php">here</A>.</P>';
					echo '</DIV>';
							
				}

				/* Login is a failure. */

				else
				{

					sleep(1);
					echo '<DIV class="failure">';
					echo '<H1><IMG src="images/x-mark.png"/></H1>';
					echo '<P>Your ID or password is incorrect.</P>';
					echo '<P>Click <A href="login.php">here</A> to try again.</P>';
					echo '</DIV>';

				}

				$stmt->close();
				$mysqli->close();

			}

		}

		else
		{

			echo '<DIV class="container">';
			echo '<DIV class="login">';
			echo '<H1>Login to WebCal Application <IMG src="images/events-calendar-icon.png"/></H1>';
			echo '<FORM action="login.php" method="POST">';
			echo '<P><INPUT type="text" name="pid" value="" placeholder="ID"/></P>';
			echo '<P><INPUT type="password" name="passwd" value="" placeholder="Password"/></P>';
			echo '<P class="submit"><INPUT type="submit" name="submission" value="submit"/></P>';
		    echo '</FORM>';
		    echo '</DIV>';
		    echo '</DIV>';
			echo '<FOOTER>';
			echo '<A href="index.php">Go Back to Home Page</A>';
		    echo '</BR>CS308: Database Project';
		    echo '</BR>Efraiyim Zitron and Ken Chan';
		    echo '</FOOTER>';

		}

	}

?></BODY>

</HTML>