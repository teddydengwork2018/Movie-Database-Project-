<!DOCTYPE html>
<html>
<head>
	<title>Show Actor Information</title>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
	<div class="container">
		<h1>Actor Information Page:</h1><br>
		<hr><hr>
		<form action="./search.php" method="GET">

		<?php

    	$db = mysql_connect("localhost", "cs143", "");
    	if (!$db){
       		die("Unable to connect to database: " . mysql_error());
    	}
    	$db_selected = mysql_select_db("CS143", $db);
    	if (!$db_selected){
        	die("Unable to select database: " . mysql_error());
    	}
    	$aid = (int) $_GET["aid"];
    	// Actor info
    	$query = "SELECT * FROM Actor WHERE id=" . $aid;
    	if (!$result = mysql_query($query)){
        	die("Error executing query: " . mysql_error());
    	}
    	if (mysql_num_rows($result) != 1){
        	die("No actor/actress with aid=$aid found.");
    	}
    	$row = mysql_fetch_assoc($result);
    	echo "<h3> $row[first] $row[last]</h3>\n";
    	echo "Sex: $row[sex]<br />\n";
    	echo "Date of birth: $row[dob]<br />\n";
    	if (is_null($row[dod])){
        	echo "Date of death: N/A<br />\n";
    	}
    	else{
        	echo "Date of death: $row[dod]<br />\n";
    	}
    	mysql_free_result($result);

        // MovieActor info
    	$query = "SELECT mid, role, title, year FROM MovieActor INNER JOIN Movie ON MovieActor.mid = Movie.id WHERE aid=" . $aid;
    	$query .= " ORDER BY year ASC";
    	if (!$result = mysql_query($query)){
        	die("Error executing query: " . mysql_error());
    	}
    	echo "<br />";
    	echo "<h4>Has acted in:</h4>\n";
    	echo "<div class=\"row\">\n";
    	echo "<div class=\"col-md-3\"></div>\n";
    	echo "<div class=\"col-md-6\">\n";
    	echo "<table class=\"table\">\n";
    	echo "<tr align=center>";
    	echo "<td><b>Movie</b></td>";
    	echo "<td><b>Role</b></td>";
    	echo "</tr>\n";
    	while ($row = mysql_fetch_assoc($result)) {
        	echo "<tr align=center>";
        	$mid = $row["mid"];
        	$role = $row["role"];
        	$title = $row["title"];
        	$year = $row["year"];
        	echo "<td><a href=\"./showmovinfo.php?mid=$mid\">$title ($year)</a></td>";
        	echo "<td>$role</td>";
        	echo "</tr>\n";
    	}    
    	echo "</table>\n";
    	echo "</div>\n";
    	echo "<div class=\"col-md-3\"></div>\n";
    	echo "</div>\n";
    	mysql_free_result($result);
    	mysql_close($db);

		?>
			Search:<br>
			<input type="text" class="form-control" name="Search"  maxlength="50"/><br>
       		<input type="submit" class="btn btn-default" value="Click me!"/>

		</form>
	</div>
</body>
</html>>
