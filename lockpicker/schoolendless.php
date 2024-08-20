<!DOCTYPE html>
<html lang="cs">

<head>
  <meta charset="UTF-8">
  <title>School Endless</title>
  <link rel="icon" href="../mlogo.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

    #timer-container {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
    }

    .arrow {
      font-size: 2.5em;
      color: white;
      opacity: 0.3;
      transition: opacity 0.3s;
      margin: 0 20px;
    }

    .active {
      opacity: 1;
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

    #popup {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: #1a1a1a;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(255, 204, 0, 0.5);
      z-index: 1000;
      text-align: center;
      opacity: 95%;
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
      color: #ffcc00;
      cursor: pointer;
      border-radius: 5px;
      font-size: 1.5em;
      transition: background-color 0.3s, color 0.3s;
    }

    #popup-button:hover {
      background-color: #ffcc00;
      color: #1a1a1a;
    }
  </style>
</head>

<body>
  <a href="schoolmenu.php" class="buttonexit">EXIT</a>
  <div id="game-container">
    <div id="timer-container">
      <i id="arrow-left" class="arrow fas fa-arrow-left"></i>
      <i id="arrow-right" class="arrow fas fa-arrow-right"></i>
    </div>
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
  <script>
document.addEventListener('DOMContentLoaded', () => {
    const lock = document.getElementById('lock');
    const dial = document.getElementById('dial');
    const indicator = document.getElementById('indicator');
    const scoreElement = document.getElementById('score-value');
    const arrowLeft = document.getElementById('arrow-left');
    const arrowRight = document.getElementById('arrow-right');
    let dialAngle = 0;
    let correctAngle = Math.floor(Math.random() * 360);
    let isSpacePressed = false;
    let score = 0;

    const resetLock = () => {
        dialAngle = 0;
        correctAngle = Math.floor(Math.random() * 360);
        updateDialPosition();
    };

    const resetGame = () => {
        score = 0;
        scoreElement.textContent = score;
        resetLock();
    };

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

        // Determine shortest direction to correctAngle
        let clockwiseDist = (correctAngle - dialAngle + 360) % 360;
        let counterClockwiseDist = (dialAngle - correctAngle + 360) % 360;

        if (clockwiseDist < counterClockwiseDist) {
            arrowRight.classList.add('active');
            arrowLeft.classList.remove('active');
        } else {
            arrowRight.classList.remove('active');
            arrowLeft.classList.add('active');
        }
    };

    document.addEventListener('keydown', (event) => {
        if (event.code === 'ArrowLeft') {
            dialAngle = (dialAngle - 5 + 360) % 360;
            updateDialPosition();
        } else if (event.code === 'ArrowRight') {
            dialAngle = (dialAngle + 5) % 360;
            updateDialPosition();
        } else if (event.code === 'Space') {
            if (!isSpacePressed) {
                isSpacePressed = true;
                if (Math.abs(dialAngle - correctAngle) < 10) {
                    score += 1;
                    scoreElement.textContent = score;
                    resetLock();
                }
            }
        } else if (event.code === 'KeyR') {
            resetGame();
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
