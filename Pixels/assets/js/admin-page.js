import { createPixelGrid, generateMockPixels } from "./pixel-grid.js";

document.addEventListener("DOMContentLoaded", () => {
  const container = document.querySelector("#admin-grid");
  if (!container) return;

  const pixels = generateMockPixels(300, 100);
  createPixelGrid(container, { pixels, gridSize: 100 });
});
