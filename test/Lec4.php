<?php
    echo "<br>";

    // 3 different ways to write arrays

    $arrayList = array("item1", "item2", "item3");
    print_r($arrayList);

    echo "<br>";

    $newArrayList = array_push($arrayList, "item4", "item5");
    print_r($newArrayList);

    echo "<br>";

    $arr = ["Mercedes", "BMW", "Toyota"];
    print_r($arr);

    echo "<br>";

    $newArr[] = "first";
    $newArr[] = "second";
    print_r($newArr);


    echo "<br>";
    // 2 dimensional arrays

    $Ounces = array(1, 0.125, 0.0625, 0.03125, 0.0078125);
    $Cups = array(8, 1, 0.5, 0.25, 0.0625);
    $Pints = array(16, 2, 1, 0.5, 0.125);
    $Quarts = array(32, 4, 2, 1, 0.25);
    $Gallons = array(128, 16, 8, 4, 1);

    $Volume = array($Ounces, $Cups, $Pints, $Quarts, $Gallons);
    echo"<pre>";
    print_r($Volume);
    echo"</pre>";


    $ArrayedVolume = array("Ounces" => $Ounces, "Cups" => $Cups, "Pints" => $Pints, "Quarts" => $Quarts, "Gallons" => $Gallons);
    echo"<pre>";
    print_r($ArrayedVolume);
    echo"</pre>";
?>