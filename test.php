<?php
$to = 'simonecek2005@gmail.com';
$subject = 'Test email';
$message = 'This is a test email sent from PHP!';
$headers = 'From: svarcs.05@gmail.com' . "\r\n" .
           'Reply-To: svarcs.05@gmail.com' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

if (mail($to, $subject, $message, $headers)) {
    echo 'Email sent successfully!';
} else {
    echo 'Email sending failed.';
}
?>