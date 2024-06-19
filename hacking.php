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
    <title>Hacker</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <a href="index.php" class="buttonexit">EXIT</a>
        <div class="logo">HACKING</div>
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
            <div class="card" id="cyberpunk">
                <a href="hacker/cyberpunkmenu.php">
                    <div class="card-content">
                        <h2>CYBERPUNK</h2>
                    </div>
                </a>
            </div>
            <div class="card" id="utopia">
                <a href="hacker/utopiamenu.php">
                    <div class="card-content">
                        <h2>UTOPIA</h2>
                    </div>
                </a>
            </div>
            <div class="card" id="mayhem">
                <a href="hacker/mayhemmenu.php">
                    <div class="card-content">
                        <h2>MAYHEM</h2>
                    </div>
                </a>
            </div>
            <div class="card" id="randomhacking">
                <a href="#">
                    <div class="card-content">
                        <h2>WORK IN PROGRESS</h2>
                    </div>
                </a>
            </div>
        </div>
    </main>
</body>
</html>
