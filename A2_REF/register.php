<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Register';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $type = $_POST['type'] ?? 'client';
    $type = in_array($type, ['admin', 'client'], true) ? $type : 'client';

    $errors = User::validate($name, $phone, $email, $password);

    if (empty($errors)) {
        try {
            $userId = User::register($name, $phone, $email, $password, $type);
            $_SESSION['flash_success'] = 'Registration successful. Please log in.';
            header('Location: login.php');
            exit;
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
}

require __DIR__ . '/includes/header.php';
?>
<div class="card" style="max-width:480px;margin:0 auto;">
    <h1>Register</h1>
    <?php foreach ($errors as $error): ?>
        <div class="alert alert-error"><?= h($error) ?></div>
    <?php endforeach; ?>
    <form method="post">
        <label>Account Type</label>
        <select name="type">
            <option value="client" <?= ($_POST['type'] ?? '') === 'client' ? 'selected' : '' ?>>Client</option>
            <option value="admin" <?= ($_POST['type'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrator</option>
        </select>

        <label>Full Name</label>
        <input type="text" name="name" value="<?= h($_POST['name'] ?? '') ?>" required>

        <label>Phone</label>
        <input type="text" name="phone" value="<?= h($_POST['phone'] ?? '') ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= h($_POST['email'] ?? '') ?>" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button class="btn" type="submit">Register</button>
    </form>
    <p style="margin-top:16px;">Already have an account? <a href="login.php">Login here</a></p>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
