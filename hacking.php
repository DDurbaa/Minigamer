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
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center; 
            gap: 20px;
            color: #00ff00;
            margin-top: 20px;
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
            color: #00ff00;
        }

        .score-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .score-table th, .score-table td {
            padding: 12px; 
            text-align: left;
            border: 1px solid #00ff00; 
            color: white; 
            border-radius: 5px; 
            font-size: 18px;
        }
        .main-content {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative; 
        }

        .scroll-arrow {
            position: absolute;
            bottom: 100px; 
            left: 50%;
            transform: translateX(-50%);
            font-size: 40px; 
            color: #00ff00; 
            cursor: pointer;
            animation: bounce 3s infinite; 
        }


        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateX(-50%) translateY(0);
            }
            40% {
                transform: translateX(-50%) translateY(-15px); 
            }
            60% {
                transform: translateX(-50%) translateY(-7px);
            }
        }

        .scroll-arrow:before {
            content: '\2193'; 
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
        </div>
        <div class="scroll-arrow"></div>
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
