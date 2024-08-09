<?php

    include "inc/loader.php";

    $Login = new Login();session_start();
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
        .main-content {
            height: 100vh; 
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative; /* Needed for positioning the arrow */
        }

        .leaderboards {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center; 
            gap: 20px;
            color: #ffcc00;
            margin-top: 0; 
        }

        .score-table {
            width: 400px; 
            background-color: transparent; 
            border-radius: 10px;
            box-shadow: none; 
            margin: 0 20px; 
        }

        .score-table h2 {
            text-align: center;
            color: #ffcc00;
        }

        .score-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .score-table th, .score-table td {
            padding: 12px; 
            text-align: left;
            border: 1px solid #ffcc00;
            color: white; 
            border-radius: 5px; 
            font-size: 18px; 
        }

        /* Arrow styling */
        .scroll-arrow {
            position: absolute;
            bottom: 100px; /* Raise the arrow higher */
            left: 50%;
            transform: translateX(-50%);
            font-size: 40px; /* Increase the size of the arrow */
            color: #ffcc00;
            cursor: pointer;
            animation: bounce 3s infinite; /* Slowing down the animation */
        }

        /* Animation for the arrow */
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateX(-50%) translateY(0);
            }
            40% {
                transform: translateX(-50%) translateY(-15px); /* Increase bounce height */
            }
            60% {
                transform: translateX(-50%) translateY(-7px);
            }
        }

        /* Using a proper arrow symbol */
        .scroll-arrow:before {
            content: '\2193'; /* Down arrow symbol */
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const arrow = document.querySelector('.scroll-arrow');
            arrow.addEventListener('click', function() {
                document.querySelector('#leaderboard').scrollIntoView({ 
                    behavior: 'smooth' 
                });
            });
        });
    </script>
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
    <main class="main-content">
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
        </div>
        <!-- Scroll Arrow -->
        <div class="scroll-arrow"></div>
    </main>
    <section class="leaderboards" id="leaderboard">
        <?php 
            $ScoreKCD->displayScoresTable("Medieval");
            $ScoreMafia->displayScoresTable("Mafia");
            $ScoreSchool->displayScoresTable("School");
        ?>
    </section>
</body>
</html>
