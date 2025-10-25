<?php
require_once __DIR__ . '/require_scholar.php';
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

// Get upcoming seminars from database
$upcoming_sql = "SELECT * FROM seminars 
                 WHERE date >= CURDATE() 
                 ORDER BY date ASC, time ASC";
$upcoming_result = $conn->query($upcoming_sql);
$upcoming_seminars = [];
if ($upcoming_result) {
    while ($row = $upcoming_result->fetch_assoc()) {
        $upcoming_seminars[] = $row;
    }
}

// Get past seminars from database
$past_sql = "SELECT * FROM seminars 
             WHERE date < CURDATE() 
             ORDER BY date DESC 
             LIMIT 10";
$past_result = $conn->query($past_sql);
$past_seminars = [];
if ($past_result) {
    while ($row = $past_result->fetch_assoc()) {
        $past_seminars[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seminars - CROSS</title>
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
                            <h1 style="margin: 0; color: var(--maroon);">Seminars & Workshops</h1>
                            <p style="margin: 5px 0 0; color: var(--text-light);">Browse and register for upcoming events</p>
                        </div>
                        <div>
                            <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                            <button class="btn btn-primary" onclick="window.location.href='seminars_manage.php'">
                                <i class="fas fa-plus"></i> Manage Seminars
                            </button>
                            <?php else: ?>
                            <span class="badge" style="background-color: var(--gold); color: white; padding: 8px 16px; border-radius: 20px;">
                                <i class="fas fa-user-graduate"></i> Scholar
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="content-body">
                    <!-- Seminars Content -->
                    <div class="card">
                        <h2>Available Seminars</h2>
                        
                        <!-- Search and Filter -->
                        <div style="display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap;">
                            <div style="flex: 1; min-width: 250px;">
                                <input type="text" class="form-control" placeholder="Search seminars..." id="searchInput">
                            </div>
                            <select class="form-control" style="width: 200px;" id="categoryFilter">
                                <option value="all">All Seminars</option>
                                <option value="upcoming">Upcoming</option>
                                <option value="past">Past Seminars</option>
                            </select>
                            <button class="btn btn-primary" onclick="filterSeminars()">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>

                        <!-- Upcoming Seminars -->
                        <?php if (!empty($upcoming_seminars)): ?>
                            <div class="seminar-section">
                                <h3 style="color: var(--maroon); margin-bottom: 20px;">Upcoming Seminars</h3>
                                <div class="table-container">
                                    <table class="data-table">
                                        <thead>
                                            <tr>
                                                <th>Seminar Title</th>
                                                <th>Date & Time</th>
                                                <th>Speaker</th>
                                                <th>Location</th>
                                                <th>Available Slots</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($upcoming_seminars as $seminar): ?>
                                                <tr>
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($seminar['title']); ?></strong>
                                                        <?php if ($seminar['description']): ?>
                                                            <br><small style="color: var(--text-light);"><?php echo htmlspecialchars($seminar['description']); ?></small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo date('M j, Y', strtotime($seminar['date'])); ?><br>
                                                        <small><?php echo date('g:i A', strtotime($seminar['time'])); ?></small>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($seminar['speaker']); ?></td>
                                                    <td><?php echo htmlspecialchars($seminar['location']); ?></td>
                                                    <td>
                                                        <?php if ($seminar['max_attendees']): ?>
                                                            <span class="badge" style="background: var(--gold); color: white;">
                                                                <?php echo $seminar['max_attendees']; ?> slots
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge" style="background: green; color: white;">
                                                                Unlimited
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-primary" onclick="rsvpSeminar(<?php echo $seminar['id']; ?>)">
                                                            <i class="fas fa-calendar-check"></i> RSVP
                                                        </button>
                                                        <button class="btn btn-secondary" onclick="viewSeminarDetails(<?php echo $seminar['id']; ?>)">
                                                            <i class="fas fa-info-circle"></i> Details
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php else: ?>
                            <div style="text-align: center; padding: 40px; color: var(--text-light);">
                                <i class="fas fa-calendar-alt" style="font-size: 3rem; margin-bottom: 15px; display: block; color: var(--gold);"></i>
                                <h3>No Upcoming Seminars</h3>
                                <p>Check back soon for upcoming seminars and workshops. New events are added regularly!</p>
                            </div>
                        <?php endif; ?>

                        <!-- Past Seminars -->
                        <?php if (!empty($past_seminars)): ?>
                            <div class="seminar-section" style="margin-top: 40px;">
                                <h3 style="color: var(--maroon); margin-bottom: 20px;">Past Seminars</h3>
                                <div class="table-container">
                                    <table class="data-table">
                                        <thead>
                                            <tr>
                                                <th>Seminar Title</th>
                                                <th>Date</th>
                                                <th>Speaker</th>
                                                <th>Location</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($past_seminars as $seminar): ?>
                                                <tr>
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($seminar['title']); ?></strong>
                                                        <?php if ($seminar['description']): ?>
                                                            <br><small style="color: var(--text-light);"><?php echo htmlspecialchars(substr($seminar['description'], 0, 100)) . '...'; ?></small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo date('M j, Y', strtotime($seminar['date'])); ?><br>
                                                        <small><?php echo date('g:i A', strtotime($seminar['time'])); ?></small>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($seminar['speaker']); ?></td>
                                                    <td><?php echo htmlspecialchars($seminar['location']); ?></td>
                                                    <td>
                                                        <span class="badge" style="background-color: var(--text-light); color: white;">
                                                            Completed
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-secondary" onclick="viewSeminarDetails(<?php echo $seminar['id']; ?>)">
                                                            <i class="fas fa-eye"></i> View Details
                                                        </button>
                                                        <?php if (checkCertificateEligibility($seminar['id'], $user_id)): ?>
                                                            <button class="btn btn-success" onclick="downloadCertificate(<?php echo $seminar['id']; ?>)">
                                                                <i class="fas fa-certificate"></i> Get Certificate
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
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

    <script>
        function filterSeminars() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const category = document.getElementById('categoryFilter').value;
            const rows = document.querySelectorAll('.data-table tbody tr');
            
            rows.forEach(row => {
                const title = row.cells[0].textContent.toLowerCase();
                const status = row.cells[4] ? row.cells[4].textContent.toLowerCase() : '';
                
                const matchesSearch = title.includes(searchTerm);
                const matchesCategory = category === 'all' || 
                    (category === 'upcoming' && status.includes('slot')) ||
                    (category === 'past' && status.includes('completed'));
                
                row.style.display = matchesSearch && matchesCategory ? '' : 'none';
            });
        }

        function rsvpSeminar(seminarId) {
            if (confirm('Are you sure you want to RSVP for this seminar?')) {
                // Implement RSVP functionality
                alert('RSVP feature will be implemented soon for seminar ID: ' + seminarId);
                // You can redirect to RSVP page or show a modal
                // window.location.href = 'rsvp.php?seminar_id=' + seminarId;
            }
        }

        function viewSeminarDetails(seminarId) {
            alert('Viewing details for seminar ID: ' + seminarId);
            // Implement seminar details view
            // window.location.href = 'seminar_details.php?id=' + seminarId;
        }

        function downloadCertificate(seminarId) {
            alert('Downloading certificate for seminar ID: ' + seminarId);
            // Implement certificate download
            // window.location.href = 'download_certificate.php?seminar_id=' + seminarId;
        }

        // PHP helper function for certificate eligibility
        <?php 
        function checkCertificateEligibility($seminar_id, $user_id) {
            // This should check if user attended the seminar and is eligible for certificate
            // For now, return false as placeholder
            return false;
        }
        ?>
    </script>
</body>
</html>