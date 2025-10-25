<?php 
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Redirect applicants to the applicant dashboard and admins to the admin dashboard.
// This page is intended for users with role 'scholar'.
$user_role_check_sql = "SELECT role FROM users WHERE id = ?";
$stmt_role = $conn->prepare($user_role_check_sql);
$stmt_role->bind_param("i", $_SESSION['user_id']);
$stmt_role->execute();
$role_res = $stmt_role->get_result()->fetch_assoc();
if ($role_res) {
    if ($role_res['role'] === 'applicant') {
        header('Location: dashboard_applicant.php');
        exit();
    }
    if ($role_res['role'] === 'admin') {
        header('Location: dashboard_admin.php');
        exit();
    }
}
$stmt_role->close();

// Get user data from database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Get user statistics
$attendance_sql = "SELECT COUNT(*) as attended FROM attendance WHERE user_id = ? AND attended = 1";
$stmt = $conn->prepare($attendance_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$attendance_result = $stmt->get_result();
$attendance_data = $attendance_result->fetch_assoc();
$seminars_attended = $attendance_data['attended'] ?? 0;

$total_seminars_sql = "SELECT COUNT(*) as total FROM seminars WHERE date >= CURDATE()";
$total_result = $conn->query($total_seminars_sql);
$total_data = $total_result->fetch_assoc();
$total_seminars = $total_data['total'] ?? 0;

$certificates_sql = "SELECT COUNT(*) as cert_count FROM certificates WHERE user_id = ?";
$stmt = $conn->prepare($certificates_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$certificates_result = $stmt->get_result();
$certificates_data = $certificates_result->fetch_assoc();
$certificates_earned = $certificates_data['cert_count'] ?? 0;

$attendance_rate = $total_seminars > 0 ? round(($seminars_attended / $total_seminars) * 100) : 0;

// Get upcoming seminars
$upcoming_sql = "SELECT * FROM seminars WHERE date >= CURDATE() ORDER BY date ASC LIMIT 3";
$upcoming_result = $conn->query($upcoming_sql);
$upcoming_seminars = [];
while ($row = $upcoming_result->fetch_assoc()) {
    $upcoming_seminars[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholar Dashboard - CROSS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Ensure critical styles are inlined as fallback */
        .sidebar-layout { display: grid; grid-template-columns: 280px 1fr; min-height: 100vh; }
        .main-content { padding: 2rem; background: var(--light); }
        .content-header { margin-bottom: 2rem; }
        .content-body { width: 100%; }
    </style>
</head>
<body>
    <div class="sidebar-layout">
        <!-- Sidebar -->
        <?php include __DIR__ . '/includes/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-header">
                <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                    <div>
                        <h1 style="margin: 0; color: var(--maroon);">Scholar Dashboard</h1>
                        <p style="margin: 5px 0 0; color: var(--text-light);">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
                    </div>
                    <div>
                        <span class="badge" style="background-color: var(--gold); color: white; padding: 8px 16px; border-radius: 20px;">
                            <i class="fas fa-user-graduate"></i> <?php echo ucfirst($_SESSION['user_role']); ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <!-- Statistics Cards -->
                <div class="dashboard-stats">
                    <div class="dashboard-card">
                        <div class="card-icon">📊</div>
                        <h3><?php echo $attendance_rate; ?>%</h3>
                        <p>Attendance Rate</p>
                    </div>
                    <div class="dashboard-card">
                        <div class="card-icon">🎓</div>
                        <h3><?php echo $seminars_attended; ?></h3>
                        <p>Seminars Attended</p>
                    </div>
                    <div class="dashboard-card">
                        <div class="card-icon">🏆</div>
                        <h3><?php echo $certificates_earned; ?></h3>
                        <p>Certificates Earned</p>
                    </div>
                    <div class="dashboard-card">
                        <div class="card-icon">📅</div>
                        <h3><?php echo count($upcoming_seminars); ?></h3>
                        <p>Upcoming Seminars</p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <h2 style="margin-bottom: 20px;">Quick Actions</h2>
                    <div class="action-buttons">
                        <a href="seminars.php" class="action-btn">
                            <i class="fas fa-calendar-plus"></i>
                            <h4>View Seminars</h4>
                            <p>Browse and RSVP for upcoming seminars</p>
                        </a>
                        <a href="certificates.php" class="action-btn">
                            <i class="fas fa-download"></i>
                            <h4>My Certificates</h4>
                            <p>Download your seminar certificates</p>
                        </a>
                        <a href="attendance.php" class="action-btn">
                            <i class="fas fa-clipboard-list"></i>
                            <h4>Attendance</h4>
                            <p>View your attendance history</p>
                        </a>
                        <a href="profile.php" class="action-btn">
                            <i class="fas fa-user-edit"></i>
                            <h4>Update Profile</h4>
                            <p>Manage your personal information</p>
                        </a>
                    </div>
                </div>

                <!-- Upcoming Seminars -->
                <div class="card" style="margin-top: 30px;">
                    <h2 style="margin-bottom: 20px;">Upcoming Seminars</h2>
                    <?php if (!empty($upcoming_seminars)): ?>
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Seminar Title</th>
                                        <th>Date & Time</th>
                                        <th>Speaker</th>
                                        <th>Location</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($upcoming_seminars as $seminar): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($seminar['title']); ?></td>
                                            <td>
                                                <?php echo date('M j, Y', strtotime($seminar['date'])); ?><br>
                                                <small><?php echo date('g:i A', strtotime($seminar['time'])); ?></small>
                                            </td>
                                            <td><?php echo htmlspecialchars($seminar['speaker']); ?></td>
                                            <td><?php echo htmlspecialchars($seminar['location']); ?></td>
                                            <td>
                                                <button class="btn btn-primary" style="padding: 8px 16px;">
                                                    <i class="fas fa-calendar-check"></i> RSVP
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p style="text-align: center; padding: 40px; color: var(--text-light);">
                            <i class="fas fa-calendar-times" style="font-size: 3rem; margin-bottom: 15px; display: block;"></i>
                            No upcoming seminars scheduled.
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Recent Activity -->
                <div class="card" style="margin-top: 30px;">
                    <h2 style="margin-bottom: 20px;">Recent Activity</h2>
                    <div style="display: grid; gap: 15px;">
                        <div style="display: flex; align-items: center; gap: 15px; padding: 15px; background: var(--gray); border-radius: var(--radius);">
                            <div style="width: 40px; height: 40px; background: var(--gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <div>
                                <strong>Certificate Earned</strong>
                                <p style="margin: 5px 0 0; color: var(--text-light);">Time Management Workshop - Issued today</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 15px; padding: 15px; background: var(--gray); border-radius: var(--radius);">
                            <div style="width: 40px; height: 40px; background: var(--maroon); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div>
                                <strong>Seminar Attended</strong>
                                <p style="margin: 5px 0 0; color: var(--text-light);">Financial Literacy - Completed yesterday</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="script.js"></script>
</body>
</html>