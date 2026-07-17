<?php
    $pageTitle = "Welcome";
    include 'includes/header.php';
?>

<div class="card">
    <h2>
        Welcome to Learning Hub!
    </h2>
    <p>
        Answer the questions and climb the leaderboard!
    </p>

    <?php if (isset($_GET['error'])):?>
        <p class="error">
            Please enter a nickname to continue.
        </p>
    <?php endif; ?>

    <form action="start.php" method="post">
        <label for="nickname">What's your nickname?</label>
        <input type="text" id="nickname" name="nickname" maxlength="20" pattern="[A-Za-z0-9 ]+" 
        title="Letter and numbers only" required>
        <button type="Submit">Start Game 🚀</button>
    </form>
</div>

<?php
    include "includes/footer.php";
?>