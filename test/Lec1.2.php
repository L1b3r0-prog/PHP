<html>
    <head>
        <title>
            1.2
        </title>
    </head>

    <body>
        declare(strict_types=1);
        // function can be written as so <br>
        // reusable blocks of code for specific task <br>
        // function nameOfFunc(parameters) {statements;} <br>
        <?php
            function averagingNumbers($a, $b, $c) {
                $Sum = $a + $b + $c;
                $Result = $Sum / 3;
                return $Result . "<br>";
            }

            echo averagingNumbers(5, 6, 7);
            echo averagingNumbers(5, 5, 7) . "<br>";


            echo "--------------", "<br>";
            function multiCalc($n1, $n2, $n3) {
                $sum = $n1 + $n2 + $n3;
                $prod = $n1 * $n2 * $n3;
                return array($sum, $prod);
            }

            $result = multiCalc(5, 6, 7);
            echo "Results are: ", $result[0], " and ", $result[1], "<br>";


            echo "--------------", "<br>";
            function IncrementByValue($CountByValue) {
                ++$CountByValue;
                echo "<p>IncrementByValue() value is $CountByValue</p>";
            };

            function IncrementByReference(&$CountByReference) {
                ++$CountByReference;
                echo "<p>IncrementByReference() value is $CountByReference</p>";
            };

            $Count = 1;
            echo "<p>Main program starting value is $Count</p>";
            IncrementByValue($Count);
            echo "<p>Main program after call for IncrementByReference, count value is $Count</p>", "<br>";


            echo "--------------", "<br>";
            function addSome(&$text) {
                $text = $text."problem?";
            }
            $myText = "Is there ";
            echo "<p> $myText </p>";
            addSome($myText);
            echo "<p> $myText </p>";


            echo "--------------", "<br>";
            function coffee($type = "mocha") {
                return "<p>Making a cup of $type. </p>";
            }
            echo coffee();
            echo coffee(null);
            echo coffee("espresso");


            echo "--------------", "<br>";
            // for declaration, must be at the top (line 8)
            function doubleNum(int|float $number) : int|float {
                return $number *= 2;
            }
            $num = 4.8;
            echo "$num = ", $num, "<br>";
            echo "doubleNum returns ", doubleNum($num), "<br>";


            echo "--------------", "<br>";
            function makeSen($name, $activity="no activity", $hours="") {
                return "Hi $name, you have $activity for $hours hrs";
            }
            echo makeSen("John"), "<br>";
            echo makeSen("John", "swimming"), "<br>";
            echo makeSen("John", "swimming", "1"), "<br>";
            echo makeSen("John", activity:"hiking"), "<br>";
            echo makeSen(activity:"hiking", name:"John", hours:"8"), "<br>";
            echo makeSen("John", hours:"8", activity:"hiking"), "<br>"; 
            echo makeSen("John", hours:"5"), "<br>";
            echo makeSen(hours:"5", name:"John"), "<br>";


            echo "--------------", "<br>";
            $increase10 = function($p1) {
                return $p1 + 10;
            };
            echo $increase10(10);
            echo "<br>-----------<br>";
            $count = 5;
            $multi = fn($num) => $num * $count;
            echo $multi(10);


            echo "--------------", "<br>";
            $glVar = "this is my value";
            function scopeEx() {
                global $glVar;
                echo "<p>$glVar</p>";
                $glVar = "if i change it";
            }
            scopeEx();
            echo "<p>$glVar </p>";


            echo "--------------", "<br>";
            $exampleVar = 5;
            if ($exampleVar == 5) {
                echo "<p>The condition evaluates to true<p>";
                echo "<p>$exampleVar is equal to ", "$exampleVar</p>";
                echo " <p>Each of these lines will be printed</p>";
            }
            echo "<p>This statement always executes after the if statement</p>";

            $today = "Tue";
            echo $today;
            if ($today == "Mon") 
                echo "<p>Today is Mon</p>";
            else
                echo "<p>Today is not Mon</p>";

            echo $today == "Mon" ? "<p>Today is Mon</p>" : "<p>Today is not Mon</p>";
            $tomorrow = $tomorrow ?? "not defined";
            echo $tomorrow;
        ?>
    </body>
</html>