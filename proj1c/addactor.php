<!DOCTYPE html>
<html>
<head>
	<title>Add new Actor/Director</title>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
	<div class="container">
		<h1>Add new Actor/Director</h1>
		<form action="addactor.php" method="POST">
			<input type="radio" name="identity" value="Director">Director
			<input type="radio" name="identity" value="Actor" checked="true">Actor<br>
			<hr>
			First Name: <br>
			<input type="text" name="first" maxlength="20"><br>
			Last Name:  <br> 
			<input type="text" name="last" maxlength="20"><br>
			<input type="radio" name="sex" value="Male" checked="true">Male
			<input type="radio" name="sex" value="Female">Female<br>
			Date of Birth: <br>
			<input type="text" name="dob"><br>
			ie:1997-05-05<br>
			Date of Die: <br>
			<input type="text" name="dod"><br>
			(leave blank if alive now)<br>
			<input type="submit" class="btn btn-default" value="Add!"/>
		</form>
		<?php
			$first = $_POST["first"];
    		$last = $_POST["last"];
   	 		$dob = $_POST["dob"];
    		$dod = $_POST["dod"];
    		$identity = $_POST["identity"];
    		$sex = $_POST["sex"];

			if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($first) && !empty($first) && !empty($last) && !empty($dob) && !empty($identity)) {
        		$db = mysql_connect("localhost", "cs143", "");
        		if (!$db){
        			die("Unable to connect to database: " . mysql_error());
        		}
        		$db_selected = mysql_select_db("CS143", $db);
        		if (!$db_selected){
            		die("Unable to select database: " . mysql_error());		
				}

				$first = "'" .mysql_real_escape_string($first). "'";
				$last = "'" .mysql_real_escape_string($last). "'";
				$dob = "'" .mysql_real_escape_string($dob). "'";
				$sex = "'" .mysql_real_escape_string($sex). "'";
				if (!empty($dod)){
					$dod = "'" . mysql_real_escape_string($dod) . "'";
				}
        		else{
        			$dod = "NULL";

        		}

        		//get id 
        		mysql_query("START TRANSACTION");
        		$commit = true;
 				$query = "SELECT id FROM MaxPersonID"; 
 				$result = mysql_query($query);
 				if(!$result) {
 					die("Error executing query: " . mysql_error());
 				}

 				$row = mysql_fetch_assoc($result);
 				$old_id = $row["id"];
 				$id = $old_id + 1;
 				mysql_free_result($result);

 				$query = "UPDATE MaxPersonID SET id=$id WHERE id=$old_id";
 				$result = mysql_query($query);
 				if(!$result) {
 					$commit = false;
 				}

 				if($identity == "Actor"){
 					$query = "INSERT INTO Actor(id, last, first, sex, dob, dod) VALUES ($id, $last, $first, $sex, $dob, $dod)"; 
 					$result = mysql_query($query);
 					if(!$result) {
 						$commit = false;
 					}

 				}else if($identity == "Director"){
 					$query = "INSERT INTO Director(id, last, first, dob, dod) VALUES ($id, $last, $first, $dob, $dod)"; 
 					$result = mysql_query($query);
 					if(!$result) {
 						$commit = false;
 					}


 				}

 				if($commit) {
 					echo "Add Success.\n";
 					echo " ".htmlspecialchars($id)." ".htmlspecialchars($last)." ".htmlspecialchars($first)." ".htmlspecialchars($sex)." ".htmlspecialchars($dob)." ".htmlspecialchars($dod);
 					mysql_query("COMMIT");

 				}else {
 					echo "Error adding an actor/director to the database.\n";

            		mysql_query("ROLLBACK");

 				}

 				mysql_close($db);

			}
			else if($_SERVER["REQUEST_METHOD"] === "POST")
			{
				echo "Invalid Input, Must input at least a first name, last name, and date of birth.\n";
			}



		?>
	</div>
</body>
</html>>
