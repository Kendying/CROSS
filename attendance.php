<?php
require_once __DIR__ . '/require_scholar.php';
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user data from database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Get attendance records
$attendance_sql = "SELECT a.*, s.title as seminar_title, s.date as seminar_date, s.time as seminar_time, s.speaker, s.location
                   FROM attendance a 
                   JOIN seminars s ON a.seminar_id = s.id 
                   WHERE a.user_id = ? 
                   ORDER BY s.date DESC, s.time DESC";
$stmt = $conn->prepare($attendance_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$attendance_result = $stmt->get_result();
$attendance_records = [];
while ($row = $attendance_result->fetch_assoc()) {
    $attendance_records[] = $row;
}

// Calculate attendance statistics
$total_attended = count(array_filter($attendance_records, fn($a) => $a['attended'] == 1));
$total_seminars = count($attendance_records);
$attendance_rate = $total_seminars > 0 ? round(($total_attended / $total_seminars) * 100) : 0;

// Get all past seminars for comparison
$past_seminars_sql = "SELECT * FROM seminars WHERE date <= CURDATE() ORDER BY date DESC LIMIT 10";
$past_seminars_result = $conn->query($past_seminars_sql);
$past_seminars = [];
while ($row = $past_seminars_result->fetch_assoc()) {
    $past_seminars[] = $row;
}

// Check certificate eligibility for each seminar
$certificates_sql = "SELECT seminar_id FROM certificates WHERE user_id = ?";
$stmt = $conn->prepare($certificates_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$certificates_result = $stmt->get_result();
$user_certificates = [];
while ($row = $certificates_result->fetch_assoc()) {
    $user_certificates[] = $row['seminar_id'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance - CROSS</title>
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
                        <h1 style="margin: 0; color: var(--maroon);">Attendance Records</h1>
                        <p style="margin: 5px 0 0; color: var(--text-light);">Track your seminar attendance and participation</p>
                    </div>
                    <div>
                        <span class="badge" style="background-color: var(--gold); color: white; padding: 8px 16px; border-radius: 20px;">
                            <i class="fas fa-user-graduate"></i> Scholar
                        </span>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <!-- Attendance Statistics -->
                <div class="dashboard-stats">
                    <div class="dashboard-card">
                        <div class="card-icon">📊</div>
                        <h3><?php echo $attendance_rate; ?>%</h3>
                        <p>Attendance Rate</p>
                    </div>
                    <div class="dashboard-card">
                        <div class="card-icon">✅</div>
                        <h3><?php echo $total_attended; ?></h3>
                        <p>Seminars Attended</p>
                    </div>
                    <div class="dashboard-card">
                        <div class="card-icon">📅</div>
                        <h3><?php echo $total_seminars; ?></h3>
                        <p>Total Registered</p>
                    </div>
                    <div class="dashboard-card">
                        <div class="card-icon">🏆</div>
                        <h3><?php echo count($user_certificates); ?></h3>
                        <p>Certificates Earned</p>
                    </div>
                </div>

                <!-- Attendance Records -->
                <div class="card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2 style="margin: 0;">Attendance History</h2>
                        <div style="display: flex; gap: 10px;">
                            <input type="text" id="searchAttendance" placeholder="Search seminars..." class="form-control" style="width: 250px;">
                            <select id="filterStatus" class="form-control" style="width: 150px;">
                                <option value="all">All Records</option>
                                <option value="attended">Attended</option>
                                <option value="absent">Absent</option>
                                <option value="certificate">With Certificate</option>
                            </select>
                        </div>
                    </div>

                    <?php if (!empty($attendance_records)): ?>
                        <div class="table-container">
                            <table class="data-table" id="attendanceTable">
                                <thead>
                                    <tr>
                                        <th>Seminar Title</th>
                                        <th>Date & Time</th>
                                        <th>Speaker</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Attendance Time</th>
                                        <th>Certificate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($attendance_records as $record): 
                                        $has_certificate = in_array($record['seminar_id'], $user_certificates);
                                    ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($record['seminar_title']); ?></strong>
                                            </td>
                                            <td>
                                                <?php echo date('M j, Y', strtotime($record['seminar_date'])); ?><br>
                                                <small><?php echo date('g:i A', strtotime($record['seminar_time'])); ?></small>
                                            </td>
                                            <td><?php echo htmlspecialchars($record['speaker']); ?></td>
                                            <td><?php echo htmlspecialchars($record['location']); ?></td>
                                            <td>
                                                <span class="badge" style="background: <?php echo $record['attended'] ? 'green' : 'red'; ?>; color: white;">
                                                    <?php echo $record['attended'] ? 'Present' : 'Absent'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($record['attended'] && $record['attendance_time']): ?>
                                                    <?php echo date('M j, Y g:i A', strtotime($record['attendance_time'])); ?>
                                                <?php else: ?>
                                                    <span style="color: var(--text-light);">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($has_certificate): ?>
                                                    <a href="certificates.php" class="btn btn-success">
                                                        <i class="fas fa-download"></i> Download
                                                    </a>
                                                <?php elseif ($record['attended']): ?>
                                                    <span class="badge" style="background: var(--gold); color: white;">
                                                        Processing
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge" style="background: var(--text-light); color: white;">
                                                        Not Available
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div style="text-align: center; padding: 40px; color: var(--text-light);">
                            <i class="fas fa-clipboard-list" style="font-size: 3rem; margin-bottom: 15px; display: block; color: var(--gold);"></i>
                            <h3>No Attendance Records</h3>
                            <p>Your attendance records will appear here once you start attending seminars.</p>
                            <a href="seminars.php" class="btn btn-primary" style="margin-top: 15px;">
                                <i class="fas fa-calendar-alt"></i> Browse Seminars
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Recent Seminars Comparison -->
                <div class="card" style="margin-top: 30px;">
                    <h2 style="margin-bottom: 20px;">Recent Seminars Overview</h2>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Seminar Title</th>
                                    <th>Date</th>
                                    <th>Speaker</th>
                                    <th>Your Status</th>
                                    <th>Certificate</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($past_seminars as $seminar): 
                                    $attendance_record = array_filter($attendance_records, fn($a) => $a['seminar_id'] == $seminar['id']);
                                    $attended = !empty($attendance_record) && reset($attendance_record)['attended'];
                                    $has_certificate = in_array($seminar['id'], $user_certificates);
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($seminar['title']); ?></td>
                                        <td><?php echo date('M j, Y', strtotime($seminar['date'])); ?></td>
                                        <td><?php echo htmlspecialchars($seminar['speaker']); ?></td>
                                        <td>
                                            <span class="badge" style="background: 
                                                <?php echo $attended ? 'green' : 'red'; ?>; 
                                                color: white;">
                                                <?php echo $attended ? 'Attended' : 'Not Attended'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($has_certificate): ?>
                                                <span class="badge" style="background: green; color: white;">
                                                    <i class="fas fa-certificate"></i> Available
                                                </span>
                                            <?php elseif ($attended): ?>
                                                <span class="badge" style="background: var(--gold); color: white;">
                                                    Processing
                                                </span>
                                            <?php else: ?>
                                                <span class="badge" style="background: var(--text-light); color: white;">
                                                    Not Eligible
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Attendance Guidelines -->
                <div class="card" style="margin-top: 30px;">
                    <h2 style="margin-bottom: 15px;">Attendance Guidelines</h2>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                        <div style="display: flex; align-items: flex-start; gap: 15px;">
                            <div style="background: var(--gold); color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">
                                1
                            </div>
                            <div>
                                <h4 style="margin: 0 0 5px;">Check-in Required</h4>
                                <p style="margin: 0; color: var(--text-light); font-size: 0.9rem;">You must check-in at the seminar venue to be marked as present.</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: flex-start; gap: 15px;">
                            <div style="background: var(--gold); color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">
                                2
                            </div>
                            <div>
                                <h4 style="margin: 0 0 5px;">Full Participation</h4>
                                <p style="margin: 0; color: var(--text-light); font-size: 0.9rem;">Stay for the entire seminar duration to receive full credit.</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: flex-start; gap: 15px;">
                            <div style="background: var(--gold); color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">
                                3
                            </div>
                            <div>
                                <h4 style="margin: 0 0 5px;">Certificate Eligibility</h4>
                                <p style="margin: 0; color: var(--text-light); font-size: 0.9rem;">Certificates are issued only for attended seminars.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Search and filter functionality
        document.getElementById('searchAttendance').addEventListener('input', function() {
            filterAttendance();
        });

        document.getElementById('filterStatus').addEventListener('change', function() {
            filterAttendance();
        });

        function filterAttendance() {
            const searchTerm = document.getElementById('searchAttendance').value.toLowerCase();
            const statusFilter = document.getElementById('filterStatus').value;
            const rows = document.querySelectorAll('#attendanceTable tbody tr');
            
            rows.forEach(row => {
                const title = row.cells[0].textContent.toLowerCase();
                const status = row.cells[4].textContent.toLowerCase();
                const certificate = row.cells[6].textContent.toLowerCase();
                
                const matchesSearch = title.includes(searchTerm);
                let matchesStatus = true;
                
                if (statusFilter === 'attended') {
                    matchesStatus = status === 'present';
                } else if (statusFilter === 'absent') {
                    matchesStatus = status === 'absent';
                } else if (statusFilter === 'certificate') {
                    matchesStatus = certificate.includes('download');
                }
                
                row.style.display = matchesSearch && matchesStatus ? '' : 'none';
            });
        }
    </script>
</body>
</html>