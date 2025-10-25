<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: apply.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$statement = isset($_POST['statement']) ? trim($_POST['statement']) : null;
$additional = isset($_POST['additional']) ? trim($_POST['additional']) : null;

if (empty($statement)) {
    header('Location: apply.php?error=statement_required');
    exit();
}

// Save application into a dedicated table `applications` if present, otherwise update users table fields
$create_app_table_sql = "
CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    statement TEXT,
    additional TEXT,
    status ENUM('pending','reviewed','rejected','approved') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)
";
$conn->query($create_app_table_sql);

$ins = $conn->prepare("INSERT INTO applications (user_id, statement, additional) VALUES (?, ?, ?) ");
$ins->bind_param('iss', $user_id, $statement, $additional);
$ok = $ins->execute();
$ins->close();

if ($ok) {
    // Mark user application status as pending
    $u = $conn->prepare("UPDATE users SET status = 'pending' WHERE id = ?");
    $u->bind_param('i', $user_id);
    $u->execute();
    $u->close();

    // Optionally notify admin (mailer_wrapper fallback)
    if (function_exists('send_email')) {
        send_email('admin@localhost', 'New application submitted', "User ID {$user_id} submitted an application.");
    }

    header('Location: dashboard_applicant.php?success=application_submitted');
    exit();
} else {
    header('Location: apply.php?error=save_failed');
    exit();
}
