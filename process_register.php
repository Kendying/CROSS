<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $course = $_POST['course'];
    $year = $_POST['year'];
    $id_number = trim($_POST['id_number']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate passwords match
    if ($password !== $confirm_password) {
        header("Location: register.php?error=password_mismatch");
        exit();
    }
    
    // Validate password length
    if (strlen($password) < 8) {
        header("Location: register.php?error=password_length");
        exit();
    }
    
    // Validate email domain (optional - uncomment if you want to restrict to specific email domains)
    /* 
    if (!preg_match('/@gmail\.com$/i', $email)) {
        header("Location: register.php?error=invalid_email_domain");
        exit();
    }
    */
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Generate verification token
    $verification_token = bin2hex(random_bytes(32));
    
    // Check if email already exists
    $check_email = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        header("Location: register.php?error=email_exists");
        exit();
    }
    
    // Check if ID number already exists
    $check_id = "SELECT id FROM users WHERE id_number = ?";
    $stmt = $conn->prepare($check_id);
    $stmt->bind_param("s", $id_number);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        header("Location: register.php?error=id_exists");
        exit();
    }
    
    // Generate verification token
    $verification_token = bin2hex(random_bytes(32));

    // Insert new user as unverified applicant
    $sql = "INSERT INTO users (fullname, email, course, year_level, id_number, password, role, status, verification_token, email_verified, token_created_at) 
            VALUES (?, ?, ?, ?, ?, ?, 'applicant', 'unverified', ?, 0, NOW())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $fullname, $email, $course, $year, $id_number, $hashed_password, $verification_token);
    
    if ($stmt->execute()) {
        // Send verification email
        require_once __DIR__ . '/vendor/autoload.php';
        try {
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            
            // Server settings
            $mail->isSMTP();
            $mail->Host = $smtp_host;
            $mail->SMTPAuth = true;
            $mail->Username = $smtp_username;
            $mail->Password = $smtp_password;
            $mail->SMTPSecure = $smtp_secure;
            $mail->Port = $smtp_port;

            // Recipients
            $mail->setFrom($smtp_from_email, $smtp_from_name);
            $mail->addAddress($email, $fullname);

            // Content
            $verificationLink = "http://" . $_SERVER['HTTP_HOST'] . "/cross-system/verify.php?token=" . $verification_token;
            
            $mail->isHTML(true);
            $mail->Subject = 'Verify Your CROSS Account';
            $mail->Body = "
                <h2>Welcome to CROSS System</h2>
                <p>Dear {$fullname},</p>
                <p>Thank you for registering. Please click the button below to verify your email address:</p>
                <p><a href='{$verificationLink}' style='background-color: #800000; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Verify Email Address</a></p>
                <p>Or copy and paste this link in your browser:</p>
                <p>{$verificationLink}</p>
                <p>This link will expire in 24 hours.</p>
                <p>Best regards,<br>CROSS System</p>";

            $mail->send();
            
            // Log successful email send
            log_email_send($email, 'Verify Your CROSS Account', 'Verification email sent successfully', true);
        } catch (\Exception $e) {
            // Log the error
            error_log("Failed to send verification email to {$email}: " . $e->getMessage());
            log_email_send($email, 'Verify Your CROSS Account', 'Email sending failed: ' . $e->getMessage(), false, $e->getMessage());
            
            // Store link in session so user can verify manually
            if (session_status() == PHP_SESSION_NONE) session_start();
            $_SESSION['verification_link'] = $verificationLink;
        }

        // Always show the check email message, even if email sending failed
        $stmt->close();
        $conn->close();
        header("Location: register.php?success=check_email");
        exit();
    } else {
        $stmt->close();
        $conn->close();
        header("Location: register.php?error=registration_failed");
        exit();
    }
} else {
    header("Location: register.php");
    exit();
}
?>