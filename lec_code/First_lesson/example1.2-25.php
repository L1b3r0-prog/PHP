<?php
    $glVar = "this is my value";

    function scopeExample() {
        global $glVar;
        echo "<p>$glVar</p>";
        $glVar = "if I change it";
    }
    
    scopeExample();
    echo "<p>$glVar</p>";
    echo "<p>something to display</p>";

    $glVar = "new value";
    echo "<p>$glVar</p>";
?>
