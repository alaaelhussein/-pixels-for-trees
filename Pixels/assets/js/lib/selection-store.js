const key = "wallSelection";

export function loadSelection() {
  const value = localStorage.getItem(key);

  if (!value) {
    return {
      pixels: [],
      message: "",
    };
  }

  try {
    const data = JSON.parse(value);

    if (Array.isArray(data)) {
      return {
        pixels: data,
        message: "",
      };
    }

    if (Array.isArray(data?.pixels)) {
      return {
        pixels: data.pixels,
        message: data.message || "",
      };
    }
  } catch (error) {
    return {
      pixels: [],
      message: "",
    };
  }

  return {
    pixels: [],
    message: "",
  };
}

export function saveSelection(data) {
  localStorage.setItem(
    key,
    JSON.stringify(data)
  );
}
