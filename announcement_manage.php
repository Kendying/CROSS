<?php
include 'config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Get pending approvals count for sidebar
$pending_sql = "SELECT COUNT(*) as count FROM users WHERE status = 'pending'";
$pending_result = $conn->query($pending_sql);
$pending_data = $pending_result->fetch_assoc();
$pending_count = $pending_data['count'] ?? 0;

// Get all announcements
$announcements_sql = "SELECT a.*, u.fullname as author_name 
                      FROM announcements a 
                      LEFT JOIN users u ON a.author_id = u.id 
                      ORDER BY a.created_at DESC";
$announcements_result = $conn->query($announcements_sql);
$announcements = [];
if ($announcements_result) {
    while ($row = $announcements_result->fetch_assoc()) {
        $announcements[] = $row;
    }
}

// Handle announcement actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_announcement'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $is_published = isset($_POST['is_published']) ? 1 : 0;
        $author_id = $_SESSION['user_id'];
        
        $sql = "INSERT INTO announcements (title, content, author_id, is_published) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $title, $content, $author_id, $is_published);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Announcement created successfully!";
        } else {
            $_SESSION['error'] = "Error creating announcement.";
        }
        
        header("Location: announcement_manage.php");
        exit();
    }
    
    if (isset($_POST['delete_announcement'])) {
        $announcement_id = $_POST['announcement_id'];
        $conn->query("DELETE FROM announcements WHERE id = $announcement_id");
        $_SESSION['success'] = "Announcement deleted successfully!";
        header("Location: announcement_manage.php");
        exit();
    }
    
    if (isset($_POST['toggle_publish'])) {
        $announcement_id = $_POST['announcement_id'];
        $current_status = $_POST['current_status'];
        $new_status = $current_status ? 0 : 1;
        
        $conn->query("UPDATE announcements SET is_published = $new_status WHERE id = $announcement_id");
        $_SESSION['success'] = "Announcement " . ($new_status ? "published" : "unpublished") . " successfully!";
        header("Location: announcement_manage.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Announcements - CROSS</title>
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
                        <h1 style="margin: 0; color: var(--maroon);">Manage Announcements</h1>
                        <p style="margin: 5px 0 0; color: var(--text-light);">Create and manage system announcements</p>
                    </div>
                    <button class="btn btn-primary" onclick="openAddModal()">
                        <i class="fas fa-plus"></i> New Announcement
                    </button>
                </div>
            </div>

            <div class="content-body">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <!-- Announcements List -->
                <div class="card">
                    <h2>System Announcements</h2>
                    
                    <?php if (!empty($announcements)): ?>
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Content</th>
                                        <th>Author</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($announcements as $announcement): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($announcement['title']); ?></strong>
                                            </td>
                                            <td><?php echo htmlspecialchars(substr($announcement['content'], 0, 100)) . '...'; ?></td>
                                            <td><?php echo htmlspecialchars($announcement['author_name']); ?></td>
                                            <td>
                                                <span class="badge" style="background: <?php echo $announcement['is_published'] ? 'green' : 'orange'; ?>; color: white;">
                                                    <?php echo $announcement['is_published'] ? 'Published' : 'Draft'; ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M j, Y', strtotime($announcement['created_at'])); ?></td>
                                            <td>
                                                <div style="display: flex; gap: 5px;">
                                                    <form method="POST" style="display: inline;">
                                                        <input type="hidden" name="announcement_id" value="<?php echo $announcement['id']; ?>">
                                                        <input type="hidden" name="current_status" value="<?php echo $announcement['is_published']; ?>">
                                                        <input type="hidden" name="toggle_publish">
                                                        <button type="submit" class="btn btn-<?php echo $announcement['is_published'] ? 'warning' : 'success'; ?>">
                                                            <i class="fas fa-<?php echo $announcement['is_published'] ? 'eye-slash' : 'eye'; ?>"></i>
                                                            <?php echo $announcement['is_published'] ? 'Unpublish' : 'Publish'; ?>
                                                        </button>
                                                    </form>
                                                    <button class="btn btn-primary" onclick="editAnnouncement(<?php echo $announcement['id']; ?>)">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                                                        <input type="hidden" name="announcement_id" value="<?php echo $announcement['id']; ?>">
                                                        <input type="hidden" name="delete_announcement">
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p style="text-align: center; padding: 40px; color: var(--text-light);">
                            <i class="fas fa-bullhorn" style="font-size: 3rem; margin-bottom: 15px; display: block; color: var(--gold);"></i>
                            No announcements yet. Create your first announcement!
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Announcement Modal -->
    <div id="announcementModal" class="modal">
        <div class="modal-content" style="max-width: 700px;">
            <div class="modal-header">
                <h3>Create New Announcement</h3>
                <button class="modal-close">&times;</button>
            </div>
            <form method="POST">
                <input type="hidden" name="add_announcement" value="1">
                
                <div class="form-group">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control" name="title" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Content</label>
                    <textarea class="form-control" name="content" rows="6" required></textarea>
                </div>
                
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="is_published" checked>
                        <span>Publish immediately</span>
                    </label>
                </div>
                
                <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" onclick="closeAnnouncementModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Announcement</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('announcementModal').style.display = 'flex';
        }

        function closeAnnouncementModal() {
            document.getElementById('announcementModal').style.display = 'none';
        }

        function editAnnouncement(id) {
            alert('Edit functionality for announcement ID: ' + id + ' would be implemented here.');
            // You can implement edit functionality by:
            // 1. Fetching announcement data via AJAX
            // 2. Populating a modal form with the data
            // 3. Submitting the update via another POST handler
        }

        // Close modal when clicking outside
        document.getElementById('announcementModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAnnouncementModal();
            }
        });

        document.querySelector('#announcementModal .modal-close').addEventListener('click', closeAnnouncementModal);
    </script>
</body>
</html>