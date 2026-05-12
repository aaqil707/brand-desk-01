<?php
require_once 'db.php';
header('Content-Type: application/json');

$profileId = $_REQUEST['profile_id'] ?? null;

if (!$profileId) {
    echo json_encode(['success' => false, 'error' => 'Missing profile_id']);
    exit;
}

// Strip 'prof_' prefix to get empId
$empId = str_replace('prof_', '', $profileId);

try {
    // Load current profile from database
    $stmt = $conn->prepare("SELECT * FROM recruiter_profiles WHERE empId = ?");
    $stmt->execute([$empId]);
    $currentProfile = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$currentProfile) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => "Profile not found for Employee ID: {$empId}"]);
        exit;
    }

    $googleUrl = 'https://docs.google.com/spreadsheets/d/1tOHMOzioUGjjwmJvSlEFHrPrP1hXphk46q8Q_VrejVk/gviz/tq?tqx=out:json&_cb=' . time();
    $response = @file_get_contents($googleUrl);
    if ($response === false) {
        echo json_encode(['success' => false, 'error' => 'Unable to reach Google Spreadsheet endpoint']);
        exit;
    }

    $jsonString = str_replace('google.visualization.Query.setResponse(', '', $response);
    $jsonString = rtrim($jsonString, ')');
    $data = json_decode($jsonString, true);

    if (!$data || !isset($data['table']['rows'])) {
        echo json_encode(['success' => false, 'error' => 'Invalid response from Google Sheet']);
        exit;
    }

    $rows = $data['table']['rows'];
    $foundRow = null;

    // Fix: Start from index 0 (Bug 5)
    for ($i = 0; $i < count($rows); $i++) {
        $cells = $rows[$i]['c'];
        if (isset($cells[0]['v']) && $cells[0]['v'] === $empId) {
            $foundRow = $cells;
            break;
        }
    }

    if (!$foundRow) {
        echo json_encode(['success' => false, 'error' => "Employee ID {$empId} not found in spreadsheet"]);
        exit;
    }

    // Mapping:
    // 0: empid, 1: name, 2: designation, 3: email, 4: linkedin_url, 5: Team lead, 6: Lead name, 7: rating, 8: review
    $name = $foundRow[1]['v'] ?? $currentProfile['name'];
    $title = $foundRow[2]['v'] ?? $currentProfile['title'];
    $email = $foundRow[3]['v'] ?? $currentProfile['email'];
    $linkedin = $foundRow[4]['v'] ?? $currentProfile['linkedin'];
    $teamLead = isset($foundRow[5]['v']) ? (strtolower($foundRow[5]['v']) === 'yes' ? 'yes' : 'no') : $currentProfile['teamLead'];
    $leadName = $foundRow[6]['v'] ?? $currentProfile['leadName'];

    // Update recruiter_profiles if data changed
    $changed = false;
    if ($name !== $currentProfile['name'] || $title !== $currentProfile['title'] || 
        $email !== $currentProfile['email'] || $linkedin !== $currentProfile['linkedin'] || 
        $teamLead !== $currentProfile['teamLead'] || $leadName !== $currentProfile['leadName']) {
        
        $updateStmt = $conn->prepare("UPDATE recruiter_profiles SET name = ?, title = ?, email = ?, linkedin = ?, teamLead = ?, leadName = ?, updated_at = CURRENT_TIMESTAMP WHERE empId = ?");
        $updateStmt->execute([$name, $title, $email, $linkedin, $teamLead, $leadName, $empId]);
        $changed = true;
    }

    // Handle reviews and ratings
    $rawReviews = $foundRow[8]['v'] ?? '';
    $rawRatings = $foundRow[7]['v'] ?? '';
    $reviews = [];

    if ($rawReviews) {
        // Fix: Use "\n" as separator (Bug 3)
        $reviewParts = explode("\n", $rawReviews);
        $ratingParts = explode("\n", $rawRatings);

        foreach ($reviewParts as $index => $reviewText) {
            $reviewText = trim($reviewText);
            if (empty($reviewText)) continue;

            $parts = explode('-', $reviewText);
            $author = 'Unknown';
            $text = $reviewText;

            if (count($parts) > 1) {
                $author = trim(array_pop($parts));
                $text = trim(implode('-', $parts));
            }

            $rating = isset($ratingParts[$index]) ? trim($ratingParts[$index]) : null;
            if ($rating) {
                $rating = (int) filter_var($rating, FILTER_SANITIZE_NUMBER_INT);
            }

            $reviews[] = [
                'text' => $text,
                'author' => $author,
                'rating' => $rating
            ];
        }
    }

    // Check if reviews changed
    $currentReviewsStmt = $conn->prepare("SELECT position, text, author, rating FROM recruiter_reviews WHERE empId = ? ORDER BY position ASC");
    $currentReviewsStmt->execute([$empId]);
    $dbReviews = $currentReviewsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Simple comparison of reviews
    $dbReviewsClean = array_map(function($r) {
        return ['text' => $r['text'], 'author' => $r['author'], 'rating' => (int)$r['rating']];
    }, $dbReviews);

    if (json_encode($reviews) !== json_encode($dbReviewsClean)) {
        $conn->beginTransaction();
        try {
            $delStmt = $conn->prepare("DELETE FROM recruiter_reviews WHERE empId = ?");
            $delStmt->execute([$empId]);

            $insStmt = $conn->prepare("INSERT INTO recruiter_reviews (empId, position, text, author, rating) VALUES (?, ?, ?, ?, ?)");
            foreach ($reviews as $idx => $rev) {
                $insStmt->execute([$empId, $idx, $rev['text'], $rev['author'], $rev['rating']]);
            }
            $conn->commit();
            $changed = true;
        } catch (Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    // Fetch final state to return
    $stmt = $conn->prepare("SELECT * FROM recruiter_profiles WHERE empId = ?");
    $stmt->execute([$empId]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT position, text, author, rating FROM recruiter_reviews WHERE empId = ? ORDER BY position ASC");
    $stmt->execute([$empId]);
    $reviewsFinal = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => [
            'profile' => $profile,
            'reviews' => $reviewsFinal
        ],
        'changed' => $changed
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
