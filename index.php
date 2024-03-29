<html>
<head>
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css" />
</head>
<body>
<div class="panel panel-default">
  <div class="panel-heading">Console</div>
  <div class="panel-body jumbotron">
<?php	
	require_once 'src/whatsprot.class.php';
	require 'src/events/Events.php';
	
	$username = "15169608138";
	$password = "5yLAHeyfVd50eFOt17CpT9kpQ/Y=";
	 
	$w = new WhatsProt($username, 0, "Enaijize System", true);
	
	$events = new MyEvents($w);
	$events->setEventsToListenFor($events->activeEvents);
	
	echo "<b>Connecting...</b>";
	$w->connect();
	echo "<b>Logging in...</b><br/>";
	$w->loginWithPassword($password);
 
	echo "<b>Connecting to database...</b>";
	$dbservername = "us-cdbr-iron-east-01.cleardb.net";
	$dbusername = "bcdca4bcfe9366";
	$dbpassword = "bfc05937";
	$dbname = "heroku_555506e4f7e7997";

	$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	while(true) {
		echo "<b>Polling...</b><br/>";
		$w->pollMessage();
		
		echo "<b>Processing...</b><br/>";
		$sql = "SELECT * FROM messages WHERE new = 1";
		$result = mysqli_query($conn, $sql);

		while($message = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			switch($message["message"]) {
				case "YES":
					if($message["prev_message"] == "YES" || !$message["prev_message"])
						$reply = "Nothing to confirm. Please enter a valid command.";
					else	
						$reply = "Previous message '" . $message["prev_message"] . "' has been confirmed.";
					break;
				default:
					$reply = "You entered '" . $message["message"] . "'. Please reply with 'YES' to confirm.";
			}
				
			$sql = "UPDATE messages SET new=0 WHERE sender='" . $message["sender"] . "'";		
			if ($conn->query($sql) === TRUE) {
				echo "Record updated successfully";
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
				
			echo "<b>Sending...</b><br/>";
			$w->sendMessage($message["sender"], $reply);

		}
	}
		
	echo "<b>Disconnecting...</b>";
	$w->disconnect();
	
	echo "<b>Done.</b>";
    ?>
  </div>
</div>
</body>
</html>
