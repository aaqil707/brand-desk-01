<?php
/**
 * register.php — JSON API endpoint for user registration.
 * Accepts POST with name, email & password, returns JSON response.
 */
header('Content-Type: application/json');

require_once '../session-config.php';
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($name) || empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

// Validate email domain (preserve original business logic)
$allowed_domains = ['vdartinc.com', 'vdartdigital.com', 'trustpeople.com.au', 'dimiour.com'];
$email_domain = strtolower(substr(strrchr($email, "@"), 1));

if (!in_array($email_domain, $allowed_domains)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Only company email addresses are allowed'
    ]);
    exit;
}

try {
    // Check if email already exists
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $checkStmt->execute([$email]);
    $emailExists = $checkStmt->fetchColumn() > 0;

    if ($emailExists) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'Email already registered']);
        exit;
    }

    // Hash password for secure storage
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $hashedPassword]);

    echo json_encode([
        'success' => true,
        'message' => 'Account created successfully'
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}

$conn = null;
?>
