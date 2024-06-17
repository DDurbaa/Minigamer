<?php

    include "../inc/loader.php";

    $Login = new Login();
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
  <title>Stardew Fishing</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <a href="valleymenu.php" class="buttonexit">EXIT</a>
  <div class="fishing" data-reelpower="20" data-baitweight="1" data-progress="3" data-progresspenalty="3" data-progressupdaterate="100">
    <div class="rod">
      <div class="reel">
        <div class="handle"></div>
      </div>
    </div>
    <div class="sea">
      <div class="area">
        <div class="bait" id="bait"></div>
        <div class="fish" data-movepremsec="1500" data-jumprange="100" data-speed="1000" data-depth="20">
          <i class="fas fa-fish"></i>
        </div>
      </div>
    </div>
    <div class="progress">
      <div class="area">
        <div class="bar" style="height:0%"></div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="script.js"></script>
</body>
</html>
