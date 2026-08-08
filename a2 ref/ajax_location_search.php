<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();

header('Content-Type: application/json');

$term = trim($_GET['q'] ?? '');

// Empty query -> empty result set, don't return everything.
if ($term === '') {
    echo json_encode([]);
    exit;
}

// Matches on location description AND studio labels, e.g. typing "vocal"
// finds a location whose studio is named "Vocal Booth".
$results = Location::searchWithStudios($term);

$out = array_map(function ($loc) {
    return [
        'location_id'    => (int)$loc['location_id'],
        'description'    => $loc['description'],
        'num_studios'    => (int)$loc['num_studios'],
        'cost_per_hour'  => (float)$loc['cost_per_hour'],
        'matched_studio' => $loc['matched_studio_label'] ?? null,
    ];
}, $results);

echo json_encode($out);
