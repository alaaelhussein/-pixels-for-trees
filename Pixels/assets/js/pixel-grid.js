import { createParticles } from "./grid/particles.js";
import { drawGrid, drawMiniMap } from "./grid/render.js";

function getSettings(options) {
  return {
    gridSize: options?.gridSize || 1000,
    pixels: options?.pixels || [],
    interactive: Boolean(options?.interactive),
    fullScreen: Boolean(options?.fullScreen),
    onChange: options?.onChange,
  };
}

function makePixelMap(pixels) {
  const map = new Map();

  pixels.forEach((pixel) => {
    map.set(`${pixel.x}-${pixel.y}`, pixel);
  });

  return map;
}

function getSelectedPixels(selectedMap) {
  return Array.from(selectedMap.values());
}

function makeEmptyApi() {
  return {
    getSelectedPixels() {
      return [];
    },
    getActiveItem() {
      return null;
    },
    resetView() {
      return null;
    },
    zoomIn() {
      return null;
    },
    zoomOut() {
      return null;
    },
    goToCell() {
      return null;
    },
    setDraftColor() {
      return null;
    },
    setActiveColor() {
      return null;
    },
    removeActive() {
      return null;
    },
    clearSelection() {
      return null;
    },
    clearActive() {
      return null;
    },
  };
}

