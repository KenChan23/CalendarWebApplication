<!DOCTYPE html>

<HTML lang="en">

<HEAD>

	<META charset="utf-8"/>
	<META name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1"/>
	<TITLE>Pending Invites Page</TITLE>
	<LINK rel="stylesheet" href="styles/reset.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/notlogged.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/template.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/pending.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/table.css" type="text/css"/>
	<LINK rel="shortcut icon" href="images/events-calendar-icon.png"/>

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

</HEAD>

<BODY>

	<?php
	
		include "connect_db.php";
		
		$page_title = 'Pending Invitations';
		echo '<H1>Pending Invitations</H1>';
     
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

			if(isset($_POST["response"]) && isset($_POST["level"])) 	
			{
			
				if($stmt = $mysqli->prepare("update invited set response = ?, visibility = ? where pid = ? and eid = ?;"))
				{

				      $stmt->bind_param("ssss", $_POST["response"],  $_POST["level"], $_SESSION["pid"], $_POST["eid"]);
					  $stmt->execute();
					  $stmt->close();
					  echo '<META http-equiv="refresh" content="0">';

				}

			}
			
			else
			{	

				/* Selects the start and end times, start and end dates, and description of events that the user has been invited to, but has not responded to yet. */
				
				if($stmt = $mysqli->prepare("select eid, date(start_dt) as start_date, time(start_dt) as start_time, time(end_dt) as end_time , date(end_dt) as end_date, description
												  from eventinfo natural join invited 
												  where response = 0 and pid = ?"))
				{

					$stmt->bind_param("s", $_SESSION["pid"]);
					$stmt->execute();
					$stmt->bind_result($eid, $start_date, $start_time, $end_time, $end_date, $description);
								
					if($stmt->fetch())
					{ 
							   
					    echo 'If you wish to respond to an event that has several dates, please input your response in only one of the rows.<BR/>'; 
						echo 'The others will  subsequently be updated as well.<BR/><BR/>';
						echo 'Please update only one event at a time';
						echo '<BR/><BR/>';
						echo '<TABLE border="3">';
						echo '<TR>';
						echo '<TH>Start Date</TH>';
						echo '<TH>Start Time</TH>';
						echo '<TH>End Time</TH>';
						echo '<TH>End Date</TH>';
						echo '<TH>Description</TH>';
						echo '<TH>Accept Invite</TH>';
						echo '<TH>Privacy Level</TH>';
						echo '</TR>';
	
						do
						{
								
							$eid = htmlspecialchars($eid);
							$start_date = htmlspecialchars($start_date); 
							$start_time = htmlspecialchars($start_time);
							$end_time = htmlspecialchars($end_time);
							$end_date = htmlspecialchars($end_date);
							$description = htmlspecialchars($description);
									
							$start_time = date("g:i a", strtotime("$start_time"));
							$end_time = date("g:i a", strtotime("$end_time"));
							$start_date = date("M jS, Y", strtotime("$start_date")); 
							$end_date = date("M jS, Y", strtotime("$end_date")); 

							echo '<TR>';
							echo '<TD>' . $start_date . '</TD>';
							echo '<TD>' . $start_time . '</TD>';
							echo '<TD>' . $end_time . '</TD>';
							echo '<TD>' . $end_date . '</TD>';
							echo '<TD>' . $description . '</TD>';
							echo '<FORM action="pendinginvites.php" method="post">';
							echo "<TD><INPUT type=\"radio\" name=\"response\" value=\"1\">Accept"; 
							echo "<INPUT type=\"radio\" name=\"response\" value=\"2\">Decline";	
							echo "<INPUT type=\"hidden\" name=\"eid\" value=\"$eid\"></TD>";							
							echo "<TD><INPUT type=\"number\" name=\"level\" min =\"1\"  max= \"10\" required></TD>";											 
							echo "<TD><class=\"submit\"> <INPUT type=\"submit\" name=\"submission\" value=\"Update\"></FORM></TD>";
							echo '</TR>';	
										
						}while($stmt->fetch());
								
						echo '</TABLE>';
						
					}
							
					else
						
						echo 'You have no more pending invitations!';
													
				}

				$stmt->close();
				$mysqli->close();
			
			}	
			
			echo '<FOOTER>';
			echo '<A href="index.php">Home Page</A>';
			echo '<A href="logout.php">Logout</A>';
			echo '</FOOTER>';
					
		}			
        
	?>

</BODY>

</HTML>