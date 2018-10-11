<!DOCTYPE html>
<html>
<head>
	<title>Add Movie/Actor Relation</title>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
	<div class="container">
		<form action="addmovieact.php" method="POST">
		<?php
		$mid = $_POST["mid"];
    	$aid = $_POST["aid"];
    	$role = $_POST["role"];


    	if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($mid) && !empty($aid) && !empty($role)){
        $db = mysql_connect("localhost", "cs143", "");
        if (!$db){
            die("Unable to connect to database: " . mysql_error());
        }


        $db_selected = mysql_select_db("CS143", $db);
        if (!$db_selected){
            die("Unable to select database: " . mysql_error());
        }


        $mid = (int) $mid;
        $aid = (int) $aid;
        $role = "'" . mysql_real_escape_string($role) . "'";
        $query = "INSERT INTO MovieActor (mid, aid, role) VALUES ($mid, $aid, $role)";
        if(!$result = mysql_query($query)) {
            die("Error executing query: " . mysql_error());
        }

        mysql_close($db);
        echo "Added actor with id=$aid (role of $role) to movie with id=$mid.\n";
        echo "<hr />\n";


    	}
    	else if($_SERVER["REQUEST_METHOD"] == "POST"){
        	echo "No role specified.\n";
        	echo "<hr />\n";

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
        //select movie
        $query = "SELECT * FROM Movie ORDER BY title ASC";
        if (!$result = mysql_query($query)){
            die("Error executing query: " . mysql_error());
        }

        echo "Movie Title: <select class = \"form-control\" name = \"mid\">\n";
        while($row = mysql_fetch_assoc($result)) {
            $title = $row["title"];
            $year = $row["year"];
            $mid = $row["id"];
            echo "<option value = \"$mid\">$title ($year)</option>\n";
		}
		echo "</select><br>\n";
		mysql_free_result($result);

		//select actor
		$query = "SELECT * FROM Actor ORDER BY first ASC";
        if (!$result = mysql_query($query)){
            die("Error executing query: " . mysql_error());
        }

        echo "Actor: <select class = \"form-control\" name = \"aid\">\n";
        while($row = mysql_fetch_assoc($result)) {
            $first = $row["first"];
            $last = $row["last"];
            $dob = $row["dob"];
            $aid = $row["id"];
            echo "<option value = \"$aid\">$first $last($dob)</option>\n";
		}
		echo "</select><br>\n";
		mysql_free_result($result);
		mysql_close($db);
		?>
        <div class="row">
        <div class="col-xs-4">
        Role: <input type="text" class="form-control" name="role" maxlength="50"/><br>
        </div>
        </div>
        <br>
        <input type="submit" class="btn btn-default" value="Click me!"/>

		</form>


	</div>
</body>
</html>>
