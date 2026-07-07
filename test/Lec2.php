<html>
    <head>

    </head>

    <body>
        <?php
            //both are the same methods
            echo "<p>PHP literal text string</p>";
            $stringVariable = "<p>PHP literal text string</p>";
            echo $stringVariable;


            // backslash is used when inner quotes matches outer quotes
            echo "--------------", "<br>";
            echo '<p>This code\'s going to work</p>';
            echo "<p>\"Be ready\" they said.</p>";


            echo "--------------", "<br>";
            // requires curly braces otherwise will read as manys
            // kind redundent as can label as pages instead of {$many}s
            $many = "page";
            echo "<p>How many {$many}s you have?</p>";
            $manys = "pages";
            echo "<p>How many $manys you have?</p>";


            echo "--------------", "<br>";
            $ExampleString = "woodworking project";
            echo $ExampleString . "<br>";

            echo substr($ExampleString,4) . "<br>";
            echo substr($ExampleString,4,7) . "<br>";
            echo substr($ExampleString,0,8) . "<br>";
            echo substr($ExampleString,-7) . "<br>";
            echo substr($ExampleString,-12,4) . "<br>";
            $subString = substr($ExampleString, 5, -2);
            echo $subString . "<br>";

            echo substr($ExampleString,-5,-2) . "<br>";

            echo strrev($ExampleString) . "<br>";
            echo str_shuffle($ExampleString) . "<br>";


            echo "--------------", "<br>";
            $Email = "email@uow.edu.au";
            echo "<p>if i use strstr - ". strstr($Email, "."). "</p>";
            echo "<p>if i use strstr - ". strstr($Email, ".ed"). "</p>";


            echo "--------------", "<br>";
            $subjects = "CSIT128; CSIT884; CSIT323; MTS9307";
            $subjectsArray = explode(";", $subjects);

            foreach ($subjectsArray as $subject) {
                echo "$subject <br>";
            }


            echo "--------------", "<br>";
            $subjectsArray = array("CSIT128", "CSIT884", "CSIT323", "MTS9307");
            $subjects = implode(", ", $subjectsArray);
            echo $subjects, "<br>";


            echo "--------------", "<br>";
            //regex matching
            // first one returns 0 and second returns 1
            // matches 5 digits
            $ZIP = "015";
            preg_match("/...../", $ZIP);
            echo $ZIP;

            $ZIP = "12345";
            preg_match("/...../", $ZIP);
        ?>
    </body>
</html>