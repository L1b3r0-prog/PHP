<?php
    $write = fopen("data.txt", "w");
    fwrite($write, "New line\n");
    fwrite($write, "Another line\n");
    
    $handle = fopen("data.txt", "r");
    while(!feof($handle)){
        $line = fgets($handle);
        echo $line . "<br>"; 
    }
    fclose($write);
    fclose($handle);
?>