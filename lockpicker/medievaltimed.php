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
    $Score = new Score(4, $_SESSION['user_id']);
    $Login->checkVerification($_SESSION['user_id']);
    $best = $Score->getPlayerScore();
    $popupMsg = "BEST: " . $best . "<br>CURRENT: ";
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if ($isUserLoggedIn) {
        $gameScore = $_POST['score'];
        if ($gameScore > $best) {
            $Score->savePlayerScore($gameScore);
        }
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
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
    <title>Medieval Timed</title>
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #1a1a1a;
            cursor: none;
            font-family: Arial, sans-serif;
        }

        #cursorCanvas {
            position: absolute;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 1000;
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

        #score {
            position: absolute;
            bottom: 0px;
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
            cursor: none;
        }

        .buttonexit:hover {
            background-color: #CD090F;
            color: #333;
        }

        #timer {
            font-size: 2.5em;
            color: white;
            position: fixed;
            left: 50.2%;
            transform: translateX(-50%);
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
            color: #ffcc00;
            font-size: 2em;
        }

        #popup-button {
            padding: 10px 20px;
            margin-top: 10px;
            border: 2px solid #ffcc00;
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
    </style>
</head>

<body>

    <canvas id="lockCanvas" width="800" height="700"></canvas>

    <canvas id="cursorCanvas"></canvas>

    <a href="medievalmenu.php" class="buttonexit">EXIT</a>

    <div id="tooltip">
        <div class="tooltip-item">Restart: <span class="tooltip-key">R</span></div>
        <div class="tooltip-item">Rotate: <span class="tooltip-key">Space</span></div>
        <div class="tooltip-item">Move cursor: <span class="tooltip-key">Mouse</span></div>
    </div>

    <div id="timer">60</div>
    <div id="score">0</div>

    <form action="" method="post" id="popupForm">
        <div id="popup" style="display: none;">
            <p id="popup-message"><?php echo $popupMsg ?><br><span id="current-score"></span></p>
            <input type="hidden" id="hidden-score" name="score" value="">
            <button type="submit" id="popup-button" name="update_score">OK</button>
        </div>
    </form>
    <script>
    const lockCanvas = document.getElementById('lockCanvas');
    const lockCtx = lockCanvas.getContext('2d');
    const cursorCanvas = document.getElementById('cursorCanvas');
    const cursorCtx = cursorCanvas.getContext('2d');
    let rotation = 0;
    let imgRotation = 0;
    let isRotating = false;
    let isInSweetSpot = false;
    const sweetSpotRadius = 30;
    let circleRadius = Math.random() < 0.5 ? 240 : 130;
    let sweetSpotAngle = Math.random() * Math.PI * 2;
    const initialImgRotation = 0;
    let score = 0;
    let startTime = null;
    let lastTimeInSweetSpot = null;
    let cursorX = 0;
    let cursorY = 0;
    let initialSweetSpotAngle = sweetSpotAngle;
    let initialCircleRadius = circleRadius;
    let timeLeft = 60; // Initial 60 seconds countdown
    const timerElement = document.getElementById("timer");
    let popupActive = false;
    let form = document.getElementById('popupForm');
    let scoreAdded = false;
    const timeBonus = 5; // Time bonus for each successful lock

    cursorCanvas.width = window.innerWidth;
    cursorCanvas.height = window.innerHeight;

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

    function resetGame() {
        rotation = 0;
        imgRotation = initialImgRotation;
        sweetSpotAngle = Math.random() * Math.PI * 2;
        circleRadius = Math.random() < 0.5 ? 240 : 130;
        initialSweetSpotAngle = sweetSpotAngle;
        initialCircleRadius = circleRadius;
        startTime = null;
        lastTimeInSweetSpot = null;
        isRotating = false;
        popupActive = false; // Ensure the game is active again
    }

    function resetPosition() {
        rotation = 0;
        imgRotation = initialImgRotation;
        sweetSpotAngle = initialSweetSpotAngle;
        circleRadius = initialCircleRadius;
        startTime = null;
        lastTimeInSweetSpot = null;
        isRotating = false;
    }

    function resetScore() {
        score = 0;
        document.getElementById('score').innerText = score;
    }

    document.body.addEventListener('keydown', (e) => {
        if (e.code === 'KeyR') {
            resetGame();
            resetScore();
            timeLeft = 60; // Reset to initial 60 seconds
        } else if (e.key === "Escape") {
            if (popupActive) {
                popupActive = false;
                const popup = document.getElementById("popup");
                popup.style.display = "none";
                form.submit();
                location.reload();
            }
        }
    });

    const img = new Image();
    img.src = 'wheel.png';

    function drawLock() {
        lockCtx.clearRect(0, 0, lockCanvas.width, lockCanvas.height);
        lockCtx.save();
        lockCtx.translate(lockCanvas.width / 2, lockCanvas.height / 2);
        lockCtx.rotate(imgRotation);
        lockCtx.drawImage(img, -img.width / 2, -img.height / 2);
        lockCtx.restore();
        lockCtx.save();
        lockCtx.translate(lockCanvas.width / 2, lockCanvas.height / 2);
        lockCtx.rotate(rotation);
        lockCtx.beginPath();
        lockCtx.fillStyle = 'rgba(255, 0, 0, 0.000001)';
        lockCtx.arc(circleRadius * Math.cos(sweetSpotAngle), circleRadius * Math.sin(sweetSpotAngle), sweetSpotRadius, 0, Math.PI * 2);
        lockCtx.fill();
        lockCtx.restore();
        document.getElementById('score').innerText = score;
        drawCustomCursor(cursorX, cursorY, isInSweetSpot ? 'green' : 'red');
    }

    function getSweetSpotPosition() {
        return {
            x: (lockCanvas.width / 2) + circleRadius * Math.cos(sweetSpotAngle + rotation),
            y: (lockCanvas.height / 2) + circleRadius * Math.sin(sweetSpotAngle + rotation)
        };
    }

    function checkSweetSpot(cursorX, cursorY) {
        const sweetSpotPos = getSweetSpotPosition();
        const dx = cursorX - sweetSpotPos.x;
        const dy = cursorY - sweetSpotPos.y;
        return (dx * dx + dy * dy) < sweetSpotRadius * sweetSpotRadius;
    }

    function moveCursor() {
        cursorX += 0.000001;
        cursorY += 0.000001;
        const rect = lockCanvas.getBoundingClientRect();
        const canvasX = cursorX - rect.left;
        const canvasY = cursorY - rect.top;
        isInSweetSpot = checkSweetSpot(canvasX, canvasY);
        drawCustomCursor(cursorX, cursorY, isInSweetSpot ? 'green' : 'red');
        if (isInSweetSpot) {
            lastTimeInSweetSpot = Date.now();
        }
    }

    document.addEventListener('mousemove', (event) => {
        cursorX = event.clientX;
        cursorY = event.clientY;
    });

    function drawCustomCursor(x, y, color) {
        const cursorSize = 30;
        cursorCtx.clearRect(0, 0, cursorCanvas.width, cursorCanvas.height);
        cursorCtx.save();
        cursorCtx.beginPath();
        cursorCtx.arc(x, y, cursorSize, 0, Math.PI * 2);
        cursorCtx.fillStyle = color;
        cursorCtx.fill();
        cursorCtx.restore();
    }

    function update() {
        if (popupActive) return; // Exit if the popup is active
        if (isRotating && isInSweetSpot) {
            if (!startTime) {
                startTime = Date.now();
            }
            rotation -= 0.035;
            imgRotation -= 0.035;
            if (rotation < -Math.PI * 2) {
                rotation = 0;
            }
            if (imgRotation < -Math.PI * 2) {
                imgRotation = 0;
            }
            if (Date.now() - startTime >= 2500) {
                score++;
                timeLeft += timeBonus; // Add bonus time
                resetGame();
            }
        } else {
            if (!isInSweetSpot && lastTimeInSweetSpot && Date.now() - lastTimeInSweetSpot >= 200) {
                startTime = null;
                lastTimeInSweetSpot = null;
                resetPosition();
            }
        }
        drawLock();
    }

    document.body.addEventListener('keydown', (e) => {
        if (e.code === 'Space' && !popupActive) {
            isRotating = true;
        } else if (e.code === 'KeyR') {
            resetGame();
            resetScore();
            timeLeft = 60; // Reset to initial 60 seconds
        }
    });

    document.body.addEventListener('keyup', (e) => {
        if (e.code === 'Space') {
            isRotating = false;
        }
    });

    setInterval(update, 1000 / 60);

    window.addEventListener('resize', () => {
        cursorCanvas.width = window.innerWidth;
        cursorCanvas.height = window.innerHeight;
    });

    startTimer();
    setInterval(moveCursor, 1000 / 60);

</script>
</body>

</html>