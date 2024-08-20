<?php

    include "../inc/loader.php";

    $Login = new Login();session_start();
    $Login->checkRememberMe();

    if (isset($_SESSION['user_id']))
    {
        $Login->checkVerification($_SESSION['user_id']);
    }
    

?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../mlogo.png">
    <title>Medieval Endless</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #1a1a1a;
            cursor: none;
            font-family: Arial, sans-serif;
        }

        #cursorCanvas {
            position: absolute;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 1000;
        }
        #tooltip {
            position: absolute;
            bottom: 20px;
            right: 20px;
            color: white;
            font-size: 26px;
            text-align: right;
        }
        .tooltip-item {
            margin: 5px 0;
        }

        .tooltip-key {
            background-color: #444;
            border-radius: 3px;
            padding: 4px 8px;
            display: inline-block;
            margin-left: 10px;
        }

        #score {
            position: absolute;
            bottom: 0px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-size: 80px;
        }

        .buttonexit {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 20px 40px;
            border: 2px solid #CD090F;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 1.5em;
            transition: background-color 0.3s, color 0.3s;
            cursor: none;
        }
        .buttonexit:hover {
            background-color: #CD090F;
            color: #333;
        }
    </style>
</head>
<body>
    <canvas id="lockCanvas" width="800" height="700"></canvas>
    <canvas id="cursorCanvas"></canvas>

    <a href="medievalmenu.php" class="buttonexit">EXIT</a>

    <div id="tooltip">
        <div class="tooltip-item">Restart: <span class="tooltip-key">R</span></div>
        <div class="tooltip-item">Rotate: <span class="tooltip-key">Space</span></div>
        <div class="tooltip-item">Move cursor: <span class="tooltip-key">Mouse</span></div>
    </div>

    <div id="score">0</div>

    <script src="script.js"></script>
</body>
</html>
