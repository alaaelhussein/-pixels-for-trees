import { drawGrid, drawMiniMap } from "./render.js";

export function createGridView(
  container,
  canvas,
  ctx,
  settings,
  pixelMap,
  selectedMap,
  particles
) {
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

  function moveBy(dx, dy) {
    state.offsetX += dx;
    state.offsetY += dy;
    clampOffset();
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

  function draw(activeItem) {
    ctx.fillStyle = document.documentElement.classList.contains("dark")
      ? "#111111"
      : "#F3F4F6";
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
  }

  function startRender(getActiveItem) {
    function tick() {
      draw(getActiveItem());
      window.requestAnimationFrame(tick);
    }

    window.requestAnimationFrame(tick);
  }

  return {
    state,
    resize,
    resetView,
    moveBy,
    zoomAt,
    goToCell,
    getGridPos,
    startRender,
  };
}
