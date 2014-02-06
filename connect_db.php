<?php

	$mysqli = new mysqli("localhost", "root", "root", "project_database");

	/**********

		Connection Checking. 
		
		mysqli_connect_errno() - Returns recent error code number from mysqli_connect() call (Procedural) or mysqli() initialization (OOP).
		
		mysqli_connect_error() - Returns string description of recent connection error.
	
	**********/

	if(mysqli_connect_errno())
	{

		printf("Connection to database failed: %s\n", mysqli_connect_error());
		exit();

	}

	session_start();

	/**********

		Security Measures for IP Address Infiltration.

		-	First condition deals with first-time users.
		-	Second condition ensures the session's IP address matches the client's IP address.
	
	**********/

	/**********

	Alternative Code Snippet:

	if(empty($SESSION))
	{
		$SESSION["IP"] = md5($SERVER["REMOTE_ADDR"]);
	}

	if(isset($SESSION["IP"]) && ($SESSION["IP"] != md5($SERVER["REMOTE_ADDR"])))
	{
		session_destroy();
		session_start();
	}

	**********/

	if(isset($SESSION["REMOTE_ADDR"]) && ($SESSION["REMOTE_ADDR"] != $SERVER["REMOTE_ADDR"]))
	{

		session_destroy();
		session_start();

	}

?>