<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['user_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $role = $_POST['role'] ?? 'user';

    try {
        // Check if email exists for other users
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND id != ?");
        $checkStmt->execute([$email, $id]);
        $emailExists = $checkStmt->fetchColumn() > 0;

        if ($emailExists) {
            http_response_code(409);
            echo "Email already exists";
            exit;
        }

        // Update user
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
        $stmt->execute([$name, $email, $role, $id]);
        echo "User updated successfully";
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Error updating user";
    }
}
?>