<html>
    <head>
    </head>

    <body>
        <h1>First PHP Task</h1>
        <?php
            $singleFamilyHome = "5500";
            $singleFamilyHome_Display = number_format($singleFamilyHome, 2);

            echo "<p>The current median price", " of a single-family home in Australia", " is \$$singleFamilyHome_Display.</p>";
        ?>
    </body>
</html>