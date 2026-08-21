<?php
    // Two ways to create a database is to either use
    // the sql in php code or to create in the DB itself
    include "NameOfPHPFile.php";
    $conn->select_db("DBName");

    sql = "CREATE TABLE TableName ( // Use () instead of {}
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(30) NOT NULL,
        email VARCHAR(30),
        reg_date TIMESTAMP
    )"; // The ending " is to be here instead of after the table name

    // try catch is written the same way
    try {
        $conn -> query($sql);
        echo "Table created successfully";
    }
    catch(mysqli_sql_exception $e){
        die("Error creating Table" . $e->getCode() . $e->getMessage());
    }

    // the sql code to delete a table is
    // sql = "DROP TABLE TableName";
    // Note that the " ends here now for delete

    // To add new record, is INSERT
    $sql = INSERT INTO TableName(name, email) VALUES("Name", "Email");

    // To update existing, is to use UPDATE
    $sql = "UPDATE TableName SET email ='" . $email . "' WHERE id=" . $id;
?>