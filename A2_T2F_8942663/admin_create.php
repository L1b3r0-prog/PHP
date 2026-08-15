<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_role('admin');
$pageTitle = 'Add Administrator';
$errors = [];

// This page is only reachable by someone already logged in as admin.
// It is intentionally not linked from register.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $type = 'admin';

    $errors = User::validate($name, $phone, $email, $password, $type);

    if (empty($errors)) {
        try {
            User::register($name, $phone, $email, $password, $type);
            $_SESSION['flash_success'] = 'Administrator account created for ' . $email . '.';
            header('Location: admin_client_list.php');
            exit;
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
}

require __DIR__ . '/includes/header.php';
?>
<div class="card" style="max-width:480px;margin:0 auto;">
    <h1>Add Administrator</h1>
    <p><small>Staff accounts only. Must use the organisation email domain.</small></p>
    <?php foreach ($errors as $error): ?>
        <div class="alert alert-error"><?= h($error) ?></div>
    <?php endforeach; ?>
    <form method="post">
        <label>Full Name</label>
        <input type="text" name="name" value="<?= h($_POST['name'] ?? '') ?>" required>

        <label>Phone number</label>
        <input type="text" name="phone" maxlength="8" pattern="[0-9]{1,8}" inputmode="numeric" oninput="this.value=this.value.replace(/[^0-9]/g,'')" value="<?= h($_POST['phone'] ?? '') ?>" required>

        <label>Email (@myrecordingstudio.com)</label>
        <input type="email" name="email" value="<?= h($_POST['email'] ?? '') ?>" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button class="btn" type="submit">Create Administrator</button>
    </form>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
