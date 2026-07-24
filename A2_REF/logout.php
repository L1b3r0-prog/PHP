<?php
require_once __DIR__ . '/includes/bootstrap.php';
User::logout();
header('Location: login.php');
exit;
