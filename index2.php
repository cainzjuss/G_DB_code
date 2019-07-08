<?php //var_dump($GLOBALS);
/* Ideaalis, upload kaust saab sessiooni nime või midagi muud tuvastatavat. 
	Probably should have it make a folder by session for backups
	 */
	session_start();

	$ye = "jdj";
	if($_SERVER['REQUEST_METHOD'] == "POST") {
	
		$file_name = $_FILES['fileToUpload']['name'];
		$file_tmp = $_FILES['fileToUpload']['tmp_name'];
		$_SESSION['uploaded_file'] = $file_name;
		
		if (file_exists('uploads/')) {
			move_uploaded_file($file_tmp, 'uploads/'.$file_name);
			echo "uploaded";
		}else{
			mkdir('uploads/',0777,true);
			move_uploaded_file($file_tmp, 'uploads/'.$file_name);
			echo "uploaded+dir";

		}

		header("Location: uploadcheck.php");
    	exit;


			/*while (true) {	    
		    if (file_exists("test.txt")) {
		        echo "The file was found: " . date("d-m-Y h:i:s") . "<br>";
		        break;
		    }
		}  --- dont need this yet*/
	}
 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<title></title>
 </head>
 <body>
 	
 <form action="" method="post" enctype="multipart/form-data">
    Select file to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload" name="submit">
</form>

<a href="search.php">Search</a>
 </body>
 </html>

