<?php 
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

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $course = $_POST['course'];
    $year_level = $_POST['year_level'];
    $id_number = $_POST['id_number'];
    
    // Check if email already exists (excluding current user)
    $email_check_sql = "SELECT id FROM users WHERE email = ? AND id != ?";
    $stmt = $conn->prepare($email_check_sql);
    $stmt->bind_param("si", $email, $user_id);
    $stmt->execute();
    $email_result = $stmt->get_result();
    
    if ($email_result->num_rows > 0) {
        $_SESSION['error'] = "Email already exists. Please use a different email.";
    } else {
        // Check if ID number already exists (excluding current user)
        $id_check_sql = "SELECT id FROM users WHERE id_number = ? AND id != ?";
        $stmt = $conn->prepare($id_check_sql);
        $stmt->bind_param("si", $id_number, $user_id);
        $stmt->execute();
        $id_result = $stmt->get_result();
        
        if ($id_result->num_rows > 0) {
            $_SESSION['error'] = "ID number already exists. Please use a different ID number.";
        } else {
            $update_sql = "UPDATE users SET fullname = ?, email = ?, course = ?, 
                           year_level = ?, id_number = ?, updated_at = CURRENT_TIMESTAMP 
                           WHERE id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("sssssi", $fullname, $email, $course, $year_level, $id_number, $user_id);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = "Profile updated successfully!";
                // Update session variables
                $_SESSION['user_name'] = $fullname;
                // Refresh user data
                $user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
            } else {
                $_SESSION['error'] = "Error updating profile. Please try again.";
            }
        }
    }
    
    header("Location: profile.php");
    exit();
}

// Handle reapply action for rejected applicants
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reapply'])) {
    // Only allow if user is rejected
    if ($user['status'] === 'rejected') {
        $update_sql = "UPDATE users SET status = 'pending', rejection_reason = NULL, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Reapplication submitted. Your application is now pending review.";
            // refresh user
            $user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
        } else {
            $_SESSION['error'] = "Error submitting reapplication. Please try again.";
        }
    } else {
        $_SESSION['error'] = "Reapply is only available for rejected applicants.";
    }
    header("Location: profile.php");
    exit();
}

