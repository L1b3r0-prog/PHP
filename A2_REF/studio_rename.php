<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_role('admin');

$studioId = (int)($_POST['studio_id'] ?? 0);
$locationId = (int)($_POST['location_id'] ?? 0);
$label = trim($_POST['label'] ?? '');

if ($studioId <= 0 || $locationId <= 0) {
    $_SESSION['flash_error'] = 'Invalid studio.';
    header('Location: location_list.php');
    exit;
}

if (strlen($label) > 50) {
    $_SESSION['flash_error'] = 'Studio name must be 50 characters or fewer.';
} else {
    Studio::rename($studioId, $label);
    $_SESSION['flash_success'] = $label === '' ? 'Studio name cleared.' : 'Studio renamed to "' . $label . '".';
}

header('Location: location_edit.php?id=' . $locationId);
exit;
