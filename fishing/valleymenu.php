<?php

    include "../inc/loader.php";

    $Login = new Login();
    $Login->checkRememberMe();

    if (isset($_SESSION['user_id']))
    {
        $Login->checkVerification($_SESSION['user_id']);
    }
    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../mlogo.png">
    <title>Valley Menu</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #1a1a1a;
            font-family: Arial, sans-serif;
            color: white;
        }

        .menu {
            text-align: center;
        }

        h1 {
            margin-bottom: 40px;
            font-size: 3em;
        }

        .buttons,
        .bottom-buttons {
            margin-bottom: 40px;
        }

        .button {
            display: block;
            padding: 20px 40px;
            margin: 10px auto;
            width: 200px;
            border: 2px solid #007fff;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 1.5em;
            transition: background-color 0.3s, color 0.3s;
        }
        .buttonexit {
            display: block;
            padding: 20px 40px;
            margin: 10px auto;
            width: 200px;
            border: 2px solid #CD090F;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 1.5em;
            transition: background-color 0.3s, color 0.3s;
        }
        .buttonexit.hover,
        .buttonexit:hover {
            background-color: #CD090F;
            color: #333;
        }
        .button.hover,
        .button:hover {
            background-color: #007fff;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="menu">
        <h1>VALLEY</h1>
        <div class="buttons">
            <a href="valley.php" class="button">PLAY</a>
        </div>
        <div class="bottom-buttons">
            <a href="#" class="button">HOW TO PLAY</a>
            <a href="#" class="button">LEADERBOARD</a>
            <a href="../fishing.php" class="buttonexit">EXIT</a>
        </div>
    </div>
</body>
</html>
