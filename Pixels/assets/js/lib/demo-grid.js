import { createPixelGrid } from "../pixel-grid.js";
import { appConfig } from "./app-config.js";
import { readWallData } from "./page-wall-data.js";

export function mountWallGrid(selector) {
  const container = document.querySelector(
    selector
  );

  if (!container) {
    return null;
  }

  const pixels = readWallData();

  return createPixelGrid(container, {
    pixels,
    gridSize: appConfig.demoGridSize,
  });
}
