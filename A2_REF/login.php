<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Login';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $row = User::attemptLogin($email, $password);
    if ($row) {
        $_SESSION['user_id'] = (int)$row['user_id'];
        $_SESSION['user_type'] = $row['type'];
        $_SESSION['user_name'] = $row['name'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid email or password.';
    }
}

require __DIR__ . '/includes/header.php';
?>
<div class="card" style="max-width:420px;margin:0 auto;">
    <h1>Login</h1>
    <?php if ($error): ?><div class="alert alert-error"><?= h($error) ?></div><?php endif; ?>
    <form method="post">
        <label>Email</label>
        <input type="email" name="email" value="<?= h($_POST['email'] ?? '') ?>" required>
        <label>Password</label>
        <input type="password" name="password" required>
        <button class="btn" type="submit">Login</button>
    </form>
    <p style="margin-top:16px;">No account? <a href="register.php">Register here</a></p>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
