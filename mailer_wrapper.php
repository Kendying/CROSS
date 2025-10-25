<?php
// Mailer wrapper: attempts PHPMailer (Composer) then falls back to local stub.
// To install PHPMailer via Composer in the project root:
// composer require phpmailer/phpmailer

// Load configuration globals (SMTP credentials are expected in config.php)
if (file_exists(__DIR__ . '/config.php')) {
    // Use include_once to avoid re-running session_start or redeclaring helpers
    include_once __DIR__ . '/config.php';
}

function mailer_send_email($to, $subject, $message) {
    $autoload = __DIR__ . '/vendor/autoload.php';

    // Try PHPMailer when available
    if (file_exists($autoload)) {
        require_once $autoload;
        if (class_exists('\\PHPMailer\\PHPMailer\\PHPMailer')) {
            try {
                $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

                // Configure SMTP using globals from config.php
                if (!empty($GLOBALS['smtp_host'])) {
                    $mail->isSMTP();
                    $mail->Host = $GLOBALS['smtp_host'];
                    $mail->Port = $GLOBALS['smtp_port'] ?? 587;
                    $mail->SMTPAuth = !empty($GLOBALS['smtp_username']);
                    if ($mail->SMTPAuth) {
                        $mail->Username = $GLOBALS['smtp_username'];
                        $mail->Password = $GLOBALS['smtp_password'];
                    }
                    if (!empty($GLOBALS['smtp_secure'])) {
                        $mail->SMTPSecure = $GLOBALS['smtp_secure'];
                    }
                }

                $fromEmail = $GLOBALS['smtp_from_email'] ?? 'no-reply@example.com';
                $fromName = $GLOBALS['smtp_from_name'] ?? 'CROSS System';

                $mail->setFrom($fromEmail, $fromName);
                $mail->addAddress($to);
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $message;

                $mail->send();
                return true;
            } catch (\Exception $e) {
                error_log('PHPMailer exception: ' . $e->getMessage());
                // fall through to fallback
            }
        }
    }

    // If PHPMailer not available or failed, fall back to stub if present
    if (file_exists(__DIR__ . '/email_stub.php')) {
        // Use include_once to avoid redeclaration
        include_once __DIR__ . '/email_stub.php';
        if (function_exists('send_email')) {
            return \send_email($to, $subject, $message);
        }
    }

    // Final fallback: write to a local log file
    $log_entry = "-----\nTo: {$to}\nSubject: {$subject}\n\n{$message}\n-----\n";
    file_put_contents(__DIR__ . '/email_log.txt', $log_entry, FILE_APPEND | LOCK_EX);
    error_log('[MAILER FALLBACK] Sent to ' . $to . ': ' . $subject);
    return true;
}

// Provide global send_email if not already defined
if (!function_exists('send_email')) {
    function send_email($to, $subject, $message) {
        return mailer_send_email($to, $subject, $message);
    }
}

?>