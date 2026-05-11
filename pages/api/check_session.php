<?php
require_once 'cors_config.php';
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
set_time_limit(5);

$response = ['loggedIn' => false, 'user' => null];

try {
    if (session_status() === PHP_SESSION_NONE) {
        @ini_set('session.use_strict_mode', 0);
        @session_start();
    }
    
    if (!empty($_SESSION['email'])) {
        $response = [
            'loggedIn' => true,
            'user' => [
                'email' => $_SESSION['email'] ?? '',
                'name' => $_SESSION['name'] ?? '',
                'role' => $_SESSION['role'] ?? 'user',
            ]
        ];
    }
} catch (Throwable $e) {
    error_log('Session check error: ' . $e->getMessage());
}

echo json_encode($response);
exit;
?>
