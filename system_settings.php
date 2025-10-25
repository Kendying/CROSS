<?php
include 'config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Handle settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_settings'])) {
        $_SESSION['success'] = "System settings updated successfully!";
        header("Location: system_settings.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings - CROSS</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="sidebar-layout">
        <?php include __DIR__ . '/includes/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h1 style="margin: 0; color: var(--maroon);">System Settings</h1>
                        <p style="margin: 5px 0 0; color: var(--text-light);">Configure system preferences and policies</p>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>

                <form method="POST">
                    <!-- Scholarship Settings -->
                    <div class="card">
                        <h2>Scholarship Settings</h2>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label class="form-label">Scholarship Duration (Years)</label>
                                <input type="number" class="form-control" name="scholarship_duration" value="4" min="1" max="10" required>
                                <small style="color: var(--text-light);">Standard duration for scholarship eligibility</small>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Minimum Required Seminars per Semester</label>
                                <input type="number" class="form-control" name="min_seminars" value="2" min="0" max="10" required>
                                <small style="color: var(--text-light);">Minimum seminars scholars must attend</small>
                            </div>
                        </div>
                    </div>

                    <!-- Email Settings -->
                    <div class="card" style="margin-top: 30px;">
                        <h2>Email & Notification Settings</h2>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label class="form-label">System Email</label>
                                <input type="email" class="form-control" name="system_email" value="noreply@cross.org" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Admin Notification Email</label>
                                <input type="email" class="form-control" name="admin_email" value="admin@cross.org" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="checkbox" name="email_notifications" checked>
                                <span>Enable email notifications for new applications</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="checkbox" name="certificate_emails" checked>
                                <span>Automatically email certificates when generated</span>
                            </label>
                        </div>
                    </div>

                    <!-- System Policies -->
                    <div class="card" style="margin-top: 30px;">
                        <h2>System Policies</h2>
                        <div class="form-group">
                            <label class="form-label">Data Retention Period (Months)</label>
                            <input type="number" class="form-control" name="data_retention" value="24" min="1" max="120" required>
                            <small style="color: var(--text-light);">How long to keep user data after account deletion</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Maximum Login Attempts</label>
                            <input type="number" class="form-control" name="max_login_attempts" value="5" min="1" max="10" required>
                            <small style="color: var(--text-light);">Before temporary account lockout</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Session Timeout (Minutes)</label>
                            <input type="number" class="form-control" name="session_timeout" value="30" min="5" max="240" required>
                            <small style="color: var(--text-light);">Automatic logout after inactivity</small>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div class="card" style="margin-top: 30px; border-left: 4px solid #dc3545;">
                        <h2 style="color: #dc3545;">Danger Zone</h2>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div>
                                <h4 style="color: var(--text-light); margin-bottom: 10px;">Clear All Data</h4>
                                <p style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 15px;">
                                    Permanently delete all user data, seminars, and certificates. This action cannot be undone.
                                </p>
                                <button type="button" class="btn btn-danger" onclick="confirmClearData()">
                                    <i class="fas fa-trash"></i> Clear All Data
                                </button>
                            </div>
                            <div>
                                <h4 style="color: var(--text-light); margin-bottom: 10px;">Reset System</h4>
                                <p style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 15px;">
                                    Reset all settings to default values. This will not delete user data.
                                </p>
                                <button type="button" class="btn btn-warning" onclick="confirmReset()">
                                    <i class="fas fa-undo"></i> Reset to Defaults
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Save Settings -->
                    <div style="margin-top: 30px; text-align: right;">
                        <button type="submit" name="update_settings" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        function confirmClearData() {
            if (confirm('⚠️ DANGER: This will permanently delete ALL data including users, seminars, and certificates. This action cannot be undone!\n\nAre you absolutely sure?')) {
                alert('Data clearance initiated. This feature would be implemented in a production system.');
            }
        }

        function confirmReset() {
            if (confirm('Reset all settings to default values?')) {
                alert('Settings reset. This feature would be implemented in a production system.');
            }
        }
    </script>
</body>
</html>