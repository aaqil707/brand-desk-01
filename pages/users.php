<?php
// Include database connection
include 'db.php';

// Select the correct database (if needed)
mysqli_select_db($conn, 'formdata');

// Query to get all records from paperworkdetails table
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

// Check if there are any records
if ($result->num_rows > 0) {
    // Start the table
    echo "<table border='1'>";
    
    // Dynamically generate the table headers
    echo "<tr>";
    $fields = $result->fetch_fields(); // Get all column names
    foreach ($fields as $field) {
        echo "<th>" . $field->name . "</th>";
    }
    echo "</tr>";
    
    // Fetch and display each record dynamically
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $columnData) {
            echo "<td>" . $columnData . "</td>";
        }
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "No records found.";
}

// Close connection
$conn->close();
?>
