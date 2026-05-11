<?php
header('Content-Type: application/json');
$id = isset($_GET['id']) ? basename($_GET['id']) : '';
if (!$id) { echo json_encode(['success' => false]); exit; }
$filePath = __DIR__ . '/profiles/' . $id . '.json';
if (!file_exists($filePath)) { echo json_encode(['success' => false, 'error' => 'Not found']); exit; }
$data = json_decode(file_get_contents($filePath), true);
echo json_encode(['success' => true, 'data' => $data, 'ts' => filemtime($filePath)]);
