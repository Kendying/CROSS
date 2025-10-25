<?php
include 'config.php';

if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];
    
    // Prepare statement to find user with this token and check it hasn't expired (24 hours)
    $stmt = $conn->prepare("SELECT id, token_created_at FROM users WHERE verification_token = ? AND token_created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Update user to verified status
        $update = $conn->prepare("UPDATE users SET email_verified = 1, verification_token = NULL, token_created_at = NULL, status = 'pending' WHERE id = ?");
        $update->bind_param("i", $user['id']);
        
        if ($update->execute()) {
            header("Location: login.php?success=verified");
            exit();
        }
    }
    
    // If token not found or error occurred
    header("Location: login.php?error=invalid_verification");
    exit();
} else {
    header("Location: login.php");
    exit();
}
?>