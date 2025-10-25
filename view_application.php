<?php
include 'config.php';

// Admin only
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: admin_login.php');
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: pending_approvals.php');
    exit();
}

$stmt = $conn->prepare('SELECT * FROM users WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    $_SESSION['error'] = 'User not found.';
    header('Location: pending_approvals.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>View Application - CROSS</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="sidebar-layout">
        <?php include __DIR__ . '/includes/sidebar.php'; ?>
        <main class="main-content">
            <div class="content-header">
                <h1>Application Details</h1>
            </div>
            <div class="content-body">
                <div class="card">
                    <h3><?php echo htmlspecialchars($user['fullname']); ?></h3>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Course:</strong> <?php echo htmlspecialchars($user['course']); ?></p>
                    <p><strong>Year Level:</strong> <?php echo htmlspecialchars($user['year_level']); ?></p>
                    <p><strong>ID Number:</strong> <?php echo htmlspecialchars($user['id_number']); ?></p>
                    <p><strong>Applied On:</strong> <?php echo date('M j, Y H:i', strtotime($user['created_at'])); ?></p>
                    <?php if (!empty($user['rejection_reason'])): ?>
                        <p><strong>Previous Rejection Reason:</strong> <?php echo nl2br(htmlspecialchars($user['rejection_reason'])); ?></p>
                    <?php endif; ?>
                </div>

                <div style="margin-top: 16px;">
                    <form method="POST" action="process_approval.php" style="display:inline;">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_get_token()); ?>">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <input type="hidden" name="action" value="approve">
                        <button type="submit" class="btn btn-success">Approve</button>
                    </form>

                    <form method="POST" action="process_approval.php" style="display:inline; margin-left:8px;" onsubmit="return confirm('Are you sure you want to reject this application?')">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_get_token()); ?>">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <input type="hidden" name="action" value="reject">
                        <input type="text" name="reason" placeholder="Rejection reason (optional)" style="padding:8px; margin-left:8px;">
                        <button type="submit" class="btn btn-danger">Reject</button>
                    </form>
                </div>

                <div style="margin-top:20px;">
                    <a href="pending_approvals.php" class="btn">Back to pending list</a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>