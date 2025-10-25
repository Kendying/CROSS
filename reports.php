<?php
include 'config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Get report data
// Attendance statistics
$attendance_stats_sql = "SELECT 
    COUNT(*) as total_attendance,
    COUNT(DISTINCT user_id) as unique_attendees,
    AVG(attended) as attendance_rate
    FROM attendance";
$attendance_stats = $conn->query($attendance_stats_sql)->fetch_assoc();

// Seminar statistics
$seminar_stats_sql = "SELECT 
    COUNT(*) as total_seminars,
    COUNT(CASE WHEN date >= CURDATE() THEN 1 END) as upcoming_seminars,
    COUNT(CASE WHEN date < CURDATE() THEN 1 END) as past_seminars
    FROM seminars";
$seminar_stats = $conn->query($seminar_stats_sql)->fetch_assoc();

// User statistics
$user_stats_sql = "SELECT 
    COUNT(*) as total_users,
    COUNT(CASE WHEN role = 'scholar' AND status = 'approved' THEN 1 END) as active_scholars,
    COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_users
    FROM users";
$user_stats = $conn->query($user_stats_sql)->fetch_assoc();

// Monthly attendance data for chart
$monthly_attendance_sql = "SELECT 
    DATE_FORMAT(created_at, '%Y-%m') as month,
    COUNT(*) as attendance_count
    FROM attendance 
    WHERE attended = 1
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY month DESC
    LIMIT 12";
$monthly_attendance_result = $conn->query($monthly_attendance_sql);
$monthly_data = [];
while ($row = $monthly_attendance_result->fetch_assoc()) {
    $monthly_data[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Analytics - CROSS</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="sidebar-layout">
        <?php include __DIR__ . '/includes/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h1 style="margin: 0; color: var(--maroon);">Reports & Analytics</h1>
                        <p style="margin: 5px 0 0; color: var(--text-light);">System performance and usage statistics</p>
                    </div>
                    <div>
                        <button class="btn btn-primary" onclick="exportReports()">
                            <i class="fas fa-download"></i> Export Reports
                        </button>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <!-- Key Metrics -->
                <div class="dashboard-stats">
                    <div class="dashboard-card">
                        <div class="card-icon">📊</div>
                        <h3><?php echo $attendance_stats['total_attendance'] ?? 0; ?></h3>
                        <p>Total Attendances</p>
                    </div>
                    <div class="dashboard-card">
                        <div class="card-icon">👥</div>
                        <h3><?php echo $attendance_stats['unique_attendees'] ?? 0; ?></h3>
                        <p>Unique Attendees</p>
                    </div>
                    <div class="dashboard-card">
                        <div class="card-icon">📚</div>
                        <h3><?php echo $seminar_stats['total_seminars'] ?? 0; ?></h3>
                        <p>Total Seminars</p>
                    </div>
                    <div class="dashboard-card">
                        <div class="card-icon">🎓</div>
                        <h3><?php echo $user_stats['active_scholars'] ?? 0; ?></h3>
                        <p>Active Scholars</p>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="card">
                    <h2>Attendance Trends</h2>
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
                        <div>
                            <canvas id="attendanceChart" height="250"></canvas>
                        </div>
                        <div>
                            <h4>Quick Stats</h4>
                            <div style="display: flex; flex-direction: column; gap: 15px; margin-top: 20px;">
                                <div style="padding: 15px; background: var(--gray); border-radius: var(--radius);">
                                    <strong>Attendance Rate</strong>
                                    <div style="font-size: 1.5rem; color: var(--maroon);">
                                        <?php echo round(($attendance_stats['attendance_rate'] ?? 0) * 100, 1); ?>%
                                    </div>
                                </div>
                                <div style="padding: 15px; background: var(--gray); border-radius: var(--radius);">
                                    <strong>Upcoming Seminars</strong>
                                    <div style="font-size: 1.5rem; color: var(--gold);">
                                        <?php echo $seminar_stats['upcoming_seminars'] ?? 0; ?>
                                    </div>
                                </div>
                                <div style="padding: 15px; background: var(--gray); border-radius: var(--radius);">
                                    <strong>Pending Applications</strong>
                                    <div style="font-size: 1.5rem; color: orange;">
                                        <?php echo $user_stats['pending_users'] ?? 0; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Reports -->
                <div class="card" style="margin-top: 30px;">
                    <h2>Detailed Reports</h2>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
                        <div style="padding: 20px; background: var(--gray); border-radius: var(--radius);">
                            <h4 style="color: var(--maroon);">Seminar Performance</h4>
                            <p>View attendance rates and participation for each seminar</p>
                            <button class="btn btn-primary" style="margin-top: 10px;">
                                <i class="fas fa-chart-bar"></i> View Report
                            </button>
                        </div>
                        <div style="padding: 20px; background: var(--gray); border-radius: var(--radius);">
                            <h4 style="color: var(--maroon);">Scholar Progress</h4>
                            <p>Track individual scholar attendance and certificate progress</p>
                            <button class="btn btn-primary" style="margin-top: 10px;">
                                <i class="fas fa-user-graduate"></i> View Report
                            </button>
                        </div>
                        <div style="padding: 20px; background: var(--gray); border-radius: var(--radius);">
                            <h4 style="color: var(--maroon);">System Usage</h4>
                            <p>Analyze platform usage patterns and user engagement</p>
                            <button class="btn btn-primary" style="margin-top: 10px;">
                                <i class="fas fa-desktop"></i> View Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Initialize Charts
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($monthly_data, 'month')); ?>,
                datasets: [{
                    label: 'Monthly Attendance',
                    data: <?php echo json_encode(array_column($monthly_data, 'attendance_count')); ?>,
                    backgroundColor: 'var(--maroon)',
                    borderColor: 'var(--dark)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
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

        function exportReports() {
            alert('Exporting reports...');
            // Implement export functionality
        }
    </script>
</body>
</html>