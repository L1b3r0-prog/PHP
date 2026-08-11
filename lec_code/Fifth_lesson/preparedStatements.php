<?php


    include ("inc_dbconnect.php");
	$conn->select_db("mydb");
    
try {

    // prepare and bind
    $stmt = $conn->prepare("INSERT INTO MyGuests	(firstname, lastname, email) VALUES (?, ?, ?)"); 
    $stmt->bind_param("sss", $fname, $lname, $email);


    // set parameters and execute
    $fname = "John555";
    $lname = "Doe555";
    $email = "john@example.com";
    $stmt->execute();

    $fname = "Mary333";
    $lname = "Moe333";
    $email = "mary@example.com";
    $stmt->execute();
	
	echo "2 records inserted";
	
    $stmt->close();
}
catch (mysqli_sql_exception $e){
        die("Unable to execute the query" . $e->getCode(). ": " . $e->getMessage());
}
  
$conn->close();

?>
