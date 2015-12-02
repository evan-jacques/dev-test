<!DOCTYPE html>
<html>
<head>
	  <title>Dev-Test</title>
	  <link href="devtest.css" rel="stylesheet">
	</head>
<body>
<div id="creator">
	<form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>">
	<label for='create'>Select how many users you want to create:</label><br>
	  <select name="create" onchange="CheckOption(this.value);">
	  	<option value="5"> 5 </option>
	  	<option value="10"> 10 </option>
	  	<option value="20"> 20 </option>
	  	<option value="other"> Other </option>
	  </select>
	<!--  <input type="text" name="create" id="create" style='display:none;'/> -->
	  <input type="submit" name="createSubmit" value="Submit" >
	</form>
</div>
<div id="display">
	<form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>">
	<label for='display'>Select how many users you want to display:</label><br>
	  <select name="display" onchange="CheckOption(this.value);">
	  	<option value="5"> 5 </option>
	  	<option value="10"> 10 </option>
	  	<option value="20"> 20 </option>
	  	<option value="other"> Other </option>
	  </select>
	<!--  <input type="text" name="display" id="display" style='display:none;'/> -->
	  <input type="submit" name="displaySubmit" value="Submit" >
	</form>
</div>

	<?php
	$servername = "localhost";
	$username = "root";
	$password = "********";
	$dbname = "devtest";

	// Create connection
	$conn = new mysqli($servername, $username, $password,$dbname);

	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 
	//echo "Connected successfully";

	/*
	$sql = "CREATE DATABASE myDB";
	if ($conn->query($sql) === TRUE) {
	    echo "Database created successfully";
	} else {
	    echo "Error creating database: " . $conn->error;
	}


	$sql = "CREATE TABLE MyGuests (
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	firstname VARCHAR(30) NOT NULL,
	lastname VARCHAR(30) NOT NULL,
	email VARCHAR(50),
	reg_date TIMESTAMP
	)";

	if ($conn->query($sql) === TRUE) {
	    echo "Table MyGuests created successfully";
	} else {
	    echo "Error creating table: " . $conn->error;
	}


	$sql = "INSERT INTO MyGuests (firstname, lastname, email)
	VALUES ('John', 'Doe', 'john@example.com')";

	if ($conn->query($sql) === TRUE) {
	    echo "New record created successfully";
	} else {
	    echo "Error: " . $sql . "<br>" . $conn->error;
	}
	*/

	$f1 = fopen("CSV_Database_of_First_Names.csv", 'r');
	$f2 = fopen("CSV_Database_of_Last_Names.csv", 'r');
	$f3 = fopen("cityzip.csv", 'r');

	$fn = fgets($f1);
	$fnames = preg_split('/\s+/', $fn);
	$ln = fgets($f2);
	$lnames = preg_split('/\s+/', $ln);
	$cities = array();


	$i = 0;
	while(!feof($f3)){

		$cities[$i] = fgets($f3);
		$i += 1;

	}

	//print_r($cities);

	fclose($f1);
	fclose($f2);
	fclose($f3);
	
	function createInfo($fnames,$lnames,$cities, $conn){
	
		$fname = $fnames[rand(0,count($fnames) - 1)];
		$lname = $lnames[rand(0,count($lnames) - 1)];

		$g = array('m','f');
		$gender = $g[array_rand($g)];

		$birthdate = "'".rand(1930,2015)."-".rand(0,12)."-".rand(0,30)."'";
		$addsuff = array("St.","Ave.","Cr.","Rd.","Boul.","Hwy.");
		$address = "'".rand(0,10000)." ".$lnames[rand(0,count($lnames) - 1)]." ".$addsuff[rand(0,count($addsuff) - 1)]."'";

		$loc = $cities[rand(0,count($cities) - 1)];
		$cs = explode(",", $loc);
		$city = $cs[1];
		$state = $cs[0];


		$esuff = array("@hotmail.com","@gmail.com", "@shaw.com", "@yahoo.com");
		$email = strtolower($fname.".".$lname.$esuff[array_rand($esuff)]);

		$phone = rand(0,10000000000);
		$sin = rand(0,10000000000);

		$sql = "INSERT INTO info (name, gender, birthdate, address, city, province, email, phone, sin)
		VALUES ('".$fname." ".$lname."','".$gender."',".$birthdate.",".$address.",'".$city."', '".$state."','".$email."','".$phone."','".$sin."')";
		//echo $sql;

		if ($conn->query($sql) === TRUE) {
		   // echo "New record created successfully";
		} else {
		    echo "Error: " . $sql . "<br>" . $conn->error;
		}
	
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
	    $num = $_POST['create'];
	   // print_r($_POST);
	    if (empty($num)) {
	       // echo "No amount 1";
	    } 
	    else {
	        $i = 0;
	        while($i < $num){
	        	createInfo($fnames,$lnames,$cities, $conn);
	        	$i += 1;
	        	
	        }
	    }
    }
	//createInfo($fnames,$lnames,$cities, $conn);
	
	//$conn->close();
	?>
	<div class="results">

	<table id="results">
		
	<tr>
		<th>Name</th>
		<th>Gender</th>
		<th>Birthdate</th>
		<th>address</th>
		<th>City</th>
		<th>State</th>
		<th>Email</th>
		<th>Phone Number</th>
		<th>SIN</th>
	</tr>
	<?php

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	    // collect value of input field
	    $nums = $_POST['display'];
	    //print_r($_POST);
	    if (empty($nums)) {
	      //  echo "No amount 2";
	    } 
	    else {
	    	$sqls = "SELECT * FROM info ORDER BY ID DESC LIMIT ".$nums;
	    	//print_r($sqls."!!");
	    	$result = $conn->query($sqls);
	    	//print_r($result);
	    	if ($result->num_rows > 0) {
				    // output data of each row
			    while($row = $result->fetch_assoc()) {
			        echo "<tr>
			        	<td>".$row["name"]."</td>
			        	<td>".$row["gender"]."</td>
			        	<td>".$row["birthdate"]."</td>
			        	<td>".$row["address"]."</td>
			        	<td>".$row["city"]."</td>
			        	<td>".$row["province"]."</td>
			        	<td>".$row["email"]."</td>
			        	<td>".$row["phone"]."</td>
			        	<td>".$row["sin"]."</td></tr>";
			    }
			} 
			else {
			    echo "0 results";
			}

		}
	}

	$conn -> close();
	?>
	</table>

	</div>
</body>
<script src="devtest.js"></script>
</html>
