<?php
require_once '../../api/cors_config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

try {
    $id = null;
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        // Handle DELETE /delete_profile.php?id=...
        $id = $_GET['id'] ?? null;
    } else {
        // Handle POST /delete_profile.php with id in body
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;
    }

    if (!$id) {
        throw new Exception('Profile ID is required');
    }

    $safeId = basename($id);
    $filename = "prof_{$safeId}.json";
    $filePath = __DIR__ . '/' . $filename;

    if (!file_exists($filePath)) {
        http_response_code(404);
        throw new Exception('Profile not found');
    }

    if (!is_writable($filePath)) {
        http_response_code(500);
        throw new Exception('Profile file is not writable');
    }

    if (unlink($filePath)) {
        echo json_encode(['status' => 'success', 'message' => 'Profile deleted successfully']);
    } else {
        throw new Exception('Failed to delete profile file');
    }
} catch (Exception $e) {
    $code = http_response_code() === 200 ? 400 : http_response_code();
    http_response_code($code);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
