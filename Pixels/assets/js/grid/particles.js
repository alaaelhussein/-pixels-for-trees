export function createParticles() {
  let items = [];

  function add(x, y, size, color) {
    for (let i = 0; i < 8; i += 1) {
      items.push({
        //AH start each spark at cell center
        x: x * size + size / 2,
        y: y * size + size / 2,
        vx: (Math.random() - 0.5) * 4,
        vy: (Math.random() - 0.5) * 4 - 2,
        life: 1,
        color,
      });
    }
  }

  function step() {
    items = items.filter((item) => item.life > 0);

    items.forEach((item) => {
      //MF slow fade keeps old sparks cheap
      item.x += item.vx;
      item.y += item.vy;
      item.vy += 0.1;
      item.life -= 0.02;
    });
  }

  function draw(ctx) {
    items.forEach((item) => {
      ctx.globalAlpha = item.life;
      ctx.fillStyle = item.color;
      ctx.beginPath();
      ctx.arc(item.x, item.y, 2, 0, Math.PI * 2);
      ctx.fill();
    });

    ctx.globalAlpha = 1;
  }

  return {
    add,
    step,
    draw,
  };
}
