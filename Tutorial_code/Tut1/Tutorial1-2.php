<html>
    <head></head>
    <body>
        <h1>Second PHP Task</h1>
        <?php
            $f = 0;
            for ($f=0; $f<=100; $f=$f+10)
                {
                    $c = ($f-32)*(5/5);
                    echo "Farenheit=". number_format($f,2) .
                    " Celsius" . number_format($c,2) . "<br>";
                }
        ?>
    </body>
</html>