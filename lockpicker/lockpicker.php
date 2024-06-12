<?php

    include "inc/loader.php";

    $Login = new Login();
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
    <title>Lockpicking Minihra</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #1a1a1a;
            cursor: none; /* Schová výchozí kurzor */
        }

        #cursorCanvas {
            position: absolute;
            top: 0;
            left: 0;
            pointer-events: none; /* Zabrání interakci kurzoru s canvasem */
            z-index: 1000; /* Ujistíme se, že je canvas s kurzorem na vrcholu */
        }
    </style>
</head>
<body>
    <canvas id="lockCanvas" width="800" height="700"></canvas>
    <canvas id="cursorCanvas"></canvas>

    <script src="script.js"></script>
</body>
</html>
