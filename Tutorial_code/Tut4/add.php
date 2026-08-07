<?php
    $pid = $_GET['pid'];
    $pnm = $_GET['pnm'];
    echo "pid,pname=", $pid, ",", $pnm,"<br>";

    $servername = "localhost";
    $username = "root";
    $password ="";
    $dbname = "test1";
    $dbtable = "mytable";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {die("Can't connect...");}

    $sql = "insert into $dbtable(pid, pname) values ('$pid', '$pnm')";
    if ($conn->query($sql)==TRUE) {
        echo "record inserted...","<br>";
    }
    else {
        echo "record inserted...","<br>";
    }

    $conn->close();
?>