<?php 
$users = [ ['Tom', 'tom@email.com'], 
			['Sam', 'sam@email.com'], 
			['Kim', 'kim@email.com']];

echo "<pre>";
print_r($users);
echo "</pre>";

echo "--------------<br />";
$users = [ ['name' => 'Tom', 'email' => 'tom@email.com'], 
			['name' => 'Sam', 'email' => 'sam@email.com'], 
			['name' => 'Kim', 'email' => 'kim@email.com']];

echo "<pre>";
print_r($users);
echo "</pre>";

echo "--------------<br />";

$users = [ "user1" => ['name' => 'Tom', 'email' => 'tom@email.com'], 
			"user2" => ['name' => 'Sam', 'email' => 'sam@email.com'], 
			"user3" => ['name' => 'Kim', 'email' => 'kim@email.com']];

echo "<pre>";
print_r($users);
echo "</pre>";

echo "--------------<br />";

foreach($users as $user){
	//print_r($user);
	echo"<p>" . $user['name'] . "-" . $user['email'] . "</p>";
}

echo "--------------<br />";

foreach($users as $user){
	foreach ($user as $key => $info)
		echo"$key: $info, ";
	echo "<br>";
}

echo "--------------<br />";

foreach($users as ['name' => $name, 'email' => $email]){
	echo"name: $name, email: $email <br>";
}

?>