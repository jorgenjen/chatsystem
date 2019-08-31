<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';
$email = $_POST["email"];
$msg = $_POST["msg"];

$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPDebug = 0;
$mail->Host = 'smtp.hostinger.com';
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->Username = 'jensvold@bitbybit.no';
$mail->Password = '[6?f9&^Y2R+3rku1';
$mail->setFrom('jensvold@bitbybit.no', 'Jorgen');
$mail->addReplyTo('jensvold@bitbybit.no', 'jorgen');
$mail->addAddress($email, "jorgen jens");
$mail->Subject = $msg;
$mail->msgHTML(file_get_contents('message.html'), __DIR__);
$mail->AltBody = 'This is a plain text message body';
$mail->addAttachment('test.txt');
if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message sent!';
}
?>
