<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Check if user exists and is admin (allow 'active' or 'approved' for compatibility)
    $sql = "SELECT * FROM users WHERE email = ? AND role = 'admin' AND status IN ('active','approved')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['fullname'];
            
            // Redirect to admin dashboard
            header("Location: dashboard_admin.php");
            exit();
        } else {
            header("Location: admin_login.php?error=invalid_password");
            exit();
        }
    } else {
        header("Location: admin_login.php?error=user_not_found");
        exit();
    }
} else {
    header("Location: admin_login.php");
    exit();
}
?>