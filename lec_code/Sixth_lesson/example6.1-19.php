<?php
    setcookie("name", "Elena", time()+3600);
    setcookie("surname", "Vlahu", time()+3600);
    setcookie("occup", "lecturer", time()+3600);
    
    setcookie("firstName", "John");
    setcookie("lastName", "Doe");
    setcookie("occupation", "lecturer");


if (isset($_COOKIE['name']) && isset($_COOKIE['surname']) && isset($_COOKIE['occup']))
     echo "{$_COOKIE['name']} {$_COOKIE['surname']}
           is a {$_COOKIE['occup']}. <br>";

if (isset($_COOKIE['firstName']) && isset($_COOKIE['lastName']) && isset($_COOKIE['occupation']))
     echo "{$_COOKIE['firstName']} {$_COOKIE['lastName']}
           is a {$_COOKIE['occupation']}.";

?>

