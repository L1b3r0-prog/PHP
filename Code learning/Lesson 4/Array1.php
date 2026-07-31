<?php
    $FirstArray = array("item1", "item2", "item3");
    echo "Before adding in";
    print_r($FirstArray);
    echo "</br>";

    $num = array_push($FirstArray, 101, 102);
    echo "After pushing in";
    print_r($FirstArray);
    echo "</br>";

    $elem = array_pop($FirstArray);
    echo "After popping";
    print_r($FirstArray);
    echo "</br>";
?>