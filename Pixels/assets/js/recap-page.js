document.addEventListener("DOMContentLoaded", () => {
  const selectedPixelsRaw = localStorage.getItem("selectedPixels");
  const selectedPixels = selectedPixelsRaw ? JSON.parse(selectedPixelsRaw) : [];

  if (!Array.isArray(selectedPixels) || selectedPixels.length === 0) {
    window.location.href = "grid.php";
    return;
  }

  const pixelCountEl = document.querySelector("#pixel-count");
  const totalAmountEl = document.querySelector("#total-amount");
  const impactCountEl = document.querySelector("#impact-count");
  const previewContainer = document.querySelector("#pixel-preview");
  const confirmButton = document.querySelector("#confirm-button");

  if (pixelCountEl) pixelCountEl.textContent = String(selectedPixels.length);
  if (totalAmountEl) totalAmountEl.textContent = `${selectedPixels.length * 5} €`;
  if (impactCountEl) impactCountEl.textContent = String(selectedPixels.length);

  if (previewContainer) {
    previewContainer.innerHTML = "";
    selectedPixels.slice(0, 20).forEach((pixel) => {
      const item = document.createElement("div");
      item.className = "w-6 h-6 bg-orange-400 rounded border border-orange-500";
      item.title = `Pixel ${pixel.x},${pixel.y}`;
      previewContainer.appendChild(item);
    });

    if (selectedPixels.length > 20) {
      const extra = document.createElement("div");
      extra.className = "w-6 h-6 flex items-center justify-center text-xs text-gray-600";
      extra.textContent = `+${selectedPixels.length - 20}`;
      previewContainer.appendChild(extra);
    }
  }

  if (confirmButton) {
    confirmButton.addEventListener("click", () => {
      const totalAmount = selectedPixels.length * 5;
      alert(`Redirection vers Every.org pour finaliser le don de ${totalAmount}€`);
    });
  }
});
