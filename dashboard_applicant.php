<?php
include 'config.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare('SELECT * FROM users WHERE id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Determine application state text
switch ($user['status']) {
    case 'active':
        $app_status = 'Registered (application not yet submitted)';
        break;
    case 'pending':
        $app_status = 'Application submitted — awaiting review';
        break;
    case 'rejected':
        $app_status = 'Application rejected';
        break;
    default:
        $app_status = ucfirst($user['status']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard - CROSS</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="sidebar-layout">
        <?php include __DIR__ . '/includes/sidebar.php'; ?>
        <main class="main-content">
            <div class="content-header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h1 style="margin: 0; color: var(--maroon);">Dashboard</h1>
                        <p style="margin: 5px 0 0; color: var(--text-light);">Welcome to your applicant dashboard</p>
                    </div>
                    <div>
                        <span class="badge" style="background-color: var(--maroon); color: white; padding: 8px 16px; border-radius: 20px;">
                            <i class="fas fa-user"></i> Applicant
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="content-body">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>
                
                <div class="card">
                    <h2>Quick Actions</h2>
                    <div style="margin-top:12px;"><a href="apply.php" class="btn btn-primary">Apply for scholarship</a></div>
                </div>

                <div class="card" id="status" style="margin-top:20px;">
                    <h2>Application status</h2>
                    <p style="font-weight:600; margin-top:8px;"><?php echo $app_status; ?></p>
                    <?php if (!empty($user['rejection_reason'])): ?>
                        <div style="margin-top:12px;">
                            <h4 style="margin:0;">Rejection Reason</h4>
                            <p style="color:#444;"><?php echo nl2br(htmlspecialchars($user['rejection_reason'])); ?></p>
                            <p class="small-note">You may update your profile and reapply.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
