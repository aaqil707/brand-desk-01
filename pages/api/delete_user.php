<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    try {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        echo "User deleted successfully";
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Error deleting user";
    }
}
?>