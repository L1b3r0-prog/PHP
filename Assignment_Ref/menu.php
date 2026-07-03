<?php
session_start();

if (!isset($_SESSION['nickname'])) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Menu';
include 'includes/header.php';
?>

<div class="card">
    <h2>Hi, <?php echo $_SESSION['nickname']; ?>! 👋</h2>
    <p>Your points this game so far: <strong><?php echo $_SESSION['game_points']; ?></strong></p>

    <h3>Choose a quiz topic:</h3>
    <div class="menu-options">
        <a class="btn" href="quiz.php?topic=math">➕ Math Quiz</a>
        <a class="btn" href="quiz.php?topic=sea">🐬 Sea World Quiz</a>
        <a class="btn" href="leaderboard.php">🏆 Leaderboard</a>
        <a class="btn btn-exit" href="exit.php">🚪 Exit</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
