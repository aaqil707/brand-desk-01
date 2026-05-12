<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../config.php';

$raw = file_get_contents('php://input');
error_log("[sheets-sync] " . $raw);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']); exit;
}

// Verify Webhook Secret
$token = $_SERVER['HTTP_X_WEBHOOK_TOKEN'] ?? '';
if ($token !== WEBHOOK_SECRET) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized: Invalid Webhook Token']);
    exit;
}

try {
    $data = json_decode($raw, true);
    if (!$data) throw new Exception('Invalid JSON payload');

    $empId = isset($data['empId']) ? trim((string)$data['empId']) : '';
    if ($empId === '') throw new Exception('Missing empId');

    $profileId = "prof_$empId";

    // UPSERT profile
    $stmt = $conn->prepare("
        INSERT INTO recruiter_profiles
          (empId, profile_id, name, title, phone, email, linkedin, teamLead, leadName)
        VALUES (?,?,?,?,?,?,?,?,?)
        ON DUPLICATE KEY UPDATE
          name=VALUES(name), title=VALUES(title), phone=VALUES(phone),
          email=VALUES(email), linkedin=VALUES(linkedin),
          teamLead=VALUES(teamLead), leadName=VALUES(leadName)
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
    ]);

    // Replace reviews atomically: only if the sheet sent review data
    if (!empty($data['review'])) {
        $reviewParts = explode("\n", (string)$data['review']);
        $ratingParts = explode("\n", (string)($data['rating'] ?? ''));

        $conn->beginTransaction();
        $conn->prepare("DELETE FROM recruiter_reviews WHERE empId = ?")->execute([$empId]);

        $ins = $conn->prepare("INSERT INTO recruiter_reviews (empId, position, text, author, rating) VALUES (?,?,?,?,?)");
        $pos = 0;
        foreach ($reviewParts as $i => $text) {
            $text = trim($text);
            if ($text === '') continue;

            // Split "text-author" — keep middle dashes inside text
            $parts  = explode('-', $text);
            $author = count($parts) > 1 ? trim(array_pop($parts)) : 'Unknown';
            $text   = trim(implode('-', $parts));

            // Clean rating: extract first integer 1..5, default 5
            $rawRating = $ratingParts[$i] ?? '';
            $rating = (int) filter_var($rawRating, FILTER_SANITIZE_NUMBER_INT);
            if ($rating < 1 || $rating > 5) $rating = 5;

            $ins->execute([$empId, $pos++, $text, $author, $rating]);
        }
        $conn->commit();
    }

    echo json_encode(['status' => 'success', 'empId' => $empId]);

} catch (Exception $e) {
    if (isset($conn) && $conn->inTransaction()) $conn->rollBack();
    http_response_code(400);
    error_log("[sheets-sync] ERROR: " . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
}
?>
