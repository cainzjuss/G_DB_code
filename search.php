<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>


<script type="text/javascript">
	
		function presearch(isikukood) {
			document.getElementById("results").innerHTML = "Refreshing...<br><br><br><br><br><br><br><br><br><br><br><br>";
		    if (isikukood.length == 0) { 
		        document.getElementById("results").innerHTML = "";
		        return;
		    } else {
		        var xmlhttp = new XMLHttpRequest();
		        xmlhttp.onreadystatechange = function() {
		            if (this.readyState == 4 && this.status == 200) {
		                document.getElementById("results").innerHTML = this.responseText;
		            }
		        };
		        xmlhttp.open("GET", "presearch.php?isikukood=" + isikukood , true);
		        xmlhttp.send();
		    }
		}
		function fullsearch(isikukood,offset) {
			document.getElementById("results2").innerHTML = "Loading...";
		    if (isikukood.length == 0) { 
		        document.getElementById("results2").innerHTML = "";
		        return;
		    } else {
		        var xmlhttp = new XMLHttpRequest();
		        xmlhttp.onreadystatechange = function() {
		            if (this.readyState == 4 && this.status == 200) {
		                document.getElementById("results2").innerHTML = this.responseText;
		            }
		        };
		        xmlhttp.open("GET", "fullsearch.php?isikukood=" + isikukood+"&offset="+offset , true);
		        xmlhttp.send();
		    }
		}
</script>





<input type="number" name="isikukood" id="isikukood" onchange="presearch(this.value)" oninput="presearch(this.value)"> <button onclick="fullsearch(document.getElementById('isikukood').value)">Search</button>

<p>Did you mean?<br><span id="results"></span></p><br>

<p>Data: </p><br><p><span id="results2"></span></p>

</body>
</html>