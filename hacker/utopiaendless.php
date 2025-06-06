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
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../mlogo.png">
    <title>Utopia Hacking</title>
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
            overflow: hidden; /* Disable scrolling */
            margin: 0; /* Remove default margin */
        }

        #game {
            position: relative;
            width: 700px; /* Zvětšená šířka herního pole */
            height: 75px; /* Zvětšená výška herního pole */
            margin-bottom: 20px;
            overflow: hidden;
            border: 4px solid #2a2a2a;
            --pulse-color: #2a2a2a; /* Default border color */
        }

        #game.pulse {
            animation: pulseBorder 2s forwards; /* Slow fading animation */
        }

        #pointer {
            position: absolute;
            float: right;
            width: 10px; /* Zvětšená šířka pointeru */
            height: 75px; /* Zvětšená výška pointeru */
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
    <div id="game">
        <div id="pointer"></div>
        <div id="zones"></div>
    </div>
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
            <div class="color-box grey-box"></div>Nothing
        </div>
    </div>
    <div id="score">0</div>
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
        let direction = Math.random() < 0.5 ? -1 : 1;

        document.addEventListener('keydown', (event) => {
            if (event.key === 'e' || event.key === 'E') {
                stopPointer();
            } else if (event.key === 'r' || event.key === 'R') {
                resetGame();
            }
        });

        function stopPointer() {
            const pointerRect = pointer.getBoundingClientRect();
            const zones = document.querySelectorAll('.zone');
            let hit = false;
            zones.forEach((zone) => {
                const zoneRect = zone.getBoundingClientRect();
                // Přesnější kontrola pozice pointeru vůči zónám
                if (pointerRect.right > zoneRect.left && pointerRect.left < zoneRect.right) {
                    hit = true;
                    if (zone.classList.contains('green')) {
                        score++;
                        pulseBorder('#0be20b');
                        console.log('Hit green zone');
                    } else if (zone.classList.contains('blue')) {
                        score += 2;
                        pulseBorder('#007fff');
                        console.log('Hit blue zone');
                    } else if (zone.classList.contains('red')) {
                        score = Math.max(0, score - 3);
                        pulseBorder('#CD090F');
                        console.log('Hit red zone');
                    } else if (zone.classList.contains('grey')) {
                        pulseBorder('grey');
                        console.log('Hit grey zone');
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
