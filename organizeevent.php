<!DOCTYPE html>

<HTML lang="en">

<HEAD>

	<META charset="utf-8"/>
	<META name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1"/>
	<TITLE>Create Event Page</TITLE>
	<LINK rel="stylesheet" href="styles/reset.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/notlogged.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/template.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/create.css" type="text/css"/>
	<LINK rel="shortcut icon" href="images/events-calendar-icon.png"/>

</HEAD>

<BODY>
	
	<?php 

		$page_title = 'Organize Event';
		echo '<H1>Organize an Event</H1>';
		echo '<P>Please enter times in the following format: HH:MM:SS</P>';
		echo '<P>The first Date entry is required. The others are optional.</P>';

		include "connect_db.php";

		if(!isset($_SESSION["pid"]))
		{

			echo '<DIV id="notlogged">';
			echo 'Hello, you are not logged in...';
			echo 'You will be redirected to the login page shortly.';
			echo '<META http-equiv="refresh" content="3; url=login.php"/>';
			echo '</DIV>';

		}

		else 
		{

			/* Check if the form has been submitted. */

			if(isset($_POST["starttime"]) && isset($_POST["duration"]) && isset($_POST["description"]) && ((isset($_POST["date1"])) || (isset($_POST["date2"])) || (isset($_POST["date3"]))))
			{

				/* Inserts the data that the user has entered into the event table. */
			
				if($stmt = $mysqli->prepare("insert into event (start_time, duration, description, pid) values (?, ?, ?, ?);"))   
				{								
			
					$stmt->bind_param("ssss",  $_POST["starttime"],  $_POST["duration"], $_POST["description"], $_SESSION["pid"]);
					$stmt->execute();
					$stmt->close();
					$eid = $mysqli->insert_id;
							
				}
					
				if((isset($_POST["date1"])) && (!empty($_POST["date1"])))
				{
					
					/* Make the query. */
					
					if($stmt = $mysqli->prepare("insert into eventdate (eid, edate) values (?, ?);"))   
					{			  
						
						$stmt->bind_param("ss",  $eid,  $_POST["date1"]);
						$stmt->execute();	   
							   
					}

				}
		
				if((isset($_POST["date2"])) && (!empty($_POST["date2"])))
				{

					/* Make the query. */

				 	if($stmt = $mysqli->prepare("insert into eventdate (eid, edate) values (?, ?);"))   
					{	

						$stmt->bind_param("ss",  $eid,  $_POST["date2"]);
						$stmt->execute();
							  	   
					}

				}	
		
				if((isset($_POST["date3"])) && (!empty($_POST["date3"])))
				{

					/* Make the query. */
					  	
				  	if($stmt = $mysqli->prepare("insert into eventdate (eid, edate) values (?, ?);"))   
					{

						$stmt->bind_param("ss",  $eid,  $_POST["date3"]);
					   $stmt->execute();	  
						
					}

				}
					
				if($stmt = $mysqli->prepare("insert into invited (pid, eid, response, visibility) values (?, ?, 1, ?);"))   
				{		
						
					/* $eid = $mysqli->insert_id; */
						
					$stmt->bind_param("sss", $_SESSION["pid"], $eid,  $_SESSION["d_privacy"]);
				   	$stmt->execute();
						
				}
							
				$stmt->close();
				$mysqli->close();
					
				echo '<META http-equiv="refresh" content="2; url=organizeevent.php"/>';
				echo 'You have successfully organized an event.<BR/>';

			}	

			else
			{
	  
				echo '<FORM action="organizeevent.php" method="post">';
				echo '<P>Event Description: <INPUT type="text" name="description" value="" / required></P>';
				echo '<P>Start Time:<INPUT type="time" name="starttime" value="" placeholder="Start Time"/ required></P>';	
				echo '<P>Duration:<INPUT type="text" name="duration" value="" / required></P>';	
				echo '<P>Date 1<INPUT type="date" name="date1" value="" placeholder="Date 1"/ required></P>';	
				echo '<P>Date 2<INPUT type="date" name="date2" value="" placeholder="Date 2"/></P>';	
				echo '<P>Date 3<INPUT type="date" name="date3" value="" placeholder="Date 3"/></P>';							
				echo '<P class="submit"><INPUT type="submit" name="submission" value="Create"/></P>';
				echo '</FORM>';

			}	
			
		}
		
	    echo '<FOOTER>';
		echo '<A href="index.php">Home Page</A>';
		echo '<A href="logout.php">Logout</A>';
		echo '</FOOTER>';
	
	?>

</BODY>

</HTML>