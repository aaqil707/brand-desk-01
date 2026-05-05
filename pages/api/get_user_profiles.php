<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

require_once 'db.php';

if (empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("SELECT profile_id as id, profile_name as name, updated_at as date FROM user_profiles WHERE user_id = ? ORDER BY updated_at DESC");
    $stmt->execute([$userId]);
    $profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'data' => $profiles]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>