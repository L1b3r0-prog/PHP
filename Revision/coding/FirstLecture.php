<?php
    $number = 18;
    echo $number, "<br>";

    echo "My number is ", $number, "<br>";

    function division($a, $b) {
        $div = $a / $b;
        return $div;
    }
    echo division(2,4), "<br>";

    function mul($a, $b, $c) {
        $add = $a + $b + $c;
        $mult = $a * $b * $c;
        return array($add, $mult);
    }
    $result = mul(5,6,7);
    echo "Results are: ", $result[0], " and ", $result[1];
?>