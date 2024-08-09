<?php

include "../inc/loader.php";

$Login = new Login();
session_start();
$Login->checkRememberMe();
$popupMsg = "Sign in to save your score!";
$gameScore = "";
$Score = "";
$best = "";
$isUserLoggedIn = isset($_SESSION['user_id']);

if ($isUserLoggedIn) {
    $Score = new Score(5, $_SESSION['user_id']);
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
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../mlogo.png">
    <title>Utopia Timed</title>
    <style>
        @keyframes pulseBorder {
            0% {
                box-shadow: 0 0 50px var(--pulse-color);
            }

            100% {
                box-shadow: none;
            }
        }

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

        #game {
            position: relative;
            width: 700px;
            height: 75px;
            margin-bottom: 20px;
            overflow: hidden;
            border: 4px solid #2a2a2a;
            --pulse-color: #2a2a2a;
        }

        #game.pulse {
            animation: pulseBorder 2s forwards;
        }

        #pointer {
            position: absolute;
            float: right;
            width: 10px;
            height: 75px;
            background-color: #ffcc00;
        }

        #zones {
            display: flex;
            height: 100%;
            width: 100%;
        }

        .zone {
            height: 100%;
        }

        .green {
            background-color: #0be20b;
        }

        .red {
            background-color: #CD090F;
        }

        .blue {
            background-color: #007fff;
        }

        .grey {
            background-color: grey;
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
            background-color: #1a1a1a;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 255, 0, 0.5);
            z-index: 1000;
            text-align: center;
        }

        #popup-message {
            color: #00ff00;
            font-size: 2em;
        }

        #popup-button {
            padding: 10px 20px;
            margin-top: 10px;
            border: 2px solid #00ff00;
            background-color: transparent;
            color: #00ff00;
            cursor: pointer;
            border-radius: 5px;
            font-size: 1.5em;
            transition: background-color 0.3s, color 0.3s;
        }

        #popup-button:hover {
            background-color: #00ff00;
            color: #1a1a1a;
        }

        #score-info {
            display: flex;
            align-items: center;
            gap: 20px;
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .score-box {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.5em;
            color: white;
        }

        .score-box .color-box {
            width: 30px;
            height: 30px;
            border-radius: 5px;
        }

        .score-box .blue-box {
            background-color: #007fff;
        }

        .score-box .green-box {
            background-color: #0be20b;
        }
        .score-box .red-box {
            background-color: #CD090F;
        }

        .score-box .grey-box {
            background-color: grey;
        }
    </style>
</head>

