<?php
// mailer.php
// Minimal compatibility wrapper ensuring send_email($to, $subject, $message) exists.
// Prefer mailer_wrapper.php if available (uses PHPMailer), otherwise fall back to email_stub.php.

if (file_exists(__DIR__ . '/mailer_wrapper.php')) {
    require_once __DIR__ . '/mailer_wrapper.php';
    return;
}

if (!function_exists('send_email')) {
    function send_email($to, $subject, $message) {
        $log_entry = "-----\nTo: {$to}\nSubject: {$subject}\n\n{$message}\n-----\n";
        file_put_contents(__DIR__ . '/email_log.txt', $log_entry, FILE_APPEND | LOCK_EX);
        error_log('[MAILER FALLBACK] Sent to ' . $to . ': ' . $subject);
        return true;
    }
}

?>
