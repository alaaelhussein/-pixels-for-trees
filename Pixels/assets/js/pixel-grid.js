export function generateMockPixels(count, gridSize = 1000) {
  const pixels = [];
  const messages = [
    "Pour un futur plus vert",
    "Ensemble, plantons l'espoir",
    "Chaque arbre compte",
    "Pour mes enfants",
    "La nature nous sauvera",
  ];
  const usernames = ["Marie L.", "Jean D.", "Sophie M.", "Lucas B.", "Emma R.", "Thomas V."];

  for (let i = 0; i < count; i += 1) {
    pixels.push({
      id: `${i}-${Math.floor(Math.random() * gridSize)}`,
      x: Math.floor(Math.random() * gridSize),
      y: Math.floor(Math.random() * gridSize),
      funded: true,
      username: usernames[Math.floor(Math.random() * usernames.length)],
      amount: Math.floor(Math.random() * 20) + 5,
      message: Math.random() > 0.5 ? messages[Math.floor(Math.random() * messages.length)] : undefined,
    });
  }

  return pixels;
}

function buildDisplayPixels(pixels, gridSize, displaySize) {
  const pixelMap = new Map(pixels.map((p) => [`${p.x}-${p.y}`, p]));
  const result = [];

  for (let y = 0; y < displaySize; y += 1) {
    for (let x = 0; x < displaySize; x += 1) {
      const gridX = Math.floor((x / displaySize) * gridSize);
      const gridY = Math.floor((y / displaySize) * gridSize);
      const key = `${gridX}-${gridY}`;
      result.push(pixelMap.get(key) || { id: key, x: gridX, y: gridY, funded: false });
    }
  }

  return result;
}

function getPixelClass(pixel, isSelected, interactive) {
  let className = "aspect-square transition-all ";

  if (pixel.funded) {
    className += "bg-green-500 hover:bg-green-600";
  } else if (isSelected) {
    className += "bg-orange-400 hover:bg-orange-500";
  } else if (interactive) {
    className += "bg-gray-100 hover:bg-blue-200 cursor-pointer";
  } else {
    className += "bg-gray-100";
  }

  return className;
}

function createTooltip() {
  const tooltip = document.createElement("div");
  tooltip.className = "fixed z-50 bg-white border border-gray-300 rounded-lg shadow-xl p-3 pointer-events-none hidden";
  tooltip.setAttribute("role", "tooltip");
  document.body.appendChild(tooltip);
  return tooltip;
}

export function createPixelGrid(container, options) {
  const {
    gridSize = 1000,
    displaySize = 100,
    pixels = [],
    interactive = false,
    onSelectionChange,
  } = options || {};

  const displayPixels = buildDisplayPixels(pixels, gridSize, displaySize);
  const selectedIds = new Set();
  const tooltip = createTooltip();

  const grid = document.createElement("div");
  grid.className = "grid gap-[1px] bg-gray-200 p-1 rounded-lg shadow-lg";
  grid.style.gridTemplateColumns = `repeat(${displaySize}, 1fr)`;
  grid.style.maxWidth = "600px";
  grid.style.aspectRatio = "1";

  displayPixels.forEach((pixel) => {
    const cell = document.createElement("div");
    cell.className = getPixelClass(pixel, false, interactive);
    cell.dataset.pixelId = pixel.id;

    if (interactive && !pixel.funded) {
      cell.addEventListener("click", () => {
        if (selectedIds.has(pixel.id)) {
          selectedIds.delete(pixel.id);
        } else {
          selectedIds.add(pixel.id);
        }
        cell.className = getPixelClass(pixel, selectedIds.has(pixel.id), interactive);

        if (typeof onSelectionChange === "function") {
          const selectedPixels = displayPixels.filter((p) => selectedIds.has(p.id));
          onSelectionChange(selectedPixels);
        }
      });
    }

    if (pixel.funded) {
      cell.addEventListener("mouseenter", (event) => {
        const messageHtml = pixel.message
          ? `<p class=\"text-gray-500 italic text-xs max-w-xs\">${pixel.message}</p>`
          : "";
        const amountHtml = pixel.amount
          ? `<p class=\"text-gray-600\">${pixel.amount}€</p>`
          : "";

        tooltip.innerHTML = `
          <div class=\"text-sm space-y-1\">
            <p class=\"font-semibold text-gray-900\">${pixel.username || "Anonyme"}</p>
            ${amountHtml}
            ${messageHtml}
          </div>
        `;
        tooltip.classList.remove("hidden");
        tooltip.style.left = `${event.clientX + 15}px`;
        tooltip.style.top = `${event.clientY + 15}px`;
      });

      cell.addEventListener("mousemove", (event) => {
        tooltip.style.left = `${event.clientX + 15}px`;
        tooltip.style.top = `${event.clientY + 15}px`;
      });

      cell.addEventListener("mouseleave", () => {
        tooltip.classList.add("hidden");
      });
    }

    grid.appendChild(cell);
  });

  container.innerHTML = "";
  container.appendChild(grid);

  return {
    getSelectedPixels() {
      return displayPixels.filter((p) => selectedIds.has(p.id));
    },
  };
}
