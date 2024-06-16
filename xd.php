<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.example.com'; // Set the SMTP server to send through
    $mail->SMTPAuth   = true;
    $mail->Username   = 'your_email@example.com'; // SMTP username
    $mail->Password   = 'your_email_password'; // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('your_email@example.com', 'Minigamer');
    $mail->addAddress('recipient@example.com', 'Recipient Name'); // Add a recipient

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Verify Your Email';

    // HTML email body
    $body = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Verify Your Email</title>
        <style>
            body {
                margin: 0;
                font-family: Arial, sans-serif;
                background-color: #1a1a1a;
                color: #ffffff;
                display: flex;
                flex-direction: column;
                align-items: center;
                min-height: 50vh;
            }

            main {
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
                flex-grow: 1;
                width: 100%;
            }

            .container {
                max-width: 600px;
                margin: 20px auto;
                padding: 20px;
                background-color: inherit;
            }

            .logo-container {
                text-align: center;
                padding-bottom: 20px;
            }

            .logo-container img {
                width: 150px;
            }

            .welcome-text {
                text-align: center;
                color: #ffcc00;
            }

            .content-text {
                font-size: 1.2em;
            }

            .button-container {
                text-align: center;
            }

            .button-container a {
                background-color: #1d1d1dfc;
                color: white;
                padding: 15px 30px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                border: 2px solid #ffcc00;
                border-radius: 5px;
                font-size: 1.2em;
            }
            .text-container {
                background-color: #1e1e1e;
                padding: 20px;
                margin: 15px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }
        </style>
    </head>
    <body>
        <main>
            <div class="container">
                <div class="logo-container">
                    <img src="https://vanek-josef.mzf.cz/mlogo.png" alt="Logo">
                </div>
                <div class="text-container">
                    <h2 class="welcome-text">Welcome to Minigamer!</h2>
                    <p class="content-text">Hi [First Name],</p>
                    <p class="content-text">Thank you for signing up for Minigamer. To complete your registration, please confirm your email address by clicking the button below:</p>
                    <div class="button-container">
                        <a href="https://vanek-josef.mzf.cz/">Confirm Email</a>
                    </div>
                    <p class="content-text">Thanks,</p>
                    <p class="content-text">The Minigamer Team</p>
                </div>
            </div>
        </main>
    </body>
    </html>';

    $mail->Body = $body;

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
