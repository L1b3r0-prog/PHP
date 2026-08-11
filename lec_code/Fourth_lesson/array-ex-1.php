<body>
<?php
$double = function($a) {
    return $a * 2;
};
function cube($a){
	return $a*$a*$a;
};

// This is our range of numbers
$numbers = range(1, 5);
echo "<pre>";
print_r ($numbers);
echo "</pre>";
echo "--------------<br />";

// Use a callback function here to double the size of each element in our range
$new_numbers = array_map($double, $numbers);
echo "<pre>";
print_r ($new_numbers);
echo "</pre>";
echo "--------------<br />";

// Use a callback function here to cube the size of each element in our range
$new_numbers = array_map('cube', $numbers);
echo "<pre>";
print_r ($new_numbers);
echo "</pre>";
echo "--------------<br />";

//defining callback function
$new_numbers = array_map(function($a) {
							return $a * 5;
						}, $numbers);
echo "<pre>";
print_r ($new_numbers);
echo "</pre>";

echo "--------------<br />";
$a1 = array("a"=>1, "b"=>2, "c"=>3);
$new_numbers = array_map('cube', $a1);
echo "<pre>";
print_r ($new_numbers);
echo "</pre>";

?>
</body>
</html>
