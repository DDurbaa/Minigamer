<?php

    include "../inc/loader.php";

    $Login = new Login();session_start();
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
            overflow: hidden;
            margin: 0;
        }
        #score {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
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

        #instruction {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: #00ff00;
            padding: 20px;
            border: 2px solid #00ff00;
            border-radius: 10px;
            font-size: 24px;
            text-align: center;
            display: none;
        }

        #tutorialComplete {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: #00ff00;
            padding: 20px;
            border: 2px solid #00ff00;
            border-radius: 10px;
            text-align: center;
            display: none;
        }

        .button {
            display: flex;
            flex-direction: column;
            margin: 10px;
            padding: 10px 20px;
            color: #00ff00;
            border: 2px solid #00ff00;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1.2em;
            transition: background-color 0.3s, color 0.3s;
        }

        .button:hover {
            background-color: #00ff00;
            color: #333;
        }

        @keyframes greenGlow {
            0% {
                box-shadow: 0 0 0 rgba(0, 255, 0, 0);
            }
            50% {
                box-shadow: 0 0 20px rgba(0, 255, 0, 1);
            }
            100% {
                box-shadow: 0 0 0 rgba(0, 255, 0, 0);
            }
        }

        .glow {
            animation: greenGlow 1s ease-out infinite;
        }
    </style>
</head>
<body>
    <a href="mayhemmenu.php" class="buttonexit">EXIT</a>
    <div class="game-container">
        <canvas id="gameCanvas" width="700" height="700"></canvas>
        <div id="tooltip">
            <div class="tooltip-item">Restart: <span class="tooltip-key">R</span></div>
            <div class="tooltip-item">Select: <span class="tooltip-key">Space</span></div>
        </div>
        <div class="score" id="score">0</div>
    </div>
    <div id="instruction">Press SPACE to hit the target!</div>
    <div id="tutorialComplete">
        <p>Congratulations! You have completed the tutorial.</p>
        <a href="mayhemmenu.php" class="button">Back to Menu</a>
        <a href="#" onclick="location.reload();" class="button">Try Again</a>
    </div>
    <script>
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');
        const scoreDisplay = document.getElementById('score');
        const instruction = document.getElementById('instruction');
        const tutorialComplete = document.getElementById('tutorialComplete');

        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        const radius = 200;
        const targetWidth = 0.2;
        let targetPoints = [];
        let currentAngle = 0;
        let speed = 0.05;
        let hits = 0;
        let lastHitAngle = -Infinity;
        const hitColors = ['#FF0', '#FF0', '#FF0'];
        const hitStatus = [false, false, false];
        const hitGlows = [false, false, false];

        let lastTime = performance.now();
        let isPaused = false;
        let tutorialInProgress = true;

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
            const minDistance = 0.5;
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
            ctx.strokeStyle = '#0F0';
            ctx.lineWidth = 5;
            ctx.stroke();
            ctx.closePath();
        }

        function drawTargetPoints() {
            targetPoints.forEach((angle, index) => {
                const x = centerX + radius * Math.cos(angle);
                const y = centerY + radius * Math.sin(angle);
                ctx.beginPath();
                ctx.arc(x, y, 20, 0, Math.PI * 2);
                ctx.fillStyle = hitColors[index];
                ctx.fill();
                ctx.closePath();
                if (hitGlows[index]) {
                    drawGlow(x, y);
                }
            });
        }

        function drawGlow(x, y) {
            ctx.save();
            ctx.shadowBlur = 20;
            ctx.shadowColor = 'rgba(0, 255, 0, 1)';
            ctx.beginPath();
            ctx.arc(x, y, 20, 0, Math.PI * 2);
            ctx.fillStyle = '#0F0';
            ctx.fill();
            ctx.closePath();
            ctx.restore();
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
            const minDistanceAfterHit = 0.5;

            for (let i = 0; i < targetPoints.length; i++) {
                if (!hitStatus[i] && Math.abs(currentAngle - targetPoints[i]) < targetWidth) {
                    hitColors[i] = '#0F0';
                    hitStatus[i] = true;
                    hitGlows[i] = true;
                    lastHitAngle = currentAngle;
                    hits++;
                    if (hits === 3) {
                        tutorialInProgress = false;
                        showTutorialComplete();
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
            hitGlows.fill(false);
            lastHitAngle = -Infinity;
            generateRandomAngles();
        }

        function resetAll() {
            scoreDisplay.textContent = 0;
            resetGame();
        }

        function gameLoop(currentTime) {
            const deltaTime = (currentTime - lastTime) / 1000;
            lastTime = currentTime;

            if (!isPaused) {
                drawCircle();
                drawTargetPoints();
                drawMovingIndicator();
                currentAngle += speed * deltaTime * 60;
                if (currentAngle >= Math.PI * 2) {
                    currentAngle = 0;
                }

                if (tutorialInProgress && !isPaused) {
                    checkProximityToTarget();
                }
            }
            requestAnimationFrame(gameLoop);
        }

        function checkProximityToTarget() {
            for (let i = 0; i < targetPoints.length; i++) {
                if (!hitStatus[i] && Math.abs(currentAngle - targetPoints[i]) < targetWidth) {
                    pauseGame();
                    showInstruction();
                    break;
                }
            }
        }

        function pauseGame() {
            isPaused = true;
        }

        function resumeGame() {
            isPaused = false;
            instruction.style.display = 'none';
        }

        function showInstruction() {
            instruction.style.display = 'block';
        }

        function showTutorialComplete() {
            tutorialComplete.style.display = 'block';
        }

        document.addEventListener('keydown', event => {
            if (event.code === 'Space') {
                if (isPaused) {
                    checkHit();
                    resumeGame();
                }
            } else if (event.code === 'KeyR') {
                resetAll();
            }
        });

        generateRandomAngles();
        gameLoop(lastTime);
    </script>
</body>
</html>