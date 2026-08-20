<?php
    // This is supposed to be the connection php code
    // This will be in another file itself and not the same one
    $servername = "localhost";
    $username = "root";
    $password = "";

    try {
        $conn = new mysqli($servername, $username, $password);
        echo "Connection successful";
    }
    catch (mysqli_sql_exception $e){
        die ($e->getCode(). ": " . $e->getMessage());
    }
    $conn->close();

    // To create a DB
    // Add in the connection php code (NameOfPHPFile.php)
    /* 
    include "NameOfPHPFile.php";

    $sql = "Create DATABASE name"; // This is to create the database name which is (function name)
    try {
        $conn -> query($sql);
        echo "DB created successfully";
    }
    catch(mysqli_sql_exception $e){
    die("Error creating DB" . $e->getCode() . $e->getMessage());
    }

    // To drop the DB
    $sql = "DROP DATABASE name"; // Same process for dropping database
    if ($conn->query($sql) === TRUE) { // Has to be 3 = sign
        echo "Database deleted";
    }
    else {
        echo "Error: " . $conn->error;
    }
    */

    // Always end with $conn->close();
?>