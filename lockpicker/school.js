document.addEventListener('DOMContentLoaded', () => {
  const lock = document.getElementById('lock');
  const dial = document.getElementById('dial');
  const indicator = document.getElementById('indicator');
  const scoreElement = document.getElementById('score-value');
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
  });

  document.addEventListener('keyup', (event) => {
    if (event.code === 'Space') {
      isSpacePressed = false;
    }
  });

  updateDialPosition();
});
