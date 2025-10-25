<?php
// Migration: add token_created_at column for verification link expiry
require_once __DIR__ . '/config.php';

function column_exists($conn, $table, $column) {
    $tableEsc = $conn->real_escape_string($table);
    $colEsc = $conn->real_escape_string($column);
    $sql = "SHOW COLUMNS FROM `{$tableEsc}` LIKE '{$colEsc}'";
    $res = $conn->query($sql);
    return ($res && $res->num_rows > 0);
}

if (!column_exists($conn, 'users', 'token_created_at')) {
    $sql = "ALTER TABLE users ADD COLUMN token_created_at TIMESTAMP NULL DEFAULT NULL";
    if ($conn->query($sql) === TRUE) {
        echo "Added token_created_at column.\n";
    } else {
        echo "Failed to add token_created_at: " . $conn->error . "\n";
    }
}

$conn->close();
?>