<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'D:/Xampp/htdocs/Minigamer/Lockpicker/vendor/autoload.php';
//require "../vendor/autoload.php";

class Signup 
{
    private $error = "";
    private $pwdCheck = "";

    public function Evaluate($data)
    {
        foreach ($data as $key => $value)
        {
            if ($key == "username")
            {
                if (empty($value))
                {
                    $this->error = $this->error . "Insert username!<br>";
                }
                else 
                {
                    if ($this->UsernameIsTaken($value))
                    {
                        $this->error = $this->error . "Username already taken!<br>";
                    }
                    if (is_numeric($value))
                    {
                        $this->error = $this->error . "Username cannot be numerical only!<br>";
                    }
                    if (strlen($value) > 15)
                    {
                        $this->error = $this->error . "Username cannot be longer than 15 characters!<br>";
                    }
                    if (strstr($value, " "))
                    {
                        $this->error = $this->error . "Username cannot contain empty spaces!<br>";
                    }
                    if (!preg_match('/^[a-zA-Z0-9]+$/', $value))
                    {
                        $this->error = $this->error . "Username cannot contain special characters!<br>";
                    }
                }
            }
            else if ($key == "email")
            {
                if (empty($value))
                {
                    $this->error = $this->error . "Insert email!<br>";
                }
                else 
                {
                    if ($this->EmailIsTaken($value))
                    {
                        $this->error = $this->error . "E-mail already taken!<br>";
                    }
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL))
                    {
                        $this->error = $this->error . "Use a real e-mail address!<br>";
                    }
                }
            }
            else if ($key == "password")
            {
                if (empty($value))
                {
                    $this->error = $this->error . "Insert password!<br>";
                }
                else 
                {
                    if (strstr($value, " "))
                    {
                        $this->error = $this->error . "Password cannot contain empty spaces!<br>";
                    }
                    if (!$this->PasswordIsStrongEnough($value))
                    {
                        $this->error = $this->error . "Password is not strong enough!<br>";
                    }
                }
                $this->pwdCheck = $value;
            }
            else if ($key == "passwordTwo")
            {
                if (empty($value))
                {
                    $this->error = $this->error . "Confirm your password!<br>";
                }
                else if ($this->pwdCheck != $value)
                {
                    $this->error = $this->error . "Passwords aren't matching!<br>";
                }
            }
        }

        if ($this->error == "")
        {
            $this->CreateUser($data);
            return "";
        }
        else 
        {
            return $this->error;
        }
    }

    private function CreateUser($data)
    {
        $DB = new DB();
        $username = addslashes(ucfirst(substr($data["username"], 0, 15)));
        $email = addslashes($data['email']);
        $password = password_hash($this->pwdCheck, PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(50));

        $DB->query("INSERT INTO users (username, email, password, token, verified, created_at) VALUES (?, ?, ?, ?, 0, NOW())", $username, $email, $password, $token);
        $this->sendVerificationEmail($data['email'], $token, $data['username']);
        $result = $DB->query("SELECT * FROM users WHERE email = ? LIMIT 1", $email)->fetchArray();
        session_start();
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['username'] = $result['username'];
    }

    private function sendVerificationEmail($email, $token, $username) {
        // Vytvoř nový objekt PHPMailer
        $mail = new PHPMailer(true);
    
        try {
            // Nastavení serveru
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'simonecek2005@gmail.com'; // EMAIL ODESILATELE
            $mail->Password = 'tjyjwahhqikfijmd'; // HESLO (HESLO APLIKACE KDYZ 2FA)
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
    
            // Nastavení odesílatele a příjemce
            $mail->setFrom('simonecek2005@gmail.com', 'Minigamer'); 
            $mail->addAddress($email); 
            $mail->Subject = 'Minigamer - Email Verification';
            
            // Tělo zprávy v HTML formátu
            $mail->isHTML(true);
    
            // Vložení HTML šablony do těla zprávy
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
                        background-color: #000000; /* Set the background to black */
                        color: #ffffff;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        min-height: 100vh;
                        padding: 20px; /* Add some padding */
                    }
    
                    .container {
                        max-width: 600px;
                        margin: 20px;
                        padding: 20px;
                        background-color: #1e1e1e;
                        border-radius: 8px;
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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
                        color: #ffffff; /* Text color set to white */
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
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="logo-container">
                        <img src="https://vanek-josef.mzf.cz/mlogo.png" alt="Logo">
                    </div>
                    <div class="text-container">
                        <h2 class="welcome-text">Welcome to Minigamer!</h2>
                        <p class="content-text">Hi ' . htmlspecialchars($username) . ',</p>
                        <p class="content-text">Thank you for signing up for Minigamer. To complete your registration, please confirm your email address by clicking the button below:</p>
                        <div class="button-container">
                            <a href="http://localhost/Minigamer/Lockpicker/verify-email.php?t=' . urlencode($token) . '">Confirm Email</a>
                        </div>
                        <p class="content-text">Do not reply to this e-mail</p>
                        <p class="content-text">The Minigamer Team</p>
                    </div>
                </div>
            </body>
            </html>';
    
            $mail->Body = $body;
    
            // Odeslání e-mailu
            $mail->send();
            echo 'Verification email has been sent';
        } catch (Exception $e) {
            echo 'Failed to send verification email. Error: ' . $mail->ErrorInfo;
        }
    }
    
    

    private function UsernameIsTaken($username)
    {
        $DB = new DB();
        $DB->query("SELECT username FROM users WHERE username = ?", $username);

        if ($DB->numRows() > 0) {
            return true;
        }
        
        return false;
    }

    public function EmailIsTaken($email)
    {
        $DB = new DB();
        $DB->query("SELECT email FROM users WHERE email = ?", $email);

        if ($DB->numRows() > 0) {
            return true;
        }
        
        return false;
    }

    private function PasswordIsStrongEnough($password)
    {
        $minLength = 8;

        return strlen($password) >= $minLength;
    }

    public function sendPasswordResetEmail($email)
    {
        $mail = new PHPMailer(true);
        $expFormat = mktime(
            date("H"), date("i"), date("s"), date("m") ,date("d")+1, date("Y")
            );
            $expDate = date("Y-m-d H:i:s",$expFormat);
            $key = md5(2418*2 . $email);
            $addKey = substr(md5(uniqid(rand(),1)),3,10);
            $key = $key . $addKey;
            $DB = new DB();
            $DB->query("INSERT INTO `password_reset_temp` (`email`, `key`, `expDate`) VALUES (?, ?, ?)", $email, $key, $expDate);
    
        try {
            // Nastavení serveru
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'simonecek2005@gmail.com'; // EMAIL ODESILATELE
            $mail->Password = 'tjyjwahhqikfijmd'; // HESLO (HESLO APLIKACE KDYZ 2FA)
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
    
            // Nastavení odesílatele a příjemce
            $mail->setFrom('simonecek2005@gmail.com', 'Minigamer'); 
            $mail->addAddress($email); 
            $mail->Subject = 'Minigamer - Password recovery';
            
            // Tělo zprávy v HTML formátu
            $mail->isHTML(true);
    
            // Vložení HTML šablony do těla zprávy
            $body = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Password recovery</title>
                <style>
                    body {
                        margin: 0;
                        font-family: Arial, sans-serif;
                        background-color: #000000; /* Set the background to black */
                        color: #ffffff;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        min-height: 100vh;
                        padding: 20px; /* Add some padding */
                    }
    
                    .container {
                        max-width: 600px;
                        margin: 20px;
                        padding: 20px;
                        background-color: #1e1e1e;
                        border-radius: 8px;
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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
                        color: #ffffff; /* Text color set to white */
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
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="logo-container">
                        <img src="https://vanek-josef.mzf.cz/mlogo.png" alt="Logo">
                    </div>
                    <div class="text-container">
                        <h2 class="welcome-text">Dear user</h2>
                        <p class="content-text">click on the following link to reset your password: <br></p>
                        <div class="button-container">
                            <a href="http://localhost/Minigamer/Lockpicker/reset-password.php?k=' . urlencode($key) . '&email='.$email.'&action=reset" target="_blank">Reset password</a>
                        </div>
                        <p class="content-text">If you did not request this, you can ignore this e-mail.</p>
                        <p class="content-text">Do not reply to this e-mail</p>
                        <p class="content-text">The Minigamer Team</p>
                    </div>
                </div>
            </body>
            </html>';
    
            $mail->Body = $body;
    
            // Odeslání e-mailu
            $mail->send();
        } catch (Exception $e) {
            echo 'Failed to send password recovery email. Error: ' . $mail->ErrorInfo;
        }
    }
}

