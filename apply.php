<?php
include 'config.php';

// Only logged-in applicants allowed
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare('SELECT * FROM users WHERE id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// If already submitted and pending, show message
if ($user['status'] === 'pending') {
    header('Location: dashboard_applicant.php?info=already_submitted');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Apply for Scholarship - CROSS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Apply for Scholarship</h1>
        <p>Please complete the short application below. When you submit, your application will be sent for admin review.</p>

        <form action="process_application.php" method="POST">
            <div class="form-group">
                <label for="statement">Personal Statement (short)</label>
                <textarea id="statement" name="statement" rows="6" class="form-control" required></textarea>
            </div>

            <div class="form-group">
                <label for="additional">Additional Information (optional)</label>
                <textarea id="additional" name="additional" rows="4" class="form-control"></textarea>
            </div>

            <div style="margin-top:12px;">
                <button type="submit" class="btn btn-primary">Submit Application</button>
                <a href="dashboard_applicant.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
