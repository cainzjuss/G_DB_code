<?php 

	//get inputs	
	$isikukood = $_GET["isikukood"];

	$offset=$_GET["offset"];
	$offset=(int)$offset;
	//connect to DB- get header titles and content by 10 results
	$host        = "host = 127.0.0.1";
   	$port        = "port = 5432";
   	$dbname      = "dbname = postgres";
   	$credentials = "user = postgres password=postgres";

   	$db = pg_connect( "$host $port $dbname $credentials"  );
	$sql = "select * from dataxxl where isikukood::text ILIKE '$isikukood%' order by isikukood OFFSET $offset ROWS FETCH NEXT 10 ROWS ONLY;;";

	$sqlHeaders = "select column_name from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME='dataxxl'";
	
	$retHeaders = pg_query($db, $sqlHeaders);
	if(!$retHeaders) {
		echo pg_last_error($db);
	} 
	$ret = pg_query($db, $sql);
	if(!$ret) {
		echo pg_last_error($db);
	} 

	// spit out the table headers
	echo "<table border='1' width='100%'><tr>";
	while($row=pg_fetch_assoc($retHeaders)){
		foreach ($row as $value) {
			echo "<th>$value<th>";		
		}

	}
	echo "</tr>";

	//spit out the table content
	
	while($row=pg_fetch_assoc($ret)){
		echo "<tr>";
		foreach ($row as $value) {
			echo "<td>$value<td>";
		
		}
		echo "</tr>";
		echo "<br>";
		
	}
	echo "</table>";

	//make the buttons for next 10 or previous results
	$offsetNext= $offset+10;
	$offsetPrevious= $offset-10;
	if ($offsetPrevious<0){
		$offsetPrevious = 0;
	}
	echo "<button onclick=' fullsearch($isikukood, $offsetPrevious)'>Previous</button> <button onclick=' fullsearch($isikukood, $offsetNext)'>Next</button>";
	
	pg_close($db);
	//clean input
	unset($isikukood);

	//depending on needs, loads and data sizes... it might be better to have the whole data on client side and flip pages there if the data size is small


 ?>