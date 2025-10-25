<?php
// Simple email stub for local testing. Logs email content to a file and error_log.
if (!function_exists('send_email')) {
    function send_email($to, $subject, $message) {
        $log_entry = "-----\nTo: {$to}\nSubject: {$subject}\n\n{$message}\n-----\n";
        // Append to a local log file for inspection
        file_put_contents(__DIR__ . '/email_log.txt', $log_entry, FILE_APPEND | LOCK_EX);
        // Also log to PHP error log for visibility
        error_log("[EMAIL STUB] Sent to {$to}: {$subject}");
        return true;
    }
}

?>
