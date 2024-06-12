<?php

    include "inc/loader.php";

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
      background-color: #8B7356;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
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
      background-color: #705d47;
      position: absolute;
      bottom: 0;
      transition: bottom 0.1s;
      border-radius: 3px;
    }

    .green {
      background-color: green !important;
    }

    .score {
      margin-bottom: 20px;
      font-size: 24px;
      font-weight: bold;
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
  </style>
</head>

<body>
  <a href="mafiamenu.html" class="buttonexit">EXIT</a>
  <div class="container">
    <span id="score" class="score">Score: 0</span>
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
    </div>
  </div>
  <div id="tooltip">
    <div class="tooltip-item">Restart: <span class="tooltip-key">R</span></div>
    <div class="tooltip-item">Lock-in: <span class="tooltip-key">E</span></div>
    <div class="tooltip-item">Move pin: <span class="tooltip-key">Up/Down Arrow</span></div>
  </div>
  <script>
    let score = 0;
    const locks = document.querySelectorAll('.lock');
    let currentLockIndex = 0;
    let positions = [0, 0, 0];
    let sweetSpots = [];
    const tolerance = 5; // Tolerance na sweet spoty

    function generateSweetSpots() {
      for (let i = 0; i < locks.length; i++) {
        sweetSpots.push(Math.floor(Math.random() * 161) + 20); // 20 - 180 rozmezí
      }
    }

    generateSweetSpots();

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
        }
      } else if (e.key === 'ArrowDown') {
        if (currentPosition > 0) {
          currentPosition -= 10;
          positions[currentLockIndex] = currentPosition;
          pin.style.bottom = `${currentPosition}px`;
          checkSweetSpot(currentLockIndex, currentPosition, pin);
        }
      } else if (e.key === 'e') {
        if (Math.abs(positions[currentLockIndex] - sweetSpots[currentLockIndex]) <= tolerance) {
          pin.classList.add('green');
          currentLockIndex++;
          if (currentLockIndex >= locks.length) {
            score++;
            document.getElementById("score").innerHTML = "Score: " + score;
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
    }

    function movePinsDown() {
      const pin = locks[currentLockIndex].querySelector('.pin');
      let currentPosition = positions[currentLockIndex];

      if (currentPosition > 0 ) {
        currentPosition -= 1;
        positions[currentLockIndex] = currentPosition;
        pin.style.bottom = `${currentPosition}px`;
        checkSweetSpot(currentLockIndex, currentPosition, pin);
      }
    }

    // Soupne se dolu kazdych 50ms
    setInterval(movePinsDown, 50);

  </script>
</body>

</html>