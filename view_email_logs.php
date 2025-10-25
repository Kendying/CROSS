<?php
include 'config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Handle log clearing if requested
if (isset($_POST['clear_logs']) && $_POST['clear_logs'] === 'yes') {
    file_put_contents(__DIR__ . '/email_log.txt', '');
    header("Location: view_email_logs.php?cleared=1");
    exit();
}

// Read the email log file
$logContent = '';
if (file_exists(__DIR__ . '/email_log.txt')) {
    $logContent = file_get_contents(__DIR__ . '/email_log.txt');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Logs - CROSS Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="sidebar-layout">
        <?php include __DIR__ . '/includes/sidebar.php'; ?>

        <main class="main-content">
            <div class="content-header">
                <h1>Email Logs</h1>
                <p class="text-muted">View email delivery logs and verification links</p>
            </div>

            <?php if (isset($_GET['cleared'])): ?>
            <div class="alert alert-success">
                Email logs have been cleared successfully.
            </div>
            <?php endif; ?>

            <div class="content-body">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2>Email Log Contents</h2>
                        <form method="POST" onsubmit="return confirm('Are you sure you want to clear all email logs?');">
                            <input type="hidden" name="clear_logs" value="yes">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash-alt"></i> Clear Logs
                            </button>
                        </form>
                    </div>
                    
                    <div class="card-body">
                        <?php if (empty($logContent)): ?>
                            <p class="text-muted">No email logs found.</p>
                        <?php else: ?>
                            <div class="email-logs" style="white-space: pre-wrap; font-family: monospace; background: #f8f9fa; padding: 15px; border-radius: 4px; max-height: 600px; overflow-y: auto;">
                                <?php echo htmlspecialchars($logContent); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>