<body>
    <a href="utopiamenu.php" class="buttonexit">EXIT</a>
    <div id="timer">60</div>
    <div id="game">
        <div id="pointer"></div>
        <div id="zones"></div>
    </div>
    <div id="score">0</div>

    <form action="" method="post" id="popupForm">
        <div id="popup" style="display: none;">
        <p id="popup-message"><?php echo $popupMsg ?><br><span id="current-score"></span></p>
            <input type="hidden" id="hidden-score" name="score" value="">
            <button type="submit" id="popup-button" name="update_score">OK</button>
        </div>
    </form>

    <div id="score-info">
        <div class="score-box">
            <div class="color-box blue-box"></div>+2 points
        </div>
        <div class="score-box">
            <div class="color-box green-box"></div>+1 point
        </div>
        <div class="score-box">
            <div class="color-box red-box"></div>-3 points
        </div>
        <div class="score-box">
            <div class="color-box grey-box"></div>-2 Seconds
        </div>
    </div>

    <script>
        const fields = [
            ['blue', 'blue', 'red', 'gray', 'gray', 'gray', 'red', 'red', 'red', 'blue', 'gray', 'green', 'green'],
            ['gray', 'gray', 'red', 'blue', 'green', 'green', 'green', 'red', 'blue', 'blue', 'red', 'red', 'gray'],
            ['red', 'blue', 'red', 'red', 'green', 'green', 'green', 'red', 'blue', 'red', 'gray', 'gray', 'gray'],
            ['red', 'red', 'blue', 'blue', 'green', 'green', 'gray', 'red', 'red', 'red', 'gray', 'gray', 'green'],
            ['red', 'red', 'gray', 'green', 'green', 'red', 'blue', 'red', 'blue', 'gray', 'red', 'red', 'gray']
        ];

        let score = 0;
        let currentFieldIndex = Math.floor(Math.random() * fields.length); // Vybere náhodný index pro první pole
        const pointer = document.getElementById('pointer');
        const scoreDisplay = document.getElementById('score');
        const zonesContainer = document.getElementById('zones');
        const gameContainer = document.getElementById('game');
        const popup = document.getElementById('popup');
        const popupMessage = document.getElementById('popup-message');
        const currentScoreElement = document.getElementById('current-score');
        let direction = Math.random() < 0.5 ? -1 : 1;
        let timeLeft = 60;
        const timerElement = document.getElementById("timer");
        let popupActive = false;
        let gameActive = true;
        let scoreAdded = false;

        function endGame() {
            gameActive = false;
            const hiddenScoreInput = document.getElementById("hidden-score");
            hiddenScoreInput.value = score;
            if (!scoreAdded && isUserLoggedIn) {
                popupMessage.innerHTML = `BEST: <?php echo $best ?> <br>CURRENT: ` + score;
                scoreAdded = true;
            }
            currentScoreElement.textContent = score;
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

        document.addEventListener('keydown', (event) => {
            if (event.key === 'e' || event.key === 'E') {
                stopPointer();
            } else if (event.key === 'r' || event.key === 'R') {
                resetGame();
            }
        });

        function stopPointer() {
            if (!gameActive) return;
            const pointerRect = pointer.getBoundingClientRect();
            const zones = document.querySelectorAll('.zone');
            let hit = false;
            zones.forEach((zone) => {
                const zoneRect = zone.getBoundingClientRect();
                if (pointerRect.right > zoneRect.left && pointerRect.left < zoneRect.right) {
                    hit = true;
                    if (zone.classList.contains('green')) {
                        score++;
                        pulseBorder('#0be20b');
                    } else if (zone.classList.contains('blue')) {
                        score += 2;
                        pulseBorder('#007fff');
                    } else if (zone.classList.contains('red')) {
                        score = Math.max(0, score - 3);
                        pulseBorder('#CD090F');
                    } else if (zone.classList.contains('grey')) {
                        pulseBorder('grey');
                        timeLeft = Math.max(0, timeLeft - 2);
                    }
                    scoreDisplay.textContent = `${score}`;
                    currentFieldIndex = (currentFieldIndex + 1) % fields.length;
                    resetPointer();
                    generateZones(fields[currentFieldIndex]);
                }
            });
            if (!hit) {
                console.log('Missed all zones');
            }
        }

        function pulseBorder(color) {
            gameContainer.style.setProperty('--pulse-color', color);
            gameContainer.classList.remove('pulse'); // Restart the animation
            void gameContainer.offsetWidth; // Trigger reflow to restart animation
            gameContainer.classList.add('pulse');
        }

        function resetPointer() {
            pointer.style.left = '50%';
            direction = Math.random() < 0.5 ? -1 : 1;
        }

        function resetGame() {
            score = 0;
            scoreDisplay.textContent = `${score}`;
            currentFieldIndex = Math.floor(Math.random() * fields.length); // Vybere náhodný index pro první pole
            resetPointer();
            generateZones(fields[currentFieldIndex]);
            timeLeft = 60;
            timerElement.textContent = timeLeft;
            gameActive = true;
            popup.style.display = "none";
        }

        function generateZones(field) {
            zonesContainer.innerHTML = '';
            const zoneWidth = 100 / field.length;
            field.forEach((color) => {
                const zone = document.createElement('div');
                zone.classList.add('zone');
                zone.style.width = `${zoneWidth}%`;
                zone.classList.add(getColorClass(color));
                zonesContainer.appendChild(zone);
            });
        }

        function getColorClass(color) {
            switch (color) {
                case 'green':
                    return 'green';
                case 'red':
                    return 'red';
                case 'blue':
                    return 'blue';
                case 'gray':
                default:
                    return 'grey';
            }
        }

        function movePointer() {
            if (!gameActive) return;
            const gameWidth = document.getElementById('game').offsetWidth;
            const pointerWidth = pointer.offsetWidth;
            let left = parseFloat(pointer.style.left || '50%');
            left += direction * 9; // Adjust the speed of movement here
            if (left <= 0) {
                left = 0;
                direction = 1;
            } else if (left >= gameWidth - pointerWidth) {
                left = gameWidth - pointerWidth;
                direction = -1;
            }
            pointer.style.left = left + 'px';
            requestAnimationFrame(movePointer);
        }

        resetGame();
        requestAnimationFrame(movePointer);
    </script>
    <div id="tooltip">
        <div class="tooltip-item">Restart: <span class="tooltip-key">R</span></div>
        <div class="tooltip-item">Hack: <span class="tooltip-key">E</span></div>
    </div>
</body>

</html>
