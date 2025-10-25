<?php
// Only start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "crossdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8");

// --- CSRF helpers ---
if (!function_exists('csrf_get_token')) {
    function csrf_get_token() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

// Email Logging Function
if (!function_exists('log_email_send')) {
    function log_email_send($to, $subject, $body, $success, $error = '') {
        $log_entry = sprintf(
            "[%s] To: %s | Subject: %s | Status: %s%s\n",
            date('Y-m-d H:i:s'),
            $to,
            $subject,
            $success ? 'Success' : 'Failed',
            $error ? ' | Error: ' . $error : ''
        );
        
        $log_file = __DIR__ . '/email_log.txt';
        file_put_contents($log_file, $log_entry, FILE_APPEND);
    }
}

// Create email log file if it doesn't exist
if (!file_exists(__DIR__ . '/email_log.txt')) {
    file_put_contents(__DIR__ . '/email_log.txt', "=== CROSS Email System Log ===\nCreated: " . date('Y-m-d H:i:s') . "\n\n");
}

// --- SMTP settings (configure for your host) ---
// Default placeholder values for Hostinger SMTP. Replace these with your Hostinger SMTP credentials when deploying.
// Hostinger typical SMTP settings:
// Host: smtp.hostinger.com (or smtp.your-domain.com depending on your plan)
// Port: 587 (TLS) or 465 (SSL)
// Encryption: tls or ssl
// Username: your full email address (e.g. no-reply@yourdomain.com)
// Password: the mailbox password

// Example (replace with your Hostinger-provided values):
$smtp_host = 'smtp.hostinger.com';
$smtp_port = 587;
$smtp_username = 'servus_amoris@cross-web.org';
$smtp_password = 'Weareteamcrosswow123.';
$smtp_secure = 'tls'; // 'ssl' for port 465
$smtp_from_email = 'servus_amoris@cross-web.org';
$smtp_from_name = 'CROSS System';

// NOTE: Previously used Gmail settings (kept for reference):
// $smtp_host = 'smtp.gmail.com';
// $smtp_port = 587;
// $smtp_username = 'CROSS-WEB@gmail.com';
// $smtp_password = 'ldgbnfxklhlnpluy';
// $smtp_secure = 'tls';
// $smtp_from_email = 'CROSS-WEB@gmail.com';
// $smtp_from_name = 'CROSS System';

if (!function_exists('csrf_validate')) {
    function csrf_validate($token) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
?>