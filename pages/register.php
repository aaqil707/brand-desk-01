<?php
session_start();
include 'db.php';

$allowedDomains = ['vdartinc.com', 'dimiour.io', 'trustpeople.com'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = strtolower(trim($_POST['email']));
    $password = trim($_POST['password']);

    // Check for empty fields
    if (empty($name) || empty($email) || empty($password)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: loginpage.php");
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Please enter a valid email address.";
        header("Location: loginpage.php");
        exit();
    }

    // Additional email format validation
    if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
        $_SESSION['error'] = "Please enter a valid email address.";
        header("Location: loginpage.php");
        exit();
    }

    // Check domain
    $domain = substr($email, strpos($email, '@') + 1);
    if (!in_array($domain, $allowedDomains)) {
        $_SESSION['error'] = "Registration is only allowed with company email domains (@vdartinc.com, @dimiour.io, @trustpeople.com)";
        header("Location: loginpage.php");
        exit();
    }

    // Sanitize inputs
    $name = mysqli_real_escape_string($conn, $name);
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Check for existing email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "This email is already registered. Please login or use a different email.";
        header("Location: loginpage.php");
        exit();
    } else {
        // Hash password
        // $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            $_SESSION['success'] = "🎉 Welcome to VDart Hub! Your account has been created successfully. Please login to continue.";
            
            // Log success
            $log_message = date('Y-m-d H:i:s') . " - New user registered: " . $email . "\n";
            error_log($log_message, 3, "registration.log");
            
            header("Location: loginpage.php");
            exit();
        } else {
            $_SESSION['error'] = "Registration failed: " . $conn->error . ". Please try again or contact support.";
            
            // Log error
            $error_message = date('Y-m-d H:i:s') . " - Registration failed for: " . $email . " - Error: " . $conn->error . "\n";
            error_log($error_message, 3, "registration_errors.log");
            
            header("Location: loginpage.php");
            exit();
        }
    }

    $stmt->close();
} else {
    header("Location: loginpage.php");
    exit();
}

$conn->close();
?>