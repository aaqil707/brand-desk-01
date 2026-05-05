<?php
// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
try {
    require 'db.php';
    echo "Database connection successful<br><br>";
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

try {
    // Get all tables in the database
    echo "Attempting to get tables...<br>";
    $sql = "SHOW TABLES";
    $tablesResult = $conn->query($sql);
    
    if (!$tablesResult) {
        throw new Exception("Error getting tables: " . $conn->error);
    }
    
    echo "Number of tables found: " . $tablesResult->num_rows . "<br>";

    // Loop through each table
    while ($table = $tablesResult->fetch_array()) {
        $tableName = $table[0];
        echo "Processing table: " . htmlspecialchars($tableName) . "<br>";
        
        // Get records from current table
        echo "Attempting to get records from " . htmlspecialchars($tableName) . "<br>";
        $recordsSql = "SELECT * FROM `" . $tableName . "`";
        $recordsResult = $conn->query($recordsSql);
        
        if (!$recordsResult) {
            echo "Error reading from table $tableName: " . $conn->error . "<br>";
            continue;
        }
        
        echo "<h3>Table: " . htmlspecialchars($tableName) . "</h3>";
        echo "<table border='1' style='margin-bottom: 20px; border-collapse: collapse;'>";
        
        // Display column headers
        $fields = $recordsResult->fetch_fields();
        echo "<tr style='background-color: #f2f2f2;'>";
        foreach ($fields as $field) {
            echo "<th style='padding: 8px; border: 1px solid #ddd;'>" . 
                 htmlspecialchars($field->name) . "</th>";
        }
        echo "</tr>";
        
        // Display records
        if ($recordsResult->num_rows > 0) {
            while ($row = $recordsResult->fetch_assoc()) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . 
                         htmlspecialchars($value ?? 'NULL') . "</td>";
                }
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='" . count($fields) . "' style='padding: 8px; border: 1px solid #ddd;'>" .
                 "No records found</td></tr>";
        }
        
        echo "</table>";
    }
    
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
} finally {
    // Close connection
    $conn->close();
    echo "Connection closed<br>";
}
?>