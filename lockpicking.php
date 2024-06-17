<?php

    include "inc/loader.php";

    session_start();

    $Login = new Login();
    $Score = new Score(1);
    $Login->checkRememberMe();

    if (isset($_SESSION['user_id']))
    {
        $Score = new Score(1, $_SESSION['user_id']);
        $Login->checkVerification($_SESSION['user_id']);
    }
    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="mlogo.png">
    <title>Lockpicker</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <a href="index.php" class="buttonexit">EXIT</a>
        <div class="logo">LOCKPICKING</div>
        <?php 
        if (!isset($_SESSION['user_id'])) 
        {
            echo "<a href='sign-in.php' class='buttonsignin'>SIGN IN</a>";
        }
        else 
        {
            echo "<a href='log-out.php' class='buttonsignin'>LOGOUT</a>";
        }
        ?>
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
