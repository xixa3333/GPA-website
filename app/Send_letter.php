<?php
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

function sendPasswordResetEmail($email, $subject, $message, $data)
{
    $username = getenv('GPA_SMTP_USERNAME') ?: '';
    $password = getenv('GPA_SMTP_PASSWORD') ?: '';
    $from = getenv('GPA_SMTP_FROM') ?: $username;

    if ($username === '' || $password === '') {
        error_log('SMTP credentials are not configured.');
        echo $data;
        exit();
    }

    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Host = getenv('GPA_SMTP_HOST') ?: 'smtp.gmail.com';
    $mail->Port = (int) (getenv('GPA_SMTP_PORT') ?: 587);
    $mail->CharSet = 'UTF-8';
    $mail->Username = $username;
    $mail->Password = $password;
    $mail->setFrom($from, 'GPA 成績管理系統');
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->isHTML(true);
    $mail->addAddress($email);

    try {
        $mail->send();
    } catch (Throwable $exception) {
        error_log('Email delivery failed: ' . $exception->getMessage());
        echo $data;
        exit();
    }
}
