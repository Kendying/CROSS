<?php include 'config.php'; 

$error = '';
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'invalid_password':
            $error = 'Invalid password. Please try again.';
            break;
        case 'user_not_found':
            $error = 'No admin account found with this email.';
            break;
        case 'not_admin':
            $error = 'This account does not have admin privileges.';
            break;
        default:
            $error = 'Login failed. Please try again.';
    }
}

// Check if admin is already logged in
if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin') {
    header("Location: dashboard_admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - CROSS</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <div class="logo-icon">C</div>
                    <div class="logo-text">CROSS</div>
                </div>
                <div class="nav-links">
                    <a href="index.php" class="nav-link">Home</a>
                    <a href="about.php" class="nav-link">About</a>
                    <a href="login.php" class="nav-link">Scholar Login</a>
                </div>
                <div class="user-menu">
                    <a href="login.php" class="btn btn-secondary">Scholar Login</a>
                </div>
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </nav>
        </div>
    </header>

    <!-- Admin Login Form -->
    <section class="section">
        <div class="container">
            <div class="form-container fade-in">
                <div class="form-header">
                    <h2>Admin Login</h2>
                    <p>Administrator access only</p>
                    
                    <!-- Error Message -->
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-error">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <form id="adminLoginForm" action="process_admin_login.php" method="POST">
                    <div class="form-group">
                        <label for="admin-email" class="form-label">Admin Email</label>
                        <input type="email" id="admin-email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="admin-password" class="form-label">Password</label>
                        <input type="password" id="admin-password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-gold" style="width: 100%;">Login as Admin</button>
                    </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="copyright">
                <p>&copy; <?php echo date('Y'); ?> Servus Amoris Foundation. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>