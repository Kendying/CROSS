<?php include 'config.php'; 

// Check for error messages
$error = '';
$success = '';

if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'password_mismatch':
            $error = 'Passwords do not match. Please try again.';
            break;
        case 'password_length':
            $error = 'Password must be at least 8 characters long.';
            break;
        case 'email_exists':
            $error = 'An account with this email already exists.';
            break;
        case 'id_exists':
            $error = 'This student ID number is already registered.';
            break;
        case 'registration_failed':
            $error = 'Registration failed. Please try again.';
            break;
        default:
            $error = 'An error occurred. Please try again.';
    }
}

if (isset($_GET['success'])) {
    if ($_GET['success'] === 'check_email') {
        $success = 'Registration successful! Please check your email for a verification link to activate your account.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholar Registration - CROSS</title>
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
                    <a href="login.php" class="btn btn-secondary">Login</a>
                </div>
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </nav>
        </div>
    </header>


    <!-- Registration Form -->
    <section class="section">
        <div class="container">
            <div class="form-container fade-in">
                <div class="form-header">
                    <h2>Register</h2>
                    <p>Fill out the form below to create your CROSS account</p>
                    
                    <!-- Success Message -->
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success">
                            <?php echo $success; ?>
                        </div>
                        <?php if (session_status() == PHP_SESSION_NONE) session_start(); ?>
                        <?php if (!empty($_SESSION['verification_link'])): ?>
                            <div class="alert alert-info" style="margin-top:10px;">
                                <p><strong>Verification link (copy/paste):</strong></p>
                                <p><a href="<?php echo htmlspecialchars($_SESSION['verification_link']); ?>" target="_blank"><?php echo htmlspecialchars($_SESSION['verification_link']); ?></a></p>
                                <p style="font-size:0.9rem;color:#666;">The link was generated for you because email delivery failed. Please use it to verify your account.</p>
                            </div>
                            <?php unset($_SESSION['verification_link']); ?>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <!-- Error Message -->
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-error">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <form id="registrationForm" action="process_register.php" method="POST">
                    <div class="form-group">
                        <label for="fullname" class="form-label">Full Name</label>
                        <input type="text" id="fullname" name="fullname" class="form-control" required value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="course" class="form-label">Course</label>
                        <select id="course" name="course" class="form-control" required>
                            <option value="">Select Course</option>
                            <option value="BS Computer Science" <?php echo (isset($_POST['course']) && $_POST['course'] === 'BS Computer Science') ? 'selected' : ''; ?>>BS Computer Science</option>
                            <option value="BS Information Technology" <?php echo (isset($_POST['course']) && $_POST['course'] === 'BS Information Technology') ? 'selected' : ''; ?>>BS Information Technology</option>
                            <option value="BS Business Administration" <?php echo (isset($_POST['course']) && $_POST['course'] === 'BS Business Administration') ? 'selected' : ''; ?>>BS Business Administration</option>
                            <option value="BS Education" <?php echo (isset($_POST['course']) && $_POST['course'] === 'BS Education') ? 'selected' : ''; ?>>BS Education</option>
                            <option value="BS Engineering" <?php echo (isset($_POST['course']) && $_POST['course'] === 'BS Engineering') ? 'selected' : ''; ?>>BS Engineering</option>
                            <option value="BS Nursing" <?php echo (isset($_POST['course']) && $_POST['course'] === 'BS Nursing') ? 'selected' : ''; ?>>BS Nursing</option>
                            <option value="Other" <?php echo (isset($_POST['course']) && $_POST['course'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="year" class="form-label">Year Level</label>
                        <select id="year" name="year" class="form-control" required>
                            <option value="">Select Year</option>
                            <option value="1st Year" <?php echo (isset($_POST['year']) && $_POST['year'] === '1st Year') ? 'selected' : ''; ?>>1st Year</option>
                            <option value="2nd Year" <?php echo (isset($_POST['year']) && $_POST['year'] === '2nd Year') ? 'selected' : ''; ?>>2nd Year</option>
                            <option value="3rd Year" <?php echo (isset($_POST['year']) && $_POST['year'] === '3rd Year') ? 'selected' : ''; ?>>3rd Year</option>
                            <option value="4th Year" <?php echo (isset($_POST['year']) && $_POST['year'] === '4th Year') ? 'selected' : ''; ?>>4th Year</option>
                            <option value="5th Year" <?php echo (isset($_POST['year']) && $_POST['year'] === '5th Year') ? 'selected' : ''; ?>>5th Year</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="id_number" class="form-label">Student ID Number</label>
                        <input type="text" id="id_number" name="id_number" class="form-control" required value="<?php echo isset($_POST['id_number']) ? htmlspecialchars($_POST['id_number']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                        <small>Password must be at least 8 characters long</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <div class="card" style="background-color: #f8f9fa; padding: 15px;">
                            <h4 style="margin-bottom: 10px;">Data Privacy Notice</h4>
                            <p style="font-size: 0.9rem; margin-bottom: 15px;">
                                By submitting this application, you consent to the collection and processing of your personal information 
                                in accordance with the Data Privacy Act of 2012. Your information will be used solely for scholarship 
                                application and management purposes.
                            </p>
                            <label style="display: flex; align-items: flex-start; gap: 10px;">
                                <input type="checkbox" required>
                                <span>I have read and agree to the data privacy policy</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Register</button>
                    </div>
                </form>
                
                <div style="text-align: center;">
                    <p>Already have an account? <a href="login.php">Login here</a></p>
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