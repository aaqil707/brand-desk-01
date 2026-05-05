<?php
require_once 'db.php';

try {
    // Simple query to get users
    $stmt = $conn->query("SELECT id, name, email, role FROM users ORDER BY name ASC");
    
    // Check if we have any users
    if ($stmt->rowCount() > 0) {
        // Loop through users and output HTML directly
        while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td class='px-6 py-4'>" . htmlspecialchars($user['name']) . "</td>";
            echo "<td class='px-6 py-4'>" . htmlspecialchars($user['email']) . "</td>";
            echo "<td class='px-6 py-4'>" . htmlspecialchars($user['role']) . "</td>";
            echo "<td class='px-6 py-4 text-right'>";
            echo "<td>
                <button class='action-btn edit-btn' onclick='editUser(" . $row['id'] . ")'>
                    <i class='fas fa-edit'></i>
                </button>
                <button class='action-btn delete-btn' onclick='deleteUser(" . $row['id'] . ")'>
                    <i class='fas fa-trash'></i>
                </button>
            </td>";
            // echo "<i class='fas fa-trash-alt'></i>";
            echo "</button>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        // No users found
        echo "<tr><td colspan='3' class='text-center py-4'>No users found</td></tr>";
    }
} catch (PDOException $e) {
    // Output error message
    echo "<tr><td colspan='3' class='text-center py-4 text-red-600'>";
    echo "<i class='fas fa-exclamation-circle'></i> Error loading users: " . htmlspecialchars($e->getMessage());
    echo "</td></tr>";
}

$conn = null;
?>