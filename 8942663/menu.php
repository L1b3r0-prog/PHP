<?php
    session_start();

    if (!isset($_SESSION['nickname'])) {
        header("Location: index.php");
        exit;
    }

    $pageTitle = "Menu";
    include "includes/header.php";
?>

<div class="card">
    <h2>
        Hi, <?php echo $_SESSION['nickname']; ?>! 👋
    </h2>

    <p>
        your points this game so far: <strong><?php echo $_SESSION['game_points']; ?></strong>
    </p>

    <h3>
        Choose a quiz topic:
    </h3>

    <div class="menu-options">
        <a href="quiz.php?topic=math" class="btn">Math</a>
        <a href="quiz.php?topic=sea" class="btn">Sea</a>
        <a href="leaderboard.php" class="btn">Leaderboard</a>
        <a href="exit.php" class="btn btn-exit">Exit</a>
    </div>
</div>

<?php
    include "includes/footer.php"
?>