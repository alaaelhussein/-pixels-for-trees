import { appConfig } from "./lib/app-config.js";
import {
  getGridNodes,
  isGridReady,
  setPanelColor,
  toggleInfo,
  setGridSummary,
} from "./lib/grid-page-ui.js";
import { readWallData } from "./lib/page-wall-data.js";
import { saveSelection } from "./lib/selection-store.js";
import { createPixelGrid } from "./pixel-grid.js";

function readAuthState() {
  const node = document.querySelector("#grid-auth");

  if (!node?.textContent) {
    return {
      loggedIn: false,
      nextUrl: "recap.php",
      loginUrl: "login.php?next=recap.php",
    };
  }

  try {
    return JSON.parse(node.textContent);
  } catch (error) {
    return {
      loggedIn: false,
      nextUrl: "recap.php",
      loginUrl: "login.php?next=recap.php",
    };
  }
}

function normalizeColor(value) {
  const text = String(value || "")
    .trim()
    .toUpperCase();

  if (/^#[0-9A-F]{6}$/.test(text)) {
    return text;
  }

  if (/^[0-9A-F]{6}$/.test(text)) {
    return `#${text}`;
  }

  return "";
}

function buildExportFileName() {
  const now = new Date();
  const yyyy = now.getFullYear();
  const mm = String(now.getMonth() + 1).padStart(2, "0");
  const dd = String(now.getDate()).padStart(2, "0");
  const hh = String(now.getHours()).padStart(2, "0");
  const min = String(now.getMinutes()).padStart(2, "0");
  const ss = String(now.getSeconds()).padStart(2, "0");

  return `pixels-grid-${yyyy}${mm}${dd}-${hh}${min}${ss}.png`;
}

