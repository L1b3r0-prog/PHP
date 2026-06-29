<html>
    <head>
        
    </head>

    <body>
        // function can be written as so
        // reusable blocks of code for specific task
        // function nameOfFunc(parameters) {statements;}
        <?php
            function averagingNumbers($a, $b, $c) {
                $Sum = $a + $b + $c;
                $Result = $Sum / 3;
                return $Result . "<br>";
            }

            echo averagingNumbers(5, 6, 7) . "<br>";
            echo averagingNumbers(5, 5, 7);
        ?>
        
        <?php
            function addSome(&$text) {
                $text = $text."problem?";
            }
        ?>

        
    </body>
</html>