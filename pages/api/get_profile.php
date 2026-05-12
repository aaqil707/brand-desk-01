<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$id = isset($_GET['id']) ? trim($_GET['id']) : '';
if ($id === '') { echo json_encode(['success' => false, 'message' => 'Profile ID is required']); exit; }

$empId = preg_replace('/^prof_/', '', $id);

try {
    $p = $conn->prepare("SELECT * FROM recruiter_profiles WHERE empId = ? LIMIT 1");
    $p->execute([$empId]);
    $profile = $p->fetch(PDO::FETCH_ASSOC);
    if (!$profile) { echo json_encode(['success' => false, 'message' => 'Profile not found']); exit; }

    $r = $conn->prepare("SELECT text, author, rating FROM recruiter_reviews WHERE empId = ? ORDER BY position ASC");
    $r->execute([$empId]);
    $profile['reviews'] = $r->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $profile]);
} catch (PDOException $e) {
    error_log("[get_profile] " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'DB error']);
}