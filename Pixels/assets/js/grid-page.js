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

  if (nodes.infoButton) {
    nodes.infoButton.addEventListener("click", () => {
      toggleInfo(nodes);
    });
  }

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

  if (nodes.jump) {
    const runJump = () => {
      const x = Number(nodes.jumpX?.value || -1);
      const y = Number(nodes.jumpY?.value || -1);

      if (x < 0 || y < 0) {
        return;
      }

      grid.goToCell(x, y);
    };

    nodes.jump.addEventListener("click", runJump);
    nodes.jumpX?.addEventListener("keydown", (event) => {
      if (event.key === "Enter") {
        runJump();
      }
    });
    nodes.jumpY?.addEventListener("keydown", (event) => {
      if (event.key === "Enter") {
        runJump();
      }
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

  // swatch clicks
  document.querySelectorAll(".color-swatch").forEach((btn) => {
    btn.addEventListener("click", () => {
      applyColor(btn.dataset.color);
    });
  });

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