// Password changes are now handled by administrators only
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - CROSS</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="sidebar-layout">
        <!-- Sidebar -->
        <?php include __DIR__ . '/includes/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h1 style="margin: 0; color: var(--maroon);">My Profile</h1>
                        <p style="margin: 5px 0 0; color: var(--text-light);">Manage your personal information and account settings</p>
                    </div>
                    <div>
                        <span class="badge" style="background-color: var(--gold); color: white; padding: 8px 16px; border-radius: 20px;">
                            <i class="fas fa-user-graduate"></i> <?php echo ucfirst($user['status']); ?> Scholar
                        </span>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                    <!-- Profile Information -->
                    <div class="card">
                        <h2 style="margin-bottom: 20px;">Profile Information</h2>
                        <form method="POST">
                            <input type="hidden" name="update_profile" value="1">
                            
                            <div style="text-align: center; margin-bottom: 30px;">
                                <div class="profile-picture-container" style="width: 120px; height: 120px; border-radius: 50%; background: var(--gray); display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; overflow: hidden; border: 3px solid var(--gold);">
                                    <span style="font-size: 2.5rem; color: var(--maroon); font-weight: bold;">
                                        <?php echo strtoupper(substr($user['fullname'], 0, 1)); ?>
                                    </span>
                                </div>
                                <p style="color: var(--text-light); font-size: 0.9rem; margin: 0;">
                                    Profile picture feature coming soon
                                </p>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                <div class="form-group">
                                    <label class="form-label">Course/Program</label>
                                    <input type="text" class="form-control" name="course" value="<?php echo htmlspecialchars($user['course']); ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Year Level</label>
                                    <select class="form-control" name="year_level" required>
                                        <option value="">Select Year Level</option>
                                        <option value="1st Year" <?php echo $user['year_level'] === '1st Year' ? 'selected' : ''; ?>>1st Year</option>
                                        <option value="2nd Year" <?php echo $user['year_level'] === '2nd Year' ? 'selected' : ''; ?>>2nd Year</option>
                                        <option value="3rd Year" <?php echo $user['year_level'] === '3rd Year' ? 'selected' : ''; ?>>3rd Year</option>
                                        <option value="4th Year" <?php echo $user['year_level'] === '4th Year' ? 'selected' : ''; ?>>4th Year</option>
                                        <option value="5th Year" <?php echo $user['year_level'] === '5th Year' ? 'selected' : ''; ?>>5th Year</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Student ID Number</label>
                                <input type="text" class="form-control" name="id_number" value="<?php echo htmlspecialchars($user['id_number']); ?>" required>
                            </div>

                            <div style="text-align: right; margin-top: 20px;">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Profile
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Account Settings -->
                    <div>
                        <!-- Password Notice -->
                        <div class="card" style="margin-bottom: 30px;">
                            <h2 style="margin-bottom: 20px;">Password Management</h2>
                            <div style="background: rgba(128, 0, 0, 0.1); padding: 20px; border-radius: var(--radius); border-left: 4px solid var(--maroon);">
                                <p style="margin: 0; color: var(--text-dark);">
                                    For security reasons, password changes can only be initiated by administrators. 
                                    Please contact your administrator if you need to change your password.
                                </p>
                            </div>
                        </div>

                        <!-- Account Information -->
                        <div class="card">
                            <h2 style="margin-bottom: 20px;">Account Information</h2>
                            <div style="display: grid; gap: 15px;">
                                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee;">
                                    <span style="font-weight: 600;">Account Status:</span>
                                    <span class="badge" style="background: <?php echo ($user['status'] === 'approved' || $user['status'] === 'active') ? 'green' : ($user['status'] === 'pending' ? 'orange' : 'red'); ?>; color: white;">
                                        <?php echo ucfirst($user['status']); ?>
                                    </span>
                                </div>
                                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee;">
                                    <span style="font-weight: 600;">User Role:</span>
                                    <span class="badge" style="background: var(--maroon); color: white;">
                                        <?php echo ucfirst($user['role']); ?>
                                    </span>
                                </div>
                                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee;">
                                    <span style="font-weight: 600;">Member Since:</span>
                                    <span><?php echo date('M j, Y', strtotime($user['created_at'])); ?></span>
                                </div>
                                <div style="display: flex; justify-content: space-between; padding: 10px 0;">
                                    <span style="font-weight: 600;">Last Updated:</span>
                                    <span><?php echo date('M j, Y g:i A', strtotime($user['updated_at'])); ?></span>
                                </div>
                                <?php if (!empty($user['rejection_reason'])): ?>
                                <div style="margin-top: 10px; padding: 10px; background: #ffecec; border-left: 4px solid #dc3545; border-radius: 4px;">
                                    <strong style="color: #dc3545;">Rejection Reason</strong>
                                    <p style="margin: 8px 0 0; color: #444; font-size: 0.95rem;"><?php echo nl2br(htmlspecialchars($user['rejection_reason'])); ?></p>
                                </div>
                                <?php endif; ?>
                                <?php if ($user['status'] === 'rejected'): ?>
                                <div style="margin-top: 12px; text-align: right;">
                                    <form method="POST" onsubmit="return confirm('Submit reapplication? This will set your application back to pending.');">
                                        <input type="hidden" name="reapply" value="1">
                                        <button type="submit" class="btn btn-primary">Reapply</button>
                                    </form>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Scholarship Information -->
                        <div class="card" style="margin-top: 30px;">
                            <h2 style="margin-bottom: 20px;">Scholarship Information</h2>
                            <div style="background: rgba(201, 155, 67, 0.1); padding: 20px; border-radius: var(--radius); border-left: 4px solid var(--gold);">
                                <h4 style="color: var(--gold); margin-bottom: 10px;">CROSS Scholarship</h4>
                                <p style="margin: 0; color: var(--text-light); font-size: 0.9rem;">
                                    Your scholarship is valid for the standard duration of your course. 
                                    Maintain good academic standing to remain eligible.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>