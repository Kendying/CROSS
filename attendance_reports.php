<?php
include 'config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Get attendance data
$attendance_sql = "SELECT a.*, u.fullname, u.email, s.title as seminar_title, s.date as seminar_date
                   FROM attendance a
                   LEFT JOIN users u ON a.user_id = u.id
                   LEFT JOIN seminars s ON a.seminar_id = s.id
                   ORDER BY a.created_at DESC";
$attendance_result = $conn->query($attendance_sql);
$attendance_data = [];
if ($attendance_result) {
    while ($row = $attendance_result->fetch_assoc()) {
        $attendance_data[] = $row;
    }
}

// Get attendance statistics
$stats_sql = "SELECT 
    COUNT(*) as total_records,
    SUM(attended) as total_attended,
    ROUND(SUM(attended) * 100.0 / COUNT(*), 1) as attendance_rate
    FROM attendance";
$stats = $conn->query($stats_sql)->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Reports - CROSS</title>
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
                        <h1 style="margin: 0; color: var(--maroon);">Attendance Reports</h1>
                        <p style="margin: 5px 0 0; color: var(--text-light);">Detailed attendance tracking and analytics</p>
                    </div>
                    <div>
                        <button class="btn btn-primary" onclick="exportAttendance()">
                            <i class="fas fa-download"></i> Export Report
                        </button>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <!-- Attendance Statistics -->
                <div class="dashboard-stats">
                    <div class="dashboard-card">
                        <div class="card-icon">📋</div>
                        <h3><?php echo $stats['total_records'] ?? 0; ?></h3>
                        <p>Total Records</p>
                    </div>
                    <div class="dashboard-card">
                        <div class="card-icon">✅</div>
                        <h3><?php echo $stats['total_attended'] ?? 0; ?></h3>
                        <p>Attended</p>
                    </div>
                    <div class="dashboard-card">
                        <div class="card-icon">❌</div>
                        <h3><?php echo ($stats['total_records'] ?? 0) - ($stats['total_attended'] ?? 0); ?></h3>
                        <p>Absent</p>
                    </div>
                    <div class="dashboard-card">
                        <div class="card-icon">📊</div>
                        <h3><?php echo $stats['attendance_rate'] ?? 0; ?>%</h3>
                        <p>Attendance Rate</p>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card">
                    <h2>Filter Attendance Data</h2>
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label class="form-label">Date From</label>
                            <input type="date" class="form-control" id="dateFrom">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Date To</label>
                            <input type="date" class="form-control" id="dateTo">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Seminar</label>
                            <select class="form-control" id="seminarFilter">
                                <option value="">All Seminars</option>
                                <!-- Seminar options would be populated dynamically -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select class="form-control" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="attended">Attended</option>
                                <option value="absent">Absent</option>
                            </select>
                        </div>
                    </div>
                    <div style="text-align: right; margin-top: 15px;">
                        <button class="btn btn-primary" onclick="applyFilters()">
                            <i class="fas fa-filter"></i> Apply Filters
                        </button>
                        <button class="btn btn-secondary" onclick="clearFilters()">
                            <i class="fas fa-times"></i> Clear
                        </button>
                    </div>
                </div>

                <!-- Attendance Data -->
                <div class="card" style="margin-top: 30px;">
                    <h2>Attendance Records</h2>
                    <div class="table-container">
                        <table class="data-table" id="attendanceTable">
                            <thead>
                                <tr>
                                    <th>Scholar Name</th>
                                    <th>Seminar</th>
                                    <th>Seminar Date</th>
                                    <th>Attendance Date</th>
                                    <th>Status</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($attendance_data as $record): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($record['fullname']); ?></strong>
                                        </td>
                                        <td><?php echo htmlspecialchars($record['seminar_title'] ?? 'N/A'); ?></td>
                                        <td><?php echo $record['seminar_date'] ? date('M j, Y', strtotime($record['seminar_date'])) : 'N/A'; ?></td>
                                        <td><?php echo date('M j, Y g:i A', strtotime($record['created_at'])); ?></td>
                                        <td>
                                            <span class="badge" style="background: <?php echo $record['attended'] ? 'green' : 'red'; ?>; color: white;">
                                                <?php echo $record['attended'] ? 'Attended' : 'Absent'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($record['email']); ?></td>
                                        <td>
                                            <div style="display: flex; gap: 5px;">
                                                <?php if (!$record['attended']): ?>
                                                    <button class="btn btn-success" onclick="markAttended(<?php echo $record['id']; ?>)">
                                                        <i class="fas fa-check"></i> Mark Attended
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-warning" onclick="markAbsent(<?php echo $record['id']; ?>)">
                                                        <i class="fas fa-times"></i> Mark Absent
                                                    </button>
                                                <?php endif; ?>
                                                <button class="btn btn-primary" onclick="viewDetails(<?php echo $record['id']; ?>)">
                                                    <i class="fas fa-eye"></i> Details
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function applyFilters() {
            // Implement filter logic
            alert('Filters applied!');
        }

        function clearFilters() {
            document.getElementById('dateFrom').value = '';
            document.getElementById('dateTo').value = '';
            document.getElementById('seminarFilter').value = '';
            document.getElementById('statusFilter').value = '';
            alert('Filters cleared!');
        }

        function markAttended(recordId) {
            if (confirm('Mark this attendance record as attended?')) {
                alert('Attendance marked as attended!');
                // Implement API call to update attendance
            }
        }

        function markAbsent(recordId) {
            if (confirm('Mark this attendance record as absent?')) {
                alert('Attendance marked as absent!');
                // Implement API call to update attendance
            }
        }

        function viewDetails(recordId) {
            alert('Viewing details for record: ' + recordId);
            // Implement detailed view modal
        }

        function exportAttendance() {
            alert('Exporting attendance report...');
            // Implement export functionality
        }
    </script>
</body>
</html>