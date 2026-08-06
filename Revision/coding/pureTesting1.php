<?php
    echo "<p>PHP Literal text string</p>";

    $stringVariable = "<p>PHP Literal text string</p>";
    echo $stringVariable;

    echo '<p>This code\'s going to work</p>';

    echo "<p>\"Be ready\" they said.";

    $many = "page";
    echo "<p>How many {$many}s you have?";

    $title = "I love PHP";
    echo "<p>The title contains " . strlen($title) . " chars, ";
    echo " and " . str_word_count($title) . " words.</p>";

    $subjects = "CSIT218; CSIT884; CSIT323; MTS9307";
    $subjectsArray = explode(";", $subjects);

    foreach ($subjectsArray as $subject) {
        echo "$subject <br>";
    }

    $array = array("CSIT128", "CSIT884", "CSIT323", "MST9307");
    $arrays = implode(",", $array);
    echo $arrays;
?>