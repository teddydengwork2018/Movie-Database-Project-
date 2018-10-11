<!DOCTYPE html>
<html>
<head>
	<title>Search/Actor Movie</title>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
	<div class="container">
		<h1>Searching Page:</h1>
		<hr>
		<form action="search.php" method="POST">
			Search:<br>
			<input type="text" class="form-control" name="Search"  maxlength="50"/><br>
       		<input type="submit" class="btn btn-default" value="Click me!"/>

		</form>
       	<?php
       	if (!isset($_POST["Search"]) || $_POST["Search"] == ""){
       		die("Nothing entered.");
       	}
    	$db = mysql_connect("localhost", "cs143", "");
    	if (!$db){
        	die("Unable to connect to database: " . mysql_error());
    	}
    	$db_selected = mysql_select_db("CS143", $db);
    	if (!$db_selected){
        	die("Unable to select database: " . mysql_error());
    	}
    	$keywords = explode(" ", $_POST["Search"]);

    	// Actors matching keywords
    	echo "<h2>Matching Actors are: </h2>\n";
    	$query = "SELECT * FROM Actor WHERE";
    	
    	for ($i = 0; $i < count($keywords) - 1; $i++)
    	{
        	$safe = mysql_real_escape_string($keywords[$i]);
        	$query .= " (first LIKE '%" . $safe . "%' OR last LIKE '%" . $safe . "%') AND";
    	}
    	$safe = mysql_real_escape_string($keywords[count($keywords) - 1]);
    	$query .= " (first LIKE '%" . $safe . "%' OR last LIKE '%" . $safe . "%')";
    	$query .= " ORDER BY first";
    	
    	if (!$result = mysql_query($query)){
    		die("Error executing query: " . mysql_error());
    	}
      
    	if (mysql_num_rows($result) == 0){
    		echo "<h5>No matching actors found.</h5>\n";
    	}
    	else{
        	while ($row = mysql_fetch_assoc($result))
    		{
        		$name = "$row[first] $row[last]";
        		$aid = $row["id"];
        		$dob = $row["dob"];
        		echo "<a href=\"./showactinfo.php?aid=$aid\">$name ($dob)</a>";
        		echo "<br>\n";
    		}  		
    	}

    	mysql_free_result($result);
    	// Movies matching keywords
    	echo "<h2>Matching Movies are: </h2>\n";
    	$query = "SELECT * FROM Movie WHERE";
    	for ($i = 0; $i < count($keywords) - 1; $i++)
    	{
        	$safe = mysql_real_escape_string($keywords[$i]);
        	$query .= " (title LIKE \"%" . $safe . "%\") AND";
    	}
    	$safe = mysql_real_escape_string($keywords[count($keywords) - 1]);
    	$query .= " (title LIKE \"%" . $safe . "%\")";
    	$query .= " ORDER BY title";
    	if (!$result = mysql_query($query)){
        	die("Error executing query: " . mysql_error());
    	}
    	if (mysql_num_rows($result) == 0){
        	echo "<h5>No matching movies found.</h5>\n";
    	}
    	else{
        	while ($row = mysql_fetch_assoc($result))
    		{
        		$title = $row["title"];
        		$year = $row["year"];
        		$mid = $row["id"];
        		echo "<a href=\"./showmovinfo.php?mid=$mid\">$title ($year)</a>";
        		echo "<br>\n";
    		}
    	}

    	mysql_free_result($result);
    	mysql_close($db);

       	?>

	</div>
</body>
</html>>
