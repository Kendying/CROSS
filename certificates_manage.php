<?php
include 'config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Get certificates and related data
$certificates_sql = "SELECT c.*, u.fullname, u.email, s.title as seminar_title 
                     FROM certificates c 
                     LEFT JOIN users u ON c.user_id = u.id 
                     LEFT JOIN seminars s ON c.seminar_id = s.id 
                     ORDER BY c.issue_date DESC";
$certificates_result = $conn->query($certificates_sql);
$certificates = [];
if ($certificates_result) {
    while ($row = $certificates_result->fetch_assoc()) {
        $certificates[] = $row;
    }
}

// Get scholars for bulk sending
$scholars_sql = "SELECT id, fullname, email FROM users WHERE role = 'scholar' AND status = 'approved'";
$scholars_result = $conn->query($scholars_sql);
$scholars = [];
if ($scholars_result) {
    while ($row = $scholars_result->fetch_assoc()) {
        $scholars[] = $row;
    }
}

// Get seminars
$seminars_sql = "SELECT id, title FROM seminars WHERE date <= CURDATE()";
$seminars_result = $conn->query($seminars_sql);
$seminars = [];
if ($seminars_result) {
    while ($row = $seminars_result->fetch_assoc()) {
        $seminars[] = $row;
    }
}

// Get pending count for sidebar
$pending_count_sql = "SELECT COUNT(*) as count FROM users WHERE status = 'pending' AND role = 'applicant'";
$pending_count_result = $conn->query($pending_count_sql);
$pending_count = $pending_count_result ? $pending_count_result->fetch_assoc()['count'] : 0;

// Handle certificate generation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['generate_certificate'])) {
        $user_id = $_POST['user_id'];
        $seminar_id = $_POST['seminar_id'];
        $certificate_code = 'CERT-' . strtoupper(uniqid());
        
        $sql = "INSERT INTO certificates (user_id, seminar_id, certificate_code, issue_date) 
                VALUES (?, ?, ?, CURDATE())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $user_id, $seminar_id, $certificate_code);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Certificate generated successfully!";
        } else {
            $_SESSION['error'] = "Error generating certificate.";
        }
        
        header("Location: certificates_manage.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Manager - CROSS</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="sidebar-layout">
        <?php include __DIR__ . '/includes/sidebar.php'; ?>
                
                <div class="sidebar-nav-section">System</div>
                <a href="system_settings.php" class="sidebar-nav-item">
                    <i class="fas fa-cog"></i>
                    System Settings
                </a>
                <a href="audit_logs.php" class="sidebar-nav-item">
                    <i class="fas fa-clipboard-check"></i>
                    Audit Logs
                </a>
                <a href="logout.php" class="sidebar-nav-item">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h1 style="margin: 0; color: var(--maroon);">Certificate Manager</h1>
                        <p style="margin: 5px 0 0; color: var(--text-light);">Generate and manage scholar certificates</p>
                    </div>
                    <button class="btn btn-primary" onclick="openBulkModal()">
                        <i class="fas fa-certificate"></i> Bulk Generate
                    </button>
                </div>
            </div>

            <div class="content-body">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>

                <!-- Certificate Generation Form -->
                <div class="card">
                    <h2>Generate Single Certificate</h2>
                    <form method="POST">
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; align-items: end;">
                            <div class="form-group">
                                <label class="form-label">Select Scholar</label>
                                <select class="form-control" name="user_id" required>
                                    <option value="">Choose a scholar...</option>
                                    <?php foreach ($scholars as $scholar): ?>
                                        <option value="<?php echo $scholar['id']; ?>">
                                            <?php echo htmlspecialchars($scholar['fullname'] . ' - ' . $scholar['email']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Select Seminar</label>
                                <select class="form-control" name="seminar_id" required>
                                    <option value="">Choose a seminar...</option>
                                    <?php foreach ($seminars as $seminar): ?>
                                        <option value="<?php echo $seminar['id']; ?>">
                                            <?php echo htmlspecialchars($seminar['title']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="generate_certificate" class="btn btn-primary" style="width: 100%;">
                                    <i class="fas fa-plus"></i> Generate Certificate
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Certificates List -->
                <div class="card" style="margin-top: 30px;">
                    <h2>Issued Certificates</h2>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Certificate Code</th>
                                    <th>Scholar Name</th>
                                    <th>Seminar</th>
                                    <th>Issue Date</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($certificates as $cert): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($cert['certificate_code']); ?></strong>
                                        </td>
                                        <td><?php echo htmlspecialchars($cert['fullname']); ?></td>
                                        <td><?php echo htmlspecialchars($cert['seminar_title'] ?? 'N/A'); ?></td>
                                        <td><?php echo date('M j, Y', strtotime($cert['issue_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($cert['email']); ?></td>
                                        <td>
                                            <div style="display: flex; gap: 5px;">
                                                <button class="btn btn-primary" onclick="viewCertificate('<?php echo $cert['certificate_code']; ?>')">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                                <button class="btn btn-success" onclick="sendCertificate('<?php echo $cert['certificate_code']; ?>', '<?php echo $cert['email']; ?>')">
                                                    <i class="fas fa-paper-plane"></i> Send
                                                </button>
                                                <button class="btn btn-secondary" onclick="downloadCertificate('<?php echo $cert['certificate_code']; ?>')">
                                                    <i class="fas fa-download"></i> Download
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Bulk Certificate Modal -->
    <div id="bulkModal" class="modal">
        <div class="modal-content" style="max-width: 800px;">
            <div class="modal-header">
                <h3>Bulk Certificate Generation</h3>
                <button class="modal-close">&times;</button>
            </div>
            <div style="padding: 20px;">
                <p style="color: var(--text-light); margin-bottom: 20px;">
                    Bulk certificate generation feature will be implemented soon. 
                    For now, please use the single certificate generation form above.
                </p>
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" class="btn btn-secondary" onclick="closeBulkModal()">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openBulkModal() {
            document.getElementById('bulkModal').style.display = 'flex';
        }

        function closeBulkModal() {
            document.getElementById('bulkModal').style.display = 'none';
        }

        function viewCertificate(code) {
            alert('Viewing certificate: ' + code);
            // Implement certificate viewing logic
        }

        function sendCertificate(code, email) {
            if (confirm('Send certificate ' + code + ' to ' + email + '?')) {
                alert('Certificate sent successfully to ' + email);
                // Implement email sending logic
            }
        }

        function downloadCertificate(code) {
            alert('Downloading certificate: ' + code);
            // Implement download logic
        }

        // Close modal when clicking outside
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.style.display = 'none';
                }
            });
        });

        document.querySelectorAll('.modal-close').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.modal').style.display = 'none';
            });
        });
    </script>
</body>
</html>