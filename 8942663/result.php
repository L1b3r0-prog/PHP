<?php
    session_start();

    if (!isset($_SESSION["nickname"]) || !isset($_SESSION["last_result"])) {
        header("Location: menu.php");
        exit;
    }

    $result = $_SESSION["last_result"];
    $pageTitle = "Quiz Result";
    include "includes/header.php";
?>

<div class="card">
    <h2>
        Quiz Result 📋
    </h2>

    <p>
        Topic: <strong><?php echo $result["topic"]; ?></strong>
    </p>

    <p>
        Correct answers: <strong><?php echo $result["correct"]; ?></strong>
    </p>

    <p>
        Incorrect answers: <strong><?php echo $result["incorrect"]; ?></strong>
    </p>

    <p>
        Points earned from correct answers: <strong>+<?php echo $result["pointsEarned"];?></strong>
    </p>

    <p>
        Points lost from incorrect answers: <strong>-<?php echo $result["pointsLost"]; ?></strong>
    </p>

    <hr>

    <p>
        Your overall points this game: <strong><?php echo $_SESSION["game_points"]; ?></strong>
    </p>

    <div class="menu-options">
        <a href="menu.php" class="btn">🔁 New Quiz</a>
        <a href="leaderboard.php" class="btn">🏆 Leaderboard</a>
        <a href="exit.php" class="btn btn-exit">🚪 Exit</a>
    </div>
</div>