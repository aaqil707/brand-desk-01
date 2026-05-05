<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user'; // Added role

    try {
        // First check if email already exists
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $checkStmt->execute([$email]);
        $emailExists = $checkStmt->fetchColumn() > 0;

        if ($emailExists) {
            http_response_code(409); // Conflict status code
            echo "Email already exists";
            exit;
        }

        // If email doesn't exist, proceed with insertion including role
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $password, $role]);
        echo "User added successfully";
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Error adding user";
    }
}
?>