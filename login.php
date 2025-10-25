<?php include 'config.php'; 

// Check for error messages
$error = '';
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'invalid_password':
            $error = 'Invalid password. Please try again.';
            break;
        case 'user_not_found':
            $error = 'No account found with this email.';
            break;
        case 'invalid_role':
            $error = 'Invalid user role. Please contact support.';
            break;
        case 'email_not_verified':
            $error = 'Please verify your email address. Check your inbox for the verification link.';
            break;
        case 'invalid_verification':
            $error = 'Invalid or expired verification link. Please contact support if you need assistance.';
            break;
        default:
            $error = 'Login failed. Please try again.';
    }
}

// Check for success messages
$success = '';
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'registered':
            $success = 'Registration successful! You can now log in using your credentials.';
            break;
        case 'verified':
            $success = 'Email verified successfully! You can now log in to your account.';
            break;
    }
}

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] === 'admin') {
        header("Location: dashboard_admin.php");
    } else {
        header("Location: dashboard.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CROSS</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <img src="assets/logo.webp" alt="CROSS" class="logo-image">
                </div>
                <div class="nav-links">
                    <a href="index.php" class="nav-link">Home</a>
                    <a href="about.php" class="nav-link">About</a>
                    <a href="seminars.php" class="nav-link">Seminars</a>
                    <a href="announcements.php" class="nav-link">Announcements</a>
                </div>
                <div class="user-menu">
                    <a href="register.php" class="btn btn-primary">Register</a>
                </div>
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </nav>
        </div>
    </header>


    <!-- Login Form -->
    <section class="section">
        <div class="container">
            <div class="form-container fade-in">
                <div class="form-header">
                    <h2>Login to CROSS</h2>
                    <p>Access your scholar dashboard</p>
                    
                    <!-- Success Message -->
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success">
                            <?php echo $success; ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Error Message -->
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-error">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Scholar Login Only -->
                <form id="scholarLoginForm" action="process_login.php" method="POST">
                    <div class="form-group">
                        <label for="scholar-email" class="form-label">Email Address</label>
                        <input type="email" id="scholar-email" name="email" class="form-control" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="scholar-password" class="form-label">Password</label>
                        <input type="password" id="scholar-password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Login as Scholar</button>
                    </div>
                    <div style="text-align: center;">
                        <a href="#">Forgot Password?</a>
                    </div>
                </form>
                
                <div style="text-align: center; margin-top: 30px;">
                    <p>Don't have an account? <a href="register.php">Register</a></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="footer-logo">CROSS</div>
                    <p>Care, Renewal, Outreach, Support & Sustainability - An initiative of Servus Amoris Foundation</p>
                </div>
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Apply</a></li>
                        <li><a href="seminars.php">Seminars</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h4>Contact</h4>
                    <ul>
                        <li>Email: info@servusamoris.org</li>
                        <li>Phone: (02) 1234-5678</li>
                        <li>Address: Manila, Philippines</li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; <?php echo date('Y'); ?> Servus Amoris Foundation. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>