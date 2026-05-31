<?php
//Tạo và trả Phpmailer object, dùng để gửi mail qua SMTP. 
//Dùng cho phần login. Cần gửi verification tới email của ng dùng

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/../../vendor/autoload.php";

$mail = new PHPMailer(true);

$mail->CharSet = PHPMailer::CHARSET_UTF8;
$mail->Encoding = 'base64';

$mail->isSMTP();
$mail->SMTPAuth = true;

$mail->Host = "smtp.gmail.com";
$mail->Username = getenv('MAIL_USERNAME') ?: "prephub207@gmail.com";
$mail->Password = getenv('MAIL_PASSWORD') ?: "xvlv ynod uola detq";
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

$mail->setFrom($mail->Username, "PrepHub");

$mail->isHtml(true);

return $mail;
?>
