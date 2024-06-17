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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../mlogo.png">
    <title>Cyberpunk Tutorial</title>
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
            overflow: hidden; /* Disable scrolling */
            margin: 0; /* Remove default margin */
            padding: 20px;
        }

        .content {
            max-width: 800px;
            text-align: center;
        }

        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 20px 40px;
            border: 2px solid #00ff00;
            border-radius: 5px;
            text-decoration: none;
            color: #00ff00;
            font-size: 1.5em;
            transition: background-color 0.3s, color 0.3s;
        }

        .button:hover {
            background-color: #00ff00;
            color: #1a1a1a;
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

        .buffer-cell-container {
            display: flex;
            align-items: center;
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

        .buffer-label {
            margin-left: 10px;
            font-size: 24px;
            color: white;
            visibility: hidden; /* Hide the label initially */
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
            bottom: 0;
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

        .tutorial-step {
            margin-bottom: 20px;
            font-size: 1.4em;
        }

        .highlighted {
            outline: 3px solid #00ff00;
        }

        .end-message {
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #00ff00;
        }

        .end-message a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            border: 2px solid #00ff00;
            border-radius: 5px;
            text-decoration: none;
            color: #00ff00;
            font-size: 20px;
            background-color: #1a1a1a;
            cursor: pointer;
        }

        .end-message a:hover {
            background-color: #00ff00;
            color: #1a1a1a;
        }
    </style>
</head>

<body>
    <a href="cyberpunkmenu.php" class="buttonexit">EXIT</a>
    <div class="content">
        <div class="tutorial-step" id="tutorial-step">
            Select the codes in the grid to match the required sequence.
            <br> For example, start with the first sequence and click on code 55.
        </div>
        <div class="container" id="game-container">
            <div class="code-matrix">
                <!-- Predefined tutorial cells -->
                <div class="cell" data-value="55">55</div>
                <div class="cell" data-value="1C">1C</div>
                <div class="cell" data-value="BD">BD</div>
                <div class="cell" data-value="E9">E9</div>
                <div class="cell" data-value="1C">1C</div>
                <div class="cell" data-value="55">55</div>
                <div class="cell" data-value="E9">E9</div>
                <div class="cell" data-value="BD">BD</div>
                <div class="cell" data-value="E9">E9</div>
                <div class="cell" data-value="BD">BD</div>
                <div class="cell" data-value="1C">1C</div>
                <div class="cell" data-value="55">55</div>
                <div class="cell" data-value="BD">BD</div>
                <div class="cell" data-value="E9">E9</div>
                <div class="cell" data-value="55">55</div>
                <div class="cell" data-value="1C">1C</div>
            </div>
            <div class="buffer">
                <div class="buffer-cell-container">
                    <div class="buffer-cell"></div>
                    <div class="buffer-label" id="buffer-label">Your chosen code</div>
                </div>
                <div class="buffer-cell-container">
                    <div class="buffer-cell"></div>
                </div>
                <div class="buffer-cell-container">
                    <div class="buffer-cell"></div>
                </div>
                <div class="buffer-cell-container">
                    <div class="buffer-cell"></div>
                </div>
                <div class="buffer-cell-container">
                    <div class="buffer-cell"></div>
                </div>
                <div class="buffer-cell-container">
                    <div class="buffer-cell"></div>
                </div>
            </div>
            <div class="sequence">
                <!-- Predefined sequences -->
                <div class="sequence-required" data-sequence="55 1C BD">55 1C BD</div>
                <div class="sequence-required" data-sequence="E9 1C">E9 1C</div>
                <div class="sequence-required" data-sequence="1C 55">1C 55</div>
            </div>
        </div>
        <div class="end-message" id="end-message">
            <a href="cyberpunkmenu.php" class="button">Back to Menu</a>
            <a id="retry-button" href="#" class="button">Try Again</a>
        </div>
    </div>
    <div id="tooltip">
        <div class="tooltip-item">Restart: <span class="tooltip-key">R</span></div>
        <div class="tooltip-item">Choose key: <span class="tooltip-key">Left Click</span></div>
    </div>
    <div class="score" id="score">0</div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const codeMatrix = document.querySelector('.code-matrix');
            const sequenceContainer = document.querySelector('.sequence');
            const bufferCells = document.querySelectorAll('.buffer-cell');
            const bufferLabel = document.getElementById('buffer-label');
            const tutorialStep = document.getElementById('tutorial-step');
            const scoreElement = document.getElementById('score');
            const endMessage = document.getElementById('end-message');
            const endText = document.getElementById('end-text');
            const retryButton = document.getElementById('retry-button');
            const tooltip = document.getElementById('tooltip');
            const gameContainer = document.getElementById('game-container');
            let bufferIndex = 0;
            let isHorizontal = true;
            let currentRow, currentCol;
            let isFirstSelection = true;
            let currentSequence = [];
            let score = 0;
            let secondSequenceCompleted = false;
            let anySequenceCompleted = false;
            let tutorialEnded = false;

            function checkSequences() {
                if (tutorialEnded) return;

                const sequencesDivs = document.querySelectorAll('.sequence-required');
                let allCompleted = true;
                anySequenceCompleted = false;

                sequencesDivs.forEach((sequenceDiv, index) => {
                    const sequence = sequenceDiv.getAttribute('data-sequence').split(' ');
                    if (currentSequence.join(' ').includes(sequence.join(' '))) {
                        if (!sequenceDiv.classList.contains('sequence-completed')) {
                            sequenceDiv.classList.add('sequence-completed');
                            score++;
                            scoreElement.textContent = score;
                            anySequenceCompleted = true;
                            if (index === 1) {
                                secondSequenceCompleted = true;
                            }
                        }
                    }

                    if (!sequenceDiv.classList.contains('sequence-completed')) {
                        allCompleted = false;
                    }
                });

                if (bufferIndex >= bufferCells.length) {
                    if (allCompleted) {
                        endTutorial('Congratulations! You have completed the tutorial.');
                    } else {
                        endTutorial('You didn\'t complete all the sequences. Please try again.');
                    }
                }

                if (secondSequenceCompleted) {
                    tutorialStep.innerHTML = 'For each completed sequence, you gain 1 point.';
                    secondSequenceCompleted = false;
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

            function resetTutorial() {
                bufferIndex = 0;
                isHorizontal = true;
                isFirstSelection = true;
                currentSequence = [];
                const cells = document.querySelectorAll('.cell');
                cells.forEach(cell => {
                    cell.classList.remove('selected', 'highlight', 'not-selectable');
                    cell.classList.add('selectable');
                });
                bufferCells.forEach(bufferCell => bufferCell.innerText = '');
                document.querySelectorAll('.sequence-required').forEach(sequenceDiv => {
                    sequenceDiv.classList.remove('sequence-completed');
                });
                attachEventListeners();
                tutorialStep.innerHTML = 'Select the codes in the grid to match the required sequence.<br>For example, start with the first sequence and click on code 55.';
                bufferLabel.style.visibility = 'hidden';
                scoreElement.style.display = 'block';
                tooltip.style.display = 'block';
                gameContainer.style.display = 'grid';
                endMessage.style.display = 'none';
                anySequenceCompleted = false;
                tutorialEnded = false;
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
                            bufferLabel.style.visibility = 'visible'; // Show the label after the first click
                            if (bufferIndex === 3) {
                                tutorialStep.innerHTML = 'Remember, you can only choose 6 codes so plan carefully';
                            }
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

                // Allow first selection on any row or column
                cells.forEach(cell => cell.classList.add('selectable'));
            }

            function endTutorial(message) {
                if (tutorialEnded) return;
                tutorialEnded = true;
                tutorialStep.innerHTML = message;
                scoreElement.style.display = 'none';
                tooltip.style.display = 'none';
                gameContainer.style.display = 'none';
                endMessage.style.display = 'flex';
                endText.innerHTML = message;
            }

            retryButton.addEventListener('click', (event) => {
                event.preventDefault();
                location.reload();
            });

            function init() {
                resetTutorial();

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'r') {
                        resetTutorial();
                    }
                });
            }

            init();
        });
    </script>
</body>
</html>