function downloadUrl(url, fileName) {
  const link = document.createElement("a");
  link.href = url;
  link.download = fileName;
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

function exportGridAsPng(nodes) {
  const canvas = nodes.container?.querySelector("canvas");

  if (!canvas) {
    return false;
  }

  const fileName = buildExportFileName();

  if (typeof canvas.toBlob === "function") {
    canvas.toBlob((blob) => {
      if (!blob) {
        return;
      }

      const url = URL.createObjectURL(blob);
      downloadUrl(url, fileName);
      URL.revokeObjectURL(url);
    }, "image/png");

    return true;
  }

  const dataUrl = canvas.toDataURL("image/png");
  downloadUrl(dataUrl, fileName);
  return true;
}

const drawerPositionKey = "pixels.grid.drawer.position.v1";
let lastClickPos = null;

function clamp(value, min, max) {
  return Math.min(Math.max(value, min), max);
}

function initDrawerDrag(nodes) {
  const drawer = nodes.drawer;
  const handle = nodes.dragHandle;

  if (!drawer || !handle || typeof window === "undefined") {
    return;
  }

  let dragging = false;
  let pendingDrag = false;
  let dragStartX = 0;
  let dragStartY = 0;
  const DRAG_THRESHOLD = 5;
  let offsetX = 0;
  let offsetY = 0;
  let drawerWidth = 0;
  let drawerHeight = 0;

  function parseSavedPosition() {
    try {
      const raw = window.localStorage.getItem(drawerPositionKey);

      if (!raw) {
        return null;
      }

      const value = JSON.parse(raw);

      if (
        typeof value?.left !== "number" ||
        typeof value?.top !== "number"
      ) {
        return null;
      }

      return value;
    } catch (error) {
      return null;
    }
  }

  function persistPosition(left, top) {
    window.localStorage.setItem(
      drawerPositionKey,
      JSON.stringify({ left, top })
    );
  }

  function getBounds() {
    const maxLeft = Math.max(8, window.innerWidth - drawerWidth - 8);
    const maxTop = Math.max(8, window.innerHeight - drawerHeight - 8);

    return {
      minLeft: 8,
      minTop: 8,
      maxLeft,
      maxTop,
    };
  }

  function applyFloatingStyles(rect) {
    drawer.style.position = "fixed";
    drawer.style.transform = "none";
    drawer.style.bottom = "auto";
    drawer.style.left = `${Math.round(rect.left)}px`;
    drawer.style.top = `${Math.round(rect.top)}px`;
    drawer.style.width = `${Math.round(rect.width)}px`;
    drawer.style.maxWidth = "calc(100vw - 16px)";
    drawer.style.zIndex = "60";
  }

  function applyPosition(left, top) {
    const bounds = getBounds();
    const nextLeft = clamp(left, bounds.minLeft, bounds.maxLeft);
    const nextTop = clamp(top, bounds.minTop, bounds.maxTop);

    drawer.style.left = `${Math.round(nextLeft)}px`;
    drawer.style.top = `${Math.round(nextTop)}px`;
    persistPosition(nextLeft, nextTop);
  }

  function ensureFloating() {
    const rect = drawer.getBoundingClientRect();
    drawerWidth = rect.width;
    drawerHeight = rect.height;
    applyFloatingStyles(rect);
  }

  function tryApplySavedPosition() {
    if (drawer.classList.contains("hidden")) {
      return;
    }

    requestAnimationFrame(() => {
      const rect = drawer.getBoundingClientRect();

      if (!rect.width || !rect.height) {
        return;
      }

      drawerWidth = rect.width;
      drawerHeight = rect.height;
      applyFloatingStyles(rect);

      const saved = parseSavedPosition();
      const bottomY = window.innerHeight - drawerHeight - 8;

      if (saved) {
        applyPosition(saved.left, bottomY);
      } else if (lastClickPos) {
        const centeredLeft = (window.innerWidth - drawerWidth) / 2;

        // avoid centering the drawer on top of the clicked pixel
        const clickX = lastClickPos.x;
        const clickY = lastClickPos.y;
        const centeredOverlaps =
          clickX >= centeredLeft &&
          clickX <= centeredLeft + drawerWidth &&
          clickY >= bottomY &&
          clickY <= bottomY + drawerHeight;

        const left = centeredOverlaps
          ? (lastClickPos.x > window.innerWidth / 2 ? 8 : window.innerWidth - drawerWidth - 8)
          : centeredLeft;

        applyPosition(left, bottomY);
      }
    });
  }

  function startDrag(clientX, clientY) {
    if (drawer.classList.contains("hidden")) {
      return false;
    }

    ensureFloating();

    const rect = drawer.getBoundingClientRect();
    drawerWidth = rect.width;
    drawerHeight = rect.height;
    offsetX = clientX - rect.left;
    offsetY = clientY - rect.top;
    dragStartX = clientX;
    dragStartY = clientY;
    pendingDrag = true;
    dragging = false;
    return true;
  }

  function moveDrag(clientX, clientY) {
    if (!pendingDrag) {
      return;
    }

    if (!dragging) {
      const dx = clientX - dragStartX;
      const dy = clientY - dragStartY;
      if (Math.sqrt(dx * dx + dy * dy) < DRAG_THRESHOLD) {
        return;
      }
      dragging = true;
      handle.classList.remove("cursor-move");
      handle.classList.add("cursor-grabbing");
    }

    const nextLeft = clientX - offsetX;
    const nextTop = clientY - offsetY;
    applyPosition(nextLeft, nextTop);
  }

  function stopDrag() {
    pendingDrag = false;
    dragging = false;
    handle.classList.remove("cursor-grabbing");
    handle.classList.add("cursor-move");
  }

  function isCloseButtonTarget(target) {
    return (
      target instanceof Element &&
      Boolean(target.closest("#close-drawer-button"))
    );
  }

  handle.addEventListener("mousedown", (event) => {
    if (event.button !== 0 || isCloseButtonTarget(event.target)) {
      return;
    }

    if (!startDrag(event.clientX, event.clientY)) {
      return;
    }

    const onMouseMove = (moveEvent) => {
      moveDrag(moveEvent.clientX, moveEvent.clientY);
    };

    const onMouseUp = () => {
      window.removeEventListener("mousemove", onMouseMove);
      window.removeEventListener("mouseup", onMouseUp);
      stopDrag();
    };

    window.addEventListener("mousemove", onMouseMove);
    window.addEventListener("mouseup", onMouseUp);
    event.preventDefault();
  });

  handle.addEventListener("touchstart", (event) => {
    if (isCloseButtonTarget(event.target) || !event.touches.length) {
      return;
    }

    const touch = event.touches[0];

    if (!startDrag(touch.clientX, touch.clientY)) {
      return;
    }

    const onTouchMove = (moveEvent) => {
      if (!moveEvent.touches.length) {
        return;
      }

      const activeTouch = moveEvent.touches[0];
      moveDrag(activeTouch.clientX, activeTouch.clientY);
      moveEvent.preventDefault();
    };

    const onTouchEnd = () => {
      window.removeEventListener("touchmove", onTouchMove);
      window.removeEventListener("touchend", onTouchEnd);
      window.removeEventListener("touchcancel", onTouchEnd);
      stopDrag();
    };

    window.addEventListener("touchmove", onTouchMove, { passive: false });
    window.addEventListener("touchend", onTouchEnd);
    window.addEventListener("touchcancel", onTouchEnd);
    event.preventDefault();
  });

  window.addEventListener("resize", () => {
    if (drawer.classList.contains("hidden")) {
      return;
    }

    tryApplySavedPosition();
    const currentLeft = Number.parseFloat(drawer.style.left);
    const currentTop = Number.parseFloat(drawer.style.top);

    if (Number.isFinite(currentLeft) && Number.isFinite(currentTop)) {
      applyPosition(currentLeft, currentTop);
    }
  });

  const observer = new MutationObserver(() => {
    tryApplySavedPosition();
  });

  observer.observe(drawer, {
    attributes: true,
    attributeFilter: ["class"],
  });

  tryApplySavedPosition();
}

document.addEventListener("DOMContentLoaded", () => {
  const nodes = getGridNodes();
  const auth = readAuthState();

  if (!isGridReady(nodes)) {
    return;
  }

  const pixels = readWallData();
  const grid = createPixelGrid(nodes.container, {
    pixels,
    gridSize: appConfig.demoGridSize,
    interactive: true,
    fullScreen: true,
    onChange(items, activeItem) {
      setGridSummary(nodes, items, activeItem);
    },
  });

  setPanelColor(nodes, "#FB923C");
  grid.setDraftColor("#FB923C");
  setGridSummary(nodes, [], null);
  initDrawerDrag(nodes);
  nodes.container.addEventListener("click", (e) => {
    lastClickPos = { x: e.clientX, y: e.clientY };
    window.localStorage.removeItem(drawerPositionKey);
  });

  if (nodes.infoButton) {
    nodes.infoButton.addEventListener("click", (event) => {
      event.stopPropagation();
      toggleInfo(nodes);
    });
  }

  if (nodes.infoClose) {
    nodes.infoClose.addEventListener("click", (event) => {
      event.stopPropagation();
      if (nodes.infoPanel) {
        nodes.infoPanel.classList.add("hidden");
      }
    });
  }

  document.addEventListener("click", () => {
    if (nodes.infoPanel && !nodes.infoPanel.classList.contains("hidden")) {
      nodes.infoPanel.classList.add("hidden");
    }
  });

  if (nodes.reset) {
    nodes.reset.addEventListener("click", () => {
      grid.resetView();
    });
  }

  if (nodes.zoomIn) {
    nodes.zoomIn.addEventListener("click", () => {
      grid.zoomIn();
    });
  }

  if (nodes.zoomOut) {
    nodes.zoomOut.addEventListener("click", () => {
      grid.zoomOut();
    });
  }

  if (nodes.exportPng) {
    nodes.exportPng.addEventListener("click", () => {
      const ok = exportGridAsPng(nodes);

      if (!ok) {
        nodes.exportPng.textContent = "Error";
        window.setTimeout(() => {
          nodes.exportPng.textContent = "PNG";
        }, 1200);
        return;
      }

      nodes.exportPng.textContent = "Saved";
      window.setTimeout(() => {
        nodes.exportPng.textContent = "PNG";
      }, 1200);
    });
  }

  if (nodes.close) {
    nodes.close.addEventListener("click", () => {
      grid.clearActive();
    });
  }

  if (nodes.remove) {
    nodes.remove.addEventListener("click", () => {
      grid.removeActive();
    });
  }

  if (nodes.clear) {
    nodes.clear.addEventListener("click", () => {
      grid.clearSelection();
    });
  }

  function applyColor(value) {
    const color = normalizeColor(value);

    if (!color) {
      return;
    }

    setPanelColor(nodes, color);
    grid.setDraftColor(color);
    grid.setActiveColor(color);
  }

  document.querySelectorAll(".color-swatch").forEach((btn) => {
    btn.addEventListener("click", () => {
      applyColor(btn.dataset.color);
    });
  });

  if (nodes.colorNative) {
    nodes.colorNative.addEventListener("input", (e) => applyColor(e.target.value));
  }

  if (nodes.colorText) {
    nodes.colorText.addEventListener("input", (event) => {
      const color = normalizeColor(event.target.value);

      if (color) {
        applyColor(color);
      }
    });

    nodes.colorText.addEventListener("blur", () => {
      const active = grid.getActiveItem();

      setPanelColor(
        nodes,
        normalizeColor(nodes.colorText.value) ||
          active?.color ||
          "#FB923C"
      );
    });
  }

  nodes.button.addEventListener("click", () => {
    const selected = grid.getSelectedPixels();

    if (!selected.length) {
      return;
    }

    saveSelection({
      pixels: selected,
      message: nodes.message?.value || "",
    });
    window.location.href = auth.loggedIn
      ? auth.nextUrl
      : auth.loginUrl;
  });
});


