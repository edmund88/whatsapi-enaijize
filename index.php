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
	
		
	echo "<b>Disconnecting...</b>";
	$w->disconnect();
	
	echo "<b>Done.</b>";
    ?>
  </div>
</div>
</body>
</html>
