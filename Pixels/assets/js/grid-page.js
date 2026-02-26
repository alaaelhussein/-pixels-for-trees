import { createPixelGrid, generateMockPixels } from "./pixel-grid.js";

document.addEventListener("DOMContentLoaded", () => {
  const container = document.querySelector("#grid-container");
  const selectedCountEl = document.querySelector("#selected-count");
  const totalAmountEl = document.querySelector("#total-amount");
  const totalTreesEl = document.querySelector("#total-trees");
  const continueButton = document.querySelector("#continue-button");
  const emptyHint = document.querySelector("#empty-hint");

  if (!container || !selectedCountEl || !totalAmountEl || !totalTreesEl || !continueButton) return;

  let selectedPixels = [];

  const gridSize = 100;
  const pixels = generateMockPixels(500, gridSize);
  const grid = createPixelGrid(container, {
    pixels,
    gridSize,
    interactive: true,
    onSelectionChange: (selection) => {
      selectedPixels = selection;
      const totalAmount = selectedPixels.length * 5;

      selectedCountEl.textContent = String(selectedPixels.length);
      totalAmountEl.textContent = `${totalAmount} €`;
      totalTreesEl.textContent = String(selectedPixels.length);

      const isDisabled = selectedPixels.length === 0;
      continueButton.disabled = isDisabled;
      if (emptyHint) {
        emptyHint.classList.toggle("hidden", !isDisabled);
      }
    },
  });

  const resetBtn = document.querySelector("#reset-view-btn");
  if (resetBtn) {
    resetBtn.addEventListener("click", () => grid.resetView());
  }

  continueButton.addEventListener("click", () => {
    if (!selectedPixels.length) return;
    localStorage.setItem("selectedPixels", JSON.stringify(selectedPixels));
    window.location.href = "recap.php";
  });
});
