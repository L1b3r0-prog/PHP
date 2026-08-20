<?php
    // while, dowhile, for, foreach
    $count = 2;
    while ($count <= 100) {
        echo "$count <br>";
        $count *= 2;
    }

    echo "End line";
    echo "<br>-----------------------";
    echo "<br>";

    do {
        echo "The count is equal to $count";
        ++$count;
    }
    while ($count < 2);
    echo "<br>-----------------------";
    echo "<br>";
    
    for ($count = 0; $count < 5; ++$count) {
        echo $count, "<br>";
    }

    echo "<br>-----------------------";
    echo "<br>";

    $Week = array("Monday", "Tuesday", "Wednesday", "Thur", "Fri", "Sat", "Sun");

    foreach ($Week as $day) {
        echo "$day";
    }

    foreach ($Week as $dayNum => $day) {
        echo "$dayNum is $day";
    }

    // Arrays
    echo "<br>-----------------------";
    echo "<br>";

    $items = array("item1", "item2", "item3");
    $insert = array_push($items, "4", "5");
    print_r($items);
    echo "<br>";

    $num = array_push($items, 15);
    print_r($items);
    echo "<br>";
    echo "Array has $num elements";
    echo "<br>";

    $removed = array_splice($items, -3);
    print_r($items);
    echo "<br>";
    print_r($removed);

    echo "<br>";
    unset($items[1]);
    print_r($items);
    $items = array_values($items);
    echo "<br>";
    print_r($items);
    echo "<br>";

    // array sorting variants
    $prices = array("apple" => 3, "banana" => 1, "cherry" => 2);
    sort($prices);
    print_r($prices);
    echo "<br>";

    $prices = array("apple" => 3, "banana" => 1, "cherry" => 2); // reset
    asort($prices);
    print_r($prices);
    echo "<br>";

    $prices = array("apple" => 3, "banana" => 1, "cherry" => 2); // reset
    ksort($prices);
    print_r($prices);
    echo "<br>";


    // array combine
    $arr1 = [1,2,3];
    $arr2 = [4,5,6];
    $combined = [...$arr1, ...$arr2];
    print_r($combined);
?>