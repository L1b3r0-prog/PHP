<html>
    <head>
        Lecture 4
    </head>

    <body>
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
    ?>  
    </body>
</html>