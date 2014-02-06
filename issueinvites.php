<!DOCTYPE html>

<HTML lang="en">

<HEAD>

	<META charset="utf-8"/>
	<META name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1"/>
	<TITLE>Issue Invitation Page</TITLE>
	<LINK rel="stylesheet" href="styles/reset.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/notlogged.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/template.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/invitations.css" type="text/css"/>
	<LINK rel="stylesheet" href="styles/table.css" type="text/css"/>
	<LINK rel="shortcut icon" href="images/events-calendar-icon.png"/>

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

</HEAD>

<BODY>

	<?php
	
	    $page_title = 'Issue Invitations';
		echo '<H1>Issue Invitations</H1>';
     
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
		
			if(isset($_POST["submission"])) 	
			{

				/* Selects people who have not been invited to the event the user has selected. */
				
				if($stmt = $mysqli->prepare("select distinct pid, fname, lname
											from person 
											where  pid not in (select pid from eventinfo natural join invited where eid = ?)"))
				{

				    $stmt->bind_param("s", $_POST["eid"]);
					$stmt->execute();
					$stmt->bind_result($pid, $fname, $lname);

					/* Refresh page. */
					/* echo '<meta http-equiv="refresh" content="0">'; */
					  
					if($stmt->fetch())
					{
						
					    echo '<FORM action="issueinvites.php" method="post">';
						echo '<TABLE border="1">';
						echo '<TR>';
						echo '<TH>Name</TH>';
						echo '</TR>';
						
					    do
					    {

							$eid =$_POST["eid"];
					        $eid = htmlspecialchars($eid);
							$pid = (htmlspecialchars($pid)); 
							$fname = htmlspecialchars($fname);
							$lname = htmlspecialchars($lname);		
						
							echo '<TR>';
							echo '<TD>' . $fname . ' ' . $lname . '</TD>';
								
							echo "<TD><INPUT type=\"hidden\" name=\"eid\" value=\"$eid\">"; 
							echo "<input type=\"checkbox\" name=\"pid[]\" value=\"$pid\">";
							echo '</TD>';
						    echo '</TR>';
								
						}while($stmt->fetch()); 
						
						echo '</TABLE>';
						echo '<class="submit"> <INPUT type="submit" name="issue" value="Issue">';
						echo '</FORM>';

					}
					
					else
					{

						echo '<META http-equiv="refresh" content="2; url=issueinvites.php"/>';
						echo 'You have no uninvited guests for this event.';
					
					}
					
					$stmt->close();
				    $mysqli->close();
					
				}	

			}
			
			/* User has selected people to invite. */

			elseif(isset($_POST["issue"]) && isset($_POST["pid"])) 
			{
				
				/* Invites those people that the user had selected. */

				if($stmt = $mysqli->prepare("insert into invited (pid, eid, response, visibility) values (?,?,0,0)"))
				{

					foreach($_POST["pid"] as $v)
					{

						 $stmt->bind_param("ss", $v,  $_POST["eid"]);
						 $stmt->execute();

							  
					}

					$stmt->close();
					 
					echo '<META http-equiv="refresh" content="2; url=issueinvites.php"/>';
					echo 'You have successfully invited guests.<BR/>';

				}

			}
			
			/* Lists events the user has organized. */
			
			else
			{	

				/* Lists events the user has organized. */

				if($stmt = $mysqli->prepare("select eid, description
									        from event where pid = ?"))
				{				

					$stmt->bind_param("s", $_SESSION["pid"]);
					$stmt->execute();
					$stmt->bind_result($eid, $description);
						
					if($stmt->fetch())
					{

						echo '<TABLE border="1">';
						echo '<TR>';
						echo '<TH>Description</TH>';
						echo '</TR>';
						
					    do{
						
						    $eid = htmlspecialchars($eid);
							$description = htmlspecialchars($description); 
								
							echo '<TR>';
							echo '<TD>' . $description . '</TD>';
							echo '<FORM action="issueinvites.php" method="post">';
							echo "<TD> <input type=\"hidden\" name=\"eid\" value=\"$eid\">";  
							echo "<class=\"submit\"> <INPUT type=\"submit\" name=\"submission\" value=\"Issue Invites\">";
							echo '</FORM></TD>';
						    echo '</TR>';
								
						}while($stmt->fetch()); 
										
						echo '</TABLE>';

					}
					
					$stmt->close();
					$mysqli->close();
					
				}

			    else

			   		echo 'You have no organized events.';
				
			}	
			
			echo '<FOOTER>';
			echo '<A href="index.php">Home Page</A>';
			echo '<A href="logout.php">Logout</A>';
			echo '</FOOTER>';
			
		}		
        
	?>

</BODY>

</HTML>