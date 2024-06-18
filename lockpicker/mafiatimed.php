<?php

include "../inc/loader.php";

session_start();

$Login = new Login();
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
  <title>Mafia Endless</title>
  <style>
    body {
      height: 100vh;
      background-color: #1a1a1a;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0;
      flex-direction: column;
      color: #ffffff;
      font-family: Arial, sans-serif;
    }

    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
    }

    .lock-container {
      display: flex;
      background-color: #ffb800;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
      position: relative;
    }

    .lock {
      width: 50px;
      height: 200px;
      margin: 0 10px;
      background-color: #2A2318;
      position: relative;
      display: flex;
      align-items: flex-end;
      border-radius: 5px;
      box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.5);
    }

    .pin {
      width: 100%;
      height: 20px;
      background-color: #a8811b;
      position: absolute;
      bottom: 0;
      transition: bottom 0.1s;
      border-radius: 3px;
    }

    .green {
      background-color: green !important;
    }

    .score {
      position: fixed;
      bottom: 0;
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

    .pick {
      width: 210px;
      height: 15px;
      background-color: #999;
      position: absolute;
      bottom: -20px;
      /* Adjust as needed */
      left: -100px;
      /* Move further left */
      transform-origin: center;
      /* Origin for animation */
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
      transition: transform 0.2s ease;
      /* Smooth animation */
    }

    .pick:before {
      content: '';
      width: 15px;
      height: 50px;
      background-color: #999;
      position: absolute;
      bottom: 0px;
      left: 200px;
      border-radius: 5px;
    }

    .pick.animate {
      transform: rotate(-10deg);
      /* Rotate on animation */
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
  <a href="mafiamenu.php" class="buttonexit">EXIT</a>
  <div class="container">
    <div id="timer">5</div> <!-- Add this line -->
    <span id="score" class="score">0</span>
    <div class="lock-container">
      <div class="lock" id="lock1">
        <div class="pin"></div>
      </div>
      <div class="lock" id="lock2">
        <div class="pin"></div>
      </div>
      <div class="lock" id="lock3">
        <div class="pin"></div>
      </div>
      <div class="pick" id="pick"></div>
    </div>
  </div>
  <div id="tooltip">
    <div class="tooltip-item">Restart: <span class="tooltip-key">R</span></div>
    <div class="tooltip-item">Lock-in: <span class="tooltip-key">E</span></div>
    <div class="tooltip-item">Move pin: <span class="tooltip-key">Up/Down Arrow</span></div>
  </div>

  <div id="popup" style="display: none;">
    <p id="popup-message"><?php echo $popupMsg ?></p>
    <form action="" method="post">
      <input type="hidden" id="hidden-score" name="score" value="">
      <button type="submit" id="popup-button" name="update_score">OK</button>
    </form>
  </div>
  <script>
    let score = 0;
    const locks = document.querySelectorAll('.lock');
    const pick = document.getElementById('pick');
    let currentLockIndex = 0;
    let positions = [0, 0, 0];
    let sweetSpots = [];
    const tolerance = 5; // Tolerance na sweet spoty
    let timeLeft = 5; // 60 seconds countdown
    const timerElement = document.getElementById('timer');

    function endGame() {

      const hiddenScoreInput = document.getElementById('hidden-score');
      hiddenScoreInput.value = score;

      const popup = document.getElementById('popup');
      const messageElement = document.getElementById('popup-message');
      popup.style.display = 'block';
    }

    function generateSweetSpots() {
      for (let i = 0; i < locks.length; i++) {
        sweetSpots.push(Math.floor(Math.random() * 161) + 20); // 20 - 180 rozmezí
      }
    }

    function updatePickPosition() {
      const lockWidth = locks[0].offsetWidth;
      pick.style.left = `${currentLockIndex * (lockWidth + 20) + (lockWidth / 2) - (pick.offsetWidth / 2) - 50}px`;
    }

    function animatePick() {
      pick.classList.add('animate');
      setTimeout(() => {
        pick.classList.remove('animate');
      }, 200);
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
      score = 0;
      document.getElementById("score").innerHTML = score;
      resetLocks();
      timeLeft = 5;
      timerElement.textContent = timeLeft;
    }

    generateSweetSpots();
    updatePickPosition();
    startTimer();

    document.addEventListener('keydown', (e) => {
      const currentLock = locks[currentLockIndex];
      const pin = currentLock.querySelector('.pin');
      let currentPosition = positions[currentLockIndex];

      if (e.key === 'ArrowUp') {
        if (currentPosition < 180) {
          currentPosition += 10;
          positions[currentLockIndex] = currentPosition;
          pin.style.bottom = `${currentPosition}px`;
          checkSweetSpot(currentLockIndex, currentPosition, pin);
          animatePick();
        }
      }
      else if (e.key === 'r') {
        resetGame();
      } else if (e.key === 'ArrowDown') {
        if (currentPosition > 0) {
          currentPosition -= 10;
          positions[currentLockIndex] = currentPosition;
          pin.style.bottom = `${currentPosition}px`;
          checkSweetSpot(currentLockIndex, currentPosition, pin);
          animatePick();
        }
      } else if (e.key === 'e') {
        if (Math.abs(positions[currentLockIndex] - sweetSpots[currentLockIndex]) <= tolerance) {
          pin.classList.add('green');
          currentLockIndex++;
          updatePickPosition();
          if (currentLockIndex >= locks.length) {
            score++;
            document.getElementById("score").innerHTML = score;
            resetLocks();
          }
        } else {
          if (currentLockIndex > 0) {
            positions[currentLockIndex] = 0;
            locks[currentLockIndex].querySelector('.pin').style.bottom = '0px';
            currentLockIndex--;
            positions[currentLockIndex] = 0;
            locks[currentLockIndex].querySelector('.pin').style.bottom = '0px';
            locks[currentLockIndex].querySelector('.pin').classList.remove('green');
            updatePickPosition();
          }
        }
      }
    });

    function checkSweetSpot(index, position, pin) {
      if (Math.abs(position - sweetSpots[index]) <= tolerance) {
        pin.classList.add('green');
      } else {
        pin.classList.remove('green');
      }
    }

    function resetLocks() {
      currentLockIndex = 0;
      positions = [0, 0, 0];
      sweetSpots = [];
      generateSweetSpots();
      locks.forEach(lock => {
        const pin = lock.querySelector('.pin');
        pin.classList.remove('green');
        pin.style.bottom = '0px';
      });
      updatePickPosition();
    }

    function movePinsDown() {
      const pin = locks[currentLockIndex].querySelector('.pin');
      let currentPosition = positions[currentLockIndex];

      if (currentPosition > 0) {
        currentPosition -= 1;
        positions[currentLockIndex] = currentPosition;
        pin.style.bottom = `${currentPosition}px`;
        checkSweetSpot(currentLockIndex, currentPosition, pin);
      }
    }

    function checkScore() {
      const scoreElement = document.getElementById('score');
      scoreElement.textContent = score;
    }

    // Move pins down every 50ms
    setInterval(movePinsDown, 50);
    setInterval(checkScore, 10);

  </script>
</body>

</html>