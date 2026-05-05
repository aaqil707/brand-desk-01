<?php
// session-config.php - Include this at the top of all your pages

// Configure session settings before starting the session
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.use_trans_sid', 0);
ini_set('session.cache_limiter', 'nocache');

// Make sure sessions are stored in a writeable location
// Uncomment and modify this if your sessions aren't working
// ini_set('session.save_path', '/tmp');

// Start the session
session_start();

// Make sure the session has an ID
if (session_id() === '') {
    // Try to start it again if it failed
    session_start();
    
    // If it still doesn't work, log the error
    if (session_id() === '') {
        error_log(date('Y-m-d H:i:s') . " - WARNING: Failed to start session\n", 3, "session_errors.log");
    }
}

// Debug the session ID and path
error_log(date('Y-m-d H:i:s') . " - Session ID: " . session_id() . " - Save Path: " . ini_get('session.save_path') . "\n", 3, "session_debug.log");

// Check if session is writable by trying to set a test value
$_SESSION['test_value'] = time();
if (!isset($_SESSION['test_value']) || $_SESSION['test_value'] !== time()) {
    error_log(date('Y-m-d H:i:s') . " - WARNING: Session is not writable\n", 3, "session_errors.log");
}
?>