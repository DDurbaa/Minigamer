<?php

    include "inc/loader.php";

    $Login = new Login();session_start();
    $ScoreCP = new Score(4);
    $ScoreUtopia = new Score(5);
    $ScoreMayhem = new Score(6);
    $Login->checkRememberMe();

    if (isset($_SESSION['user_id']))
    {
        $Login->checkVerification($_SESSION['user_id']);
        $ScoreCP = new Score(4, $_SESSION['user_id']);
        $ScoreUtopia = new Score(5, $_SESSION['user_id']);
        $ScoreMayhem = new Score(6, $_SESSION['user_id']);
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
    <style>
        .leaderboards {
            height: 100vh; /* Nastavení výšky na 100 % výšky viewportu */
            display: flex;
            justify-content: center;
            align-items: center; 
            gap: 20px; /* Přidáno pro rozestupy mezi tabulkami */
            color: #ffcc00;
            margin-top: 20px; /* Přidáno pro vizuální rozestup mezi kartami a tabulkami */
        }

        .score-table {
            width: 400px; /* Šířka tabulek stejná jako karty */
            background-color: transparent; /* Odstranění pozadí */
            border-radius: 10px;
            box-shadow: none; /* Odstranění stínu */
            margin: 0 20px; /* Přidáno pro vizuální rozestup mezi tabulkami */
        }

        .score-table h2 {
            text-align: center;
            color: #00ff00;
        }

        .score-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .score-table th, .score-table td {
            padding: 12px; /* Zvětšení paddingu pro větší text */
            text-align: left;
            border: 1px solid #00ff00; /* Změna barvy ohraničení na žlutou */
            color: white; /* Změna barvy textu na bílou */
            border-radius: 5px; /* Zaoblení rohů */
            font-size: 18px; /* Zvětšení velikosti textu */
        }
        .main-content {
            height: 100vh; /* Nastavení výšky na 100 % výšky viewportu */
            display: flex;
            justify-content: center;
            align-items: center;
        }
</style>
</head>
<body>
    <header>
        <a href="index.php" class="buttonexit">EXIT</a>
        <div class="logo">HACKING</div>
        <?php 
        if (!isset($_SESSION['user_id'])) 
        {
            echo "<a href='sign-in.php' class='buttonsigninhacking'>SIGN IN</a>";
        }
        else 
        {
            echo "<a href='log-out.php' class='buttonsigninhacking'>LOGOUT</a>";
        }
        ?>
    </header>
    <main class="main-content">
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
    <div class="leaderboards" id="leaderboard">
        <?php 
            $ScoreCP->displayScoresTable("Cyberpunk");
            $ScoreUtopia->displayScoresTable("Utopia");
            $ScoreMayhem->displayScoresTable("Mayhem");
        ?>
    </div>
</body>
</html>
