<?php
include 'config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Get all seminars
$seminars_sql = "SELECT s.*, u.fullname as created_by_name 
                 FROM seminars s 
                 LEFT JOIN users u ON s.created_by = u.id 
                 ORDER BY s.date DESC, s.time DESC";
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

// Handle seminar actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_seminar'])) {
        $seminar_id = $_POST['seminar_id'];
        $conn->query("DELETE FROM seminars WHERE id = $seminar_id");
        $_SESSION['success'] = "Seminar deleted successfully!";
        header("Location: seminars_manage.php");
        exit();
    }
    
    // Add new seminar
    if (isset($_POST['add_seminar'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $speaker = $_POST['speaker'];
        $location = $_POST['location'];
        $max_attendees = $_POST['max_attendees'];
        $created_by = $_SESSION['user_id'];
        
        $sql = "INSERT INTO seminars (title, description, date, time, speaker, location, max_attendees, created_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssii", $title, $description, $date, $time, $speaker, $location, $max_attendees, $created_by);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Seminar created successfully!";
        } else {
            $_SESSION['error'] = "Error creating seminar.";
        }
        
        header("Location: seminars_manage.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Seminars - CROSS</title>
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
                        <h1 style="margin: 0; color: var(--maroon);">Manage Seminars</h1>
                        <p style="margin: 5px 0 0; color: var(--text-light);">Create and manage seminars and workshops</p>
                    </div>
                    <button class="btn btn-primary" onclick="openAddModal()">
                        <i class="fas fa-plus"></i> Add New Seminar
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

                <!-- Seminar Statistics -->
                <div class="dashboard-stats">
                    <div class="dashboard-card">
                        <div class="card-icon">📚</div>
                        <h3><?php echo count($seminars); ?></h3>
                        <p>Total Seminars</p>
                    </div>
                    <div class="dashboard-card">
                        <div class="card-icon">📅</div>
                        <h3><?php echo count(array_filter($seminars, fn($s) => strtotime($s['date']) >= strtotime('today'))); ?></h3>
                        <p>Upcoming Seminars</p>
                    </div>
                    <div class="dashboard-card">
                        <div class="card-icon">✅</div>
                        <h3><?php echo count(array_filter($seminars, fn($s) => strtotime($s['date']) < strtotime('today'))); ?></h3>
                        <p>Past Seminars</p>
                    </div>
                    <div class="dashboard-card">
                        <div class="card-icon">👨‍🏫</div>
                        <h3><?php echo count(array_unique(array_column($seminars, 'speaker'))); ?></h3>
                        <p>Unique Speakers</p>
                    </div>
                </div>

                <!-- Seminars Table -->
                <div class="card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2 style="margin: 0;">All Seminars</h2>
                        <div style="display: flex; gap: 10px;">
                            <input type="text" id="searchSeminars" placeholder="Search seminars..." class="form-control" style="width: 250px;">
                            <select id="filterStatus" class="form-control" style="width: 150px;">
                                <option value="">All Seminars</option>
                                <option value="upcoming">Upcoming</option>
                                <option value="past">Past</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-container">
                        <table class="data-table" id="seminarsTable">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Date & Time</th>
                                    <th>Speaker</th>
                                    <th>Location</th>
                                    <th>Max Attendees</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($seminars as $seminar): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($seminar['title']); ?></strong>
                                            <?php if ($seminar['description']): ?>
                                                <br><small style="color: var(--text-light);"><?php echo htmlspecialchars(substr($seminar['description'], 0, 50)) . '...'; ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo date('M j, Y', strtotime($seminar['date'])); ?><br>
                                            <small><?php echo date('g:i A', strtotime($seminar['time'])); ?></small>
                                        </td>
                                        <td><?php echo htmlspecialchars($seminar['speaker']); ?></td>
                                        <td><?php echo htmlspecialchars($seminar['location']); ?></td>
                                        <td><?php echo $seminar['max_attendees'] ?: 'Unlimited'; ?></td>
                                        <td>
                                            <span class="badge" style="background: 
                                                <?php echo strtotime($seminar['date']) >= strtotime('today') ? 'green' : 'var(--text-light)'; ?>; 
                                                color: white;">
                                                <?php echo strtotime($seminar['date']) >= strtotime('today') ? 'Upcoming' : 'Completed'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($seminar['created_by_name']); ?></td>
                                        <td>
                                            <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                                <button class="btn btn-primary" onclick="editSeminar(<?php echo $seminar['id']; ?>)">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <button class="btn btn-success" onclick="viewAttendance(<?php echo $seminar['id']; ?>)">
                                                    <i class="fas fa-users"></i> Attendance
                                                </button>
                                                <button class="btn btn-info" onclick="viewDetails(<?php echo $seminar['id']; ?>)">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this seminar?');">
                                                    <input type="hidden" name="seminar_id" value="<?php echo $seminar['id']; ?>">
                                                    <input type="hidden" name="delete_seminar">
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
                </div>
            </div>
        </main>
    </div>

    <!-- Add Seminar Modal -->
    <div id="seminarModal" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3 id="modalTitle">Add New Seminar</h3>
                <button class="modal-close">&times;</button>
            </div>
            <form method="POST" id="seminarForm">
                <input type="hidden" name="add_seminar" value="1">
                
                <div class="form-group">
                    <label class="form-label">Seminar Title</label>
                    <input type="text" class="form-control" name="title" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="3"></textarea>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" name="date" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Time</label>
                        <input type="time" class="form-control" name="time" required>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label class="form-label">Speaker</label>
                        <input type="text" class="form-control" name="speaker" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Max Attendees</label>
                        <input type="number" class="form-control" name="max_attendees" min="1">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Location</label>
                    <input type="text" class="form-control" name="location" required>
                </div>
                
                <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Seminar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Search and filter functionality
        document.getElementById('searchSeminars').addEventListener('input', function() {
            filterSeminars();
        });

        document.getElementById('filterStatus').addEventListener('change', function() {
            filterSeminars();
        });

        function filterSeminars() {
            const searchTerm = document.getElementById('searchSeminars').value.toLowerCase();
            const statusFilter = document.getElementById('filterStatus').value;
            const rows = document.querySelectorAll('#seminarsTable tbody tr');
            
            rows.forEach(row => {
                const title = row.cells[0].textContent.toLowerCase();
                const status = row.cells[5].textContent.toLowerCase();
                
                const matchesSearch = title.includes(searchTerm);
                const matchesStatus = !statusFilter || 
                    (statusFilter === 'upcoming' && status === 'upcoming') ||
                    (statusFilter === 'past' && status === 'completed');
                
                row.style.display = matchesSearch && matchesStatus ? '' : 'none';
            });
        }

        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Add New Seminar';
            document.getElementById('seminarForm').reset();
            document.getElementById('seminarModal').style.display = 'flex';
        }

        function editSeminar(seminarId) {
            alert('Edit seminar: ' + seminarId);
            // Implement edit functionality
        }

        function closeModal() {
            document.getElementById('seminarModal').style.display = 'none';
        }

        function viewAttendance(seminarId) {
            alert('Viewing attendance for seminar ID: ' + seminarId);
            // Implement attendance view
        }

        function viewDetails(seminarId) {
            alert('Viewing details for seminar ID: ' + seminarId);
            // Implement details view
        }

        // Close modal when clicking outside
        document.getElementById('seminarModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        document.querySelector('#seminarModal .modal-close').addEventListener('click', closeModal);
    </script>
</body>
</html>