<?php
// E:\version-4\pages\api\sync_sheet.php

header('Content-Type: application/json');

$profileId = $_REQUEST['profile_id'] ?? null;

if (!$profileId) {
    echo json_encode(['success' => false, 'error' => 'Missing profile_id']);
    exit;
}

$filePath = __DIR__ . '/profiles/' . $profileId . '.json';

if (!file_exists($filePath)) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => "Profile file not found: {$profileId}"]);
    exit;
}

$currentData = json_decode(file_get_contents($filePath), true);
if (!$currentData || !isset($currentData['empId'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid profile data or missing empId']);
    exit;
}

$empId = $currentData['empId'];
$googleUrl = 'https://docs.google.com/spreadsheets/d/1d_WRPltqOlzT55bx-tNs0qvd-t9RB9EAeTTsp8m8HdM/gviz/tq?tqx=out:json&gid=1611340410&_cb=' . time();

$response = @file_get_contents($googleUrl);
if ($response === false) {
    echo json_encode(['success' => false, 'error' => 'Unable to reach Google Spreadsheet endpoint']);
    exit;
}

// Remove the google.visualization.Query.setResponse() wrapper
$jsonString = str_replace('google.visualization.Query.setResponse(', '', $response);
$jsonString = rtrim($jsonString, ')');
$data = json_decode($jsonString, true);

if (!$data || !isset($data['table']['rows'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid response from Google Sheet']);
    exit;
}

$rows = $data['table']['rows'];
$foundRow = null;

// Start from index 1 to skip headers
for ($i = 1; $i < count($rows); $i++) {
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
// A: empid [0]
// B: name [1]
// C: designation [2]
// D: email [3]
// E: linkedin_url [4]
// F: Team lead [5]
// G: Lead name [6]
// H: rating [7]
// I: review [8]

$updatedData = $currentData;
$updatedData['name'] = $foundRow[1]['v'] ?? $currentData['name'];
$updatedData['title'] = $foundRow[2]['v'] ?? $currentData['title'];
$updatedData['email'] = $foundRow[3]['v'] ?? $currentData['email'];
$updatedData['linkedin'] = $foundRow[4]['v'] ?? $currentData['linkedin'];
$updatedData['teamLead'] = isset($foundRow[5]['v']) ? (strtolower($foundRow[5]['v']) === 'yes' ? 'yes' : 'no') : $currentData['teamLead'];
$updatedData['leadName'] = $foundRow[6]['v'] ?? $currentData['leadName'];

// Handle reviews and ratings
$rawReviews = $foundRow[8]['v'] ?? '';
$rawRatings = $foundRow[7]['v'] ?? '';

if ($rawReviews) {
    $reviews = [];
    $reviewParts = explode('/n', $rawReviews);
    $ratingParts = explode('/n', $rawRatings);

    foreach ($reviewParts as $index => $reviewText) {
        $reviewText = trim($reviewText);
        if (empty($reviewText)) continue;

        // Split review text and author (format: "text-author")
        $parts = explode('-', $reviewText);
        $author = 'Unknown';
        $text = $reviewText;

        if (count($parts) > 1) {
            $author = trim(array_pop($parts));
            $text = trim(implode('-', $parts));
        }

        $rating = isset($ratingParts[$index]) ? trim($ratingParts[$index]) : null;
        // Try to extract numeric rating if it's like "4 /n 3"
        if ($rating) {
            $rating = (int) filter_var($rating, FILTER_SANITIZE_NUMBER_INT);
        }

        $reviews[] = [
            'text' => $text,
            'author' => $author,
            'rating' => $rating
        ];
    }
    $updatedData['reviews'] = $reviews;
}

$updatedData['updated_at'] = date('Y-m-d H:i:s');

// Optimization: Only write to file if data has actually changed
// Remove updated_at for comparison as it always changes
$comparisonData = $updatedData;
unset($comparisonData['updated_at']);

$currentComparisonData = $currentData;
unset($currentComparisonData['updated_at']);

if (json_encode($comparisonData) === json_encode($currentComparisonData)) {
    echo json_encode(['success' => true, 'data' => $currentData, 'changed' => false]);
    exit;
}

if (file_put_contents($filePath, json_encode($updatedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) === false) {
    echo json_encode(['success' => false, 'error' => 'Failed to write updated profile to file']);
    exit;
}

echo json_encode(['success' => true, 'data' => $updatedData, 'changed' => true]);
