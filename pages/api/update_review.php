<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

require_once 'db.php';

// Directory to store profile JSON files
$profilesDir = __DIR__ . '/profiles/';

// Receive JSON payload
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['id']) || !isset($data['reviews'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data: id and reviews are required']);
    exit;
}

$id = $data['id'];
$reviews = $data['reviews'];

$filePath = $profilesDir . $id . '.json';

if (!file_exists($filePath)) {
    echo json_encode(['success' => false, 'message' => 'Profile not found']);
    exit;
}

// Load existing profile data
$jsonContent = file_get_contents($filePath);
$profileData = json_decode($jsonContent, true);

if (!$profileData) {
    echo json_encode(['success' => false, 'message' => 'Error reading profile data']);
    exit;
}

// Update only the reviews and updated_at timestamp
$profileData['reviews'] = $reviews;
$profileData['updated_at'] = date('Y-m-d H:i:s');

// Save back to file
$result = file_put_contents($filePath, json_encode($profileData, JSON_PRETTY_PRINT));

if ($result !== false) {
    echo json_encode(['success' => true, 'message' => 'Reviews updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update profile reviews']);
}
?>
