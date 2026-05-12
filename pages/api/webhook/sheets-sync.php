<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../config.php';

$raw = file_get_contents('php://input');
error_log("[sheets-sync] raw=" . $raw);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']); exit;
}

// Optional token check — log mismatches but do not block (Apps Script may not send header)
$token = $_SERVER['HTTP_X_WEBHOOK_TOKEN'] ?? '';
if ($token !== '' && $token !== WEBHOOK_SECRET) {
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
        // Delimiter: /n (literal), \\n (backslash-n), actual newline, or pipe
        $delim = '/\s*(?:\/n|\\\\n|\r?\n|\|)\s*/i';

        $reviewParts = preg_split($delim, trim((string)$data['review']));
        $ratingParts = preg_split($delim, trim((string)($data['rating'] ?? '')));

        $conn->beginTransaction();
        $conn->prepare("DELETE FROM recruiter_reviews WHERE empId = ?")->execute([$empId]);

        $ins = $conn->prepare("INSERT INTO recruiter_reviews (empId, position, text, author, rating) VALUES (?,?,?,?,?)");
        $pos = 0;
        foreach ($reviewParts as $i => $chunk) {
            // Strip /e end marker (marks last review in sheet cell)
            $chunk = trim(preg_replace('/\s*\/e\s*$/i', '', trim($chunk)));
            if ($chunk === '') continue;

            // Split "review text-author" on the LAST dash only
            $lastDash = strrpos($chunk, '-');
            if ($lastDash !== false) {
                $author = trim(substr($chunk, $lastDash + 1));
                $chunk  = trim(substr($chunk, 0, $lastDash));
            } else {
                $author = 'Unknown';
            }

            // Preserve decimal ratings (e.g. 4.5)
            $rawRating = trim($ratingParts[$i] ?? '');
            $rating = round(floatval($rawRating), 1);
            if ($rating < 1.0 || $rating > 5.0) $rating = 5.0;

            $ins->execute([$empId, $pos++, $chunk, $author, $rating]);
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
