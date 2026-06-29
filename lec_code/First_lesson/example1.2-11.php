<?php
	function addSome(&$text) {
		$text = $text."problem?";
	}

    function check(&$text) {
        $text[] = "problem?";
        print_r($text);
    }

	$myText = "Is there ";
	echo "<p>",$myText, "</p>";
	addSome($myText);
	echo "<p>",$myText, "</p>";

	$new = "second";
	addSome($new);
	echo "<p>",$new, "</p>";

    $new1 = array ("one", "two");
    check($new1);
    echo "<pre>";
    print_r($new1);
    echo "</pre>";
?>

