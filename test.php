
<<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<div id="creator">
	<form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>">
	<label for='create'>Select how many users you want to create:</label><br>
	  <select name="create" onchange="CheckOption(this.value);">
	  	<option value=""> Choose </option>
	  	<option value="5"> 5 </option>
	  	<option value="10"> 10 </option>
	  	<option value="20"> 20 </option>
	  	<option value="other"> Other </option>
	  </select>
	  
	  <input type="submit" name="createSubmit" value="Submit" >
	</form>
</div>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field
    //$num = $_POST['create'];
    print_r($_POST);
    if (empty($num)) {
        echo "No amount";
    } 
    else {
        echo "WORKS";
    }
}
    ?>
</body>
</html>