<?php
/**
 * logout.php — JSON API endpoint for session destruction.
 * Clears all session data and returns JSON confirmation.
 */
header('Content-Type: application/json');

require_once '../session-config.php';

// Destroy session
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

echo json_encode([
    'success' => true,
    'message' => 'Logged out successfully'
]);
?>
