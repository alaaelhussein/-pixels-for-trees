import { appConfig } from "./app-config.js";

export function getGridNodes() {
  return {
    container: document.querySelector(
      "#grid-container"
    ),
    drawer: document.querySelector(
      "#cell-drawer"
    ),
    infoButton: document.querySelector(
      "#info-button"
    ),
    infoPanel: document.querySelector(
      "#grid-info-panel"
    ),
    active: document.querySelector(
      "#active-cell"
    ),
    meta: document.querySelector(
      "#drawer-meta"
    ),
    status: document.querySelector(
      "#drawer-status"
    ),
    note: document.querySelector(
      "#drawer-note"
    ),
    owner: document.querySelector(
      "#drawer-owner"
    ),
    preview: document.querySelector(
      "#drawer-color-preview"
    ),
    editor: document.querySelector(
      "#drawer-editor"
    ),
    locked: document.querySelector(
      "#drawer-locked"
    ),
    lockedText: document.querySelector(
      "#drawer-locked-text"
    ),
    count: document.querySelector(
      "#selected-count"
    ),
    amount: document.querySelector(
      "#total-amount"
    ),
    trees: document.querySelector(
      "#total-trees"
    ),
    button: document.querySelector(
      "#continue-button"
    ),
    reset: document.querySelector(
      "#reset-view-btn"
    ),
    zoomIn: document.querySelector(
      "#zoom-in-btn"
    ),
    zoomOut: document.querySelector(
      "#zoom-out-btn"
    ),
    jumpX: document.querySelector("#jump-x"),
    jumpY: document.querySelector("#jump-y"),
    jump: document.querySelector("#jump-button"),
    color: null, // native picker removed; swatches used instead
    colorText: document.querySelector(
      "#pixel-color-text"
    ),
    message: document.querySelector(
      "#pixel-message"
    ),
    remove: document.querySelector(
      "#remove-active-button"
    ),
    clear: document.querySelector(
      "#clear-selection-button"
    ),
    close: document.querySelector(
      "#close-drawer-button"
    ),
  };
}

export function isGridReady(nodes) {
  return Boolean(
    nodes.container &&
      nodes.drawer &&
      nodes.count &&
      nodes.amount &&
      nodes.trees &&
      nodes.button
  );
}

export function setPanelColor(
  nodes,
  color
) {
  if (nodes.preview) {
    nodes.preview.style.background = color;
  }

  if (nodes.colorText) {
    nodes.colorText.value = color;
  }

  // highlight matching swatch
  document.querySelectorAll(".color-swatch").forEach((btn) => {
    const match = btn.dataset.color === color;
    btn.style.outline = match ? "3px solid #1E293B" : "none";
    btn.style.outlineOffset = "2px";
  });
}

export function toggleInfo(nodes) {
  if (!nodes.infoPanel) {
    return;
  }

  nodes.infoPanel.classList.toggle("hidden");
}

function hideDrawer(nodes) {
  nodes.drawer.classList.add("hidden");
}

function showDrawer(nodes) {
  nodes.drawer.classList.remove("hidden");
}

function setStatus(nodes, text, className) {
  nodes.status.textContent = text;
  nodes.status.className = className;
}

function formatReserveTime(value) {
  const date = new Date(value);

  if (Number.isNaN(date.getTime())) {
    return value;
  }

  return date.toLocaleString();
}

function showSelected(nodes, item, count) {
  setStatus(
    nodes,
    "Selected",
    "rounded-full bg-orange-100 px-2 py-1 text-xs font-semibold text-orange-700"
  );
  nodes.active.textContent = `Cell ${item.x}, ${item.y}`;
  nodes.meta.textContent = count > 1
    ? `${count} cells in your draft`
    : "1 cell in your draft";
  nodes.note.textContent = "Pick a color for this cell.";
  nodes.owner.textContent = "Reservation starts only after checkout.";
  nodes.editor.classList.remove("hidden");
  nodes.locked.classList.add("hidden");
  nodes.remove.classList.remove("hidden");
  setPanelColor(nodes, item.color || "#FB923C");
}

function showReserved(nodes, item) {
  setStatus(
    nodes,
    "Reserved",
    "rounded-full bg-slate-200 px-2 py-1 text-xs font-semibold text-slate-700"
  );
  nodes.active.textContent = `Cell ${item.x}, ${item.y}`;
  nodes.meta.textContent = "Already locked by another selection.";
  nodes.note.textContent = item.message || "This cell is not available.";
  nodes.owner.textContent = item.username
    ? `By ${item.username}`
    : "Reserved on the wall";
  nodes.editor.classList.add("hidden");
  nodes.locked.classList.remove("hidden");
  nodes.remove.classList.add("hidden");
  nodes.lockedText.textContent = item.reservedUntil
    ? `Reservation ends at ${formatReserveTime(item.reservedUntil)}.`
    : "This cell has already been paid for.";
  setPanelColor(nodes, "#94A3B8");
}

function showConfirmed(nodes, item) {
  setStatus(
    nodes,
    "Funded",
    "rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700"
  );
  nodes.active.textContent = `Cell ${item.x}, ${item.y}`;
  nodes.meta.textContent = "Already funded on the wall.";
  nodes.note.textContent = item.message || "This cell has already been paid for.";
  nodes.owner.textContent = item.username
    ? `By ${item.username}`
    : "Funded on the wall";
  nodes.editor.classList.add("hidden");
  nodes.locked.classList.remove("hidden");
  nodes.remove.classList.add("hidden");
  nodes.lockedText.textContent = item.confirmedAt
    ? `Confirmed at ${formatReserveTime(item.confirmedAt)}.`
    : "This cell has already been paid for.";
  setPanelColor(nodes, item.color || "#22C55E");
}

export function setGridSummary(
  nodes,
  pixels,
  activeItem
) {
  const total =
    pixels.length * appConfig.pricePerPixel;

  nodes.count.textContent = String(pixels.length);
  nodes.amount.textContent = `${total} $`;
  nodes.trees.textContent = String(pixels.length);
  nodes.button.disabled = pixels.length === 0;

  if (!activeItem) {
    hideDrawer(nodes);
    return;
  }

  showDrawer(nodes);

  if (activeItem.kind === "reserved") {
    showReserved(nodes, activeItem);
    return;
  }

  if (activeItem.kind === "confirmed") {
    showConfirmed(nodes, activeItem);
    return;
  }

  showSelected(nodes, activeItem, pixels.length);
}
