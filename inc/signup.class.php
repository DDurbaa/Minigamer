<?php

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
    }

    private function sendVerificationEmail($email, $token) {
        $subject = 'Minigamer - Email Verification';
        $message = 'Click the link to verify your email: <a href="http://localhost/yourproject/verify-email.php?token=' . $token . '">Verify Email</a>';
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: svarcs.05@gmail.com' . "\r\n";
    
        if(mail($email, $subject, $message, $headers)) {
            echo 'Verification email has been sent';
        } else {
            echo 'Failed to send verification email';
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

    private function EmailIsTaken($email)
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

        // velke pismeno
        $hasUppercase = preg_match('/[A-Z]/', $password);

        // male pismeno
        $hasLowercase = preg_match('/[a-z]/', $password);

        // cislo
        $hasNumber = preg_match('/[0-9]/', $password);

        return strlen($password) >= $minLength && $hasUppercase && $hasLowercase && $hasNumber;
    }
}

?>