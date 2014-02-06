<!DOCTYPE html>

<HTML lang="en">

<HEAD>

	<META charset="utf-8"/>
	<META name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1"/>
	<TITLE>Today's Schedule Page</TITLE>
	<LINK rel="stylesheet" href="styles/reset.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/notlogged.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/template.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/scheduletoday.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/table.css" type="text/css"/>
	<LINK rel="shortcut icon" href="images/events-calendar-icon.png"/>

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

</HEAD>

<BODY>

	<?php
	
		include "connect_db.php";
		
		$page_title = 'Today\'s Schedule';
		echo '<H1>Today\'s Schedule</H1>';
	
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

			/* Selects the start and end times, start and end dates,  and description  for events that the user has accepted for today. */
			
			if($stmt = $mysqli->prepare("select date(start_dt) as start_date, time(start_dt) as start_time, time(end_dt) as end_time , date(end_dt) as end_date, description
										from eventinfo natural join invited
										where (date(end_dt) >= curdate() and date(start_dt) <= curdate())
										and pid = ? and response = 1"))
				{

				$stmt->bind_param("s", $_SESSION["pid"]);
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
						 								
						$start_date = (htmlspecialchars($start_date)); 
						$start_time = htmlspecialchars($start_time);
						$end_time = htmlspecialchars($end_time);
						$end_date = (htmlspecialchars($end_date));
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
					
					echo 'You have no events scheduled for today!';
				
				}
			
			echo '<FOOTER>';
			echo '<A href="index.php">Home Page</A>';
			echo '<A href="logout.php">Logout</A>';
			echo '</FOOTER>';

		}
			
	?>

</BODY>

</HTML>