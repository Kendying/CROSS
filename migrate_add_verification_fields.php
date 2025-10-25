<?php
// Migration: add verification_token and email_verified columns if missing
require_once __DIR__ . '/config.php';

function column_exists($conn, $table, $column) {
    // Use a direct query with escaped values to avoid issues with prepared statements and SHOW
    $tableEsc = $conn->real_escape_string($table);
    $colEsc = $conn->real_escape_string($column);
    $sql = "SHOW COLUMNS FROM `{$tableEsc}` LIKE '{$colEsc}'";
    $res = $conn->query($sql);
    return ($res && $res->num_rows > 0);
}

$added = [];

if (!column_exists($conn, 'users', 'verification_token')) {
    $sql = "ALTER TABLE users ADD COLUMN verification_token VARCHAR(64) DEFAULT NULL";
    if ($conn->query($sql) === TRUE) {
        $added[] = 'verification_token';
    } else {
        echo "Failed to add verification_token: " . $conn->error . "\n";
    }
}

if (!column_exists($conn, 'users', 'email_verified')) {
    $sql = "ALTER TABLE users ADD COLUMN email_verified TINYINT(1) DEFAULT 0";
    if ($conn->query($sql) === TRUE) {
        $added[] = 'email_verified';
    } else {
        echo "Failed to add email_verified: " . $conn->error . "\n";
    }
}

if (empty($added)) {
    echo "No changes needed. Columns already exist.\n";
} else {
    echo "Added columns: " . implode(', ', $added) . "\n";
}

$conn->close();
?>