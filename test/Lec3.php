<html>
    <head>
        Lec 3
    </head>

    <body>
        <?php
            $Dir = ".";
            $DirOpen = opendir($Dir);
            while ($CurFile = readdir($DirOpen)) {
                if ((strcmp($CurFile, '.') !=0) && strcmp($CurFile, '..')!=0)
                    echo "<a href=\"./". $CurFile . "\">". $CurFile . "</a><br/>>\n";
            }
            closedir($DirOpen);


            echo "--------------", "<br>";
            // creates a directory if it doesnt exist
            // also doesnt create if it exists
            $dirName = "volunteers";

            if (!file_exists($dirName)) {
                mkdir($dirName);
                echo "Directory created!";
            }
            else {
                echo "Directory already exists";
            }


            echo "--------------", "<br>";

        ?>
    </body>
</html>