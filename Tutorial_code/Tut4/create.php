<?php
    $servername = "localhost";
    $username = "root";
    $password ="";
    $dbname = "test2";
    $dbtable = "mytable";

    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error) {die("Can't connect...");}

    $sql = "create database if not exists $dbname";
    if ($conn->query($sql)==TRUE) {
        echo "database exists or created...","<br>";
    }
    else {
        echo "can't create database...","<br>";
    }

    $conn->close();

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {die("Can't connect...");}

    $sql = "show tables like '$dbtable'";
    $checktable = $conn->query($sql);
    $table_exists = $checktable->num_rows >=1;

    if(!$table_exists){
        $sql = "create table $dbtable (id int(11) unsigned auto_increment primary key, pid varchar(10) not null, pname varchar(30) not null)";
        if ($conn->query($sql)===TRUE){
            echo "table created...","<br>";
        }
        else {
            echo "can't create table...","<br>";
        }
    }
    else {
        echo "table exist...","<br>";
    }

    $conn->close();
?>