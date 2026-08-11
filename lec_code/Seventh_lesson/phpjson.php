<?php
$data = ["contacts" => [["id" => 37, "name" => "Tom White", "phone" => "02 1555 1212"],
		["id" => 42, "name" => "Rita Brown", "phone" => "02 2555 1212"],
		["id" => 56, "name" => "Rick Jones", "phone" => "04 1235 6765"]
		]];

$json_output = json_encode($data); 
echo $json_output;
echo "<br>---------<br>";
$json_output = json_encode($data, JSON_PRETTY_PRINT ); 
echo $json_output;

echo "<br>---------<br>";
echo "<br>---------<br>";

$json_input = file_get_contents('contacts.json');
$data_object = json_decode($json_input);
echo "<pre>";
print_r($data_object);
echo "</pre>";
echo "<br>---------<br>";
$data_array = json_decode($json_input, true);
echo "<pre>";
print_r($data_array);
echo "</pre>";

?>
