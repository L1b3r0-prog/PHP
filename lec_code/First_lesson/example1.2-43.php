<?php

	for ($count = 0; $count < 5; ++$count) {
		echo $count, " <br /> ";
	}
	echo "------------<br />";
	
	$daysOfWeek = array("Monday", "Tuesday","Wednesday", "Thursday", "Friday","Saturday", "Sunday");

	foreach ($daysOfWeek as $day) {
		 echo "<p>$day</p>";
	}
	echo "------------<br />";

	foreach ($daysOfWeek as $dayNum => $day) {
		 echo "<p>$dayNum is $day</p>";
	}

	echo "------------<br />";

?>
