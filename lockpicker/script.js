const lockCanvas = document.getElementById('lockCanvas');
const lockCtx = lockCanvas.getContext('2d');
const cursorCanvas = document.getElementById('cursorCanvas');
const cursorCtx = cursorCanvas.getContext('2d');
let rotation = 0; // Úhel rotace
let imgRotation = 0; // Úhel rotace obrázku
let isRotating = false; // Indikátor, zda se má rotovat
let isInSweetSpot = false; // Indikátor, zda je kurzor v sladkém místě
const sweetSpotRadius = 30; // Větší velikost 'sladkého místa'
let circleRadius = Math.random() < 0.5 ? 240 : 130; // Poloměr kruhu
let sweetSpotAngle = Math.random() * Math.PI * 2; // Náhodný úhel pro umístění sladkého místa
const initialImgRotation = 0; // Počáteční úhel rotace obrázku
let points = 0; // Počet bodů
let startTime = null; // Počáteční čas
let lastTimeInSweetSpot = null; // Poslední čas, kdy byl kurzor ve "sweet spotu"
let cursorX = 0; // Aktuální pozice kurzoru X
let cursorY = 0; // Aktuální pozice kurzoru Y
let initialSweetSpotAngle = sweetSpotAngle; // Počáteční úhel sladkého místa
let initialCircleRadius = circleRadius; // Počáteční poloměr kruhu

// Přizpůsobení velikosti canvasu pro kurzor na celou obrazovku
cursorCanvas.width = window.innerWidth;
cursorCanvas.height = window.innerHeight;

// Funkce pro reset hry při úspěšném lockpicknutí
function resetGame() {
    rotation = 0;
    imgRotation = initialImgRotation;
    sweetSpotAngle = Math.random() * Math.PI * 2; // Nastavení nového náhodného úhlu pro sladké místo
    circleRadius = Math.random() < 0.5 ? 240 : 130; // Náhodně vybereme poloměr kruhu pouze mezi 240 a 130
    initialSweetSpotAngle = sweetSpotAngle; // Aktualizace počátečního úhlu sladkého místa
    initialCircleRadius = circleRadius; // Aktualizace počátečního poloměru kruhu
    startTime = null;
    lastTimeInSweetSpot = null;
    isRotating = false; // Zastavíme rotaci po úspěšném lockpicknutí
}

// Funkce pro reset hry při neúspěchu
function resetPosition() {
    rotation = 0;
    imgRotation = initialImgRotation;
    sweetSpotAngle = initialSweetSpotAngle; // Reset na počáteční úhel sladkého místa
    circleRadius = initialCircleRadius; // Reset na počáteční poloměr kruhu
    startTime = null;
    lastTimeInSweetSpot = null;
    isRotating = false; // Zastavíme rotaci po neúspěchu
}

// Přidáme funkci pro resetování skóre
function resetScore() {
    points = 0;
    document.getElementById('score').innerText = points;
}

// Přidáme event listener pro klávesu "R"
document.body.addEventListener('keydown', (e) => {
    if (e.code === 'KeyR') {
        resetGame(); // Resetuje hru
        resetScore(); // Resetuje skóre
    }
});

const img = new Image();
img.src = 'wheel.png';

// Funkce pro vykreslení zámku
function drawLock() {
    lockCtx.clearRect(0, 0, lockCanvas.width, lockCanvas.height);

    // Vykreslení a rotace obrázku zámku
    lockCtx.save();
    lockCtx.translate(lockCanvas.width / 2, lockCanvas.height / 2);
    lockCtx.rotate(imgRotation);
    lockCtx.drawImage(img, -img.width / 2, -img.height / 2);
    lockCtx.restore();

    // Vykreslení 'sladkého místa' v aktuálním úhlu rotace
    lockCtx.save();
    lockCtx.translate(lockCanvas.width / 2, lockCanvas.height / 2);
    lockCtx.rotate(rotation);
    lockCtx.beginPath();
    lockCtx.fillStyle = 'rgba(255, 0, 0, 0.000001)'; // Průhledná barva
    lockCtx.arc(circleRadius * Math.cos(sweetSpotAngle), circleRadius * Math.sin(sweetSpotAngle), sweetSpotRadius, 0, Math.PI * 2);
    lockCtx.fill();
    lockCtx.restore();

    // Vykreslení skóre
    document.getElementById('score').innerText = points;

    // Vykreslení vlastního kurzoru
    drawCustomCursor(cursorX, cursorY, isInSweetSpot ? 'green' : 'red');
}

