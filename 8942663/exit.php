<?php
    session_start();
    require "includes/functions.php";

    if (!isset($_SESSION['nickname'])) {
        header("Location: index.php");
        exit;
    }

    $nickname = $_SESSION['nickname'];

    $board = loadLeaderboard();
    $totalPoints = isset($board[$nickname]) ? $board[$nickname] : 0;

    $pageTitle = "Goodbye";
    include "includes/header.php";
?>

<div class="card">
    <h2>
        Thanks for playing, <?php echo $nickname; ?>!
    </h2>

    <div>
        <a href="newgame.php" class="btn">
            Start New Game
        </a>
        <a href="leaderboard.php" class="btn">
            View Leaderboard
        </a>
    </div>
</div>

<?php
    include "includes/footer.php";
?>