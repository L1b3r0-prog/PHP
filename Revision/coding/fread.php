<?php
    $handle = fopen("data.txt", "r");
    while (!feof($handle)){
        $line = fgets($handle);
        echo $line . "<br>";
    }
    fclose($handle);
?>
