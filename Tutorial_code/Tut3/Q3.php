<?php
    session_start();

    if (!isset($_SESSION['xyz'])){
        $_SESSION['xyz'] += 1;
    }
    else{
        $_SESSION['xyz'] = 1;
    }

    $msg = "You have visited this page = " . $_SESSION['cnt'];
?>

<html>
    <body>
        <?php
            echo $msg;
        ?>
    </body>
</html>