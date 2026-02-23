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

  const pixels = generateMockPixels(200);
  createPixelGrid(container, {
    pixels,
    displaySize: 100,
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

  continueButton.addEventListener("click", () => {
    if (!selectedPixels.length) return;
    localStorage.setItem("selectedPixels", JSON.stringify(selectedPixels));
    window.location.href = "recap.php";
  });
});
