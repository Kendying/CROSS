<?php
include 'config.php';
include 'mailer_wrapper.php';

// Check admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
	header('Location: admin_login.php');
	exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// CSRF validation
	$csrf = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
	if (!csrf_validate($csrf)) {
		$_SESSION['error'] = 'Invalid or missing CSRF token.';
		header('Location: pending_approvals.php');
		exit();
	}
	$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;
	$action = isset($_POST['action']) ? $_POST['action'] : null;
	$reason = isset($_POST['reason']) ? trim($_POST['reason']) : null;

	if (!$user_id || !$action) {
		$_SESSION['error'] = 'Invalid request.';
		header('Location: dashboard_admin.php');
		exit();
	}

	// Fetch user
	$stmt = $conn->prepare('SELECT * FROM users WHERE id = ?');
	$stmt->bind_param('i', $user_id);
	$stmt->execute();
	$user = $stmt->get_result()->fetch_assoc();
	$stmt->close();

	if (!$user) {
		$_SESSION['error'] = 'User not found.';
		header('Location: dashboard_admin.php');
		exit();
	}

	if ($action === 'approve') {
		$update = $conn->prepare("UPDATE users SET status = 'approved', role = 'scholar', rejection_reason = NULL WHERE id = ?");
		$update->bind_param('i', $user_id);
		if ($update->execute()) {
			$_SESSION['success'] = 'Application approved.';
			$message = "Congratulations, your scholarship application has been approved. You are now a scholar in the CROSS system.";
			send_email($user['email'], 'Application Approved', $message);
		} else {
			$_SESSION['error'] = 'Failed to approve application.';
		}
		$update->close();
	} elseif ($action === 'reject') {
		$update = $conn->prepare("UPDATE users SET status = 'rejected', rejection_reason = ? WHERE id = ?");
		$update->bind_param('si', $reason, $user_id);
		if ($update->execute()) {
			$_SESSION['success'] = 'Application rejected.';
			$message = "We regret to inform you that your scholarship application was not approved.";
			if ($reason) $message .= "\n\nReason: {$reason}";
			send_email($user['email'], 'Application Result', $message);
		} else {
			$_SESSION['error'] = 'Failed to reject application.';
		}
		$update->close();
	}

	header('Location: pending_approvals.php');
	exit();
}
?>