<?php


    include ("inc_dbconnect.php");
	$conn->select_db("mydb");
    
try {
    $sql = "SELECT firstname, lastname, email FROM MyGuests"; //ORDER by firstname
    $stmt = $conn->prepare($sql);

    // execute statement
    $stmt->execute();

    // bind result variables
    $stmt->bind_result($fname, $lname, $email);
    // fetch values
    while ($stmt->fetch())
    {
        echo $fname. " " . $lname . " - " . $email . "<br />";
    }

    // close statement
    $stmt->close();

}
catch (mysqli_sql_exception $e){
        die("Unable to execute the query" . $e->getCode(). ": " . $e->getMessage());
}
  
$conn->close();

?>
