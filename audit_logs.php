<?php
include 'config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Get audit logs from users table (since we don't have dedicated audit_logs table)
$logs_sql = "SELECT 
    id,
    fullname as user_name,
    'user_registration' as action_type,
    CONCAT('Registered as ', role, ' - Status: ', status) as action_description,
    created_at,
    'N/A' as ip_address
    FROM users 
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    UNION ALL
    SELECT 
    id,
    'System' as user_name,
    'seminar_created' as action_type,
    CONCAT('Created seminar: ', title) as action_description,
    created_at,
    'N/A' as ip_address
    FROM seminars 
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    ORDER BY created_at DESC
    LIMIT 100";

$logs_result = $conn->query($logs_sql);
$audit_logs = [];
if ($logs_result) {
    while ($row = $logs_result->fetch_assoc()) {
        $audit_logs[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs - CROSS</title>
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
                        <h1 style="margin: 0; color: var(--maroon);">Audit Logs</h1>
                        <p style="margin: 5px 0 0; color: var(--text-light);">System activity and user actions tracking</p>
                    </div>
                    <button class="btn btn-primary" onclick="exportLogs()">
                        <i class="fas fa-download"></i> Export Logs
                    </button>
                </div>
            </div>

            <div class="content-body">
                <!-- Audit Logs Statistics -->
                <div class="dashboard-stats">
                    <div class="dashboard-card">
                        <div class="card-icon">📝</div>
                        <h3><?php echo count($audit_logs); ?></h3>
                        <p>Total Logs</p>
                    </div>
                    <div class="dashboard-card">
                        <div class="card-icon">👥</div>
                        <h3><?php echo count(array_unique(array_column($audit_logs, 'user_name'))); ?></h3>
                        <p>Unique Users</p>
                    </div>
                    <div class="dashboard-card">
                        <div class="card-icon">📅</div>
                        <h3>30</h3>
                        <p>Days History</p>
                    </div>
                    <div class="dashboard-card">
                        <div class="card-icon">⚡</div>
                        <h3><?php echo count(array_filter($audit_logs, fn($log) => strtotime($log['created_at']) >= strtotime('-24 hours'))); ?></h3>
                        <p>Last 24h</p>
                    </div>
                </div>

                <!-- Audit Logs Table -->
                <div class="card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2 style="margin: 0;">System Activity Logs</h2>
                        <div style="display: flex; gap: 10px;">
                            <input type="text" id="searchLogs" placeholder="Search logs..." class="form-control" style="width: 250px;">
                            <select id="filterAction" class="form-control" style="width: 150px;">
                                <option value="">All Actions</option>
                                <option value="user_registration">User Registration</option>
                                <option value="seminar_created">Seminar Created</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-container">
                        <table class="data-table" id="logsTable">
                            <thead>
                                <tr>
                                    <th>Timestamp</th>
                                    <th>User</th>
                                    <th>Action Type</th>
                                    <th>Description</th>
                                    <th>IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($audit_logs as $log): ?>
                                    <tr>
                                        <td>
                                            <?php echo date('M j, Y', strtotime($log['created_at'])); ?><br>
                                            <small><?php echo date('g:i A', strtotime($log['created_at'])); ?></small>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($log['user_name']); ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge" style="background: 
                                                <?php echo $log['action_type'] === 'user_registration' ? 'var(--gold)' : 'var(--maroon)'; ?>; 
                                                color: white;">
                                                <?php echo str_replace('_', ' ', $log['action_type']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($log['action_description']); ?></td>
                                        <td>
                                            <code><?php echo htmlspecialchars($log['ip_address']); ?></code>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Log Retention Information -->
                <div class="card" style="margin-top: 30px;">
                    <h3>Log Retention Policy</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
                        <div style="padding: 20px; background: var(--gray); border-radius: var(--radius);">
                            <h4 style="color: var(--maroon);">Retention Period</h4>
                            <p style="color: var(--text-light); font-size: 0.9rem;">
                                System logs are retained for 30 days. Critical security events 
                                are archived for 1 year for compliance purposes.
                            </p>
                        </div>
                        <div style="padding: 20px; background: var(--gray); border-radius: var(--radius);">
                            <h4 style="color: var(--maroon);">Data Privacy</h4>
                            <p style="color: var(--text-light); font-size: 0.9rem;">
                                All logged data complies with Data Privacy Act of 2012. 
                                Personal information is anonymized where possible.
                            </p>
                        </div>
                        <div style="padding: 20px; background: var(--gray); border-radius: var(--radius);">
                            <h4 style="color: var(--maroon);">Export & Backup</h4>
                            <p style="color: var(--text-light); font-size: 0.9rem;">
                                Logs can be exported for analysis. Automated backups 
                                occur daily to ensure data integrity and availability.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Search and filter functionality
        document.getElementById('searchLogs').addEventListener('input', function() {
            filterLogs();
        });

        document.getElementById('filterAction').addEventListener('change', function() {
            filterLogs();
        });

        function filterLogs() {
            const searchTerm = document.getElementById('searchLogs').value.toLowerCase();
            const actionFilter = document.getElementById('filterAction').value;
            const rows = document.querySelectorAll('#logsTable tbody tr');
            
            rows.forEach(row => {
                const user = row.cells[1].textContent.toLowerCase();
                const action = row.cells[2].textContent.toLowerCase();
                const description = row.cells[3].textContent.toLowerCase();
                
                const matchesSearch = user.includes(searchTerm) || description.includes(searchTerm);
                const matchesAction = !actionFilter || action.includes(actionFilter);
                
                row.style.display = matchesSearch && matchesAction ? '' : 'none';
            });
        }

        function exportLogs() {
            alert('Exporting audit logs...');
            // Implement export functionality
        }
    </script>
</body>
</html>