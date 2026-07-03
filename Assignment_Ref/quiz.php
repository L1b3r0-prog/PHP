<?php
session_start();
require 'includes/functions.php';

if (!isset($_SESSION['nickname'])) {
    header('Location: index.php');
    exit;
}

$topic = isset($_GET['topic']) ? $_GET['topic'] : (isset($_POST['topic']) ? $_POST['topic'] : '');

if (!in_array($topic, ['math', 'sea'])) {
    header('Location: menu.php');
    exit;
}

$error = '';
$quizQuestions = null;

// ---- Handle quiz submission ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_quiz'])) {

    $questions = isset($_SESSION['current_quiz']) ? $_SESSION['current_quiz'] : [];

    // Server-side check: every question must have an answer before we score it.
    // (Client-side "required" attributes stop most submissions before this point,
    // but a user could disable JS or POST directly, so we re-check here too.)
    $allAnswered = true;
    foreach ($questions as $i => $q) {
        $userAnswer = isset($_POST['answer' . $i]) ? trim($_POST['answer' . $i]) : '';
        if ($userAnswer === '') {
            $allAnswered = false;
            break;
        }
    }

    if (!$allAnswered) {
        $error = 'Please answer all 3 questions before submitting.';
        // Re-show the SAME 3 questions (don't generate a new random set)
        $quizQuestions = $questions;
    } else {
        $correct = 0;
        $incorrect = 0;

        foreach ($questions as $i => $q) {
            if ($topic === 'math') {
                $userAnswer = trim($_POST['answer' . $i]);
                if (is_numeric($userAnswer) && (int)$userAnswer === $q['answer']) {
                    $correct++;
                } else {
                    $incorrect++;
                }
            } else { // sea
                $userAnswer = $_POST['answer' . $i];
                $userSaysCorrect = ($userAnswer === 'correct') ? 1 : 0;
                if ($userSaysCorrect === $q['correct']) {
                    $correct++;
                } else {
                    $incorrect++;
                }
            }
        }

        $points = ($correct * 3) - ($incorrect * 2);
        $_SESSION['game_points'] += $points;

        // Save to the leaderboard file immediately, right after this quiz,
        // instead of waiting until Exit.
        updateLeaderboard($_SESSION['nickname'], $points);

        $_SESSION['last_result'] = [
            'topic'     => $topic,
            'correct'   => $correct,
            'incorrect' => $incorrect,
            'points'    => $points,
        ];

        unset($_SESSION['current_quiz']);

        header('Location: result.php');
        exit;
    }
}

// ---- Generate a fresh quiz (GET request), unless we're re-showing after a validation error ----
if ($quizQuestions === null) {
    $allQuestions  = ($topic === 'math') ? loadMathQuestions() : loadSeaQuestions();
    $quizQuestions = getRandomQuestions($allQuestions, 3);
    $_SESSION['current_quiz'] = $quizQuestions;
}

$pageTitle = ($topic === 'math') ? 'Math Quiz' : 'Sea World Quiz';
include 'includes/header.php';
?>

<div class="card">
    <h2><?php echo $topic === 'math' ? '➕ Math Quiz' : '🐬 Sea World Quiz'; ?></h2>

    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form action="quiz.php" method="post" id="quizForm">
        <input type="hidden" name="topic" value="<?php echo htmlspecialchars($topic); ?>">

        <?php foreach ($quizQuestions as $i => $q): ?>
            <div class="question">
                <?php if ($topic === 'math'): ?>
                    <p>Q<?php echo $i + 1; ?>: What is <?php echo $q['op1']; ?> <?php echo htmlspecialchars($q['operator']); ?> <?php echo $q['op2']; ?>?</p>
                    <input type="number" name="answer<?php echo $i; ?>" placeholder="Type your answer"
                           value="<?php echo isset($_POST['answer' . $i]) ? htmlspecialchars($_POST['answer' . $i]) : ''; ?>"
                           required>
                <?php else: ?>
                    <p>Q<?php echo $i + 1; ?>: Is this animal's name correct?</p>
                    <img src="images/<?php echo htmlspecialchars($q['image']); ?>" alt="Sea animal" class="sea-img">
                    <p class="animal-label"><?php echo htmlspecialchars($q['label']); ?></p>
                    <?php $prev = isset($_POST['answer' . $i]) ? $_POST['answer' . $i] : ''; ?>
                    <label><input type="radio" name="answer<?php echo $i; ?>" value="correct" <?php echo $prev === 'correct' ? 'checked' : ''; ?> required> Correct</label>
                    <label><input type="radio" name="answer<?php echo $i; ?>" value="incorrect" <?php echo $prev === 'incorrect' ? 'checked' : ''; ?>> Incorrect</label>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <button type="submit" name="submit_quiz" value="1">Submit Quiz ✅</button>
    </form>
</div>

<script>
// Belt-and-braces client-side check (the PHP-side check above is what actually matters for security/correctness)
document.getElementById('quizForm').addEventListener('submit', function (e) {
    const form = e.target;
    const groups = {};
    form.querySelectorAll('input[name^="answer"]').forEach(function (input) {
        if (!groups[input.name]) groups[input.name] = [];
        groups[input.name].push(input);
    });

    let missing = false;
    Object.values(groups).forEach(function (inputs) {
        if (inputs[0].type === 'radio') {
            if (!inputs.some(function (r) { return r.checked; })) missing = true;
        } else if (inputs[0].value.trim() === '') {
            missing = true;
        }
    });

    if (missing) {
        e.preventDefault();
        alert('Please answer all 3 questions before submitting.');
    }
});
</script>

<?php include 'includes/footer.php'; ?>
