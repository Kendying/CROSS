<?php
require_once __DIR__ . '/require_scholar.php';
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Certificates - CROSS</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Dashboard Layout for Logged-in Users -->
    <div class="sidebar-layout">
        <!-- Sidebar -->
        <?php include __DIR__ . '/includes/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h1 style="margin: 0; color: var(--maroon);">My Certificates</h1>
                        <p style="margin: 5px 0 0; color: var(--text-light);">Your earned seminar certificates</p>
                    </div>
                    <div>
                        <span class="badge" style="background-color: var(--gold); color: white; padding: 8px 16px; border-radius: 20px;">
                            <i class="fas fa-user-graduate"></i> Scholar
                        </span>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <!-- Certificates Content -->
                <div class="card">
                    <h2>Your Seminar Certificates</h2>
                    <div style="text-align: center; padding: 40px; color: var(--text-light);">
                        <i class="fas fa-certificate" style="font-size: 3rem; margin-bottom: 15px; display: block; color: var(--gold);"></i>
                        <h3>No Certificates Yet</h3>
                        <p>Your certificates will appear here after completing seminars. Attend seminars to earn certificates!</p>
                        
                        <div style="margin-top: 20px;">
                            <a href="seminars.php" class="btn btn-primary">
                                <i class="fas fa-calendar-alt"></i> Browse Seminars
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Certificate Preview Example -->
                <div class="card" style="margin-top: 30px;">
                    <h3>Certificate Preview</h3>
                    <div class="certificate-preview">
                        <div class="certificate-border"></div>
                        <h2 style="color: var(--maroon); margin-bottom: 20px;">Certificate of Completion</h2>
                        <p style="font-size: 1.1rem; margin-bottom: 15px;">This certifies that</p>
                        <h3 style="color: var(--gold); margin-bottom: 20px; border-bottom: 2px solid var(--gold); padding-bottom: 10px; display: inline-block;">
                            <?php echo htmlspecialchars($user_name); ?>
                        </h3>
                        <p style="margin-bottom: 20px;">has successfully completed the</p>
                        <h4 style="color: var(--maroon); margin-bottom: 25px;">Seminar Title Here</h4>
                        <p style="margin-bottom: 20px;">on Date Here</p>
                        <div style="margin-top: 30px;">
                            <button class="btn btn-gold" disabled>
                                <i class="fas fa-download"></i> Download Certificate (Available after completion)
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="script.js"></script>
</body>
</html>