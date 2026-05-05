<?php
/**
 * login.php — JSON API endpoint for authentication.
 * Accepts POST with email & password, returns JSON response.
 */
header('Content-Type: application/json');

require_once '../session-config.php';
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email and password are required']);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Check password — supports both hashed and plain-text (legacy)
        $passwordValid = false;
        if (password_verify($password, $user['password'])) {
            $passwordValid = true;
        } elseif ($password === $user['password']) {
            // Legacy plain-text password support
            $passwordValid = true;
        }

        if ($passwordValid) {
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['user_id'] = $user['id'];

            echo json_encode([
                'success' => true,
                'message' => 'Login successful',
                'user' => [
                    'email' => $user['email'],
                    'name' => $user['name'],
                    'role' => 'user',
                ]
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Invalid password']);
        }
    } else {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

$conn = null;
?>
