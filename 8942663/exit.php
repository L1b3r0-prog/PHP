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

<main class="card">
    <h2>Thanks for playing, <?php echo $nickname; ?>!</h2>

    <p>Your overall points (all games): <strong><?php echo $totalPoints; ?></strong></p>

    <nav class="menu-options">
        <a href="newgame.php" class="btn">Start New Game</a>
    </nav>
</main>

<?php include "includes/footer.php"; ?>