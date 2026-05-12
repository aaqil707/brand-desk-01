<?php
// E:\version-4\pages\api\proxy_sheet.php

// Allow cross-origin requests from the React frontend
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if (!isset($_GET['empId'])) {
    echo json_encode(['error' => 'Missing Employee ID']);
    exit;
}

$empId = urlencode($_GET['empId']);
$url = "https://script.google.com/a/macros/vdartinc.com/s/AKfycby1fQUkgYnAnQ8PuiX_UbWCX7SVPvGtYUsxXc0wyj4bgdKJhaLqNxrEjyd7gQxENvXjoQ/exec" . $empId;

// Initialize cURL. Google Apps Script requires following 302 Redirects.
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Important: Google scripts redirect!
curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($response === false) {
    echo json_encode(['error' => 'cURL Error: ' . $error]);
    exit;
}

if ($httpCode >= 400) {
    echo json_encode(['error' => "HTTP Error $httpCode. The script might be restricted to authenticated users only."]);
    exit;
}

// Return the direct response from Google
echo $response;
