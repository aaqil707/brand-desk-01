<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) @session_start();

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) { echo json_encode(['success' => false, 'message' => 'Invalid data']); exit; }

// empId is required for DB-backed profiles. If frontend doesn't send it, fall back to uniqid for legacy generator flow.
$empId     = trim($data['empId'] ?? '');
if ($empId === '') $empId = strtoupper(uniqid('GEN'));
$profileId = "prof_$empId";

try {
    $stmt = $conn->prepare("
        INSERT INTO recruiter_profiles
          (empId, profile_id, name, title, phone, email, linkedin, teamLead, leadName, photoUrl)
        VALUES (?,?,?,?,?,?,?,?,?,?)
        ON DUPLICATE KEY UPDATE
          name=VALUES(name), title=VALUES(title), phone=VALUES(phone),
          email=VALUES(email), linkedin=VALUES(linkedin),
          teamLead=VALUES(teamLead), leadName=VALUES(leadName), photoUrl=VALUES(photoUrl)
    ");
    $stmt->execute([
        $empId, $profileId,
        $data['name']     ?? null,
        $data['title']    ?? null,
        $data['phone']    ?? null,
        $data['email']    ?? null,
        $data['linkedin'] ?? null,
        strtolower($data['teamLead'] ?? 'no'),
        $data['leadName'] ?? null,
        $data['photoUrl'] ?? null,
    ]);

    // Preserve the existing user_profiles mapping for logged-in users
    if (!empty($_SESSION['user_id'])) {
        $name = $data['name'] ?? 'Unknown Profile';
        $m = $conn->prepare("INSERT INTO user_profiles (user_id, profile_id, profile_name) VALUES (?,?,?)
                             ON DUPLICATE KEY UPDATE profile_name = VALUES(profile_name)");
        $m->execute([$_SESSION['user_id'], $profileId, $name]);
    }

    echo json_encode(['success' => true, 'id' => $profileId, 'empId' => $empId]);
} catch (PDOException $e) {
    error_log("[save_profile] " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'DB error']);
}