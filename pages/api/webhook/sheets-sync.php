<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

try {
    $input = file_get_contents('php://input');
    if (empty($input)) {
        throw new Exception('Empty request body');
    }

    $data = json_decode($input, true);
    if (!$data) {
        throw new Exception('Invalid JSON payload');
    }

    // Identify the profile. Expecting 'id' or 'empId' in the payload from Apps Script
    $id = $data['id'] ?? $data['empId'] ?? null;
    if (!$id) {
        throw new Exception('Profile identifier missing in payload');
    }

    // Ensure the ID is safe to use as a filename
    $safeId = basename($id);
    
    // Check if it starts with prof_, if not, maybe it's just the empId
    $filename = str_starts_with($safeId, 'prof_') ? $safeId . '.json' : "prof_{$safeId}.json";
    $filePath = __DIR__ . '/../profiles/' . $filename;

    if (!file_exists($filePath)) {
        // If the file doesn't exist, we might want to create it or log it.
        // Given "aggressively overwrites", we assume the file should exist.
        // But for robustness, we'll create it if it's a valid sync.
    }

    // Aggressively overwrite the local JSON file with the new data
    if (file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX) === false) {
        throw new Exception('Failed to write to profile file: ' . $filename);
    }

    echo json_encode(['status' => 'success', 'message' => "Profile $filename synchronized from Sheets"]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
