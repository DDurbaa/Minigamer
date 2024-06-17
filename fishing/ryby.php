<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>fishing</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #87CEEB;
            margin: 0;
            padding: 0;
        }

        h1 {
            margin-top: 20px;
        }

        #game-area {
            position: relative;
            width: 80%;
            height: 500px;
            margin: 20px auto;
            border: 2px solid #000;
            background-color: #00BFFF;
            overflow: hidden;
        }

        #fish {
            position: absolute;
            width: 50px;
            height: 30px;
            background-image: url('fish.png');
            background-size: cover;
            cursor: pointer;
        }

        #score {
            font-size: 24px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Chyť rybu!</h1>
    
    <div id="game-area">
        
        <div id="fish"></div>
        
    </div>
    
    <div id="score">Skóre: 0</div>
    
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const fish = document.getElementById('fish');
            const gameArea = document.getElementById('game-area');
            
            let score = 0;
            let interval = 1000;
            let intervalId;

            function getRandomPosition(element) {
                
                const x = Math.floor(Math.random() * (gameArea.clientWidth - element.clientWidth));
                const y = Math.floor(Math.random() * (gameArea.clientHeight - element.clientHeight));
                return { x, y };
                
            }

            function moveFish() {
                
                const position = getRandomPosition(fish);
                fish.style.left = `${position.x}px`;
                fish.style.top = `${position.y}px`;
                
            }

            function updateScore() {
                
                score++;
                document.getElementById('score').innerText = `Skóre: ${score}`;
                increaseSpeed();
                
            }

            function increaseSpeed() {
                
                clearInterval(intervalId);
                
                interval = Math.max(200, 1000 - score * 50);
                intervalId = setInterval(moveFish, interval);
                
            }

            fish.addEventListener('click', () => {
                
                updateScore();
                moveFish();
                
            });

            moveFish();
            intervalId = setInterval(moveFish, interval);
            
        });
        
    </script>
    
</body>

</html>

ryby.php
Zobrazování položky fish.png.