// Funkce pro získání aktuální pozice 'sladkého místa' po rotaci
function getSweetSpotPosition() {
    return {
        x: (lockCanvas.width / 2) + circleRadius * Math.cos(sweetSpotAngle + rotation),
        y: (lockCanvas.height / 2) + circleRadius * Math.sin(sweetSpotAngle + rotation)
    };
}

// Funkce pro kontrolu, zda je kurzor v 'sladkém místě'
function checkSweetSpot(cursorX, cursorY) {
    const sweetSpotPos = getSweetSpotPosition();
    const dx = cursorX - sweetSpotPos.x;
    const dy = cursorY - sweetSpotPos.y;
    return (dx * dx + dy * dy) < sweetSpotRadius * sweetSpotRadius;
}

// Funkce pro simulaci pohybu kurzoru
function moveCursor() {
    cursorX += 0.000001; // Minimální posun kurzoru
    cursorY += 0.000001; // Minimální posun kurzoru

    // Aktualizujeme, zda je kurzor v 'sladkém místě'
    const rect = lockCanvas.getBoundingClientRect();
    const canvasX = cursorX - rect.left;
    const canvasY = cursorY - rect.top;
    isInSweetSpot = checkSweetSpot(canvasX, canvasY);

    // Vykreslíme vlastní kurzor
    drawCustomCursor(cursorX, cursorY, isInSweetSpot ? 'green' : 'red');

    // Aktualizujeme poslední čas, kdy byl kurzor ve "sweet spotu"
    if (isInSweetSpot) {
        lastTimeInSweetSpot = Date.now();
    }
}

// Event listener pro pohyb myši
document.addEventListener('mousemove', (event) => {
    cursorX = event.clientX;
    cursorY = event.clientY;
});

// Funkce pro vykreslení vlastního kurzoru
function drawCustomCursor(x, y, color) {
    const cursorSize = 30; // Zvětšená velikost kurzoru
    cursorCtx.clearRect(0, 0, cursorCanvas.width, cursorCanvas.height); // Vyčistíme předchozí kurzor

    cursorCtx.save();
    cursorCtx.beginPath();
    cursorCtx.arc(x, y, cursorSize, 0, Math.PI * 2);
    cursorCtx.fillStyle = color;
    cursorCtx.fill();
    cursorCtx.restore();
}

// Funkce pro aktualizaci hry
function update() {
    // Otáčení se provede pouze pokud je aktivní isRotating a kurzor je v 'sladkém místě'
    if (isRotating && isInSweetSpot) {
        if (!startTime) {
            startTime = Date.now();
        }

        rotation -= 0.035; // Změna směru rotace na proti směru hodinových ručiček
        imgRotation -= 0.035; // Rychlejší rotace obrázku

        if (rotation < -Math.PI * 2) {
            rotation = 0;
        }

        if (imgRotation < -Math.PI * 2) {
            imgRotation = 0;
        }

        if (Date.now() - startTime >= 2500) { // 2,5 sekundy
            points++;
            console.log('Points:', points);
            resetGame(); // Reset hry po úspěšném lockpicknutí
        }
    } else {
        if (!isInSweetSpot && lastTimeInSweetSpot && Date.now() - lastTimeInSweetSpot >= 200) { 
            startTime = null; 
            lastTimeInSweetSpot = null; 
            resetPosition(); 
        }
    }
    drawLock();
}

// Event listener pro stisknutí a uvolnění mezerníku
document.body.addEventListener('keydown', (e) => {
    if (e.code === 'Space') {
        isRotating = true;
    } else if (e.code === 'KeyR') {
        resetGame(); // Resetuje hru
        resetScore(); // Resetuje skóre
    }
});

document.body.addEventListener('keyup', (e) => {
    if (e.code === 'Space') {
        isRotating = false;
    }
});

// Spuštění herní smyčky
setInterval(update, 1000 / 60);

// Přizpůsobení velikosti canvasu při změně velikosti okna
window.addEventListener('resize', () => {
    cursorCanvas.width = window.innerWidth;
    cursorCanvas.height = window.innerHeight;
});

// Pravidelný pohyb kurzoru
setInterval(moveCursor, 1000 / 60);