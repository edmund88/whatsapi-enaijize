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
	$servername = "ec2-50-19-236-178.compute-1.amazonaws.com";
	$port = "5432";
	$username = "dsrxtxfrujicul";
	$password = "abU_kylvHNeWMFJRwzPnQajP8P";
	$dbname = "db7ctk55jodcoo";
	
	$pg_connection_string = "dbname=" . $dbname . " host=" . $servername . " port=" . $port . " user=" . $username . " password=" . $password . " sslmode=require";

	$db = pg_connect($pg_connection_string) or die("Could not connect");
	
	while(true) {
		echo "<b>Polling...</b><br/>";
		$w->pollMessage();
		
		echo "<b>Processing...</b><br/>";
		$sql = "SELECT * FROM messages WHERE new = 1";
		$result = pg_query($db, $sql);

		while($message = pg_fetch_assoc($result)) {
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
			if (pg_query($db, $sql)) {
				echo "Record updated successfully";
			} else {
				echo "Error: " . $sql;
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
