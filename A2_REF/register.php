<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Register';
$errors = [];

// Public registration is CLIENT-ONLY. Administrator accounts are provisioned
// separately by an existing admin (see admin_create.php) and are never
// self-registered through this public form.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $type = 'client';

    $errors = User::validate($name, $phone, $email, $password, $type);

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
    <p><small>This form creates a client account. Administrator accounts are created internally by existing staff.</small></p>
    <?php foreach ($errors as $error): ?>
        <div class="alert alert-error"><?= h($error) ?></div>
    <?php endforeach; ?>
    <form method="post">
        <label>Name</label>
        <input type="text" name="name" value="<?= h($_POST['name'] ?? '') ?>" required>

        <label>Phone number</label>
        <input type="text" name="phone" maxlength="8" pattern="[0-9]{1,8}" inputmode="numeric" oninput="this.value=this.value.replace(/[^0-9]/g,'')" value="<?= h($_POST['phone'] ?? '') ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= h($_POST['email'] ?? '') ?>" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button class="btn" type="submit">Register</button>
    </form>
    <p style="margin-top:16px;">Already have an account? <a href="login.php">Login here</a></p>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
