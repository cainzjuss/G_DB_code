<?php 

	//get input
	$isikukood = $_GET["isikukood"];

	//connect to DB and get the data then spit it out as buttons
	$host        = "host = 127.0.0.1";
   	$port        = "port = 5432";
   	$dbname      = "dbname = postgres";
   	$credentials = "user = postgres password=postgres";

   	$db = pg_connect( "$host $port $dbname $credentials"  );
	$sql = "select isikukood from dataxxl where isikukood::text ILIKE '$isikukood%' limit 10;";
	
	$ret = pg_query($db, $sql);
	if(!$ret) {
		       echo pg_last_error($db);
		    } 
	while($row=pg_fetch_assoc($ret)){
		$value = $row["isikukood"];
		echo "<button onclick=' fullsearch($value)'>".$value."</button>";
		echo "<br>";
	}

	pg_close($db);
	//clean input
	unset($isikukood);




 ?>