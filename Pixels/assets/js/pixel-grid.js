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

export function createPixelGrid(container, options) {
  const {
    gridSize = 1000,
    pixels = [],
    interactive = false,
    onSelectionChange,
  } = options || {};

  // Build a lookup map for funded pixels
  const pixelMap = new Map(pixels.map((p) => [`${p.x}-${p.y}`, p]));
  const selectedIds = new Set();
  
  // Create Canvas elements
  const canvas = document.createElement("canvas");
  const ctx = canvas.getContext("2d", { alpha: false });
  
  // Grid visual parameters
  const cellSize = 10; // Base size of a pixel
  const totalGridPX = gridSize * cellSize;
  
  // Transform state (Pan & Zoom)
  let scale = 0.5; // Start zoomed out
  let offsetX = 0;
  let offsetY = 0;
  
  // Wrapper for layout
  container.innerHTML = "";
  container.className = "relative overflow-hidden rounded-lg shadow-lg bg-gray-200 cursor-crosshair";
  container.style.width = "100%";
  container.style.aspectRatio = "1";
  container.appendChild(canvas);

  // Tooltip setup
  let tooltip = document.querySelector('[role="tooltip"]');
  if (!tooltip) {
    tooltip = document.createElement("div");
    tooltip.className = "fixed z-50 bg-white border border-gray-300 rounded-lg shadow-xl p-3 pointer-events-none hidden transition-opacity duration-200";
    tooltip.setAttribute("role", "tooltip");
    document.body.appendChild(tooltip);
  }

  // Particles for premium feedback
  let particles = [];
  function createParticles(x, y, color) {
    for (let i = 0; i < 8; i++) {
        particles.push({
            x: x * cellSize + cellSize / 2,
            y: y * cellSize + cellSize / 2,
            vx: (Math.random() - 0.5) * 4,
            vy: (Math.random() - 0.5) * 4 - 2,
            life: 1.0,
            color: color
        });
    }
  }

  function updateParticles() {
      particles = particles.filter(p => p.life > 0);
      particles.forEach(p => {
          p.x += p.vx;
          p.y += p.vy;
          p.vy += 0.1; // Gravity
          p.life -= 0.02;
      });
  }

  function resize() {
    const rect = container.getBoundingClientRect();
    canvas.width = rect.width * window.devicePixelRatio;
    canvas.height = rect.height * window.devicePixelRatio;
    canvas.style.width = `${rect.width}px`;
    canvas.style.height = `${rect.height}px`;
    ctx.scale(window.devicePixelRatio, window.devicePixelRatio);
    
    // Initial centering
    if (scale === 0.5 && offsetX === 0) {
        offsetX = (rect.width - (totalGridPX * scale)) / 2;
        offsetY = (rect.height - (totalGridPX * scale)) / 2;
    }
  }

  function render() {
    requestAnimationFrame(render);
    if (!ctx) return;
    
    const w = canvas.width / window.devicePixelRatio;
    const h = canvas.height / window.devicePixelRatio;

    // Clear background
    ctx.fillStyle = "#F3F4F6"; // bg-gray-100
    ctx.fillRect(0, 0, w, h);

    ctx.save();
    ctx.translate(offsetX, offsetY);
    ctx.scale(scale, scale);

    // Visible range calculation for optimization
    const startX = Math.max(0, Math.floor(-offsetX / (cellSize * scale)));
    const startY = Math.max(0, Math.floor(-offsetY / (cellSize * scale)));
    const endX = Math.min(gridSize, Math.ceil((w - offsetX) / (cellSize * scale)));
    const endY = Math.min(gridSize, Math.ceil((h - offsetY) / (cellSize * scale)));

    // Draw grid pixels
    for (let y = startY; y < endY; y++) {
      for (let x = startX; x < endX; x++) {
        const key = `${x}-${y}`;
        const pixel = pixelMap.get(key);
        const isSelected = selectedIds.has(key);

        if (pixel && pixel.funded) {
          ctx.fillStyle = "#22C55E"; // bg-green-500
        } else if (isSelected) {
          ctx.fillStyle = "#FB923C"; // bg-orange-400
        } else {
          ctx.fillStyle = "#FFFFFF";
        }

        ctx.fillRect(x * cellSize, y * cellSize, cellSize - 1, cellSize - 1);
      }
    }

    // Draw particles
    updateParticles();
    particles.forEach(p => {
        ctx.globalAlpha = p.life;
        ctx.fillStyle = p.color;
        ctx.beginPath();
        ctx.arc(p.x, p.y, 2, 0, Math.PI * 2);
        ctx.fill();
    });
    ctx.globalAlpha = 1.0;

    ctx.restore();

    // Draw Mini-map
    const miniSize = 100;
    const miniPad = 10;
    ctx.fillStyle = "rgba(255, 255, 255, 0.8)";
    ctx.fillRect(w - miniSize - miniPad, h - miniSize - miniPad, miniSize, miniSize);
    ctx.strokeStyle = "rgba(0, 0, 0, 0.1)";
    ctx.strokeRect(w - miniSize - miniPad, h - miniSize - miniPad, miniSize, miniSize);

    // Viewport in mini-map
    const miniScale = miniSize / (gridSize * cellSize);
    const viewX = -offsetX / scale * miniScale;
    const viewY = -offsetY / scale * miniScale;
    const viewW = w / scale * miniScale;
    const viewH = h / scale * miniScale;

    ctx.strokeStyle = "#FB923C"; // orange-400
    ctx.strokeRect(
        w - miniSize - miniPad + Math.max(0, viewX),
        h - miniSize - miniPad + Math.max(0, viewY),
        Math.min(miniSize, viewW),
        Math.min(miniSize, viewH)
    );
  }

  // Interaction Helpers
  function getGridPos(clientX, clientY) {
    const rect = canvas.getBoundingClientRect();
    const x = (clientX - rect.left - offsetX) / scale;
    const y = (clientY - rect.top - offsetY) / scale;
    return {
      gridX: Math.floor(x / cellSize),
      gridY: Math.floor(y / cellSize)
    };
  }

  // Mouse Events
  let isDragging = false;
  let lastMouseX = 0;
  let lastMouseY = 0;

  canvas.addEventListener("mousedown", (e) => {
    isDragging = true;
    lastMouseX = e.clientX;
    lastMouseY = e.clientY;
  });

  window.addEventListener("mousemove", (e) => {
    if (isDragging) {
      offsetX += e.clientX - lastMouseX;
      offsetY += e.clientY - lastMouseY;
      lastMouseX = e.clientX;
      lastMouseY = e.clientY;
      tooltip.classList.add("hidden");
    } else {
       const { gridX, gridY } = getGridPos(e.clientX, e.clientY);
       const key = `${gridX}-${gridY}`;
       const pixel = pixelMap.get(key);

       if (pixel && pixel.funded) {
         tooltip.innerHTML = `
           <div class="text-sm space-y-1">
             <p class="font-semibold text-gray-900">${pixel.username || "Anonyme"}</p>
             <p class="text-gray-600">${pixel.amount}€</p>
             ${pixel.message ? `<p class="text-gray-500 italic text-xs max-w-xs">${pixel.message}</p>` : ""}
           </div>
         `;
         tooltip.classList.remove("hidden");
         tooltip.style.left = `${e.clientX + 15}px`;
         tooltip.style.top = `${e.clientY + 15}px`;
       } else {
         tooltip.classList.add("hidden");
       }
    }
  });

  window.addEventListener("mouseup", () => {
    isDragging = false;
  });

  // Touch Events (Mobile)
  let lastTouchDist = 0;
  let lastTouchX = 0;
  let lastTouchY = 0;

  canvas.addEventListener("touchstart", (e) => {
    if (e.touches.length === 1) {
      lastTouchX = e.touches[0].clientX;
      lastTouchY = e.touches[0].clientY;
    } else if (e.touches.length === 2) {
      lastTouchDist = Math.hypot(
        e.touches[0].clientX - e.touches[1].clientX,
        e.touches[0].clientY - e.touches[1].clientY
      );
    }
  });

  canvas.addEventListener("touchmove", (e) => {
    e.preventDefault();
    if (e.touches.length === 1) {
      const dx = e.touches[0].clientX - lastTouchX;
      const dy = e.touches[0].clientY - lastTouchY;
      offsetX += dx;
      offsetY += dy;
      lastTouchX = e.touches[0].clientX;
      lastTouchY = e.touches[0].clientY;
    } else if (e.touches.length === 2) {
      const dist = Math.hypot(
        e.touches[0].clientX - e.touches[1].clientX,
        e.touches[0].clientY - e.touches[1].clientY
      );
      const factor = dist / lastTouchDist;
      
      const rect = canvas.getBoundingClientRect();
      const centerX = (e.touches[0].clientX + e.touches[1].clientX) / 2 - rect.left;
      const centerY = (e.touches[0].clientY + e.touches[1].clientY) / 2 - rect.top;

      const newScale = Math.min(Math.max(scale * factor, 0.1), 5);
      offsetX = centerX - (centerX - offsetX) * (newScale / scale);
      offsetY = centerY - (centerY - offsetY) * (newScale / scale);
      
      scale = newScale;
      lastTouchDist = dist;
    }
  }, { passive: false });

  canvas.addEventListener("click", (e) => {
    if (interactive) {
      const { gridX, gridY } = getGridPos(e.clientX, e.clientY);
      if (gridX >= 0 && gridX < gridSize && gridY >= 0 && gridY < gridSize) {
        const key = `${gridX}-${gridY}`;
        const pixel = pixelMap.get(key);
        
        if (!pixel || !pixel.funded) {
          if (selectedIds.has(key)) {
            selectedIds.delete(key);
          } else {
            selectedIds.add(key);
            createParticles(gridX, gridY, "#FB923C"); // Match selection color
          }
          
          if (typeof onSelectionChange === "function") {
            const selectedPixels = Array.from(selectedIds).map(id => {
               const [x, y] = id.split("-").map(Number);
               return { id, x, y };
            });
            onSelectionChange(selectedPixels);
          }
        }
      }
    }
  });

  canvas.addEventListener("wheel", (e) => {
    e.preventDefault();
    const factor = Math.pow(1.1, -e.deltaY / 100);
    const newScale = Math.min(Math.max(scale * factor, 0.1), 5);
    
    const rect = canvas.getBoundingClientRect();
    const mouseX = e.clientX - rect.left;
    const mouseY = e.clientY - rect.top;
    
    offsetX = mouseX - (mouseX - offsetX) * (newScale / scale);
    offsetY = mouseY - (mouseY - offsetY) * (newScale / scale);
    scale = newScale;
  }, { passive: false });

  // Initial setup
  window.addEventListener("resize", resize);
  resize();
  render(); // Start loop

  return {
    getSelectedPixels() {
      return Array.from(selectedIds).map(id => {
         const [x, y] = id.split("-").map(Number);
         return { id, x, y };
      });
    },
    resetView() {
        scale = 0.5;
        offsetX = 0;
        offsetY = 0;
        resize();
    }
  };
}
