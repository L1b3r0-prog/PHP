<?php
    session_start();

    $nickname = isset($_POST["nickname"]) ? trim($_POST["nickname"]) : "";

    if ($nickname === "" || !preg_match('/^[A-Za-z0-9 ]+$/', $nickname)) {
        header("Location: index.php?error=1");
        exit;
    }

    $_SESSION["nickname"] = htmlspecialchars($nickname);
    $_SESSION["game_points"] = 0;

    header("Location: menu.php");
    exit;
?>