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

    // Save reviews if provided
    if (!empty($data['reviews']) && is_array($data['reviews'])) {
        $conn->beginTransaction();
        $conn->prepare("DELETE FROM recruiter_reviews WHERE empId = ?")->execute([$empId]);
        $ins = $conn->prepare(
            "INSERT INTO recruiter_reviews (empId, position, text, author, rating) VALUES (?,?,?,?,?)"
        );
        foreach ($data['reviews'] as $pos => $review) {
            $text   = trim($review['text']   ?? '');
            $author = trim($review['author'] ?? 'Verified Candidate');
            $rating = round(floatval($review['rating'] ?? 5.0), 1);
            $rating = max(1.0, min(5.0, $rating));
            if ($text !== '') {
                $ins->execute([$empId, $pos, $text, $author, $rating]);
            }
        }
        $conn->commit();
    }

    // Preserve the existing user_profiles mapping for logged-in users
    if (!empty($_SESSION['user_id'])) {
        $name = $data['name'] ?? 'Unknown Profile';
        $m = $conn->prepare("INSERT INTO user_profiles (user_id, profile_id, profile_name) VALUES (?,?,?)
                             ON DUPLICATE KEY UPDATE profile_name = VALUES(profile_name)");
        $m->execute([$_SESSION['user_id'], $profileId, $name]);
    }

    echo json_encode(['success' => true, 'id' => $profileId, 'empId' => $empId]);
} catch (PDOException $e) {
    if ($conn->inTransaction()) $conn->rollBack();
    error_log("[save_profile] " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'DB error']);
}