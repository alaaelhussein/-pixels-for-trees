import { createPixelGrid, generateMockPixels } from "./pixel-grid.js";

document.addEventListener("DOMContentLoaded", () => {
  const container = document.querySelector("#landing-grid");
  if (!container) return;

  const pixels = generateMockPixels(150, 100);
  createPixelGrid(container, { pixels, gridSize: 100 });
});
