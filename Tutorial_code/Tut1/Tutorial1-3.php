<html>
    <head></head>
    <body>
        <h1>Third PHP Task</h1>
        <?php
            $passwd = "2jekFhj #";
            if (preg_match("/^(?=.*\d)(?=.*[A-Z])(?!.* )(?=.*[^a-zA-Z0-9]).{8,16}$/", $passwd))
                echo "match";
            else
                echo "not a match";
        ?>
    </body>
</html>