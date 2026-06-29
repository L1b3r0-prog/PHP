<html>
<head>
    <title>PHP Code</title>
</head>

<body>
    <p>
        My first line written in HTML (no echo needed outside PHP tags)
    </p>

    <?=
        "= instead of php is to be written without echo";
    ?>

    <?php
        echo "<p>Extra Line</p>";
    ?>

    <?php
        echo "Another Line";
    ?>

    <?php
        $Num = 1;
        echo "<p>This is lesson " . $Num . ".</p>";

        $var1 = "120-130 people";
        $var2 = (int)$var1;
        echo $var2; // prints 120
    ?>

</body>
</html>