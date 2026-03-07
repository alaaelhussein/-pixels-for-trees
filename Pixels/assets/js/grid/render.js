function getBounds(state) {
  return {
    startX: Math.max(
      0,
      Math.floor(
        -state.offsetX /
          (state.cellSize * state.scale)
      )
    ),
    startY: Math.max(
      0,
      Math.floor(
        -state.offsetY /
          (state.cellSize * state.scale)
      )
    ),
    endX: Math.min(
      state.gridSize,
      Math.ceil(
        (state.width - state.offsetX) /
          (state.cellSize * state.scale)
      )
    ),
    endY: Math.min(
      state.gridSize,
      Math.ceil(
        (state.height - state.offsetY) /
          (state.cellSize * state.scale)
      )
    ),
  };
}

function drawCell(ctx, x, y, size, color) {
  ctx.fillStyle = color;
  ctx.fillRect(x * size, y * size, size - 1, size - 1);
}

function drawCellBorder(ctx, x, y, size, color) {
  ctx.strokeStyle = color;
  ctx.lineWidth = 2;
  ctx.strokeRect(
    x * size + 1,
    y * size + 1,
    size - 3,
    size - 3
  );
}

function drawGuides(ctx, state, bounds) {
  const step = 10;
  ctx.strokeStyle = "rgba(148, 163, 184, 0.18)";
  ctx.lineWidth = 1;

  for (let x = bounds.startX; x <= bounds.endX; x += step) {
    const pos = x * state.cellSize;
    ctx.beginPath();
    ctx.moveTo(pos, bounds.startY * state.cellSize);
    ctx.lineTo(pos, bounds.endY * state.cellSize);
    ctx.stroke();
  }

  for (let y = bounds.startY; y <= bounds.endY; y += step) {
    const pos = y * state.cellSize;
    ctx.beginPath();
    ctx.moveTo(bounds.startX * state.cellSize, pos);
    ctx.lineTo(bounds.endX * state.cellSize, pos);
    ctx.stroke();
  }
}

export function drawGrid(
  ctx,
  state,
  pixelMap,
  selectedMap,
  activeKey,
  activeKind
) {
  const bounds = getBounds(state);
  drawGuides(ctx, state, bounds);

  for (let y = bounds.startY; y < bounds.endY; y += 1) {
    for (let x = bounds.startX; x < bounds.endX; x += 1) {
      const key = `${x}-${y}`;
      const reserved = pixelMap.get(key);
      const selected = selectedMap.get(key);

      if (reserved) {
        drawCell(ctx, x, y, state.cellSize, "#94A3B8");
      } else if (selected) {
        drawCell(
          ctx,
          x,
          y,
          state.cellSize,
          selected.color || "#FB923C"
        );
      } else {
        drawCell(ctx, x, y, state.cellSize, "#FFFFFF");
      }

      if (key !== activeKey) {
        continue;
      }

      drawCellBorder(
        ctx,
        x,
        y,
        state.cellSize,
        activeKind === "reserved" ? "#1D4ED8" : "#0F172A"
      );
    }
  }
}

export function drawMiniMap(ctx, state) {
  const size = 100;
  const pad = 10;
  const scale = size / state.totalGridPx;
  const x = state.width - size - pad;
  const y = state.height - size - pad;
  //AS map world coords into mini map space
  const viewX = -state.offsetX / state.scale * scale;
  const viewY = -state.offsetY / state.scale * scale;
  const viewW = state.width / state.scale * scale;
  const viewH = state.height / state.scale * scale;

  ctx.fillStyle = "rgba(255, 255, 255, 0.8)";
  ctx.fillRect(x, y, size, size);
  ctx.strokeStyle = "rgba(0, 0, 0, 0.1)";
  ctx.strokeRect(x, y, size, size);
  ctx.strokeStyle = "#FB923C";
  ctx.strokeRect(
    x + Math.max(0, viewX),
    y + Math.max(0, viewY),
    Math.min(size, viewW),
    Math.min(size, viewH)
  );
}
