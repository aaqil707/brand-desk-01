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
if (!file_exists($profilesDir)) {
    mkdir($profilesDir, 0755, true);
}

// Receive JSON payload
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

/**
 * Sends profile data to Google Apps Script with exponential backoff.
 */
function syncToGoogleSheets($data) {
    $url = 'https://script.google.com/macros/s/YOUR_GOOGLE_SCRIPT_ID/exec'; // REPLACE WITH ACTUAL WEB APP URL
    $payload = json_encode($data);
    
    $maxRetries = 3;
    $retryCount = 0;
    $wait = 1; // Initial wait in seconds

    while ($retryCount < $maxRetries) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Important for Google Script redirects
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            return true; // Success
        }

        $retryCount++;
        if ($retryCount < $maxRetries) {
            sleep($wait);
            $wait *= 2; // Exponential backoff
        }
    }
    return false;
}

// Generate a unique ID for the profile if not provided
$id = isset($data['id']) && !empty($data['id']) ? $data['id'] : uniqid('prof_');
$data['id'] = $id;
$data['updated_at'] = date('Y-m-d H:i:s');

// Save to file
$filePath = $profilesDir . $id . '.json';
$result = file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));

if ($result !== false) {
    // Outbound Sync: Send to Google Sheets
    syncToGoogleSheets($data);

    // If logged in, save to database
    if (!empty($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        $profileName = $data['name'] ?? 'Unknown Profile';
        try {
            $stmt = $conn->prepare("INSERT INTO user_profiles (user_id, profile_id, profile_name) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE profile_name = ?");
            $stmt->execute([$userId, $id, $profileName, $profileName]);
        } catch (PDOException $e) {
            error_log("Failed to insert user profile: " . $e->getMessage());
        }
    }
    
    echo json_encode(['success' => true, 'id' => $id]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save profile']);
}
?>
