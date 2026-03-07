import { loadSelection } from "./lib/selection-store.js";
import {
  fillRecap,
  fillRecapForm,
  getRecapNodes,
} from "./lib/recap-page-ui.js";

document.addEventListener("DOMContentLoaded", () => {
  const selection = loadSelection();
  const pixels = selection.pixels;

  if (!pixels.length) {
    window.location.href = "grid.php";
    return;
  }

  const nodes = getRecapNodes();
  fillRecap(nodes, pixels);
  fillRecapForm(nodes, selection);

  if (!nodes.button) {
    return;
  }
});
