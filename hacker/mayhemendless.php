<?php

    include "../inc/loader.php";

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
    <link rel="icon" href="../mlogo.png">
    <title>Mayhem Hacking</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #00ff00;
            background-color: #1a1a1a;
            font-family: Arial, sans-serif;
            overflow: hidden; /* Disable scrolling */
            margin: 0; /* Remove default margin */
        }
        #score {
            position: fixed; /* Change to fixed */
            bottom: 0; /* Stick to the bottom */
            left: 50%;
            transform: translateX(-50%); /* Center horizontally */
            color: white;
            font-size: 80px;
        }

        .buttonexit {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 20px 40px;
            border: 2px solid #CD090F;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 1.5em;
            transition: background-color 0.3s, color 0.3s;
        }

        .buttonexit:hover {
            background-color: #CD090F;
            color: #333;
        }
        #tooltip {
            position: absolute;
            bottom: 20px;
            right: 20px;
            color: white;
            font-size: 26px;
            text-align: right;
        }
        .tooltip-item {
            margin: 5px 0;
        }

        .tooltip-key {
            background-color: #444;
            border-radius: 3px;
            padding: 4px 8px;
            display: inline-block;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <a href="mayhemmenu.php" class="buttonexit">EXIT</a>
    <div class="game-container">
        <canvas id="gameCanvas" width="700" height="700"></canvas> <!-- Zvýšení velikosti plátna -->
        <div id="tooltip">
            <div class="tooltip-item">Restart: <span class="tooltip-key">R</span></div>
            <div class="tooltip-item">Select: <span class="tooltip-key">Space</span></div>
        </div>
        <p id="score">0</p>
    </div>
    <script>
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');
        const scoreDisplay = document.getElementById('score');

        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        const radius = 200; // Zvýšení poloměru kruhu
        const targetWidth = 0.2; // Zvýšená šířka cílového bodu
        let targetPoints = [];
        let currentAngle = 0;
        let speed = 0.025; // Trochu zvýšená rychlost ukazatele
        let hits = 0;
        let score = 0;
        let lastHitAngle = -Infinity; // Inicializace s hodnotou, která nemůže být dosažena
        const hitColors = ['#FF0', '#FF0', '#FF0'];
        const hitStatus = [false, false, false]; // Stav zásahu pro každý bod

        function generateRandomAngles() {
            targetPoints = [];
            while (targetPoints.length < 3) {
                let angle = Math.random() * 2 * Math.PI;
                if (isValidAngle(angle)) {
                    targetPoints.push(angle);
                }
            }
        }

        function isValidAngle(newAngle) {
            const minDistance = 0.5; // Minimální vzdálenost mezi úhly (v radiánech)
            for (let angle of targetPoints) {
                let distance = Math.abs(newAngle - angle);
                if (distance < minDistance || distance > (2 * Math.PI - minDistance)) {
                    return false;
                }
            }
            return true;
        }

        function drawCircle() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.beginPath();
            ctx.arc(centerX, centerY, radius, 0, Math.PI * 2);
            ctx.strokeStyle = '#0F0'; // Změna barvy kruhu na zelenou
            ctx.lineWidth = 5;
            ctx.stroke();
            ctx.closePath();
        }

        function drawTargetPoints() {
            targetPoints.forEach((angle, index) => {
                const x = centerX + radius * Math.cos(angle);
                const y = centerY + radius * Math.sin(angle);
                ctx.beginPath();
                ctx.arc(x, y, 20, 0, Math.PI * 2); // Zvýšený poloměr cílového bodu
                ctx.fillStyle = hitColors[index];
                ctx.fill();
                ctx.closePath();
            });
        }

        function drawMovingIndicator() {
            const x = centerX + radius * Math.cos(currentAngle);
            const y = centerY + radius * Math.sin(currentAngle);
            ctx.beginPath();
            ctx.arc(x, y, 15, 0, Math.PI * 2);
            ctx.fillStyle = '#F00';
            ctx.fill();
            ctx.closePath();
        }

        function checkHit() {
            const minDistanceAfterHit = 0.5; // Minimální vzdálenost od posledního zásahu

            for (let i = 0; i < targetPoints.length; i++) {
                if (!hitStatus[i] && Math.abs(currentAngle - targetPoints[i]) < targetWidth) {
                    hitColors[i] = '#0F0'; // Změnit barvu na zelenou při zásahu
                    hitStatus[i] = true; // Označit bod jako zasažený
                    lastHitAngle = currentAngle; // Uložit úhel posledního zásahu
                    hits++;
                    if (hits === 3) {
                        score++;
                        scoreDisplay.textContent = score;
                        resetGame();
                        return;
                    }
                    return;
                }
            }
        }

        function resetGame() {
            hits = 0;
            currentAngle = 0;
            hitColors.fill('#FF0');
            hitStatus.fill(false);
            lastHitAngle = -Infinity; // Reset úhlu posledního zásahu
            generateRandomAngles();
        }

        function resetAll() {
            score = 0;
            scoreDisplay.textContent = score;
            resetGame();
        }

        function gameLoop() {
            drawCircle();
            drawTargetPoints();
            drawMovingIndicator();
            currentAngle += speed;
            if (currentAngle >= Math.PI * 2) {
                currentAngle = 0;
            }
            requestAnimationFrame(gameLoop);
        }

        document.addEventListener('keydown', event => {
            if (event.code === 'Space') {
                checkHit();
            } else if (event.code === 'KeyR') {
                resetAll();
            }
        });

        generateRandomAngles();
        gameLoop();
    </script>
</body>
</html>
