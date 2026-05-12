<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$id = isset($_GET['id']) ? trim($_GET['id']) : '';
if ($id === '') { echo json_encode(['success' => false, 'error' => 'id required']); exit; }

// Accept either "prof_EMP001" or "EMP001"
$empId = preg_replace('/^prof_/', '', $id);

try {
    $p = $conn->prepare("SELECT * FROM recruiter_profiles WHERE empId = ? LIMIT 1");
    $p->execute([$empId]);
    $profile = $p->fetch(PDO::FETCH_ASSOC);
    if (!$profile) { echo json_encode(['success' => false, 'error' => 'Not found']); exit; }

    $r = $conn->prepare("SELECT text, author, rating FROM recruiter_reviews WHERE empId = ? ORDER BY position ASC");
    $r->execute([$empId]);
    $profile['reviews'] = $r->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data'    => $profile,
        'ts'      => strtotime($profile['updated_at']),  // useful for frontend change-detection
    ]);
} catch (PDOException $e) {
    error_log("[poll_profile] " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'DB error']);
}