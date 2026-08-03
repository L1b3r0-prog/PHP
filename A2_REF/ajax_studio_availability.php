<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();

header('Content-Type: application/json');

$locationId = (int)($_GET['location_id'] ?? 0);
$date = trim($_GET['date'] ?? '');
$startTime = trim($_GET['start_time'] ?? '');
$duration = (int)($_GET['duration'] ?? 0);

if ($locationId <= 0 || $date === '' || $startTime === '' || $duration <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing or invalid parameters.']);
    exit;
}

$errors = Booking::validate($date, $startTime, $duration);
if (!empty($errors)) {
    echo json_encode(['error' => implode(' ', $errors)]);
    exit;
}

$start = date('H:i:s', strtotime($startTime));
$end = Booking::calculateEndTime($start, $duration);

$studios = Studio::allAvailable($locationId, $date, $start, $end);

$out = array_map(function ($s) {
    return [
        'studio_id' => (int)$s['studio_id'],
        'name'      => Studio::displayName($s['label'], $s['studio_number']),
    ];
}, $studios);

echo json_encode($out);
