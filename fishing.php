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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="mlogo.png">
    <title>Fisher</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <a href="index.php" class="buttonexit">EXIT</a>
        <div class="logo">FISHING</div>
        <a href="#" class="buttonsigninfishing">SIGN IN</a>
    </header>
    <main>
        <div class="cards">
            <div class="card" id="valley">
                <a href="fishing/valleymenu.php">
                    <div class="card-content">
                        <h2>VALLEY</h2>
                    </div>
                </a>
            </div>
            <div class="card" id="valley">
                <a href="#">
                    <div class="card-content">
                        <h2>???</h2>
                    </div>
                </a>
            </div>
            <div class="card" id="valley">
                <a href="#">
                    <div class="card-content">
                        <h2>???</h2>
                    </div>
                </a>
            </div>
            <div class="card" id="randomfishing">
                <a href="#">
                    <div class="card-content">
                        <h2>RANDOM</h2>
                    </div>
                </a>
            </div>
        </div>
    </main>
</body>
</html>
