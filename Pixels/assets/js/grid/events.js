export function bindGridMouseEvents(
  canvas,
  view,
  handlers
) {
  let isDragging = false;
  let dragMoved = false;
  let lastMouseX = 0;
  let lastMouseY = 0;

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
    view.moveBy(
      event.clientX - lastMouseX,
      event.clientY - lastMouseY
    );
    lastMouseX = event.clientX;
    lastMouseY = event.clientY;
  });

  window.addEventListener("mouseup", () => {
    isDragging = false;
  });

  canvas.addEventListener("click", (event) => {
    if (dragMoved) {
      return;
    }

    const pos = view.getGridPos(
      event.clientX,
      event.clientY
    );
    handlers.focusCell(pos.gridX, pos.gridY);
  });
}

export function bindGridTouchEvents(
  canvas,
  view,
  handlers
) {
  let lastTouchX = 0;
  let lastTouchY = 0;
  let lastTouchDist = 0;
  let touchMoved = false;

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
        view.moveBy(
          event.touches[0].clientX - lastTouchX,
          event.touches[0].clientY - lastTouchY
        );
        lastTouchX = event.touches[0].clientX;
        lastTouchY = event.touches[0].clientY;
      }

      if (event.touches.length === 2) {
        const dist = Math.hypot(
          event.touches[0].clientX - event.touches[1].clientX,
          event.touches[0].clientY - event.touches[1].clientY
        );

        if (!lastTouchDist) {
          lastTouchDist = dist;
          return;
        }

        const rect = canvas.getBoundingClientRect();
        const centerX =
          (event.touches[0].clientX +
            event.touches[1].clientX) /
            2 -
          rect.left;
        const centerY =
          (event.touches[0].clientY +
            event.touches[1].clientY) /
            2 -
          rect.top;
        const factor = dist / lastTouchDist;

        view.zoomAt(
          centerX,
          centerY,
          view.state.scale * factor
        );
        lastTouchDist = dist;
      }
    },
    { passive: false }
  );

  canvas.addEventListener("touchend", (event) => {
    if (event.touches.length < 2) {
      lastTouchDist = 0;
    }

    if (touchMoved || event.changedTouches.length !== 1) {
      return;
    }

    const touch = event.changedTouches[0];
    const pos = view.getGridPos(
      touch.clientX,
      touch.clientY
    );
    handlers.focusCell(pos.gridX, pos.gridY);
  });
}

export function bindGridWheelEvents(canvas, view) {
  canvas.addEventListener(
    "wheel",
    (event) => {
      event.preventDefault();
      const rect = canvas.getBoundingClientRect();
      const x = event.clientX - rect.left;
      const y = event.clientY - rect.top;
      const factor = Math.pow(1.1, -event.deltaY / 120);

      view.zoomAt(x, y, view.state.scale * factor);
    },
    { passive: false }
  );
}

export function bindGridResize(view) {
  window.addEventListener("resize", () => {
    view.resize();
  });
}
