<!DOCTYPE html>

<HTML lang="en">

<HEAD>

	<META charset="utf-8"/>
	<META name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1"/>
	<TITLE>Organized Events Page</TITLE>
	<LINK rel="stylesheet" href="styles/reset.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/notlogged.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/template.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/organized.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/table.css" type="text/css"/>
	<LINK rel="shortcut icon" href="images/events-calendar-icon.png"/>

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

</HEAD>

<BODY>

	<?php
     
		include "connect_db.php";
		
		$page_title = 'My Organized Events';
		echo '<H1>My Organized Events</H1>';

		if(!isset($_SESSION["pid"]))
		{

			echo '<DIV id="notlogged">';
			echo 'Hello, you are not logged in...';
			echo 'You will be redirected to the login page shortly.';
			echo '<META http-equiv="refresh" content="3; url=login.php"/>';
			echo '</DIV>';

		}
		
		elseif(isset($_POST["details"])) 	
		{
			
			if($stmt = $mysqli->prepare("select fname, lname, response
												from (person natural join invited) join event using (eid)
												where event.eid = ?"))
			{
				
				$stmt->bind_param("s", $_POST["eid"]);
				$stmt->execute();
				$stmt->bind_result( $fname, $lname, $response);
					
				if($stmt->fetch())
				{
							
					echo '<TABLE border="1">';
					echo '<TR>';
					echo '<TH>Name</TH>';
					echo '<TH>Status</TH>';
					echo '</TR>';
							
					do
					{
						
						$response = htmlspecialchars($response);
						$fname = htmlspecialchars($fname);
						$lname = htmlspecialchars($lname);
								
						echo '<TR>';
						echo '<TD>' . $fname . ' ' . $lname . '</TD>';
						
						if ($response == 1)
								
							echo '<TD> ACCEPTED </TD>';
								
						else if ($response == 2)
						
							echo '<TD> DECLINED </TD>';	
				
						else if($response == 0)
		
							echo '<TD> PENDING </TD>';
				
						echo '</TR>';
								
					}while($stmt->fetch()); 			
						
					echo '</TABLE>';
				
				}
							
				$stmt->close();
				$mysqli->close();	
				
			}

		}	
				
		else
		{		

			if($stmt = $mysqli->prepare("select eid, description, count(case when response = 1 then 1 end) as accepted, count(case when response = 2 then 1 end) 
										 as declined, count(case when response = 0 then 1 end) as pending
											from event left join invited using (eid)
											where event.pid = ? 
											group by description"))
			{

				$stmt->bind_param("s", $_SESSION["pid"]);
				$stmt->execute();
				$stmt->bind_result($eid, $description, $accepted, $declined, $pending);
						
				if($stmt->fetch())
				{
					
					echo '<TABLE border="1">';
					echo '<TR>';
					echo '<TH>Description</TH>';
					echo '<TH>Arriving Guests</TH>';
					echo '<TH>Declined Guests</TH>';
					echo '<TH>Pending Guests</TH>';
					echo '</TR>';
							
					do
					{
						
						$eid = (htmlspecialchars($eid));
						$description = (htmlspecialchars($description)); 
						$accepted = htmlspecialchars($accepted);
						$declined = htmlspecialchars($declined);
						$pending = htmlspecialchars($pending);

					    echo '<TR>';
					    echo '<TD>' . $description . '</TD>';
					    echo '<TD>' . $accepted . '</TD>';
						echo '<TD>' . $declined . '</TD>';
						echo '<TD>' . $pending . '</TD>';
						echo '<FORM action="myorganized.php" method="post">';
						echo "<TD> <input type=\"hidden\" name=\"eid\" value=\"$eid\"><class=\"submit\">";
						echo "<INPUT type=\"submit\" name=\"details\" value=\"More Details\">";   
						echo '</FORM></TD>';
					    echo '</TR>';
								
					}while($stmt->fetch()); 
						
					echo '</TABLE>';
					
				}
					
				else

					echo 'You have no organized events.';
						
			}

			$stmt->close();
			$mysqli->close();

		}
			
		echo '<FOOTER>';
		echo '<A href="index.php">Home Page</A>';
		echo '<A href="logout.php">Logout</A>';			
		echo '</FOOTER>';
		
	?>

</BODY>

</HTML>