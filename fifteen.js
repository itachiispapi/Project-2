document.addEventListener("DOMContentLoaded", function () {
  const puzzleArea = document.getElementById("puzzlearea");
  const tiles = puzzleArea.getElementsByClassName("tile");

  const TILE_SIZE = 100; // Including border
  const GRID_SIZE = 4;   // 4x4 puzzle

  for (let i = 0; i < tiles.length; i++) {
    const tile = tiles[i];

    const row = Math.floor(i / GRID_SIZE);
    const col = i % GRID_SIZE;

    const x = col * TILE_SIZE;
    const y = row * TILE_SIZE;

    // Set position
    tile.style.left = x + "px";
    tile.style.top = y + "px";

    // Set background offset
    tile.style.backgroundPosition = `-${x}px -${y}px`;
  }
});
