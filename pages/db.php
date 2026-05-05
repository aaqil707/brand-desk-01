<?php
$servername = "localhost";
$username = "root";
$password = "";      // your database password
$dbname = "signature"; // your database name

// Create connection with error reporting
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to ensure proper character encoding
mysqli_set_charset($conn, "utf8mb4");
?>