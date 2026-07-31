<?php
    $indA1 = array("item1", "item2", "item3");
    
    array_unshift($indA1, "B1", "B2");
    echo "After unshift";
    print_r($indA1);
    echo "</pre>";
    echo "<br>";

    $elem = array_shift($indA1);
    echo "After shift";
    print_r($elem);
    echo "</pre>";
?>