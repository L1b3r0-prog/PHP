<?php
    $servername = "localhost";
    $username = "root";
    $password ="";
    $dbname = "test1";
    $dbtable = "mytable";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {die("Can't connect...");}

    $sql = "select pid, pname from $dbtable";
    $result = $conn->query($sql);
    if ($result->num_rows >0){
        while ($row = $result->fetch_assoc())
            {echo "pid: ".$row["pid"]. " -name: ".$row["pname"]."<br>";}
    }
    else {
        echo "no record to list...","<br>";
    }

    $conn->close();
?>