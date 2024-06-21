<?php

include "../inc/loader.php";

$Login = new Login();session_start();
$Login->checkRememberMe();
$popupMsg = "Sign in to save your score!";
$gameScore = "";
$Score = "";
$best = "";
$isUserLoggedIn = isset($_SESSION['user_id']);

if ($isUserLoggedIn) {
    $Score = new Score(6, $_SESSION['user_id']);
    $Login->checkVerification($_SESSION['user_id']);
    $best = $Score->getPlayerScore();
    $popupMsg = "BEST: " . $best . " CURRENT: ";
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if ($isUserLoggedIn) {
        $gameScore = $_POST['score'];
        if ($gameScore > $best) {
            $Score->savePlayerScore($gameScore);
        }
    }

    header("Location: " . $_SERVER['PHP_SELF']);
}

?>

<script>
    // JESTLI JE PRIHLASENEJ PHP -> JS
    const isUserLoggedIn = <?php echo json_encode($isUserLoggedIn); ?>;
</script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../mlogo.png">
    <title>Mayhem Timed</title>
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
            /* Disable scrolling */
            margin: 0;
            /* Remove default margin */
        }

        #score {
            position: fixed;
            /* Change to fixed */
            bottom: 0;
            /* Stick to the bottom */
            left: 50%;
            transform: translateX(-50%);
            /* Center horizontally */
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

        #timer {
            font-size: 2em;
            color: white;
            margin-bottom: 20px;
        }

        #popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 1000;
            text-align: center;
        }

        #popup-message {
            color: black;
        }

        #popup-button {
            padding: 10px 20px;
            margin-top: 10px;
            border: none;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }

        #popup-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <a href="mayhemmenu.php" class="buttonexit">EXIT</a>
    <div class="game-container">
        <div id="timer">60</div>
        <canvas id="gameCanvas" width="700" height="700"></canvas>
        <div id="tooltip">
            <div class="tooltip-item">Restart: <span class="tooltip-key">R</span></div>
            <div class="tooltip-item">Select: <span class="tooltip-key">Space</span></div>
        </div>
        <div class="score" id="score">0</div>
        <form action="" method="post" id="popupForm">
            <div id="popup" style="display: none;">
                <p id="popup-message"><?php echo $popupMsg ?></p>
                <input type="hidden" id="hidden-score" name="score" value="">
                <button type="submit" id="popup-button" name="update_score">OK</button>
            </div>
        </form>
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
        let speed = 0.05; // Zvýšení rychlosti ukazatele
        let hits = 0;
        let score = 0;
        let lastHitAngle = -Infinity; // Inicializace s hodnotou, která nemůže být dosažena
        const hitColors = ['#FF0', '#FF0', '#FF0'];
        const hitStatus = [false, false, false]; // Stav zásahu pro každý bod
        const hitGlows = [false, false, false]; // Glow effect status for each point

        let lastTime = performance.now();
        let spacePressed = false; // Stav pro stisknutí mezerníku
        let spacePressTimeout = false; // Zamezení držení mezerníku
        let timeLeft = 60; // 60 seconds countdown
        const timerElement = document.getElementById("timer");
        let popupActive = false;
        let scoreAdded = false;

        function endGame() {
            const hiddenScoreInput = document.getElementById("hidden-score");
            hiddenScoreInput.value = score;
            const popup = document.getElementById("popup");
            const messageElement = document.getElementById("popup-message");
            if (!scoreAdded && isUserLoggedIn) {
                messageElement.textContent += score;
                scoreAdded = true;
            }
            popup.style.display = "block";
            popupActive = true;
        }

        function updateTimer() {
            if (timeLeft > 0) timeLeft--;
            timerElement.textContent = timeLeft;
            if (timeLeft <= 0) {
                endGame();
            }
        }

        function startTimer() {
            setInterval(updateTimer, 1000);
        }
        startTimer();

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
            for (let i = 0; i < targetPoints.length; i++) {
                if (!hitStatus[i] && Math.abs(currentAngle - targetPoints[i]) < targetWidth) {
                    hitColors[i] = '#0F0'; // Změnit barvu na zelenou při zásahu
                    hitStatus[i] = true; // Označit bod jako zasažený
                    hitGlows[i] = true; // Enable glow effect
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
            hitGlows.fill(false);
            lastHitAngle = -Infinity; // Reset úhlu posledního zásahu
            generateRandomAngles();
        }

        function resetAll() {
            score = 0;
            scoreDisplay.textContent = score;
            timeLeft = 60;
            timerElement.textContent = timeLeft;
            resetGame();
        }

        function gameLoop(currentTime) {
            const deltaTime = (currentTime - lastTime) / 1000; // Calculate the time difference in seconds
            lastTime = currentTime;

            drawCircle();
            drawTargetPoints();
            drawMovingIndicator();
            currentAngle += speed * deltaTime * 60; // Scale the speed by deltaTime and FPS (assumed to be 60)
            if (currentAngle >= Math.PI * 2) {
                currentAngle = 0;
            }
            requestAnimationFrame(gameLoop);
        }

        document.addEventListener('keydown', event => {
            if (event.code === 'Space' && !spacePressTimeout) {
                checkHit();
                spacePressTimeout = true; // Zamezení držení mezerníku
                setTimeout(() => {
                    spacePressTimeout = false; // Obnovit možnost stisku mezerníku po 300ms
                }, 300);
            } else if (event.code === 'KeyR') {
                resetAll();
            }
        });

        generateRandomAngles();
        gameLoop(lastTime);
    </script>
</body>

</html>
