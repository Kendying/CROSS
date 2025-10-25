<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Check if user exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Check if email is verified
            if ($user['status'] === 'unverified' || ($user['email_verified'] == 0 && $user['role'] != 'admin')) {
                header("Location: login.php?error=email_not_verified");
                exit();
            }
            
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['fullname'];

            // Redirect based on role
            switch ($user['role']) {
                case 'admin':
                    header("Location: dashboard_admin.php");
                    break;
                case 'scholar':
                    header("Location: dashboard.php");
                    break;
                case 'applicant':
                    header("Location: dashboard_applicant.php");
                    break;
                default:
                    header("Location: login.php?error=invalid_role");
            }
            exit();
        } else {
            header("Location: login.php?error=invalid_password");
            exit();
        }
    } else {
        header("Location: login.php?error=user_not_found");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>