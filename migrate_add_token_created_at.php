<?php
include 'config.php';

try {
    // Check if the column already exists
    $result = $conn->query("SHOW COLUMNS FROM `users` LIKE 'token_created_at'");
    if ($result->num_rows === 0) {
        // Add the token_created_at column
        $sql = "ALTER TABLE `users` ADD `token_created_at` DATETIME NULL AFTER `verification_token`";
        if ($conn->query($sql) === TRUE) {
            echo "Column 'token_created_at' added successfully.";
        } else {
            echo "Error adding column: " . $conn->error;
        }
    } else {
        echo "Column 'token_created_at' already exists.";
    }
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage();
}

$conn->close();
?>