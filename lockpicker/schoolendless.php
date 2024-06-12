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
  <title>School Endless</title>
  <style>
    @keyframes shake {
      0% { transform: translate(0, 0) rotate(0deg); }
      25% { transform: translate(0, 0) rotate(-1deg); }
      75% { transform: translate(0, 0) rotate(1deg); }
      100% { transform: translate(0, 0) rotate(0deg); }
    }

    body, html {
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

    .numbers:nth-child(1) { transform: rotate(30deg) translate(170px) rotate(-30deg); }
    .numbers:nth-child(2) { transform: rotate(60deg) translate(170px) rotate(-60deg); }
    .numbers:nth-child(3) { transform: rotate(90deg) translate(170px) rotate(-90deg); }
    .numbers:nth-child(4) { transform: rotate(120deg) translate(170px) rotate(-120deg); }
    .numbers:nth-child(5) { transform: rotate(150deg) translate(170px) rotate(-150deg); }
    .numbers:nth-child(6) { transform: rotate(180deg) translate(170px) rotate(-180deg); }
    .numbers:nth-child(7) { transform: rotate(210deg) translate(170px) rotate(-210deg); }
    .numbers:nth-child(8) { transform: rotate(240deg) translate(170px) rotate(-240deg); }
    .numbers:nth-child(9) { transform: rotate(270deg) translate(170px) rotate(-270deg); }
    .numbers:nth-child(10) { transform: rotate(300deg) translate(170px) rotate(-300deg); }
    .numbers:nth-child(11) { transform: rotate(330deg) translate(170px) rotate(-330deg); }
    .numbers:nth-child(12) { transform: rotate(360deg) translate(170px) rotate(-360deg); }

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
  </style>
</head>
<body>
  <a href="medievalmenu.html" class="buttonexit">EXIT</a>
  <div id="game-container">
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
  <script src="school.js"></script>
</body>
</html>
