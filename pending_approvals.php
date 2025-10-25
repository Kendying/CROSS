<?php
include 'config.php';

// Only admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
	header('Location: admin_login.php');
	exit();
}

// Fetch pending applicants
$res = $conn->query("SELECT * FROM users WHERE role = 'applicant' AND status = 'pending' ORDER BY created_at DESC");
$pending_applications = [];
if ($res) {
	while ($row = $res->fetch_assoc()) $pending_applications[] = $row;
}

// Get pending count for sidebar
$pending_count_res = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'applicant' AND status = 'pending'");
$pending_count = $pending_count_res ? $pending_count_res->fetch_assoc()['count'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Pending Approvals - CROSS</title>
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
	<script>
		function confirmReject(form) {
			const reason = prompt('Enter a reason for rejection (optional):');
			if (reason === null) return false;
			form.reason.value = reason;
			return confirm('Are you sure you want to reject this application?');
		}
	</script>
</head>
<body>
	<div class="sidebar-layout">
        <?php include __DIR__ . '/includes/sidebar.php'; ?>
        <main class="main-content">
            <div class="content-header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h1 style="margin: 0; color: var(--maroon);">Pending Applications</h1>
                        <p style="margin: 5px 0 0; color: var(--text-light);">Review and approve/reject applications</p>
                    </div>
                    <div>
                        <span class="badge" style="background-color: var(--maroon); color: white; padding: 8px 16px; border-radius: 20px;">
                            <i class="fas fa-user-shield"></i> Admin
                        </span>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <?php if (empty($pending_applications)): ?>
                    <div class="alert alert-info">No pending applications at this time.</div>
                <?php else: ?>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Course</th>
                                    <th>Year Level</th>
                                    <th>Applied On</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($pending_applications as $app): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($app['fullname']); ?></td>
                                    <td><?php echo htmlspecialchars($app['email']); ?></td>
                                    <td><?php echo htmlspecialchars($app['course']); ?></td>
                                    <td><?php echo htmlspecialchars($app['year_level']); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($app['created_at'])); ?></td>
                                    <td>
                                        <a href="view_application.php?id=<?php echo $app['id']; ?>" class="btn">View</a>
                                        <form method="POST" action="process_approval.php" style="display:inline;margin-left:8px;">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_get_token()); ?>">
                                            <input type="hidden" name="user_id" value="<?php echo $app['id']; ?>">
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="btn btn-success">Approve</button>
                                        </form>
                                        <form method="POST" action="process_approval.php" style="display:inline; margin-left:8px;" onsubmit="return confirmReject(this);">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_get_token()); ?>">
                                            <input type="hidden" name="user_id" value="<?php echo $app['id']; ?>">
                                            <input type="hidden" name="action" value="reject">
                                            <input type="hidden" name="reason" value="">
                                            <button type="submit" class="btn btn-danger">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>