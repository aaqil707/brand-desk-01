<?php
header('Content-Type: application/json');
if (session_status() === PHP_SESSION_NONE) @session_start();
require_once __DIR__ . '/db.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['id']) || !isset($data['reviews'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data: id and reviews are required']);
    exit;
}

$profileId = trim($data['id']);
$reviews   = $data['reviews'];

// Strip prof_ prefix to get empId
$empId = preg_replace('/^prof_/', '', $profileId);

try {
    // Verify the profile exists
    $check = $conn->prepare("SELECT empId FROM recruiter_profiles WHERE empId = ? LIMIT 1");
    $check->execute([$empId]);
    if (!$check->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Profile not found']);
        exit;
    }

    // Replace reviews atomically
    $conn->beginTransaction();
    $conn->prepare("DELETE FROM recruiter_reviews WHERE empId = ?")->execute([$empId]);

    $ins = $conn->prepare("INSERT INTO recruiter_reviews (empId, position, text, author, rating) VALUES (?,?,?,?,?)");
    foreach ($reviews as $pos => $review) {
        $text   = trim($review['text']   ?? '');
        $author = trim($review['author'] ?? 'Verified Candidate');
        $rating = (int) round(floatval($review['rating'] ?? 5));
        $rating = max(1, min(5, $rating));
        if ($text !== '') {
            $ins->execute([$empId, $pos, $text, $author, $rating]);
        }
    }
    $conn->commit();

    echo json_encode(['success' => true, 'message' => 'Reviews updated successfully']);
} catch (PDOException $e) {
    if ($conn->inTransaction()) $conn->rollBack();
    error_log("[update_review] " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>