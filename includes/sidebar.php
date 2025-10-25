<?php
// Centralized sidebar include. Prefer authoritative role from DB using session user_id.
$user_name = 'User';
$user_role = 'applicant';
$current_page = basename($_SERVER['SCRIPT_NAME'] ?? '');
$pending_count = 0;

if (isset($_SESSION['user_id']) && !empty($conn)) {
    $uid = $_SESSION['user_id'];
    $stmt = $conn->prepare('SELECT role, fullname FROM users WHERE id = ? LIMIT 1');
    if ($stmt) {
        $stmt->bind_param('i', $uid);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if ($row) {
            $user_role = $row['role'] ?? $user_role;
            $user_name = $row['fullname'] ?? ($_SESSION['user_name'] ?? $user_name);
        } else {
            // fallback to session values if DB lookup fails
            $user_name = $_SESSION['user_name'] ?? $user_name;
            $user_role = $_SESSION['user_role'] ?? $user_role;
        }
        $stmt->close();
    } else {
        // fallback to session values when prepare fails
        $user_name = $_SESSION['user_name'] ?? $user_name;
        $user_role = $_SESSION['user_role'] ?? $user_role;
    }
} else {
    $user_name = $_SESSION['user_name'] ?? ($user['fullname'] ?? $user_name);
    $user_role = $_SESSION['user_role'] ?? ($user['role'] ?? $user_role);
}

// Pending count for admin (recalculate only when admin)
if ($user_role === 'admin' && !empty($conn)) {
    $res = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'applicant' AND status = 'pending'");
    $pending_count = $res ? intval($res->fetch_assoc()['count']) : 0;
}
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">CROSS</div>
        <p><?php echo $user_role === 'applicant' ? 'Applicant Portal' : ($user_role === 'admin' ? 'Admin Portal' : 'Scholar Portal'); ?></p>
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">
                <?php echo strtoupper(substr($user_name, 0, 1)); ?>
            </div>
            <div class="sidebar-user-info">
                <h4><?php echo htmlspecialchars($user_name); ?></h4>
                <p><?php echo ucfirst($user_role); ?></p>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <?php if ($user_role === 'applicant'): ?>
            <a href="dashboard_applicant.php" class="sidebar-nav-item<?php echo $current_page === 'dashboard_applicant.php' ? ' active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i>
                Applicant Dashboard
            </a>
            <a href="apply.php" class="sidebar-nav-item<?php echo $current_page === 'apply.php' ? ' active' : ''; ?>">
                <i class="fas fa-file-alt"></i>
                Apply for scholarship
            </a>
            <a href="profile.php" class="sidebar-nav-item<?php echo $current_page === 'profile.php' ? ' active' : ''; ?>">
                <i class="fas fa-info-circle"></i>
                Application status
            </a>
            <a href="announcements.php" class="sidebar-nav-item<?php echo $current_page === 'announcements.php' ? ' active' : ''; ?>">
                <i class="fas fa-bullhorn"></i>
                Announcements
            </a>
        <?php elseif ($user_role === 'admin'): ?>
            <!-- Admin-only navigation -->
            <a href="dashboard_admin.php" class="sidebar-nav-item<?php echo $current_page === 'dashboard_admin.php' ? ' active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i>
                Admin Dashboard
            </a>
            <a href="pending_approvals.php" class="sidebar-nav-item<?php echo $current_page === 'pending_approvals.php' ? ' active' : ''; ?>">
                <i class="fas fa-user-clock"></i>
                Pending Applications
                <?php if ($pending_count > 0): ?>
                    <span class="badge" style="background-color:var(--maroon); color:#fff; padding:4px 8px; border-radius:12px; margin-left:8px; font-size:0.85em; vertical-align:middle;"><?php echo $pending_count; ?></span>
                <?php endif; ?>
            </a>
            <a href="manage_users.php" class="sidebar-nav-item<?php echo $current_page === 'manage_users.php' ? ' active' : ''; ?>">
                <i class="fas fa-users-cog"></i>
                Manage Users
            </a>
            <a href="seminars_manage.php" class="sidebar-nav-item<?php echo $current_page === 'seminars_manage.php' ? ' active' : ''; ?>">
                <i class="fas fa-calendar-plus"></i>
                Manage Seminars
            </a>
            <a href="certificates_manage.php" class="sidebar-nav-item<?php echo $current_page === 'certificates_manage.php' ? ' active' : ''; ?>">
                <i class="fas fa-certificate"></i>
                Manage Certificates
            </a>
            <a href="attendance_reports.php" class="sidebar-nav-item<?php echo $current_page === 'attendance_reports.php' ? ' active' : ''; ?>">
                <i class="fas fa-clipboard-list"></i>
                Attendance Reports
            </a>
            <a href="announcement_manage.php" class="sidebar-nav-item<?php echo $current_page === 'announcement_manage.php' ? ' active' : ''; ?>">
                <i class="fas fa-bullhorn"></i>
                Manage Announcements
            </a>

            <div class="sidebar-nav-section">System</div>
            <a href="system_settings.php" class="sidebar-nav-item<?php echo $current_page === 'system_settings.php' ? ' active' : ''; ?>">
                <i class="fas fa-cog"></i>
                System Settings
            </a>
            <a href="view_email_logs.php" class="sidebar-nav-item<?php echo $current_page === 'view_email_logs.php' ? ' active' : ''; ?>">
                <i class="fas fa-envelope"></i>
                Email Logs
            </a>
            <a href="audit_logs.php" class="sidebar-nav-item<?php echo $current_page === 'audit_logs.php' ? ' active' : ''; ?>">
                <i class="fas fa-history"></i>
                Audit Logs
            </a>
        <?php else: ?>
            <!-- Scholar navigation -->
            <a href="dashboard.php" class="sidebar-nav-item<?php echo $current_page === 'dashboard.php' ? ' active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i>
                Scholar Dashboard
            </a>
            <a href="seminars.php" class="sidebar-nav-item<?php echo $current_page === 'seminars.php' ? ' active' : ''; ?>">
                <i class="fas fa-calendar-alt"></i>
                Seminars
            </a>
            <a href="certificates.php" class="sidebar-nav-item<?php echo $current_page === 'certificates.php' ? ' active' : ''; ?>">
                <i class="fas fa-certificate"></i>
                My Certificates
            </a>
            <a href="announcements.php" class="sidebar-nav-item<?php echo $current_page === 'announcements.php' ? ' active' : ''; ?>">
                <i class="fas fa-bullhorn"></i>
                Announcements
            </a>
        <?php endif; ?>

        <div class="sidebar-nav-section">Account</div>
        <?php if ($user_role !== 'admin'): ?>
            <a href="profile.php" class="sidebar-nav-item<?php echo $current_page === 'profile.php' ? ' active' : ''; ?>">
                <i class="fas fa-user"></i>
                My Profile
            </a>
        <?php endif; ?>
        <?php if ($user_role === 'scholar'): ?>
            <a href="attendance.php" class="sidebar-nav-item<?php echo $current_page === 'attendance.php' ? ' active' : ''; ?>">
                <i class="fas fa-clipboard-check"></i>
                Attendance
            </a>
        <?php endif; ?>
        <a href="logout.php" class="sidebar-nav-item">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </a>
    </nav>
</aside>