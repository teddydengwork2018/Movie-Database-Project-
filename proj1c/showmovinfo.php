<!DOCTYPE html>
<html>
<head>
	<title>Show Movie Information</title>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
	<div class="container">
		<h1>Movie Information Page:</h1><br>
		<hr><hr>
		<form action="search.php" method="GET">

		<?php
		$db = mysql_connect("localhost", "cs143", "");
    	if (!$db){
        	die("Unable to connect to database: " . mysql_error());
    	}
    	$db_selected = mysql_select_db("CS143", $db);
    	if (!$db_selected){
        	die("Unable to select database: " . mysql_error());
    	}
    	$mid = (int) $_GET["mid"];

    	// Movie info
    	$query = "SELECT * FROM Movie WHERE id=" . $mid;
    	if (!$result = mysql_query($query)){
        	die("Error executing query: " . mysql_error());
    	}
    	if (mysql_num_rows($result) != 1){
        	die("No movie with mid=$mid found.");
    	}
    	$row = mysql_fetch_assoc($result);
    	$title = $row["title"];
   		$year = $row["year"];
    	echo "<h3>$title ($year)</h3>\n";
    	if (is_null($row["rating"])){
        	echo "MPAA Rating: N/A<br>\n";
    	}
    	else{
        	echo "MPAA Rating: $row[rating]<br>\n";
    	}
    	if (is_null($row["company"])){
        	echo "Company: N/A<br>\n";
    	}
   		else{
        	echo "Company: $row[company]<br>\n";
   		}
    	mysql_free_result($result);

    	// Movie director
    	$query = "SELECT * FROM MovieDirector INNER JOIN Director ON Director.id = MovieDirector.did WHERE mid=" . $mid;
    	if (!$result = mysql_query($query))
        	die("Error executing query: " . mysql_error());

    	for ($i = 0; $i < mysql_num_rows($result); $i++)
    	{
        	$row = mysql_fetch_assoc($result);
        	$name = "$row[first] $row[last]";
        	$dob = $row["dob"];
    	}
    	echo "Director : $name($dob)<br>\n";
    	mysql_free_result($result);

    	// MovieGenre info
    	$query = "SELECT genre FROM MovieGenre WHERE mid=" . $mid. " ORDER BY genre ASC";
    	if (!$result = mysql_query($query)){
        	die("Error executing query: " . mysql_error());
    	}
    	$genres = "Genre: ";
     	for ($i = 0; $i < mysql_num_rows($result); $i++)
    	{
        	$row = mysql_fetch_assoc($result);
        	$genres .= "$row[genre] ";
    	}
	   	echo "$genres<br> \n";
    	mysql_free_result($result);

    	echo "<h4>Actors in this Movie:</h4>\n";

    	// MovieActor info
    	$query = "SELECT aid, role, first, last FROM MovieActor INNER JOIN Actor ON MovieActor.aid = Actor.id WHERE mid=" . $mid;
    	if (!$result = mysql_query($query)){
        	die("Error executing query: " . mysql_error());
    	}
    	echo "<br />";
    	echo "<div class=\"row\">\n";
    	echo "<div class=\"col-md-3\"></div>\n";
    	echo "<div class=\"col-md-6\">\n";
    	echo "<table class=\"table\">\n";
    	echo "<tr align=center>";
    	echo "<td><b>Name </b></td>";
    	echo "<td><b>Role</b></td>";
    	echo "</tr>\n";
    	while ($row = mysql_fetch_assoc($result)) {
        	echo "<tr align=center>";
        	$aid = $row["aid"];
        	$name = "$row[first] $row[last]";
        	$role = $row["role"];
        	echo "<td><a href=\"./showactinfo.php?aid=$aid\">$name</a></td>";
        	echo "<td>$role</td>";
        	echo "</tr>\n";
    	}    
    	echo "</table>\n";
    	echo "</div>\n";
    	echo "<div class=\"col-md-3\"></div>\n";
    	echo "</div>\n";
    	mysql_free_result($result);
    	echo "<hr>\n";

    	// All reviews
    	echo "<h4>User Review:</h4>\n";
    	$query = "SELECT avg(rating), count(rating) FROM Review WHERE mid=" . $mid;
    	if(!$result = mysql_query($query)){
        	die("Error executing query: " . mysql_error());
    	}
     	$row = mysql_fetch_row($result);
    	$avgrat = $row[0];
    	$countrat = $row[1];
     	if (is_null($avgrat)){
        	echo "Average Score: N/A. \n";     		
     	}
    	else{
        	echo "Average Score: $avgrat/5 by $countrat review(s). \n";    		
    	}
    	echo "<a href=\"./addreview.php?mid=$mid\">Leave your review as well!</a><br>\n";
    	mysql_free_result($result);

    	$query = "SELECT name, time, rating, comment FROM Review WHERE mid=" . $mid . " ORDER BY time DESC";
    	if(!$result = mysql_query($query)){
        	die("Error executing query: " . mysql_error());
    	}

    	echo "<h4>Comment detials shown below :</h4>";
    	while ($row = mysql_fetch_assoc($result)) {
        	$name = $row["name"];
       		$time = $row["time"];
        	$rating = $row["rating"];
        	$comment = $row["comment"];
        	if (empty($rating)){
            	$rating = "n/a";
        	}
        	if (empty($comment)){
            	$comment = "";
        	}
        	echo "<br/><br/>\n";
        	echo "On $time, <b>$name</b> rated this movie a score of $rating star(s). comment: <br/>\n";
        	echo "$comment\n";
    	}
    	mysql_free_result($result);
    	mysql_close($db);


		?>


			Search:<br>
			<input type="text" class="form-control" name="Search" maxlength="50"/><br>
       		<input type="submit" class="btn btn-default" value="Click me!"/>

		</form>
	</div>
</body>
</html>>
