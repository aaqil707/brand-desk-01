<?php
require_once 'db.php';

if (isset($_GET['id'])) {
    try {
        $stmt = $conn->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Return as comma-separated string
            echo $user['id'] . ',' . $user['name'] . ',' . $user['email'] . ',' . $user['role'];
        } else {
            http_response_code(404);
            echo "User not found";
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Error fetching user";
    }
}
?>