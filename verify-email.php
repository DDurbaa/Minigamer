<?php

include "inc/loader.php";
$msg = "";

if (isset($_GET['t'])) {
    $token = $_GET['t'];
    $DB = new DB();
    $DB->query("SELECT * FROM users WHERE token = ?", $token);

    if ($DB->numRows() > 0) {
        $DB->query("UPDATE users SET verified = 1 WHERE token = ?", $token);
        $msg = "Email verified successfully!";
    } else {
        $msg = "Invalid e-mail token!";
    }
} else {
    header("Location: index.php");
}

$colors = ['#CD090F', '#ffcc00', '#00ff00', '#007fff'];
$random_color = $colors[array_rand($colors)];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="mlogo.png">
    <title>Minigamer | Verify Your E-mail</title>
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
            font-size: 14em;
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
            <p>
                <?php
                if ($msg != "") {
                    echo $msg;
                }
                ?>
            </p>

            <a href="index.php">GO BACK</a>
        </div>
    </main>

</body>

</html>