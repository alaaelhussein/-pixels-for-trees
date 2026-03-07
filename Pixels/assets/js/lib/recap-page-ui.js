import { appConfig } from "./app-config.js";

export function getRecapNodes() {
  return {
    count: document.querySelector("#pixel-count"),
    amount: document.querySelector(
      "#total-amount"
    ),
    impact: document.querySelector(
      "#impact-count"
    ),
    preview: document.querySelector(
      "#pixel-preview"
    ),
    button: document.querySelector(
      "#confirm-button"
    ),
    pixels: document.querySelector(
      "#recap-pixels"
    ),
    message: document.querySelector(
      "#recap-message"
    ),
  };
}

function makePreviewItem(pixel) {
  const item = document.createElement("div");
  item.className = "w-6 h-6 rounded border border-gray-300";
  item.style.background = pixel.color || "#FB923C";
  item.title = `Pixel ${pixel.x},${pixel.y}`;
  return item;
}

function makeExtraItem(count) {
  const item = document.createElement("div");
  item.className = [
    "w-6 h-6 flex items-center",
    "justify-center text-xs",
    "text-gray-600",
  ].join(" ");
  item.textContent = `+${count}`;
  return item;
}

export function fillRecap(nodes, pixels) {
  const total =
    pixels.length * appConfig.pricePerPixel;

  if (nodes.count) {
    nodes.count.textContent = String(pixels.length);
  }

  if (nodes.amount) {
    nodes.amount.textContent = `${total} $`;
  }

  if (nodes.impact) {
    nodes.impact.textContent = String(pixels.length);
  }

  if (!nodes.preview) {
    return total;
  }

  nodes.preview.innerHTML = "";
  pixels.slice(0, 20).forEach((pixel) => {
    nodes.preview.appendChild(
      makePreviewItem(pixel)
    );
  });

  if (pixels.length > 20) {
    nodes.preview.appendChild(
      makeExtraItem(pixels.length - 20)
    );
  }

  return total;
}

export function fillRecapForm(nodes, data) {
  if (nodes.pixels) {
    nodes.pixels.value = JSON.stringify(data.pixels);
  }

  if (nodes.message) {
    nodes.message.value = data.message || "";
  }
}
