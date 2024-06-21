<?php

    include "inc/loader.php";

    $Login = new Login();
    $ScoreKCD = new Score(1);
    $ScoreMafia = new Score(2);
    $ScoreSchool = new Score(3);
    $Login->checkRememberMe();

    if (isset($_SESSION['user_id']))
    {
        $ScoreKCD = new Score(1, $_SESSION['user_id']);
        $ScoreMafia = new Score(2, $_SESSION['user_id']);
        $ScoreSchool = new Score(3, $_SESSION['user_id']);
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
    <style>
    .leaderboards {
        display: flex;
        justify-content: space-between;
        color: #ffcc00;
    }
    .score-table {
        margin: 10px;
    }
    .score-table h2 {
        text-align: center;
    }
    .score-table table {
        width: 100%;
        border-collapse: collapse;
    }
    .score-table th, .score-table td {
        padding: 8px;
        text-align: left;
        border: 1px solid #ddd;
    }
</style>
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
            <div class="card" id="school">
                <a href="lockpicker/schoolmenu.php">
                    <div class="card-content">
                        <h2>SCHOOL</h2>
                    </div>
                </a>
            </div>
            <div class="card" id="random">
                <a href="#">
                    <div class="card-content">
                        <h2>WORK IN PROGRESS</h2>
                    </div>
                </a>
            </div>
        </div>
    </main>
    <div class="leaderboards">
        <?php 
            $ScoreKCD->displayScoresTable("Medieval");
            $ScoreMafia->displayScoresTable("Mafia");
            $ScoreSchool->displayScoresTable("School");
        ?>
    </div>
</body>
</html>
