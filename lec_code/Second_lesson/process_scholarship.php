<html>
<head>
<title>Scholarships</title>
</head>
<body>
<?php

	$name = $_GET['name'];
	$email = $_GET['email'];


/*
    $name = $_POST['name'];
	$email = $_POST['email'];  
*/

/*
	if ($_SERVER["REQUEST_METHOD"] == "POST") { //ensure that the data is sent via POST
		$name = $_POST['name']; 
		$email = $_POST['email'];
	}
*/
    echo "Thank you for filling out the scholarship form, " . $name . ", " . $email . ".";
?>
   
</body>
</html>

