<?php
include 'config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Get all users
$users_sql = "SELECT * FROM users ORDER BY created_at DESC";
$users_result = $conn->query($users_sql);
$users = [];
if ($users_result) {
    while ($row = $users_result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Get pending count for sidebar
$pending_count_sql = "SELECT COUNT(*) as count FROM users WHERE status = 'pending' AND role = 'applicant'";
$pending_count_result = $conn->query($pending_count_sql);
$pending_count = $pending_count_result ? $pending_count_result->fetch_assoc()['count'] : 0;

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $user_id = $_POST['user_id'];
        
        switch ($_POST['action']) {
            case 'activate':
                // Mark user as approved scholar
                $conn->query("UPDATE users SET status = 'approved', role = 'scholar' WHERE id = $user_id");
                $_SESSION['success'] = "User approved successfully!";
                break;
                
            case 'deactivate':
                // Mark user as rejected/inactive
                $conn->query("UPDATE users SET status = 'rejected' WHERE id = $user_id");
                $_SESSION['success'] = "User marked as rejected successfully!";
                break;
                
            case 'reset_password':
                // Generate a strong random password
                function generate_strong_password($length = 12) {
                    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789!@#$%^&*';
                    $bytes = random_bytes($length);
                    $password = '';
                    for ($i = 0; $i < $length; $i++) {
                        $password .= $chars[ord($bytes[$i]) % strlen($chars)];
                    }
                    return $password;
                }

                $plain_password = generate_strong_password(14);
                $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);
                $conn->query("UPDATE users SET password = '" . $conn->real_escape_string($hashed_password) . "' WHERE id = $user_id");

                // Get user email
                $user_row = $conn->query("SELECT email, fullname FROM users WHERE id = $user_id")->fetch_assoc();
                $user_email = $user_row['email'] ?? '';
                $user_name = $user_row['fullname'] ?? 'User';

                // Send email with new password using PHPMailer directly
                require_once __DIR__ . '/vendor/autoload.php';
                try {
                    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
                    
                    // Server settings for localhost
                    $mail->isSMTP();
                    $mail->Host = 'localhost';
                    $mail->Port = 25;
                    $mail->SMTPAuth = false;

                    // Recipients
                    $mail->setFrom('no-reply@cross-system.local', 'CROSS Admin');
                    $mail->addAddress($user_email, $user_name);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Your Password Has Been Reset';
                    $mail->Body = "Hello $user_name,<br><br>Your password has been reset by the administrator.<br><br>New Password: <strong>$plain_password</strong><br><br>Please log in and change your password as soon as possible.<br><br>Regards,<br>CROSS Admin";

                    $mail->send();
                } catch (\Exception $e) {
                    // If PHPMailer fails, fall back to simple file logging
                    $log_entry = "-----\nTo: {$user_email}\nSubject: Password Reset\nMessage: New password: {$plain_password}\n-----\n";
                    file_put_contents(__DIR__ . '/email_log.txt', $log_entry, FILE_APPEND | LOCK_EX);
                }

                $_SESSION['success'] = "Password reset successfully! New password sent to user's email.";
                break;
                
            case 'delete':
                $conn->query("DELETE FROM users WHERE id = $user_id AND role != 'admin'");
                $_SESSION['success'] = "User deleted successfully!";
                break;
        }
        
        header("Location: manage_users.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - CROSS</title>
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
                        <h1 style="margin: 0; color: var(--maroon);">Manage Users</h1>
                        <p style="margin: 5px 0 0; color: var(--text-light);">Manage all system users and scholars</p>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>

                <!-- User Statistics -->
                <div class="dashboard-stats">
                    <div class="dashboard-card">
                        <div class="card-icon">👥</div>
                        <h3><?php echo count($users); ?></h3>
                        <p>Total Users</p>
                    </div>
                    <div class="dashboard-card">
                        <div class="card-icon">🎓</div>
                        <h3><?php echo count(array_filter($users, fn($u) => $u['role'] === 'scholar' && $u['status'] === 'approved')); ?></h3>
                        <p>Approved Scholars</p>
                    </div>
                    <div class="dashboard-card">
                        <div class="card-icon">⏳</div>
                        <h3><?php echo count(array_filter($users, fn($u) => $u['status'] === 'pending')); ?></h3>
                        <p>Pending Applicants</p>
                    </div>
                    <div class="dashboard-card">
                        <div class="card-icon">👑</div>
                        <h3><?php echo count(array_filter($users, fn($u) => $u['role'] === 'admin')); ?></h3>
                        <p>Administrators</p>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2 style="margin: 0;">All Users</h2>
                        <div style="display: flex; gap: 10px;">
                            <input type="text" id="searchUsers" placeholder="Search users..." class="form-control" style="width: 250px;">
                            <select id="filterStatus" class="form-control" style="width: 150px;">
                                <option value="">All Status</option>
                                <option value="approved">Approved</option>
                                <option value="pending">Pending</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-container">
                        <table class="data-table" id="usersTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Course</th>
                                    <th>Year Level</th>
                                    <th>Student ID</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Registered</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($user['fullname']); ?></strong>
                                            <?php if ($user['role'] === 'admin'): ?>
                                                <br><small class="badge" style="background: var(--gold); color: white;">Admin</small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><?php echo htmlspecialchars($user['course']); ?></td>
                                        <td><?php echo htmlspecialchars($user['year_level']); ?></td>
                                        <td><?php echo htmlspecialchars($user['id_number']); ?></td>
                                        <td>
                                            <span class="badge" style="background: <?php echo $user['role'] === 'admin' ? 'var(--maroon)' : 'var(--gold)'; ?>; color: white;">
                                                <?php echo ucfirst($user['role']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge" style="background: 
                                                <?php echo $user['status'] === 'active' ? 'green' : 
                                                      ($user['status'] === 'pending' ? 'orange' : 'red'); ?>; 
                                                color: white;">
                                                <?php echo ucfirst($user['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                        <td>
                                            <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                                <?php if ($user['role'] !== 'admin'): ?>
                                                    <?php if ($user['status'] === 'active'): ?>
                                                        <form method="POST" style="display: inline;">
                                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                            <input type="hidden" name="action" value="deactivate">
                                                            <button type="submit" class="btn btn-warning" style="padding: 4px 8px; font-size: 0.8rem;">
                                                                <i class="fas fa-pause"></i> Deactivate
                                                            </button>
                                                        </form>
                                                    <?php elseif ($user['status'] === 'inactive'): ?>
                                                        <form method="POST" style="display: inline;">
                                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                            <input type="hidden" name="action" value="activate">
                                                            <button type="submit" class="btn btn-success" style="padding: 4px 8px; font-size: 0.8rem;">
                                                                <i class="fas fa-play"></i> Activate
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                    
                                                    <form method="POST" style="display: inline;">
                                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                        <input type="hidden" name="action" value="reset_password">
                                                        <button type="submit" class="btn btn-secondary" style="padding: 4px 8px; font-size: 0.8rem;">
                                                            <i class="fas fa-key"></i> Reset PW
                                                        </button>
                                                    </form>
                                                    
                                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                        <input type="hidden" name="action" value="delete">
                                                        <button type="submit" class="btn btn-danger" style="padding: 4px 8px; font-size: 0.8rem;">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <span class="badge" style="background: var(--dark); color: white;">Protected</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- User Policy Information -->
                <div class="card" style="margin-top: 30px;">
                    <h3>Scholarship Policy Information</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
                        <div style="padding: 20px; background: var(--gray); border-radius: var(--radius);">
                            <h4 style="color: var(--maroon); margin-bottom: 10px;">4-Year Scholarship Duration</h4>
                            <p style="color: var(--text-light); font-size: 0.9rem;">
                                All scholarships are valid for 4 years (standard college duration). 
                                After this period, scholar status automatically changes to inactive.
                            </p>
                        </div>
                        <div style="padding: 20px; background: var(--gray); border-radius: var(--radius);">
                            <h4 style="color: var(--maroon); margin-bottom: 10px;">Academic Requirements</h4>
                            <p style="color: var(--text-light); font-size: 0.9rem;">
                                Scholars must maintain a minimum GPA of 2.5 and complete at least 
                                2 seminars per semester to remain eligible.
                            </p>
                        </div>
                        <div style="padding: 20px; background: var(--gray); border-radius: var(--radius);">
                            <h4 style="color: var(--maroon); margin-bottom: 10px;">Data Privacy</h4>
                            <p style="color: var(--text-light); font-size: 0.9rem;">
                                All personal information is protected under the Data Privacy Act of 2012. 
                                Users can request data deletion at any time.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Search and filter functionality
        document.getElementById('searchUsers').addEventListener('input', function() {
            filterUsers();
        });

        document.getElementById('filterStatus').addEventListener('change', function() {
            filterUsers();
        });

        function filterUsers() {
            const searchTerm = document.getElementById('searchUsers').value.toLowerCase();
            const statusFilter = document.getElementById('filterStatus').value;
            const rows = document.querySelectorAll('#usersTable tbody tr');
            
            rows.forEach(row => {
                const name = row.cells[0].textContent.toLowerCase();
                const email = row.cells[1].textContent.toLowerCase();
                const status = row.cells[6].textContent.toLowerCase();
                
                const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
                const matchesStatus = !statusFilter || status === statusFilter;
                
                row.style.display = matchesSearch && matchesStatus ? '' : 'none';
            });
        }
    </script>
</body>
</html>