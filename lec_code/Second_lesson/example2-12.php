<?php
    $ExampleString = "woodworking project";
    echo $ExampleString . "<br>";

    echo substr($ExampleString,4) . "<br>";
    echo substr($ExampleString,4,7) . "<br>";
    echo substr($ExampleString,0,8) . "<br>";
    echo substr($ExampleString,-7) . "<br>";
    echo substr($ExampleString,-12,4) . "<br>";
    $subString = substr($ExampleString, 5, -2);
    echo $subString . "<br>";

    echo substr($ExampleString,-5,-2) . "<br>";
        
    echo strrev($ExampleString) . "<br>";
    echo str_shuffle($ExampleString) . "<br>";
	
?>

