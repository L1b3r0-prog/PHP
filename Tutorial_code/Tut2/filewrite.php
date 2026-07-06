<?php
    $nm = $_GET["name"];
    $ag = $_GET["age"];
    $av = $_GET["average"];
    $txt = $nm . "," . $ag . "," . $av . "\n";
    echo $txt,"<br>";

    $fileopen = FALSE;
    if ( file_exists("user.txt") &&
            ($fp = fopen("user.txt", "r")) == TRUE)
    {
        $fileopen = TRUE;
        while (( $data=fgetcsv($fp, 1000, ",")) == TRUE)
            {
                $name = $data[0];
                $age = $data[1];
                $average = $data[2];
                $bowler[$name] = array($age, $average);
            }
            fclose($fp);
    }
    else
        { echo "file doesn't exist or can't open...", "<br>"; }

    if ($fileopen && array_key_exists($nm, $bowler))
        {
            echo "duplicate name, can't insert...", "<br>";
        }
    else
        {
            $myFile = fopen("user.txt", "a") or die("can't open file");
            fwrite($myFile, $txt);
            fclose($myFile);
            echo "record inserted...","<br>";            
        }
?>