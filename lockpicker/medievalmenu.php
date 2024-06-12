<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medieval Menu</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #1a1a1a;
            font-family: Arial, sans-serif;
            color: white;
        }

        .menu {
            text-align: center;
        }

        h1 {
            margin-bottom: 40px;
            font-size: 3em;
        }

        .buttons,
        .bottom-buttons {
            margin-bottom: 40px;
        }

        .button {
            display: block;
            padding: 20px 40px;
            margin: 10px auto;
            width: 200px;
            border: 2px solid #ffcc00;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 1.5em;
            transition: background-color 0.3s, color 0.3s;
        }
        .buttonexit {
            display: block;
            padding: 20px 40px;
            margin: 10px auto;
            width: 200px;
            border: 2px solid #CD090F;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 1.5em;
            transition: background-color 0.3s, color 0.3s;
        }
        .buttonexit.hover,
        .buttonexit:hover {
            background-color: #CD090F;
            color: #333;
        }
        .button.hover,
        .button:hover {
            background-color: #ffcc00;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="menu">
        <h1>MEDIEVAL</h1>
        <div class="buttons">
            <a href="medievalendless.html" class="button">TIMED</a>
            <a href="medievalendless.html" class="button">ENDLESS</a>
        </div>
        <div class="bottom-buttons">
            <a href="#" class="button">HOW TO PLAY</a>
            <a href="#" class="button">LEADERBOARD</a>
            <a href="../lockpicking.html" class="buttonexit">EXIT</a>
        </div>
    </div>
</body>
</html>
