<?php 
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user data for sidebar
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];

// Get published announcements from database
$announcements_sql = "SELECT a.*, u.fullname as author_name 
                      FROM announcements a 
                      LEFT JOIN users u ON a.author_id = u.id 
                      WHERE a.is_published = 1 
                      ORDER BY a.created_at DESC";
$announcements_result = $conn->query($announcements_sql);
$announcements = [];
if ($announcements_result) {
    while ($row = $announcements_result->fetch_assoc()) {
        $announcements[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements - CROSS</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- Dashboard Layout for Logged-in Users -->
        <div class="sidebar-layout">
            <?php include __DIR__ . '/includes/sidebar.php'; ?>

            <!-- Main Content -->
            <main class="main-content">
                <div class="content-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h1 style="margin: 0; color: var(--maroon);">Announcements</h1>
                            <p style="margin: 5px 0 0; color: var(--text-light);">Stay updated with the latest news</p>
                        </div>
                        <div>
                            <span class="badge" style="background-color: var(--gold); color: white; padding: 8px 16px; border-radius: 20px;">
                                <?php if ($user_role === 'applicant'): ?>
                                    <i class="fas fa-user"></i> Applicant
                                <?php elseif ($user_role === 'admin'): ?>
                                    <i class="fas fa-user-shield"></i> Admin
                                <?php else: ?>
                                    <i class="fas fa-user-graduate"></i> Scholar
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="content-body">
                    <!-- Announcements Content -->
                    <div class="card">
                        <h2>Foundation Announcements</h2>
                        
                        <?php if (!empty($announcements)): ?>
                            <div class="announcements-list">
                                <?php foreach ($announcements as $announcement): ?>
                                    <div class="announcement-item" style="border: 1px solid #eee; border-radius: 8px; padding: 20px; margin-bottom: 20px; background: white;">
                                        <div style="display: flex; justify-content: between; align-items: flex-start; margin-bottom: 15px;">
                                            <h3 style="margin: 0; color: var(--maroon); flex: 1;">
                                                <?php echo htmlspecialchars($announcement['title']); ?>
                                            </h3>
                                            <span style="background: var(--gold); color: white; padding: 4px 12px; border-radius: 15px; font-size: 0.8rem;">
                                                New
                                            </span>
                                        </div>
                                        
                                        <div style="color: var(--text-light); margin-bottom: 15px; line-height: 1.6;">
                                            <?php echo nl2br(htmlspecialchars($announcement['content'])); ?>
                                        </div>
                                        
                                        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #eee; padding-top: 15px;">
                                            <div style="color: var(--text-light); font-size: 0.9rem;">
                                                <strong>Posted by:</strong> <?php echo htmlspecialchars($announcement['author_name']); ?>
                                            </div>
                                            <div style="color: var(--text-light); font-size: 0.9rem;">
                                                <strong>Date:</strong> <?php echo date('F j, Y g:i A', strtotime($announcement['created_at'])); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div style="text-align: center; padding: 40px; color: var(--text-light);">
                                <i class="fas fa-bullhorn" style="font-size: 3rem; margin-bottom: 15px; display: block; color: var(--gold);"></i>
                                <h3>No Announcements Yet</h3>
                                <p>Check back later for important updates and news from the foundation.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>

    <?php else: ?>
        <!-- Public Layout for Non-Logged-in Users -->
        <!-- ... (keep the existing public layout code) ... -->
    <?php endif; ?>

    <script src="script.js"></script>
</body>
</html>