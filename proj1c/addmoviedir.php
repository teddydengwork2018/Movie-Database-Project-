<!DOCTYPE html>
<html>
<head>
	<title>Add Movie/Director Relation</title>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
	<div class="container">
		<form action="addmoviedir.php" method="POST">
 		<?php
    	$mid = $_POST["mid"];
    	$did = $_POST["did"];
    	if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($mid) && !empty($did)){
        	$db = mysql_connect("localhost", "cs143", "");
        	if (!$db){
        		die("Unable to connect to database: " . mysql_error());
        	}

        	$db_selected = mysql_select_db("CS143", $db);
        	if (!$db_selected){
        		die("Unable to select database: " . mysql_error());
        	}
            
        // Insert into MovieActor
        $mid = (int) $mid;
        $did = (int) $did;
        $query = "INSERT INTO MovieDirector (mid, did) VALUES ($mid, $did)";

        if (!$result = mysql_query($query)){
            die("Error executing query: " . mysql_error());
        }
        mysql_close($db);
        echo "Added director with id=$did to movie with id=$mid.\n";
        echo "<hr>\n";
    	}
    	?>

        <?php
        $db = mysql_connect("localhost", "cs143", "");
        if (!$db){
            die("Unable to connect to database: " . mysql_error());
        }
        $db_selected = mysql_select_db("CS143", $db);
        if (!$db_selected){
            die("Unable to select database: " . mysql_error());
        }
        // All movies
        $query = "SELECT * FROM Movie ORDER BY title ASC";
        if (!$result = mysql_query($query))
            die("Error executing query: " . mysql_error());
        echo "Movie: <select class=\"form-control\" name=\"mid\">\n";
        while ($row = mysql_fetch_assoc($result)) {
            $title = $row["title"];
            $year = $row["year"];
            $mid = $row["id"];
            echo "<option value=\"$mid\">$title ($year)</option>\n";
        }    
        echo "</select><br>\n";
        mysql_free_result($result);
        // All directors
        $query = "SELECT * FROM Director ORDER BY first ASC";
        if (!$result = mysql_query($query))
            die("Error executing query: " . mysql_error());
        echo "Director: <select class=\"form-control\" name=\"did\">\n";
        while ($row = mysql_fetch_assoc($result)) {
            $last = $row["last"];
            $first = $row["first"];
            $aid = $row["id"];
            $dob = $row["dob"];
            echo "<option value=\"$aid\">$first $last($dob)</option>\n";
        }    
        echo "</select><br>\n";
        mysql_free_result($result);
        mysql_close($db);
        ?>	

		<input type="submit" class="btn btn-default" value="Click me!"/>

		</form>
	</div>
</body>
</html>>
