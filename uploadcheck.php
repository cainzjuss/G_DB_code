
 <!DOCTYPE html>
 <html>
 <head>
 	<title></title>
 </head>
 <body>

 <?php //var_dump($GLOBALS);
	session_start();
	

	//connect to DB
	$host        = "host = 127.0.0.1";
   $port        = "port = 5432";
   $dbname      = "dbname = postgres";
   $credentials = "user = postgres password=postgres";

   //open the uploaded file
   $file = fopen("uploads/".$_SESSION['uploaded_file'],"r");
	$firstLine = true;
	//punch the first line of file into a array
	$str =  explode(";",fgets($file));
	//get header names from DB
	$db = pg_connect( "$host $port $dbname $credentials"  );
	$sql = "select column_name from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME='dataxxl'";
	$ret = pg_query($db, $sql);

	
	$DB_columns = array();
	$i = 0;
	echo '<br>';
	//make select boxes with IDs from DB headers and make user match the header names, make array for JS script
	while($row=pg_fetch_assoc($ret)){

		if($row["column_name"] !== "autoIncrement"){
			$DB_columns[$i] = $row["column_name"];
			$i2 = 0;
			echo $DB_columns[$i].':  <select id="'.$DB_columns[$i].'">';

			foreach ($str as $value) {
			    echo '<option value="'.$i2.'">'.$value.'</option>';
			    $i2++;
			}	
		  	
		  	echo '</select><br> ';
			$i++;
		}
	}
//close the file and DB connection
pg_close($db);
fclose($file);
 ?>

<script type="text/javascript">
	function remap_column(){
		document.getElementById("info").style.display = "block";
		var i = 0;
		var to_db = "data_to_db.php?";

		//php array to JS array transfusion
		var db_col_names = [<?php $firstOfLoop = true; foreach($DB_columns as $value){ if ($firstOfLoop == true){echo '"'.$value.'"'; $firstOfLoop = false;}else{echo ',"'.$value.'"';} } ?>];

		//get values from array and coresponding select boxes via ID and prepare url string
		while(db_col_names.length > i){
			var SelectboxID = db_col_names[i];
			
			var element = document.getElementById(SelectboxID).options[document.getElementById(SelectboxID).selectedIndex].value;
			
			to_db = to_db+db_col_names[i]+"="+element;
			i++;

			if(db_col_names.length !== i){
				to_db = to_db+"&";
			}
		}
		//send header remap data via url to the next php file
		window.location.href = to_db;

	}
</script>
<button onclick="remap_column()">confirm</button>

<p style="display:none;" id="info">inserting...</p>
 </body>
 </html>