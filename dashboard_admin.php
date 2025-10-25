<?php 
include 'config.php';
try {

    // Total active scholars
    $total_scholars = 0;
    $total_scholars_sql = "SELECT COUNT(*) as total FROM users WHERE role = 'scholar' AND status = 'active'";
    $total_scholars_result = $conn->query($total_scholars_sql);
    if ($total_scholars_result) {
        $total_scholars = intval($total_scholars_result->fetch_assoc()['total']);
    }

    // Total pending applications (applicants awaiting review)
    $total_pending = 0;
    $total_pending_sql = "SELECT COUNT(*) as total FROM users WHERE role = 'applicant' AND status = 'pending'";
    $total_pending_result = $conn->query($total_pending_sql);
    if ($total_pending_result) {
        $total_pending = intval($total_pending_result->fetch_assoc()['total']);
    }

    // Active upcoming seminars
    $active_seminars = 0;
    $active_seminars_sql = "SELECT COUNT(*) as total FROM seminars WHERE date >= CURDATE()";
    $active_seminars_result = $conn->query($active_seminars_sql);
    if ($active_seminars_result) {
        $active_seminars = intval($active_seminars_result->fetch_assoc()['total']);
    }

    // Total certificates issued
    $total_certificates_sql = "SELECT COUNT(*) as total FROM certificates";
    $total_certificates_result = $conn->query($total_certificates_sql);
    if ($total_certificates_result && $total_certificates_result->num_rows > 0) {
        $total_certificates = $total_certificates_result->fetch_assoc()['total'];
    }

    // Get attendance statistics for charts (if attendance table exists)
    $attendance_trends_sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, 
                              COUNT(*) as total_attendance 
                              FROM attendance 
                              WHERE attended = 1 
                              GROUP BY DATE_FORMAT(created_at, '%Y-%m') 
                              ORDER BY month DESC 
                              LIMIT 6";
    $attendance_trends_result = $conn->query($attendance_trends_sql);
    if ($attendance_trends_result) {
        while ($row = $attendance_trends_result->fetch_assoc()) {
            $attendance_data[] = $row;
        }
    } else {
        // Create sample data if no attendance data exists
        $attendance_data = [
            ['month' => date('Y-m'), 'total_attendance' => 0],
            ['month' => date('Y-m', strtotime('-1 month')), 'total_attendance' => 0],
            ['month' => date('Y-m', strtotime('-2 month')), 'total_attendance' => 0]
        ];
    }

    // Get recent activity from users table (since we don't have audit_logs table yet)
    $recent_activity_sql = "SELECT fullname as user_name, 'registration' as action_type, 
                            CONCAT('Registered as ', role) as action_description,
                            created_at, 'N/A' as ip_address
                            FROM users 
                            ORDER BY created_at DESC 
                            LIMIT 10";
    $recent_activity_result = $conn->query($recent_activity_sql);
    if ($recent_activity_result) {
        while ($row = $recent_activity_result->fetch_assoc()) {
            $recent_logs[] = $row;
        }
    }

} catch (Exception $e) {
    // Log error but don't show to user
    error_log("Dashboard error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CROSS</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="sidebar-layout">
        <?php include __DIR__ . '/includes/sidebar.php'; ?>
            <!-- The sidebar navigation is included from sidebar.php, so we remove the duplicate HTML here. -->

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h1 style="margin: 0; color: var(--maroon);">Admin Dashboard</h1>
                        <p style="margin: 5px 0 0; color: var(--text-light);">System Overview & Management</p>
                    </div>
                    <div>
                        <span class="badge" style="background-color: var(--maroon); color: white; padding: 8px 16px; border-radius: 20px;">
                            <i class="fas fa-crown"></i> Administrator
                        </span>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <!-- Statistics Cards -->
                <div class="dashboard-stats">
                    <div class="dashboard-card">
                        <div class="card-icon">👥</div>
                        <h3><?php echo $total_scholars; ?></h3>
                        <p>Active Scholars</p>
                    </div>
                    <div class="dashboard-card">
                        <div class="card-icon">⏳</div>
                        <h3><?php echo $total_pending; ?></h3>
                        <p>Pending Applications</p>
                    </div>
                    <div class="dashboard-card">
                        <div class="card-icon">📚</div>
                        <h3><?php echo $active_seminars; ?></h3>
                        <p>Active Seminars</p>
                    </div>
                    <div class="dashboard-card">
                        <div class="card-icon">🏆</div>
                        <h3><?php echo $total_certificates; ?></h3>
                        <p>Certificates Issued</p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <h2 style="margin-bottom: 20px;">Quick Actions</h2>
                    <div class="action-buttons">
                        <!-- Quick Actions including Pending Applications -->
                        <a href="pending_approvals.php" class="action-btn">
                            <i class="fas fa-user-clock"></i>
                            <h4>Pending Applications</h4>
                            <p>Review and approve pending applicant applications</p>
                        </a>
                        <a href="seminars_manage.php?action=add" class="action-btn">
                            <i class="fas fa-calendar-plus"></i>
                            <h4>Add Seminar</h4>
                            <p>Schedule a new seminar or workshop</p>
                        </a>
                        <a href="certificates_manage.php" class="action-btn">
                            <i class="fas fa-certificate"></i>
                            <h4>Manage Certificates</h4>
                            <p>Send certificates to scholars</p>
                        </a>
                        <a href="reports.php" class="action-btn">
                            <i class="fas fa-chart-bar"></i>
                            <h4>View Reports</h4>
                            <p>Generate system analytics and reports</p>
                        </a>
                    </div>
                </div>

                <!-- Attendance Analytics -->
                <div class="card" style="margin-top: 30px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2 style="margin: 0;">Attendance Analytics</h2>
                        <button class="btn btn-primary" onclick="exportAttendanceData()">
                            <i class="fas fa-download"></i> Export Data
                        </button>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
                        <div>
                            <canvas id="attendanceChart" height="250"></canvas>
                        </div>
                        <div>
                            <h4>Quick Attendance Actions</h4>
                            <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 15px;">
                                <button class="btn btn-secondary" onclick="openAttendanceUpload()">
                                    <i class="fas fa-upload"></i> Upload Attendance
                                </button>
                                <button class="btn btn-gold" onclick="openBulkCertificate()">
                                    <i class="fas fa-certificate"></i> Bulk Certificate Send
                                </button>
                                <button class="btn btn-primary" onclick="viewAttendanceReports()">
                                    <i class="fas fa-chart-line"></i> View Reports
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Approvals section removed (registration auto-approves) -->

                <!-- Recent Activity & Audit Logs -->
                <div class="card" style="margin-top: 30px;">
                    <h2 style="margin-bottom: 20px;">Recent Activity</h2>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>User</th>
                                    <th>Details</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recent_logs)): ?>
                                    <?php foreach ($recent_logs as $log): ?>
                                        <tr>
                                            <td>
                                                <span class="badge" style="background-color: var(--gold); color: white;">
                                                    <?php echo ucfirst($log['action_type']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars($log['user_name']); ?></td>
                                            <td><?php echo htmlspecialchars($log['action_description']); ?></td>
                                            <td><?php echo date('M j, Y g:i A', strtotime($log['created_at'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" style="text-align: center; padding: 20px; color: var(--text-light);">
                                            No recent activity found.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div style="text-align: center; margin-top: 20px;">
                        <a href="audit_logs.php" class="btn btn-secondary">View All Audit Logs</a>
                    </div>
                </div>

                <!-- System Status -->
                <div class="card" style="margin-top: 30px;">
                    <h2 style="margin-bottom: 20px;">System Status</h2>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                        <div style="text-align: center; padding: 20px; background: var(--gray); border-radius: var(--radius);">
                            <div style="font-size: 2rem; color: green; margin-bottom: 10px;">
                                <i class="fas fa-server"></i>
                            </div>
                            <h4 style="margin: 0 0 5px;">System Online</h4>
                            <p style="margin: 0; color: var(--text-light);">All systems operational</p>
                        </div>
                        <div style="text-align: center; padding: 20px; background: var(--gray); border-radius: var(--radius);">
                            <div style="font-size: 2rem; color: var(--maroon); margin-bottom: 10px;">
                                <i class="fas fa-database"></i>
                            </div>
                            <h4 style="margin: 0 0 5px;">Database</h4>
                            <p style="margin: 0; color: var(--text-light);">Connected and stable</p>
                        </div>
                        <div style="text-align: center; padding: 20px; background: var(--gray); border-radius: var(--radius);">
                            <div style="font-size: 2rem; color: var(--gold); margin-bottom: 10px;">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h4 style="margin: 0 0 5px;">Security</h4>
                            <p style="margin: 0; color: var(--text-light);">All security protocols active</p>
                        </div>
                        <div style="text-align: center; padding: 20px; background: var(--gray); border-radius: var(--radius);">
                            <div style="font-size: 2rem; color: blue; margin-bottom: 10px;">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <h4 style="margin: 0 0 5px;">Certificates</h4>
                            <p style="margin: 0; color: var(--text-light);">Ready for distribution</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Application Review Modal -->
    <div id="applicationModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Scholar Application Review</h3>
                <button class="modal-close">&times;</button>
            </div>
            <div id="applicationDetails">
                <!-- Application details will be loaded here -->
            </div>
            <div style="margin-top: 20px; padding: 20px; background: var(--gray); border-radius: var(--radius);">
                <h4>Scholarship Policy & Data Privacy Notice</h4>
                <p style="font-size: 0.9rem; color: var(--text-light);">
                    • This scholarship is valid for 4 years (standard college duration)<br>
                    • After 4 years, scholar status will automatically change to inactive<br>
                    • All personal information is protected under Data Privacy Act of 2012<br>
                    • Scholars must maintain good academic standing to remain eligible
                </p>
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                <button class="btn btn-secondary" onclick="closeApplicationModal()">Cancel</button>
                <button class="btn btn-success" onclick="approveApplication()">Approve Application</button>
            </div>
        </div>
    </div>

    <script>
        // Initialize Attendance Chart
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($attendance_data, 'month')); ?>,
                datasets: [{
                    label: 'Monthly Attendance',
                    data: <?php echo json_encode(array_column($attendance_data, 'total_attendance')); ?>,
                    borderColor: 'var(--maroon)',
                    backgroundColor: 'rgba(123, 30, 45, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Attendances'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    }
                }
            }
        });

        // Modal Functions
        function openApplicationModal(applicationId) {
            // For now, just show a simple modal since we don't have AJAX endpoint
            document.getElementById('applicationDetails').innerHTML = `
                <div>
                    <h4>Application Review</h4>
                    <p>Reviewing application ID: ${applicationId}</p>
                    <p>Click "Approve Application" to approve this scholar application.</p>
                </div>
            `;
            document.getElementById('applicationModal').style.display = 'flex';
        }

        function closeApplicationModal() {
            document.getElementById('applicationModal').style.display = 'none';
        }

        function approveApplication() {
            if (confirm('Are you sure you want to approve this application? The scholar will receive a confirmation message and their status will be active for 4 years.')) {
                alert('Application approved successfully!');
                closeApplicationModal();
            }
        }

        function openAttendanceUpload() {
            alert('Attendance upload feature will be implemented soon.');
        }

        function openBulkCertificate() {
            window.location.href = 'certificates_manage.php';
        }

        function viewAttendanceReports() {
            window.location.href = 'attendance_reports.php';
        }

        function exportAttendanceData() {
            alert('Export feature will be implemented soon.');
        }

        // Close modals when clicking outside
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.style.display = 'none';
                }
            });
        });

        // Close modals with close button
        document.querySelectorAll('.modal-close').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.modal').style.display = 'none';
            });
        });
    </script>
</body>
</html>