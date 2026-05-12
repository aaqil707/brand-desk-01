<?php
require_once 'cors_config.php';
require_once 'db.php';

header('Content-Type: application/json');

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

    if (!$id) {
        throw new Exception('Profile ID is required');
    }

    // The id provided is usually the profile_id (e.g., prof_EMP001)
    $profileId = $id;
    $empId = str_replace('prof_', '', $profileId);

    $conn->beginTransaction();
    try {
        // Delete from recruiter_profiles (cascades to recruiter_reviews)
        $stmt1 = $conn->prepare("DELETE FROM recruiter_profiles WHERE empId = ?");
        $stmt1->execute([$empId]);

        // Delete from user_profiles
        $stmt2 = $conn->prepare("DELETE FROM user_profiles WHERE profile_id = ?");
        $stmt2->execute([$profileId]);

        $conn->commit();
    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }

    // Optionally delete generated images from output/ directory
    $outputDir = __DIR__ . '/../../output/';
    if (is_dir($outputDir)) {
        $files = glob($outputDir . $profileId . '*');
        if ($files) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
    }

    echo json_encode(['success' => true, 'message' => 'Profile deleted successfully']);
} catch (Exception $e) {
    $code = http_response_code() === 200 ? 400 : http_response_code();
    http_response_code($code);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
