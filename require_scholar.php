<?php
// require_scholar.php
// Include at the top of pages that should be scholar-only.
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch role from session if available, otherwise from DB
if (isset($_SESSION['user_role'])) {
    $role = $_SESSION['user_role'];
} else {
    include_once 'config.php';
    $stmt_r = $conn->prepare('SELECT role FROM users WHERE id = ?');
    $stmt_r->bind_param('i', $_SESSION['user_id']);
    $stmt_r->execute();
    $r = $stmt_r->get_result()->fetch_assoc();
    $role = $r['role'] ?? null;
    $stmt_r->close();
}

if ($role !== 'scholar') {
    // route non-scholars to their dashboards
    if ($role === 'applicant') {
        header('Location: dashboard_applicant.php');
        exit();
    }
    if ($role === 'admin') {
        header('Location: dashboard_admin.php');
        exit();
    }
    header('Location: login.php');
    exit();
}
