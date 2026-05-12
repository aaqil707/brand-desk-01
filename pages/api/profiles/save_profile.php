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

    $data = json_decode($input, true, 512, JSON_THROW_ON_ERROR);

    if (!isset($data['id']) || !isset($data['name'])) {
        throw new Exception('Missing required fields: id and name');
    }

    $id = basename($data['id']);
    $filename = "prof_{$id}.json";

    if (file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX) === false) {
        throw new Exception('Failed to write profile to file');
    }

    // Outbound Sync to Google Sheets
    $url = 'https://script.google.com/a/macros/vdartinc.com/s/AKfycby1fQUkgYnAnQ8PuiX_UbWCX7SVPvGtYUsxXc0wyj4bgdKJhaLqNxrEjyd7gQxENvXjoQ/exec';
    $payload = json_encode($data);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_exec($ch);
    curl_close($ch);

    echo json_encode(['status' => 'success', 'message' => 'Profile saved successfully', 'id' => $id]);
} catch (JsonException $e) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON payload: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
