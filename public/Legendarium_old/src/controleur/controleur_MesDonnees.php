<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';
$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPDebug = 2;
$mail->Host = 'smtp.hostinger.com';
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->Username = 'test@hostinger-tutorials.com';
$mail->Password = 'EMAIL_ACCOUNT_PASSWORD';
$mail->setFrom('test@hostinger-tutorials.com', 'Your Name');
$mail->addReplyTo('reply-box@hostinger-tutorials.com', 'Your Name');
$mail->addAddress('example@gmail.com', 'Receiver Name');
$mail->Subject = 'PHPMailer SMTP message';
$mail->msgHTML(file_get_contents('message.html'), __DIR__);
$mail->AltBody = 'This is a plain text message body';
$mail->addAttachment('test.txt');
if (!$mail->send()) {
    echo 'Erreur: ' . $mail->ErrorInfo;
} else {
    echo 'Message envoyé!';
}