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
    <title>Lockpicker</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <a href="index.html" class="buttonexit">EXIT</a>
        <div class="logo">LOCKPICKING</div>
        <a href="#" class="buttonsignin">SIGN IN</a>
    </header>
    <main>
        <div class="cards">
            <div class="card" id="medieval">
                <a href="lockpicker/medievalmenu.php">
                    <div class="card-content">
                        <h2>MEDIEVAL</h2>
                    </div>
                </a>
            </div>
            <div class="card" id="mafia">
                <a href="lockpicker/mafiamenu.php">
                    <div class="card-content">
                        <h2>MAFIA</h2>
                    </div>
                </a>
            </div>
            <div class="card" id="wasteland">
                <a href="lockpicker/schoolmenu.php">
                    <div class="card-content">
                        <h2>SCHOOL</h2>
                    </div>
                </a>
            </div>
            <div class="card" id="random">
                <a href="random.php">
                    <div class="card-content">
                        <h2>RANDOM</h2>
                    </div>
                </a>
            </div>
        </div>
    </main>
</body>
</html>
