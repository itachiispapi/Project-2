let blankX = 300;
let blankY = 300;
let startTime = null;
let timerInterval = null;
let moveCount = 0;
let isShuffling = false;

const bgMusic = new Audio("music.mp3");
bgMusic.loop = true;  // loop background music

const winSound = new Audio("win.wav");

document.addEventListener("DOMContentLoaded", function () {
  const puzzleArea = document.getElementById("puzzlearea");
  const tiles = puzzleArea.getElementsByClassName("tile");

  const TILE_SIZE = 100;
  const GRID_SIZE = 4;

  for (let i = 0; i < tiles.length; i++) {
    const tile = tiles[i];

    const row = Math.floor(i / GRID_SIZE);
    const col = i % GRID_SIZE;

    const x = col * TILE_SIZE;
    const y = row * TILE_SIZE;

    tile.style.left = x + "px";
    tile.style.top = y + "px";
    tile.dataset.correctX = x;
    tile.dataset.correctY = y;
    tile.style.backgroundPosition = `-${x}px -${y}px`;

    // Hover highlight
    tile.addEventListener("mouseover", function () {
      if (isMovable(tile)) {
        tile.classList.add("movablepiece");
      }
    });

    tile.addEventListener("mouseout", function () {
      tile.classList.remove("movablepiece");
    });

    // Click to move
    tile.addEventListener("click", function () {
      if (isMovable(tile)) {
        moveTile(tile);
      }
    });
  }

  function isMovable(tile) {
    const x = parseInt(tile.style.left);
    const y = parseInt(tile.style.top);

    return (
      (x === blankX && Math.abs(y - blankY) === TILE_SIZE) ||
      (y === blankY && Math.abs(x - blankX) === TILE_SIZE)
    );
  }

  function moveTile(tile) {
    const tileX = parseInt(tile.style.left);
    const tileY = parseInt(tile.style.top);

    tile.style.left = blankX + "px";
    tile.style.top = blankY + "px";

    blankX = tileX;
    blankY = tileY;

    if (!isShuffling) {
      moveCount++;
      checkWin();
    }
  }

  function checkWin() {
  const tiles = document.getElementsByClassName("tile");

  for (let i = 0; i < tiles.length; i++) {
    const tile = tiles[i];
    const currentX = parseInt(tile.style.left);
    const currentY = parseInt(tile.style.top);
    const correctX = parseInt(tile.dataset.correctX);
    const correctY = parseInt(tile.dataset.correctY);

    if (currentX !== correctX || currentY !== correctY) {
      return; // not a win yet
    }
  }
    // Win and timer logic
    showWinMessage();
    clearInterval(timerInterval); // stop timer
  }
  
  function showWinMessage() {
  // music!! background song and win chime.
  bgMusic.pause();
  bgMusic.currentTime = 0;
  winSound.play();

  const puzzleArea = document.getElementById("puzzlearea");
  puzzleArea.style.boxShadow = "0 0 30px 10px gold";

  const message = document.createElement("div");
  message.id = "winMessage";
  message.textContent = "🎉 YOU WIN! 🎉";
  message.style.fontSize = "28pt";
  message.style.fontWeight = "bold";
  message.style.color = "#006600";
  message.style.textAlign = "center";
  message.style.marginTop = "20px";

  document.body.appendChild(message);

  sendGameStats();
}


  document.getElementById("shufflebutton").addEventListener("click", shufflePuzzle);

  // shuffles puzzle
  function shufflePuzzle() {
  const tiles = document.getElementsByClassName("tile");
  const existingWinMessage = document.getElementById("winMessage");
  if (existingWinMessage) {
    existingWinMessage.remove();
  }

  // Reset any win styling
  puzzleArea.style.boxShadow = "none";

  blankX = 300;
  blankY = 300;

  for (let i = 0; i < tiles.length; i++) {
  const tile = tiles[i];
  const x = parseInt(tile.dataset.correctX);
  const y = parseInt(tile.dataset.correctY);
  tile.style.left = x + "px";
  tile.style.top = y + "px";
  }

  // set timer using minutes and seconds
  moveCount = 0;
  startTime = Date.now();

  bgMusic.currentTime = 0;  // restart from beginning
  bgMusic.play();

    if (timerInterval) {
    clearInterval(timerInterval);
  }

  timerInterval = setInterval(() => {
    const elapsed = Math.floor((Date.now() - startTime) / 1000);
    const minutes = Math.floor(elapsed / 60);
    const seconds = elapsed % 60;
    const timerDisplay = document.getElementById("timer");
    if (timerDisplay) {
      timerDisplay.textContent = `⏱ ${minutes}m ${seconds < 10 ? '0' : ''}${seconds}s`;
    }
  }, 1000);

  isShuffling = true;

  let moves = 0;
  while (moves < 300) {
    const movable = [];

    // Find all currently movable tiles
    for (let i = 0; i < tiles.length; i++) {
      if (isMovable(tiles[i])) {
        movable.push(tiles[i]);
      }
    }

    if (movable.length > 0) {
      const randIndex = Math.floor(Math.random() * movable.length);
      moveTile(movable[randIndex]);
      moves++;
    }
  }
  // win check after shuffle
  isShuffling = false;
}

function sendGameStats() {
  const timeTaken = Math.floor((Date.now() - startTime) / 1000);

  fetch("save_game.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `time=${timeTaken}&moves=${moveCount}&win=1`
  })
  .then(res => res.text())
  .then(data => console.log("SAVE RESPONSE:", data))
  .catch(err => console.error("Error:", err));
}


});
