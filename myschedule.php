<!DOCTYPE html>

<HTML lang="en">

<HEAD>

	<META charset="utf-8"/>
	<META name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1"/>
	<TITLE>Overall Schedule Page</TITLE>
	<LINK rel="stylesheet" href="styles/reset.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/notlogged.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/template.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/schedule.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/table.css" type="text/css"/>
	<LINK rel="shortcut icon" href="images/events-calendar-icon.png"/>

</HEAD>

<BODY>

	<?php 

		$page_title = 'View Schedule';
		echo '<h1>View Schedule</h1>';

		include "connect_db.php";

		if(!isset($_SESSION["pid"]))
		{

			echo '<DIV id="notlogged">';
			echo 'Hello, you are not logged in...';
			echo 'You will be redirected to the login page shortly.';
			echo '<META http-equiv="refresh" content="3; url=login.php"/>';
			echo '</DIV>';

		}

		else {

			/* Check if the form has been submitted. */

			if(isset($_POST["fromdate"]) && isset($_POST["todate"]))
			{

				// Make the query. */

			    if($stmt = $mysqli->prepare("select date(start_dt) as start_date, time(start_dt) as start_time, time(end_dt) as end_time , date(end_dt) as end_date, description
														from eventinfo natural join invited
														where (date(end_dt) >= ?  and date(start_dt) <= ?)
														and pid = ? and response = 1"))   
				{		

 					$stmt->bind_param("sss",  $_POST["fromdate"],  $_POST["todate"], $_SESSION["pid"]);					
 					$stmt->execute();
					$stmt->bind_result($start_date, $start_time, $end_time, $end_date, $description);

					if($stmt->fetch())
					{ 
					    
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
							echo '</TR>';
							
						}while($stmt->fetch());
										
						echo '</TABLE>';  
										
						$stmt->close();				
						$mysqli->close();
									
					}
									
					else
					{
						
						echo '<META http-equiv="refresh" content="2; url=myschedule.php"/>';
					    echo 'You have no events scheduled during this time period!';
						
				    }
						
				}

			}
							
			else
			{
		  
				echo '<FORM action="myschedule.php" method="post">';
				echo '<P>FROM: <INPUT type="date" name="fromdate" value="" /required></P>';
				echo '<P>TO:  <INPUT type="date" name="todate" value="" /required></P>';						
				echo '<P class="submit"><INPUT type="submit" name="submission" value="View"/></P>';
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