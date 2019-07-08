<?php 
	session_start();
 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<title></title>
 </head>
 <body>
<?php 
	echo "inserting...";
	//connect to DB
	$host        = "host = 127.0.0.1";
   	$port        = "port = 5432";
   	$dbname      = "dbname = postgres";
   	$credentials = "user = postgres password=postgres";

   	$db = pg_connect( "$host $port $dbname $credentials"  );
	$sql = "select column_name from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME='dataxxl'";
	$ret = pg_query($db, $sql);

	$DB_columns = array();
	$i = 0;
	//get DB headers and slam them insto an array
	while($row=pg_fetch_assoc($ret)){

		if($row["column_name"] !== "autoIncrement"){
			$DB_columns[$i] = $row["column_name"];		
			
			$i++;
		}
	}

	
	// This part should not be done in PHP when there are big files at play due to execution limits

	//open the uploaded file
   	$file = fopen("uploads/".$_SESSION['uploaded_file'],"r");
   	$firstLine = true;
	$str =  explode(";",fgets($file)); //trash the first line
	//prepare column names for SQL
	$columnSQL_String = " ";
	foreach($DB_columns as $value){
		if ($firstLine == true){
			$columnSQL_String = $columnSQL_String.$value;
			$firstLine = false;
		}else{
			$columnSQL_String = $columnSQL_String.",".$value;
		}
	}
	$firstLine = true;

	//while the file has data
	while(! feof($file)){
		//punch the data line to array
		$str =  explode(";",fgets($file));
		//if the file line is faulty or has a mismatch in column count, ignore it and take the next one
		if (count($str) == count($DB_columns)) {

			//prepare value side of SQL command with header remap data
			$i=0;
			$rearrangedValues = " ";
			foreach ($DB_columns as $value) {
				if ($firstLine == true){
					$rearrangedValues = $rearrangedValues."'".$str[$_GET[$value]]."'";
					$i++;
					$firstLine = false;
				}else{
					$rearrangedValues = $rearrangedValues.",'".$str[$_GET[$value]]."'";
					$i++;
				}
			}
			$firstLine = true;
			//send the full SQL command
			$sql = "insert into dataxxl ($columnSQL_String) values ($rearrangedValues)";
			$ret = pg_query($db, $sql);
		    if(!$ret) {
		       echo pg_last_error($db);
		    } 
		}
	}

	//close file and DB connection
	pg_close($db);
	fclose($file);

	//say that you have made it this far
	echo "<p>Done</p>";
 ?>
 </body>
 </html>