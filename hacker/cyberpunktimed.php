<?php

include "../inc/loader.php";

$Login = new Login();
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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../mlogo.png">
    <title>Cyberpunk Hacking</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #00ff00;
            background-color: #1a1a1a;
            font-family: Arial, sans-serif;
            overflow: hidden;
            /* Disable scrolling */
            margin: 0;
            /* Remove default margin */
        }

        .container {
            display: grid;
            grid-template-areas:
                "matrix buffer"
                "sequence buffer";
            gap: 40px;
        }

        .code-matrix {
            grid-area: matrix;
            display: grid;
            grid-template-columns: repeat(4, 100px);
            gap: 10px;
        }

        .cell {
            width: 100px;
            height: 100px;
            background-color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            border: 2px solid transparent;
            font-size: 24px;
        }

        .cell.selectable:hover {
            background-color: #555;
            border: 2px solid #00ff00;
        }

        .cell.not-selectable:hover {
            border: 2px solid red;
        }

        .highlight {
            background-color: #555;
        }

        .buffer {
            grid-area: buffer;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .buffer-cell {
            width: 100px;
            height: 100px;
            background-color: #444;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
        }

        .sequence {
            grid-area: sequence;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .sequence-required {
            background-color: #222;
            padding: 20px;
            font-size: 24px;
        }

        .sequence-completed {
            background-color: #00ff00;
            color: #0f0f0f;
        }

        .score {
            position: fixed;
            /* Change to fixed */
            bottom: 0;
            /* Stick to the bottom */
            left: 50%;
            transform: translateX(-50%);
            /* Center horizontally */
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
    <a href="cyberpunkmenu.php" class="buttonexit">EXIT</a>
    <div class="container">
        <div id="timer">60</div>
        <div class="code-matrix">
            <!-- Místa pro buňky s kódy -->
        </div>
        <div class="buffer">
            <div class="buffer-cell"></div>
            <div class="buffer-cell"></div>
            <div class="buffer-cell"></div>
            <div class="buffer-cell"></div>
            <div class="buffer-cell"></div>
            <div class="buffer-cell"></div>
        </div>
        <div class="sequence">
            <!-- Místa pro požadované sekvence -->
        </div>
    </div>
    <div id="tooltip">
        <div class="tooltip-item">Restart: <span class="tooltip-key">R</span></div>
        <div class="tooltip-item">Choose key: <span class="tooltip-key">Left Click</span></div>
    </div>
    <div class="score" id="score">0</div>
    <form action="" method="post" id="popupForm">
        <div id="popup" style="display: none;">
            <p id="popup-message"><?php echo $popupMsg ?></p>
            <input type="hidden" id="hidden-score" name="score" value="">
            <button type="submit" id="popup-button" name="update_score">X</button>
        </div>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const codeMatrix = document.querySelector('.code-matrix');
            const sequenceContainer = document.querySelector('.sequence');
            const bufferCells = document.querySelectorAll('.buffer-cell');
            const scoreElement = document.getElementById('score');
            const codes = ['BD', '1C', '55', 'E9'];
            let bufferIndex = 0;
            let isHorizontal = true;
            let currentRow, currentCol;
            let isFirstSelection = true;
            let currentSequence = [];
            let sequences = [];
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
            startTimer();

            function shuffle(array) {
                for (let i = array.length - 1; i > 0; i--) {
                    const j = Math.floor(Math.random() * (i + 1));
                    [array[i], array[j]] = [array[j], array[i]];
                }
            }

            function generateRandomMatrix() {
                const grid = Array.from({ length: 4 }, () => Array(4).fill(null));
                const counts = { BD: 0, '1C': 0, '55': 0, E9: 0 };
                const maxCount = 4;
                const rowMaxDup = 1;
                const colMaxDup = 1;

                for (let row = 0; row < 4; row++) {
                    for (let col = 0; col < 4; col++) {
                        const availableCodes = codes.filter(code => counts[code] < maxCount &&
                            grid[row].filter(c => c === code).length <= rowMaxDup &&
                            grid.map(r => r[col]).filter(c => c === code).length <= colMaxDup);

                        if (availableCodes.length === 0) {
                            throw new Error('Failed to generate valid grid');
                        }

                        const code = availableCodes[Math.floor(Math.random() * availableCodes.length)];
                        grid[row][col] = code;
                        counts[code]++;
                    }
                }

                return grid.flat();
            }

            function generateRandomSequences() {
                const sequences = [];
                for (let i = 0; i < 3; i++) {
                    const seqLength = Math.floor(Math.random() * 2) + 2; // Délka sekvence mezi 2 a 3
                    const sequence = [];
                    for (let j = 0; j < seqLength; j++) {
                        sequence.push(codes[Math.floor(Math.random() * codes.length)]);
                    }
                    sequences.push(sequence);
                }
                return sequences;
            }

            function createMatrix(matrix) {
                codeMatrix.innerHTML = '';
                matrix.forEach((code, index) => {
                    const cell = document.createElement('div');
                    cell.classList.add('cell');
                    cell.setAttribute('data-value', code);
                    cell.textContent = code;
                    codeMatrix.appendChild(cell);
                });
            }

            function createSequences(sequences) {
                sequenceContainer.innerHTML = '';
                sequences.forEach((sequence, index) => {
                    const sequenceDiv = document.createElement('div');
                    sequenceDiv.classList.add('sequence-required');
                    sequenceDiv.setAttribute('data-sequence', sequence.join(' '));
                    sequenceDiv.setAttribute('data-index', index);
                    sequenceDiv.textContent = sequence.join(' ');
                    sequenceContainer.appendChild(sequenceDiv);
                });
            }

            function checkSequences() {
                const sequencesDivs = document.querySelectorAll('.sequence-required');
                sequencesDivs.forEach(sequenceDiv => {
                    const sequence = sequenceDiv.getAttribute('data-sequence').split(' ');
                    if (currentSequence.join(' ').includes(sequence.join(' '))) {
                        if (!sequenceDiv.classList.contains('sequence-completed')) {
                            sequenceDiv.classList.add('sequence-completed');
                            score++;
                            scoreElement.textContent = score;
                        }
                    }
                });

                // Zkontrolovat, zda jsou všechny sekvence splněny
                const allCompleted = Array.from(sequencesDivs).every(sequenceDiv =>
                    sequenceDiv.classList.contains('sequence-completed')
                );

                // Restartovat hru, pokud jsou všechny sekvence splněny nebo je buffer plný
                if (allCompleted || bufferIndex >= bufferCells.length) {
                    setTimeout(resetGame, 1000); // Krátká prodleva před restartem
                }
            }

            function updateSelectableCells() {
                const cells = document.querySelectorAll('.cell');
                cells.forEach(cell => cell.classList.remove('selectable', 'highlight', 'not-selectable'));

                if (isHorizontal) {
                    for (let col = 0; col < 4; col++) {
                        const cell = document.querySelector(`.code-matrix .cell:nth-child(${currentRow * 4 + col + 1})`);
                        cell.classList.add('selectable', 'highlight');
                    }
                } else {
                    for (let row = 0; row < 4; row++) {
                        const cell = document.querySelector(`.code-matrix .cell:nth-child(${row * 4 + currentCol + 1})`);
                        cell.classList.add('selectable', 'highlight');
                    }
                }
            }

            function highlightRowOrColumn(row, col) {
                const cells = document.querySelectorAll('.cell');
                cells.forEach(cell => cell.classList.remove('highlight'));
                if (isHorizontal) {
                    for (let c = 0; c < 4; c++) {
                        const cell = document.querySelector(`.code-matrix .cell:nth-child(${row * 4 + c + 1})`);
                        cell.classList.add('highlight');
                    }
                } else {
                    for (let r = 0; r < 4; r++) {
                        const cell = document.querySelector(`.code-matrix .cell:nth-child(${r * 4 + col + 1})`);
                        cell.classList.add('highlight');
                    }
                }
            }

            function resetGame() {
                bufferIndex = 0;
                isHorizontal = true;
                isFirstSelection = true;
                currentSequence = [];
                sequences = generateRandomSequences();
                createMatrix(generateRandomMatrix());
                createSequences(sequences);
                const cells = document.querySelectorAll('.cell');
                cells.forEach(cell => {
                    cell.classList.remove('selected', 'highlight', 'not-selectable');
                    cell.classList.add('selectable');
                });
                bufferCells.forEach(bufferCell => bufferCell.innerText = '');

                // Znovu připojit event listenery
                attachEventListeners();
            }

            function resetAll() {
                score = 0;
                scoreElement.textContent = score;
                timeLeft = 60;
                timerElement.textContent = timeLeft;
                resetGame();
            }

            function attachEventListeners() {
                const cells = document.querySelectorAll('.cell');

                cells.forEach((cell, index) => {
                    const row = Math.floor(index / 4);
                    const col = index % 4;

                    cell.addEventListener('mouseover', () => {
                        if (isFirstSelection) {
                            highlightRowOrColumn(row, col);
                        } else if (!cell.classList.contains('selectable')) {
                            cell.classList.add('not-selectable');
                        }
                    });

                    cell.addEventListener('mouseout', () => {
                        if (isFirstSelection) {
                            cells.forEach(cell => cell.classList.remove('highlight'));
                        } else {
                            cell.classList.remove('not-selectable');
                        }
                    });

                    cell.addEventListener('click', () => {
                        if (cell.classList.contains('selectable') || isFirstSelection) {
                            bufferCells[bufferIndex].innerText = cell.dataset.value;
                            bufferIndex++;
                            isHorizontal = !isHorizontal;
                            currentRow = row;
                            currentCol = col;

                            cell.classList.add('selected');

                            currentSequence.push(cell.dataset.value);
                            checkSequences();

                            isFirstSelection = false;

                            if (bufferIndex < bufferCells.length) {
                                updateSelectableCells();
                            }
                        }
                    });
                });

                // Umožnit první výběr na jakémkoli řádku nebo sloupci
                cells.forEach(cell => cell.classList.add('selectable'));
            }

            function init() {
                resetGame();

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'r') {
                        resetAll();
                    } else if (event.key === "Escape") {
                if (popupActive) {
                    popupActive = false;
                    const popup = document.getElementById("popup");
                    popup.style.display = "none";
                    form.submit();
                    location.reload();
                }
            }
                });
            }

            init();
        });

    </script>
</body>

</html>