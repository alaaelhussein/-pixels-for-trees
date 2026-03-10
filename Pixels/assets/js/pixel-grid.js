import { createParticles } from "./grid/particles.js";
import {
  bindGridMouseEvents,
  bindGridResize,
  bindGridTouchEvents,
  bindGridWheelEvents,
} from "./grid/events.js";
import {
  getGridSettings,
  getSelectedPixels,
  makeEmptyApi,
  makePixelKey,
  makePixelMap,
} from "./grid/helpers.js";
import { createGridView } from "./grid/view.js";

export function createPixelGrid(container, options) {
  const settings = getGridSettings(options);
  const pixelMap = makePixelMap(settings.pixels);
  const selectedMap = new Map();
  const particles = createParticles();
  const canvas = document.createElement("canvas");
  const ctx = canvas.getContext("2d", { alpha: false });
  let draftColor = "#FB923C";
  let activeItem = null;

  if (!ctx) {
    return makeEmptyApi();
  }

  container.innerHTML = "";
  container.className = "relative overflow-hidden bg-gray-200 cursor-crosshair";
  container.style.width = "100%";
  container.style.height = "100%";
  container.appendChild(canvas);

  const view = createGridView(
    container,
    canvas,
    ctx,
    settings,
    pixelMap,
    selectedMap,
    particles
  );

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

  function setActive(kind, key) {
    activeItem = kind && key ? { kind, key } : null;
    notifyChange();
  }

  function addSelection(x, y) {
    const key = makePixelKey(x, y);

    if (pixelMap.has(key)) {
      const pixel = pixelMap.get(key);
      setActive(pixel?.status || "reserved", key);
      return;
    }

    if (!selectedMap.has(key)) {
      selectedMap.set(key, {
        id: key,
        x,
        y,
        color: draftColor,
      });
      particles.add(x, y, view.state.cellSize, draftColor);
    }

    setActive("selected", key);
  }

  function focusCell(x, y) {
    const inside =
      x >= 0 &&
      y >= 0 &&
      x < view.state.gridSize &&
      y < view.state.gridSize;

    if (!inside) {
      return;
    }

    const key = makePixelKey(x, y);

    if (selectedMap.has(key)) {
      setActive("selected", key);
      return;
    }

    if (pixelMap.has(key)) {
      const pixel = pixelMap.get(key);
      setActive(pixel?.status || "reserved", key);
      return;
    }

    if (!settings.interactive) {
      return;
    }

    addSelection(x, y);
  }

  function setDraftColor(color) {
    if (!color) {
      return;
    }

    draftColor = color;
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

  bindGridMouseEvents(canvas, view, { focusCell });
  bindGridTouchEvents(canvas, view, { focusCell });
  bindGridWheelEvents(canvas, view);
  bindGridResize(view);

  view.resize();
  notifyChange();
  view.startRender(() => activeItem);

  return {
    getSelectedPixels() {
      return getSelectedPixels(selectedMap);
    },
    getActiveItem() {
      return buildActiveData();
    },
    resetView() {
      view.resetView();
    },
    zoomIn() {
      view.zoomAt(
        view.state.width / 2,
        view.state.height / 2,
        view.state.scale * 1.2
      );
    },
    zoomOut() {
      view.zoomAt(
        view.state.width / 2,
        view.state.height / 2,
        view.state.scale / 1.2
      );
    },
    goToCell(x, y) {
      view.goToCell(x, y);
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
