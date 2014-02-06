<!DOCTYPE html>

<HTML lang="en">

<HEAD>

	<META charset="utf-8"/>
	<META name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1"/>
	<TITLE>Friend's Schedule Page</TITLE>
	<LINK rel="stylesheet" href="styles/reset.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/notlogged.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/template.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/friends.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/table.css" type="text/css"/>
	<LINK rel="shortcut icon" href="images/events-calendar-icon.png"/>

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

</HEAD>

<BODY>

	<?php
     
		include "connect_db.php";
		
		$page_title = "Friend's Schedules'";
		echo '<H1>My Friend\'s Schedule</H1>';
	
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

			if(isset($_POST["date"])) 	
			{
			
				/*	

					Selects information about events that the friend the user selected has on a user-selected day (information about all events are selected even though the description of events
					the user does not have permission for won't be displayed that will be filtered in later PHP code).
				
				*/

				if($stmt = $mysqli->prepare("select date(start_dt) as start_date, time(start_dt) as start_time, time(end_dt) as end_time , date(end_dt) as end_date, description, visibility
											 from (eventinfo natural join invited) join friend_of on pid = sharer
											 where sharer = ? and  viewer = ? and (date(end_dt) >= ? and date(start_dt) <= ?) and response = 1"))
				{

					$stmt->bind_param("ssss", $_POST["sharer"], $_SESSION["pid"], $_POST["date"],  $_POST["date"]);
					$stmt->execute();
					$stmt->bind_result($start_date, $start_time, $end_time, $end_date, $description, $visibility);
					 
					if($stmt->fetch())
					{ 
						
						echo '<DIV>';
						echo '<TABLE border="1">';
						echo '<TR>';
						echo '<TH>Start Date</TH>';
						echo '<TH>Start Time</TH>';
						echo '<TH>End Time</TH>';
						echo '<TH>End Date</TH>';
						echo '<TH>Description</TH>';
						echo '</TR>';
						
						do 
						{
								
							$sharer = $_POST["sharer"];
							$level = $_POST["level"];
							$sharer = (htmlspecialchars($sharer));
							$level = (htmlspecialchars($level));
							$start_date = (htmlspecialchars($start_date)); 
							$start_time = htmlspecialchars($start_time);
							$end_time = htmlspecialchars($end_time);
							$end_date = (htmlspecialchars($end_date));
							$description = htmlspecialchars($description);
							$visibility = htmlspecialchars($visibility);
										
							$start_time = date("g:i a", strtotime("$start_time"));
							$end_time = date("g:i a", strtotime("$end_time"));
							$start_date = date("M jS, Y", strtotime("$start_date")); 
							$end_date = date("M jS, Y", strtotime("$end_date")); 
								
							echo '<TR>';
							echo '<TD>' . $start_date . '</TD>';
							echo '<TD>' . $start_time . '</TD>';
							echo '<TD>' . $end_time . '</TD>';										
							echo '<TD>' . $end_date . '</TD>';
									
							if($visibility < $level)
										
								echo '<TD>' . $description . '</TD>';
										
							else
											
								echo '<TD> BUSY </TD>';
										
							echo '</TR>';

						}while($stmt->fetch());
								
						echo "</TABLE>";  

					}
							
					else
					{

						echo '<META http-equiv="refresh" content="2; url=friendsschedule.php"/>';
						echo 'Nothing special happening that day.';
							
					}
							
					$stmt->close();				
					$mysqli->close();
				
				}		

			}
			
			else
			{	

				/* Selects people with whom the user is friends with. */

				if($stmt = $mysqli->prepare("select sharer, level, fname, lname 
											from friend_of  join person takes on sharer = pid 
											where viewer = ?"))
				{

					$stmt->bind_param("s", $_SESSION["pid"]);
					$stmt->execute();
					$stmt->bind_result($sharer, $level, $fname, $lname);
								
					if($stmt->fetch())
					{ 
						
						echo '<P>Please enter dates in the following format: YYYY-MM-DD</P>';
						echo '<BR/><BR/>';
						echo '<TABLE border="3">';
						echo '<TR>';
						echo '<TH>Friend</TH>';
						echo '<TH>Date</TH>';
						echo '</TR>';
	
								
						do
						{
								
							$sharer = htmlspecialchars($sharer);
							$level = htmlspecialchars($level);
							$fname = htmlspecialchars($fname);
							$lname = htmlspecialchars($lname);
									
									
							echo '<TR>';
							echo '<TD>' . $fname . " " . $lname . '</TD>';
							echo '<FORM action="friendsschedule.php" method="post">';
							echo "<TD><INPUT type = \"date\"  name = \"date\" value=\"\"  /required>";
							echo "<INPUT type=\"hidden\" name=\"sharer\" value=\"$sharer\">";  
							echo "<INPUT type=\"hidden\" name=\"level\" value=\"$level\"> </TD>";	
							echo "<TD> <class=\"submit\"> <INPUT type=\"submit\" name=\"submission\" value=\"View\"></FORM></TD>";															
							echo '</TR>';	
										
						}while($stmt->fetch());
								
						echo '</TABLE>';
						echo '</DIV>';

					}
							
					else
					{
							
						echo 'You have no friends! YAY!';

					}

					$stmt->close();
					$mysqli->close();
									
				}

			}	
			
			echo '<FOOTER>';
			echo '<A href="index.php">Home Page</A>';
			echo '<A href="logout.php">Logout</A>';
			echo '</FOOTER>';
				
		}		
				
	?>

</BODY>

</HTML>