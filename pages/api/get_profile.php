<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$id = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($id)) {
    echo json_encode(['success' => false, 'message' => 'Profile ID is required']);
    exit;
}

$filePath = __DIR__ . '/profiles/' . basename($id) . '.json';

if (file_exists($filePath)) {
    $data = file_get_contents($filePath);
    echo json_encode(['success' => true, 'data' => json_decode($data, true)]);
} else {
    echo json_encode(['success' => false, 'message' => 'Profile not found']);
}
?>
