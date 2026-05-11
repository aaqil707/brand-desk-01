<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

error_log("[sheets-sync] Incoming payload: " . file_get_contents('php://input'));

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

try {
    $input = file_get_contents('php://input');
    if (empty($input)) {
        throw new Exception('Empty request body');
    }

    $data = json_decode($input, true);
    if (!$data) {
        throw new Exception('Invalid JSON payload');
    }

    // Identify the profile. Expecting 'id' or 'empId' in the payload from Apps Script
    $id = $data['id'] ?? $data['empId'] ?? null;
    if (!$id) {
        throw new Exception('Profile identifier missing in payload');
    }

    // Ensure the ID is safe to use as a filename
    $safeId = basename($id);
    
    // Check if it starts with prof_, if not, maybe it's just the empId
    $filename = str_starts_with($safeId, 'prof_') ? $safeId . '.json' : "prof_{$safeId}.json";
    $filePath = __DIR__ . '/../profiles/' . $filename;

    if (!file_exists($filePath)) {
        // If the file doesn't exist, we might want to create it or log it.
        // Given "aggressively overwrites", we assume the file should exist.
        // But for robustness, we'll create it if it's a valid sync.
    }

    // Read existing profile if it exists
    $existingProfile = file_exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];

    // Merge only the fields that come from the sheet
    $merged = array_merge($existingProfile, [
      'name'      => $data['name']     ?? $existingProfile['name']     ?? '',
      'title'     => $data['title']    ?? $existingProfile['title']    ?? '',
      'email'     => $data['email']    ?? $existingProfile['email']    ?? '',
      'linkedin'  => $data['linkedin'] ?? $existingProfile['linkedin'] ?? '',
      'teamLead'  => $data['teamLead'] ?? $existingProfile['teamLead'] ?? 'no',
      'leadName'  => $data['leadName'] ?? $existingProfile['leadName'] ?? '',
      'updated_at'=> date('Y-m-d H:i:s'),
    ]);

    // Handle reviews — only update if the sheet sent review data
    if (!empty($data['review'])) {
      $reviews = [];
      $reviewParts = explode('/n', $data['review']);
      $ratingParts = explode('/n', $data['rating'] ?? '');
      foreach ($reviewParts as $i => $text) {
        $text = trim($text);
        if (!$text) continue;
        $parts = explode('-', $text);
        $author = count($parts) > 1 ? trim(array_pop($parts)) : 'Unknown';
        $text = trim(implode('-', $parts));
        $rating = isset($ratingParts[$i]) ? (int) filter_var(trim($ratingParts[$i]), FILTER_SANITIZE_NUMBER_INT) : 5;
        $reviews[] = ['text' => $text, 'author' => $author, 'rating' => $rating];
      }
      $merged['reviews'] = $reviews;
    }

    if (file_put_contents($filePath, json_encode($merged, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX) === false) {
        throw new Exception('Failed to write to profile file: ' . $filename);
    }

    echo json_encode(['status' => 'success', 'message' => "Profile $filename synchronized from Sheets"]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
