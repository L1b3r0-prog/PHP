<?php
    $Email = "my.email@uow.edu.au";
    echo "<p>if I use strstr - " . strstr($Email, ".") . "</p>";
    echo "<p>if I use strstr - " . strstr($Email, ".ed") . "</p>";
    echo "<p> the @ is at position - " . strpos($Email, "@") . "</p>";
    echo "<p>if I replace the email - " . str_replace("email", "e-mail", $Email) . "</p>";
    
    if (strpos($Email, "m") === FALSE)
        echo "the char is not found";
    else
        echo "the char is at position ", strpos($Email, "m");
?>


