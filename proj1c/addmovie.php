<!DOCTYPE html>
<html>
<head>
	<title>Add Movie Information</title>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
	<div class="container">
		<h1>Add new Movie</h1>
		<form action="" method="POST">
			Title:<br>
			<input type="text" name="title" maxlength="20"><br>
			Company:<br>
			<input type="text" name="company" maxlength="50"><br>
			Year:<br>
			<input type="text" name="year" maxlength="4"><br>
			MPAA Rating: <br>
			<select name="rating">
				<option value="G">G</option>
            	<option value="NC-17">NC-17</option>
           		<option value="PG">PG</option>
            	<option value="PG-13">PG-13</option>
            	<option value="R">R</option>
            	<option value="surrendere">surrendere</option>
			</select><br>
			Genre:
			<input type="checkbox" name="Action" value="Action">Action</input>
        	<input type="checkbox" name="Adult" value="Adult">Adult</input>
        	<input type="checkbox" name="Adventure" value="Adventure">Adventure</input>
        	<input type="checkbox" name="Animation" value="Animation">Animation</input>
        	<input type="checkbox" name="Comedy" value="Comedy">Comedy</input>
        	<input type="checkbox" name="Crime" value="Crime">Crime</input>
        	<input type="checkbox" name="Documentary" value="Documentary">Documentary</input>
        	<input type="checkbox" name="Drama" value="Drama">Drama</input>
        	<input type="checkbox" name="Family" value="Family">Family</input>
        	<input type="checkbox" name="Fantasy" value="Fantasy">Fantasy</input>
        	<input type="checkbox" name="Horror" value="Horror">Horror</input>
        	<input type="checkbox" name="Musical" value="Musical">Musical</input>
        	<input type="checkbox" name="Mystery" value="Mystery">Mystery</input>
        	<input type="checkbox" name="Romance" value="Romance">Romance</input>
        	<input type="checkbox" name="Sci-Fi" value="Sci-Fi">Sci-Fi</input>
			<input type="checkbox" name="Short" value="Short">Short</input>
       		<input type="checkbox" name="Thriller" value="Thriller">Thriller</input>
        	<input type="checkbox" name="War" value="War">War</input>
       		<input type="checkbox" name="Western" value="Western">Western</input>
       		<br>
			<input type="submit" class="btn btn-default" value="Add!"/>

		</form>
    <?php
    $title = $_POST["title"];
    $company = $_POST["company"];
    $year = $_POST["year"];
    $rating = $_POST["rating"];

    if($_SERVER["REQUEST_METHOD"] === "POST" && !empty($title) && !empty($year) && !empty($company)) {
      $db = mysql_connect("localhost", "cs143", "");
      if(!$db){
          die("Unable to connect database: " . mysql_error());
        }
      $db_selected = mysql_select_db("CS143", $db);
      if(!$db_selected){
          die("Unable to select databse: " . mysql_error());          
        }


      $title = "'" . mysql_real_escape_string($title) . "'";
      $company = "'" . mysql_real_escape_string($company) . "'";
      $rating = "'" . mysql_real_escape_string($rating) . "'";
      

      //update id
      mysql_query("START TRANSACTION");
      $commit = true;

      $query = "SELECT id FROM MaxMovieID";
      if(!$result = mysql_query($query)) {
        $commit = false;
      }
      $row = mysql_fetch_assoc($result);
      $old_id = $row["id"];
      $id = $old_id + 1;
      mysql_free_result($result);

      $query = "UPDATE MaxMovieID SET id=$id WHERE id=$old_id";
      if(!$result = mysql_query($query)) {
        $commit = false;
      }

      //insert movie 
      $query = "INSERT INTO Movie(id, title, year, rating, company) VALUES($id, $title, $year, $rating, $company)";
      if(!$result = mysql_query($query)) {
        $commit = false;
      }

      $option = ["Action", "Adult", "Adventure", "Animation", "Comedy", "Crime",
            "Documentary", "Drama", "Family", "Fantasy", "Horror", "Musical",
            "Mystery", "Romance", "Sci-Fi", "Short", "Thriller", "War", "Western"];

      for($i=0; $i < count($option); $i++) {
        $genre = $option[$i];
        if(isset($_POST[$genre])) {
          $query = "INSERT INTO MovieGenre(mid, genre) VALUES($id, '$genre')";
          if(!$result = mysql_query($query)) {
              $commit = false;
          }
        }
      }

      if($commit) {
          echo "Add Success.\n";
          mysql_query("COMMIT");

      }else {
          echo "Error adding a movie to the database.\n";

          mysql_query("ROLLBACK");

      }      
      mysql_close($db);

    }else if ($_SERVER["REQUEST_METHOD"] === "POST") {


        echo "company,title and year can't be empty\n";


    }



    ?>

	</div>
</body>
</html>>
