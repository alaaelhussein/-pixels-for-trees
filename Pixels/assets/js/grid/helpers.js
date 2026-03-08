export function getGridSettings(options) {
  return {
    gridSize: options?.gridSize || 1000,
    pixels: options?.pixels || [],
    interactive: Boolean(options?.interactive),
    fullScreen: Boolean(options?.fullScreen),
    onChange: options?.onChange,
  };
}

export function makePixelKey(x, y) {
  return `${x}-${y}`;
}

export function makePixelMap(pixels) {
  const map = new Map();

  pixels.forEach((pixel) => {
    map.set(makePixelKey(pixel.x, pixel.y), pixel);
  });

  return map;
}

export function getSelectedPixels(selectedMap) {
  return Array.from(selectedMap.values());
}

export function makeEmptyApi() {
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
