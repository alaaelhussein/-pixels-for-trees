export function readWallData() {
  const node = document.querySelector("#wall-data");

  if (!node) {
    return [];
  }

  try {
    const data = JSON.parse(node.textContent || "[]");
    return Array.isArray(data) ? data : [];
  } catch (error) {
    return [];
  }
}
