<?php

session_start();
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
    <title>Minigamer</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">MINIGAMER</div>
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
        <div class="description">
            <h1>Welcome to Minigamer</h1>
            <p>A platform featuring a variety of mini-games inspired by popular video games. Dive into our selection of mini-games and test your skills in different challenges.</p>
        </div>
        <div class="cards">
            <div class="card" id="lockpicking">
                <a href="lockpicking.php">
                    <div class="card-content">
                        <h2>LOCKPICKING</h2>
                    </div>
                </a>
            </div>
            <div class="card" id="hacking">
                <a href="hacking.php">
                    <div class="card-content">
                        <h2>HACKING</h2>
                    </div>
                </a>
            </div>
            <div class="card" id="fishing">
                <a href="fishing.php">
                    <div class="card-content">
                        <h2>FISHING</h2>
                    </div>
                </a>
            </div>
        </div>
    </main>
</body>
</html>
