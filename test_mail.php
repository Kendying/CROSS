<?php
// Test script to send an email via PHPMailer using config.php settings.
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/vendor/autoload.php';

$to = $argv[1] ?? ($smtp_from_email ?? $smtp_username ?? null);
if (empty($to)) {
    echo "Usage: php test_mail.php recipient@example.com\n";
    exit(1);
}

$mail = new \PHPMailer\PHPMailer\PHPMailer(true);

try {
    // Enable verbose debug output
    $mail->SMTPDebug = 2; // 0 = off, 2 = client and server messages
    $mail->Debugoutput = function($str, $level) {
        echo "[DEBUG] ", $str, PHP_EOL;
    };

    if (!empty($smtp_host)) {
        $mail->isSMTP();
        $mail->Host = $smtp_host;
        $mail->Port = $smtp_port ?? 587;
        $mail->SMTPAuth = !empty($smtp_username);
        if ($mail->SMTPAuth) {
            $mail->Username = $smtp_username;
            $mail->Password = $smtp_password;
        }
        if (!empty($smtp_secure)) {
            $mail->SMTPSecure = $smtp_secure;
        }
        // Gmail often requires TLS and the ability to verify the certificate; allow self-signed for local tests if needed
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];
    }

    $from = $smtp_from_email ?? $smtp_username ?? 'no-reply@example.com';
    $fromName = $smtp_from_name ?? 'CROSS System';

    $mail->setFrom($from, $fromName);
    $mail->addAddress($to);
    $mail->isHTML(true);
    $mail->Subject = 'Test email from CROSS System';
    $mail->Body = '<p>This is a test email from CROSS System.</p>';

    echo "Sending test email to: $to\n";
    $mail->send();
    echo "Message sent successfully.\n";
} catch (Exception $e) {
    echo "PHPMailer Exception: ", $e->getMessage(), PHP_EOL;
}
?>