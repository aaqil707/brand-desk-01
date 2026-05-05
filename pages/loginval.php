<?php
session_start();

// Database connection code
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "signature";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    $_SESSION['error'] = "Connection failed. Please try again later.";
    header("Location: loginpage.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if fields are empty
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Please enter both email and password.";
        header("Location: loginpage.php");
        exit();
    }

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['email'] = $email;
        $_SESSION['loggedin'] = true;
        header("Location: ../index.php");
        exit();
    } else {
        $_SESSION['error'] = "⚠️ Invalid email or password";
        header("Location: loginpage.php");
        exit();
    }
    
    $stmt->close();
}

$conn->close();
?>