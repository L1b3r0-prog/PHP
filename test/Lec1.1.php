<html>
<head>
    <Title> PHP Code </Title>
</head>

<body>
    <p>
        echo "My first Line";
        print "Second Line";
    </p>

    <?php
        echo <p>"Extra Line";</p>
    ?>

    <?=
        echo "Another Line";
    ?>

    $Num = 1;
    <?php
        echo "<p>This is lesson " , $Num , ".</p>";
    ?>


    $var1 = "120-130 people";
    $var2 = (int)$var1;
    //$var2 = (int)$var1-5;
    // find out why this prints 120
</body>
</html>