export function createPixelGrid(container, options) {
  const settings = getSettings(options);
  const pixelMap = makePixelMap(settings.pixels);
  const selectedMap = new Map();
  const particles = createParticles();
  const canvas = document.createElement("canvas");
  const ctx = canvas.getContext("2d", { alpha: false });
  const state = {
    cellSize: 10,
    gridSize: settings.gridSize,
    width: 0,
    height: 0,
    scale: 1,
    minScale: 1,
    maxScale: 12,
    offsetX: 0,
    offsetY: 0,
    totalGridPx: settings.gridSize * 10,
    showMiniMap: !settings.fullScreen,
  };
  let draftColor = "#FB923C";
  let activeItem = null;
  let isDragging = false;
  let dragMoved = false;
  let lastMouseX = 0;
  let lastMouseY = 0;
  let lastTouchX = 0;
  let lastTouchY = 0;
  let lastTouchDist = 0;
  let touchMoved = false;

  if (!ctx) {
    return makeEmptyApi();
  }

  container.innerHTML = "";
  container.className = "relative overflow-hidden bg-gray-200 cursor-crosshair";
  container.style.width = "100%";
  container.style.height = "100%";
  container.appendChild(canvas);

  function getFitScale() {
    const scaleX = state.width / state.totalGridPx;
    const scaleY = state.height / state.totalGridPx;
    return Math.max(Math.min(scaleX, scaleY), 0.03);
  }

  function getStartScale() {
    if (!settings.fullScreen) {
      return getFitScale();
    }

    const cols = 100;
    const rows = 50;
    const scaleX = state.width / (cols * state.cellSize);
    const scaleY = state.height / (rows * state.cellSize);
    return Math.max(scaleX, scaleY);
  }

  function clampOffset() {
    const minX = Math.min(
      0,
      state.width - state.totalGridPx * state.scale
    );
    const minY = Math.min(
      0,
      state.height - state.totalGridPx * state.scale
    );

    state.offsetX = Math.min(0, Math.max(minX, state.offsetX));
    state.offsetY = Math.min(0, Math.max(minY, state.offsetY));
  }

  function centerView() {
    state.offsetX =
      (state.width - state.totalGridPx * state.scale) / 2;
    state.offsetY =
      (state.height - state.totalGridPx * state.scale) / 2;
    clampOffset();
  }

  function resetView() {
    state.minScale = getFitScale();
    state.scale = getStartScale();

    if (settings.fullScreen) {
      state.offsetX = 0;
      state.offsetY = 0;
      clampOffset();
      return;
    }

    centerView();
  }

  function resize() {
    const rect = container.getBoundingClientRect();
    const ratio = window.devicePixelRatio || 1;

    canvas.width = rect.width * ratio;
    canvas.height = rect.height * ratio;
    canvas.style.width = `${rect.width}px`;
    canvas.style.height = `${rect.height}px`;
    ctx.setTransform(ratio, 0, 0, ratio, 0, 0);

    state.width = rect.width;
    state.height = rect.height;
    resetView();
  }

  function getActivePixel() {
    if (!activeItem) {
      return null;
    }

    if (activeItem.kind === "selected") {
      return selectedMap.get(activeItem.key) || null;
    }

    return pixelMap.get(activeItem.key) || null;
  }

  function buildActiveData() {
    const item = getActivePixel();

    if (!item || !activeItem) {
      return null;
    }

    return {
      kind: activeItem.kind,
      ...item,
    };
  }

  function notifyChange() {
    if (typeof settings.onChange !== "function") {
      return;
    }

    settings.onChange(
      getSelectedPixels(selectedMap),
      buildActiveData()
    );
  }

  function getGridPos(clientX, clientY) {
    const rect = canvas.getBoundingClientRect();
    const x =
      (clientX - rect.left - state.offsetX) /
      state.scale;
    const y =
      (clientY - rect.top - state.offsetY) /
      state.scale;

    return {
      gridX: Math.floor(x / state.cellSize),
      gridY: Math.floor(y / state.cellSize),
    };
  }

  function setActive(kind, key) {
    activeItem = kind && key ? { kind, key } : null;
    notifyChange();
  }

  function zoomAt(x, y, nextScale) {
    const scale = Math.min(
      Math.max(nextScale, state.minScale),
      state.maxScale
    );

    state.offsetX =
      x - (x - state.offsetX) * (scale / state.scale);
    state.offsetY =
      y - (y - state.offsetY) * (scale / state.scale);
    state.scale = scale;
    clampOffset();
  }

  function goToCell(x, y) {
    const cellX = x * state.cellSize * state.scale;
    const cellY = y * state.cellSize * state.scale;

    state.offsetX = state.width / 2 - cellX;
    state.offsetY = state.height / 2 - cellY;
    clampOffset();
  }

  function addSelection(x, y) {
    const key = `${x}-${y}`;

    if (pixelMap.has(key)) {
      setActive("reserved", key);
      return;
    }

    if (!selectedMap.has(key)) {
      selectedMap.set(key, {
        id: key,
        x,
        y,
        color: draftColor,
      });
      particles.add(x, y, state.cellSize, draftColor);
    }

    setActive("selected", key);
  }

  function focusCell(x, y) {
    const inside =
      x >= 0 &&
      y >= 0 &&
      x < state.gridSize &&
      y < state.gridSize;

    if (!inside) {
      return;
    }

    const key = `${x}-${y}`;

    if (selectedMap.has(key)) {
      setActive("selected", key);
      return;
    }

    if (pixelMap.has(key)) {
      setActive("reserved", key);
      return;
    }

    if (!settings.interactive) {
      return;
    }

    addSelection(x, y);
  }

  function setDraftColor(color) {
    if (color) {
      draftColor = color;
    }
  }

  function setActiveColor(color) {
    if (!color) {
      return;
    }

    draftColor = color;

    if (!activeItem || activeItem.kind !== "selected") {
      return;
    }

    const item = selectedMap.get(activeItem.key);

    if (!item) {
      return;
    }

    item.color = color;
    selectedMap.set(activeItem.key, item);
    notifyChange();
  }

  function removeActive() {
    if (!activeItem || activeItem.kind !== "selected") {
      return;
    }

    selectedMap.delete(activeItem.key);
    const last = getSelectedPixels(selectedMap).pop();

    if (!last) {
      activeItem = null;
      notifyChange();
      return;
    }

    setActive("selected", last.id);
  }

  function clearSelection() {
    selectedMap.clear();
    activeItem = null;
    notifyChange();
  }

  function clearActive() {
    activeItem = null;
    notifyChange();
  }

  function render() {
    ctx.fillStyle = "#F3F4F6";
    ctx.fillRect(0, 0, state.width, state.height);
    ctx.save();
    ctx.translate(state.offsetX, state.offsetY);
    ctx.scale(state.scale, state.scale);
    drawGrid(
      ctx,
      state,
      pixelMap,
      selectedMap,
      activeItem?.key || "",
      activeItem?.kind || ""
    );
    particles.step();
    particles.draw(ctx);
    ctx.restore();

    if (state.showMiniMap) {
      drawMiniMap(ctx, state);
    }

    window.requestAnimationFrame(render);
  }

  canvas.addEventListener("mousedown", (event) => {
    isDragging = true;
    dragMoved = false;
    lastMouseX = event.clientX;
    lastMouseY = event.clientY;
  });

  window.addEventListener("mousemove", (event) => {
    if (!isDragging) {
      return;
    }

    dragMoved = true;
    state.offsetX += event.clientX - lastMouseX;
    state.offsetY += event.clientY - lastMouseY;
    lastMouseX = event.clientX;
    lastMouseY = event.clientY;
    clampOffset();
  });

  window.addEventListener("mouseup", () => {
    isDragging = false;
  });

  canvas.addEventListener("click", (event) => {
    if (dragMoved) {
      return;
    }

    const pos = getGridPos(event.clientX, event.clientY);
    focusCell(pos.gridX, pos.gridY);
  });

  canvas.addEventListener("touchstart", (event) => {
    touchMoved = false;

    if (event.touches.length === 1) {
      lastTouchX = event.touches[0].clientX;
      lastTouchY = event.touches[0].clientY;
    }

    if (event.touches.length === 2) {
      lastTouchDist = Math.hypot(
        event.touches[0].clientX - event.touches[1].clientX,
        event.touches[0].clientY - event.touches[1].clientY
      );
    }
  });

  canvas.addEventListener(
    "touchmove",
    (event) => {
      event.preventDefault();

      if (event.touches.length === 1) {
        touchMoved = true;
        state.offsetX += event.touches[0].clientX - lastTouchX;
        state.offsetY += event.touches[0].clientY - lastTouchY;
        lastTouchX = event.touches[0].clientX;
        lastTouchY = event.touches[0].clientY;
        clampOffset();
      }

      if (event.touches.length === 2) {
        const dist = Math.hypot(
          event.touches[0].clientX - event.touches[1].clientX,
          event.touches[0].clientY - event.touches[1].clientY
        );
        const rect = canvas.getBoundingClientRect();
        const centerX =
          (event.touches[0].clientX + event.touches[1].clientX) / 2 - rect.left;
        const centerY =
          (event.touches[0].clientY + event.touches[1].clientY) / 2 - rect.top;
        const factor = dist / lastTouchDist;

        zoomAt(centerX, centerY, state.scale * factor);
        lastTouchDist = dist;
      }
    },
    { passive: false }
  );

  canvas.addEventListener("touchend", (event) => {
    if (touchMoved || event.changedTouches.length !== 1) {
      return;
    }

    const touch = event.changedTouches[0];
    const pos = getGridPos(touch.clientX, touch.clientY);
    focusCell(pos.gridX, pos.gridY);
  });

  canvas.addEventListener(
    "wheel",
    (event) => {
      event.preventDefault();
      const rect = canvas.getBoundingClientRect();
      const x = event.clientX - rect.left;
      const y = event.clientY - rect.top;
      const factor = Math.pow(1.1, -event.deltaY / 120);

      zoomAt(x, y, state.scale * factor);
    },
    { passive: false }
  );

  window.addEventListener("resize", resize);
  resize();
  notifyChange();
  render();

  return {
    getSelectedPixels() {
      return getSelectedPixels(selectedMap);
    },
    getActiveItem() {
      return buildActiveData();
    },
    resetView() {
      resetView();
    },
    zoomIn() {
      zoomAt(state.width / 2, state.height / 2, state.scale * 1.2);
    },
    zoomOut() {
      zoomAt(state.width / 2, state.height / 2, state.scale / 1.2);
    },
    goToCell(x, y) {
      goToCell(x, y);
    },
    setDraftColor(color) {
      setDraftColor(color);
    },
    setActiveColor(color) {
      setActiveColor(color);
    },
    removeActive() {
      removeActive();
    },
    clearSelection() {
      clearSelection();
    },
    clearActive() {
      clearActive();
    },
  };
}
