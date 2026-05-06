<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$id = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($id)) {
    echo json_encode(['success' => false, 'message' => 'Profile ID is required']);
    exit;
}

$filePath = __DIR__ . '/profiles/' . basename($id) . '.json';

if (file_exists($filePath)) {
    $jsonContent = file_get_contents($filePath);
    $profile = json_decode($jsonContent, true);

    if (isset($profile['empId']) && !empty($profile['empId'])) {
        $empId = $profile['empId'];
        $googleUrl = 'https://docs.google.com/spreadsheets/d/1d_WRPltqOlzT55bx-tNs0qvd-t9RB9EAeTTsp8m8HdM/gviz/tq?tqx=out:json&gid=1611340410';
        
        $response = @file_get_contents($googleUrl);
        if ($response) {
            $start = strpos($response, '{');
            $end = strrpos($response, '}');
            if ($start !== false && $end !== false) {
                $jsonString = substr($response, $start, $end - $start + 1);
                $data = json_decode($jsonString, true);
                
                if (isset($data['table']['rows']) && isset($data['table']['cols'])) {
                    $cols = $data['table']['cols'];
                    $rows = $data['table']['rows'];
                    
                    $empIdCol = -1;
                    $nameCol = -1;
                    $titleCol = -1;
                    $reviewsCol = -1;
                    
                    foreach ($cols as $index => $col) {
                        if (($col['label'] ?? '') === 'empId') $empIdCol = $index;
                        if (($col['label'] ?? '') === 'Name') $nameCol = $index;
                        if (($col['label'] ?? '') === 'Designation') $titleCol = $index;
                        if (($col['label'] ?? '') === 'Reviews') $reviewsCol = $index;
                    }
                    
                    if ($empIdCol !== -1) {
                        foreach ($rows as $row) {
                            $val = $row['c'][$empIdCol]['v'] ?? null;
                            if ($val !== null && (string)$val === (string)$empId) {
                                // Update Name
                                if ($nameCol !== -1) {
                                    $profile['name'] = $row['c'][$nameCol]['v'] ?? $profile['name'];
                                }
                                // Update Title
                                if ($titleCol !== -1) {
                                    $profile['title'] = $row['c'][$titleCol]['v'] ?? $profile['title'];
                                }
                                // Update Reviews
                                if ($reviewsCol !== -1) {
                                    $reviewsRaw = $row['c'][$reviewsCol]['v'] ?? null;
                                    if ($reviewsRaw) {
                                        // The reviews are stored as a JSON string or a custom format in the sheet.
                                        // If it looks like JSON, parse it. Otherwise, keep it.
                                        $decodedReviews = json_decode($reviewsRaw, true);
                                        $profile['reviews'] = ($decodedReviews !== null) ? $decodedReviews : $reviewsRaw;
                                    }
                                }
                                
                                // Save updated profile back to JSON file
                                file_put_contents($filePath, json_encode($profile, JSON_PRETTY_PRINT));
                                break;
                            }
                        }
                    }
                }
            }
        }
    }
    
    echo json_encode(['success' => true, 'data' => $profile]);
} else {
    echo json_encode(['success' => false, 'message' => 'Profile not found']);
}
?>
