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
    $Score = new Score(3, $_SESSION['user_id']);
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
  <title>School Timed</title>
  <style>
    @keyframes shake {
      0% {
        transform: translate(0, 0) rotate(0deg);
      }

      25% {
        transform: translate(0, 0) rotate(-1deg);
      }

      75% {
        transform: translate(0, 0) rotate(1deg);
      }

      100% {
        transform: translate(0, 0) rotate(0deg);
      }
    }

    body,
    html {
      height: 100%;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #1a1a1a;
      font-family: Arial, sans-serif;
    }

    #game-container {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    #lock {
      width: 500px;
      height: 500px;
      border: 5px solid #000;
      border-radius: 50%;
      position: relative;
      background-color: #ffb800;
    }

    #dial {
      width: 400px;
      height: 400px;
      border-radius: 50%;
      position: absolute;
      top: 50%;
      left: 50%;
      transform-origin: center;
      transform: translate(-50%, -50%) rotate(0deg);
      background-color: #7c7b7b;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .numbers {
      position: absolute;
      font-size: 32px;
      color: white;
    }

    .numbers:nth-child(1) {
      transform: rotate(30deg) translate(170px) rotate(-30deg);
    }

    .numbers:nth-child(2) {
      transform: rotate(60deg) translate(170px) rotate(-60deg);
    }

    .numbers:nth-child(3) {
      transform: rotate(90deg) translate(170px) rotate(-90deg);
    }

    .numbers:nth-child(4) {
      transform: rotate(120deg) translate(170px) rotate(-120deg);
    }

    .numbers:nth-child(5) {
      transform: rotate(150deg) translate(170px) rotate(-150deg);
    }

    .numbers:nth-child(6) {
      transform: rotate(180deg) translate(170px) rotate(-180deg);
    }

    .numbers:nth-child(7) {
      transform: rotate(210deg) translate(170px) rotate(-210deg);
    }

    .numbers:nth-child(8) {
      transform: rotate(240deg) translate(170px) rotate(-240deg);
    }

    .numbers:nth-child(9) {
      transform: rotate(270deg) translate(170px) rotate(-270deg);
    }

    .numbers:nth-child(10) {
      transform: rotate(300deg) translate(170px) rotate(-300deg);
    }

    .numbers:nth-child(11) {
      transform: rotate(330deg) translate(170px) rotate(-330deg);
    }

    .numbers:nth-child(12) {
      transform: rotate(360deg) translate(170px) rotate(-360deg);
    }

    #indicator {
      width: 20px;
      height: 20px;
      background-color: red;
      position: absolute;
      top: 40px;
      left: 50%;
      transform: translateX(-50%);
      border-radius: 50%;
    }

    #score {
      position: absolute;
      bottom: 0px;
      left: 50%;
      transform: translateX(-50%);
      color: white;
      font-size: 80px;
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
  <a href="medievalmenu.php" class="buttonexit">EXIT</a>
  <div id="game-container">
  <div id="timer">60</div>
    <div id="lock" style="transform: translate(-50%, -50%)">
      <div id="dial">
        <div class="numbers">1</div>
        <div class="numbers">2</div>
        <div class="numbers">3</div>
        <div class="numbers">4</div>
        <div class="numbers">5</div>
        <div class="numbers">6</div>
        <div class="numbers">7</div>
        <div class="numbers">8</div>
        <div class="numbers">9</div>
        <div class="numbers">10</div>
        <div class="numbers">11</div>
        <div class="numbers">12</div>
      </div>
      <div id="indicator"></div>
    </div>
    <div id="score"><span id="score-value">0</span></div>
    <div id="tooltip">
      <div class="tooltip-item">Restart: <span class="tooltip-key">R</span></div>
      <div class="tooltip-item">Select: <span class="tooltip-key">Space</span></div>
      <div class="tooltip-item">Rotate: <span class="tooltip-key">Left/Right Arrow</span></div>
    </div>
  </div>
  <form action="" method="post" id="popupForm">
        <div id="popup" style="display: none;">
            <p id="popup-message"><?php echo $popupMsg ?></p>
            <input type="hidden" id="hidden-score" name="score" value="">
            <button type="submit" id="popup-button" name="update_score">X</button>
        </div>
    </form>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const lock = document.getElementById('lock');
      const dial = document.getElementById('dial');
      const indicator = document.getElementById('indicator');
      const scoreElement = document.getElementById('score-value');
      let dialAngle = 0;
      let correctAngle = Math.floor(Math.random() * 360);
      let isSpacePressed = false;
      let score = 0;
      let timeLeft = 60; // 60 seconds countdown
        const timerElement = document.getElementById("timer");
        let popupActive = false;
        let form = document.getElementById('popupForm');
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

      const resetLock = () => {
        dialAngle = 0;
        correctAngle = Math.floor(Math.random() * 360);
        updateDialPosition();
      };

      const resetGame = () => {
        score = 0;
        scoreElement.textContent = score;
        resetLock();
        timeLeft = 60;
            timerElement.textContent = timeLeft;
      };

      startTimer();

      const updateDialPosition = () => {
        dial.style.transform = `translate(-50%, -50%) rotate(${dialAngle}deg)`;
        const diff = Math.abs(dialAngle - correctAngle);
        let shakeIntensity = 0;

        if (diff < 10) {
          shakeIntensity = 0.2;
          indicator.style.backgroundColor = 'green';
        } else if (diff < 30) {
          shakeIntensity = 0.5;
          indicator.style.backgroundColor = 'red';
        } else if (diff < 60) {
          shakeIntensity = 1;
          indicator.style.backgroundColor = 'red';
        } else {
          shakeIntensity = 1.5;
          indicator.style.backgroundColor = 'red';
        }

        lock.style.animation = `shake ${shakeIntensity}s infinite`;
      };

      document.addEventListener('keydown', (event) => {
        if (event.code === 'ArrowLeft') {
          dialAngle = (dialAngle - 5 + 360) % 360; // Otočení o 5 stupňů vlevo
          updateDialPosition();
        } else if (event.code === 'ArrowRight') {
          dialAngle = (dialAngle + 5) % 360; // Otočení o 5 stupňů vpravo
          updateDialPosition();
        } else if (event.code === 'Space') {
          if (!isSpacePressed) {
            isSpacePressed = true;
            if (Math.abs(dialAngle - correctAngle) < 10) {
              score += 1;
              scoreElement.textContent = score;
              resetLock(); // Reset zámku po úspěšném odemčení
            }
          }
        } else if (event.code === 'KeyR') {
          resetGame(); // Kompletní reset hry při stisknutí R
        }
        else if (event.key === "Escape") {
                if (popupActive) {
                    popupActive = false;
                    const popup = document.getElementById("popup");
                    popup.style.display = "none";
                    form.submit();
                    location.reload();
                }
            }
      });

      document.addEventListener('keyup', (event) => {
        if (event.code === 'Space') {
          isSpacePressed = false;
        }
      });

      updateDialPosition();
    });

  </script>
</body>

</html>