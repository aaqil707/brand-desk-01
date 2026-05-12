<?php
require_once 'cors_config.php';
header('Content-Type: application/json');
if (session_status() === PHP_SESSION_NONE) @session_start();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

try {
    $id = null;
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $id = $_GET['id'] ?? null;
    } else {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;
    }

    if (!$id) throw new Exception('Profile ID is required');

    $profileId = trim(basename($id));
    $empId = preg_replace('/^prof_/', '', $profileId);

    // Verify profile exists in DB
    $check = $conn->prepare("SELECT empId FROM recruiter_profiles WHERE empId = ? LIMIT 1");
    $check->execute([$empId]);
    if (!$check->fetch()) {
        http_response_code(404);
        throw new Exception('Profile not found');
    }

    // Delete from recruiter_profiles (cascade deletes reviews via FK)
    $conn->prepare("DELETE FROM recruiter_profiles WHERE empId = ?")->execute([$empId]);
    // Also clean up user_profiles mapping
    $conn->prepare("DELETE FROM user_profiles WHERE profile_id = ?")->execute([$profileId]);

    echo json_encode(['status' => 'success', 'message' => 'Profile deleted successfully']);
} catch (Exception $e) {
    $code = http_response_code() === 200 ? 400 : http_response_code();
    http_response_code($code);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
