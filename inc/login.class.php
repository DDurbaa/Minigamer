<?php 

class Login 
{
    private $error = "";

    public function Evaluate($data)
    {
        $email = $data['email'];
        $password = $data['password'];
        $rememberMe = isset($data['remember_me']) ? $data['remember_me'] : false;
        $dbPassword = $this->getHashedPassword($email);

        if ($dbPassword) 
        {
            if (password_verify($password, $dbPassword)) 
            {
                $DB = new DB();
                $result = $DB->query("SELECT * FROM users WHERE email = ? LIMIT 1", $email)->fetchArray();

                if ($result) 
                {
                    session_start();
                    $_SESSION['user_id'] = $result['id'];
                    $_SESSION['username'] = $result['username'];

                    if ($rememberMe) 
                    {
                        // Vytvoreni remember_me tokenu
                        $token = bin2hex(random_bytes(50));
                        $DB->query("UPDATE users SET remember_me_token = ? WHERE id = ?", $token, $result['id']);
                        setcookie('remember_me', $token, time() + (86400 * 30), "/"); // 30 dní
                    }
                    else 
                    {
                        // Zniceni remember_me tokenu
                        setcookie('remember_me', '', time() - 3600, "/");
                        $DB->query("UPDATE users SET remember_me_token = NULL WHERE id = ?", $result['id']);
                    }

                } 
                else 
                {
                    $this->error = $this->error . "Nastala chyba při načítání uživatelských údajů.";
                }
            } 
            else 
            {
                $this->error = $this->error . "Nesprávné heslo.";
            }
        } 
        else 
        {
            $this->error = $this->error . "Uživatel s tímto emailem neexistuje.";
        }

        return $this->error;
    }

    private function getHashedPassword($email)
    {
        $DB = new DB();
        $result = $DB->query("SELECT password FROM users WHERE email = ?", $email)->fetchArray();

        if ($result) 
        {
            return $result['password'];
        } 
        else 
        {
            return null; // Uživatele s tímto emailem neexistuje
        }
    }

    public function checkRememberMe()
    {
        if (isset($_COOKIE['remember_me'])) 
        {
            $token = $_COOKIE['remember_me'];
            $DB = new DB();
            $result = $DB->query("SELECT * FROM users WHERE remember_me_token = ? LIMIT 1", $token)->fetchArray();

            if ($result) 
            {
                session_start();
                $_SESSION['user_id'] = $result['id'];
                $_SESSION['username'] = $result['username'];
                // Přesměrování na chráněnou stránku nebo zobrazení úspěšného přihlášení
                header("Location: dashboard.php");
                exit();
            }
        }
    }
}