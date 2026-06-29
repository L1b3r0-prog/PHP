<?php
	function makeSentence($name, $activity="no activity", $hours="") {
		return "Hi $name, you have $activity for $hours hrs";
	}
	echo makeSentence("John"), '<br>';
	echo makeSentence("John", "swimming"), '<br>';
	echo makeSentence("John", "swimming", "1"), '<br>';

	echo makeSentence("John", activity: "hiking"), '<br>';

	echo makeSentence(activity: "hiking", name: "John",  hours: "8"), '<br>';
	echo makeSentence("John", hours: "8", activity: "hiking"), '<br>';

	echo makeSentence("John", hours: "5"), '<br>';
	echo makeSentence(hours: "5", name: "John"), '<br>';

    echo "<p>--------------</p>";

	 function makeSentence1(&$name, $activity="no activity", $hours="") {
		 return "Hi $name, you have $activity for $hours hrs";
	 }
	$nn = "John";
	echo makeSentence1($nn), '<br>';
	echo makeSentence1($nn, "swimming"), '<br>';
	echo makeSentence1($nn, "swimming", "1"), '<br>';
	echo makeSentence1(activity: "hiking", name: $nn,  hours: "8"), '<br>';
?>
