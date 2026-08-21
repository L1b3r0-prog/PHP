<?php
    $handle = fopen("data.txt", "w");
    fwrite($handle, "Hello world\n");
    fwrite($handle, "John@email.com\n");
    fclose($handle);
?>