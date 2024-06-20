<?php

include "inc/loader.php";

$Login = new Login();
$Login->checkRememberMe();

if (isset($_SESSION['user_id'])) 
{
    
    $DB = new DB();
    $result = $DB->query("SELECT verified, email FROM users WHERE id = ? LIMIT 1", $_SESSION['user_id']);
    $user = $result->fetchArray();

    if ($user['verified'] == 2) 
    {
        header("Location: index.php");
        exit();
    }
    $email = $user['email'];
    $parts = explode('@', $email); // Rozdělíme e-mail na část před @ a za @
    $masked_email = substr($parts[0], 0, 2) . str_repeat('*', strlen($parts[0]) - 2) . '@' . substr($parts[1], 0, 2) . str_repeat('*', strlen($parts[1]) - 2);

    $colors = ['#CD090F', '#ffcc00', '#00ff00', '#007fff'];
    $random_color = $colors[array_rand($colors)];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="mlogo.png">
    <title>Minigamer | Verify Your Account</title>
    <style>
        body {
            background-color: #1a1a1a;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        header {
            position: absolute;
            top: 20px;
        }

        .logo img {
            height: auto;
        }

        .description {
            text-align: center;
        }

        .description h1 {
            color:
                <?php echo $random_color; ?>
            ;
            font-size: 4em;
            margin-bottom: 0.2em;
        }

        .description p {
            color: white;
            font-size: 3em;
            margin-top: 0;
            margin-bottom: 1em;
        }

        .description a {
            color: white;
            font-size: 2.5em;
            text-decoration: none;
            border: 2px solid
                <?php echo $random_color; ?>
            ;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .description a:hover {
            background-color:
                <?php echo $random_color; ?>
            ;
            color: #1a1a1a;
        }
    </style>
</head>
<body>

<header>
        <div class="logo">
            <img src="mlogo.png" alt="Minigamer Logo">
        </div>
    </header>
    <main>
        <div class="description">
        <h1>Verify Your Account To Proceed!</h1>
        <p>a verification e-mail has been sent to <?php echo $masked_email ?></p>
            <a href="index.php">RETRY</a>
        </div>
    </main>
    
</body>
</html>
